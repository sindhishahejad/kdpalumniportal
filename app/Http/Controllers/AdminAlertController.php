<?php

namespace App\Http\Controllers;

use App\Mail\WeeklyNewsletter;
use App\Models\Notice;
use App\Models\SuccessStory;
use App\Models\JobPosting;
use App\Models\User;
use App\Models\WhatsAppBroadcastLog;
use App\Services\WhatsAppAlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminAlertController extends Controller
{
    /**
     * Display the Direct WhatsApp & Email Alert Control Center.
     */
    public function index(Request $request)
    {
        // 1. Fetch system status
        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'queue_connection' => config('queue.default'),
        ];

        $whatsappConfig = [
            'provider' => env('WHATSAPP_PROVIDER', 'direct_click'),
            'has_cloud_token' => !empty(env('WHATSAPP_API_TOKEN')),
            'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', 'Not configured (Direct wa.me active)'),
        ];

        // 2. Metrics
        $stats = [
            'total_users_with_phone' => User::whereNotNull('phone')->where('phone', '!=', '')->count(),
            'total_users_with_email' => User::whereNotNull('email')->where('email', '!=', '')->count(),
            'total_whatsapp_alerts_sent' => WhatsAppBroadcastLog::count(),
            'total_direct_clicks' => WhatsAppBroadcastLog::where('channel', 'direct_click')->count(),
            'total_api_delivered' => WhatsAppBroadcastLog::where('channel', 'cloud_api')->where('status', 'delivered')->count(),
        ];

        // 3. Dynamic Departments and Batches for targeting
        $userDepts = User::whereNotNull('department')->where('department', '!=', '')->pluck('department');
        $profileDepts = \App\Models\Profile::whereNotNull('department')->where('department', '!=', '')->pluck('department');
        $departments = $userDepts->merge($profileDepts)->unique()->filter()->sort()->values();

        if ($departments->isEmpty()) {
            $departments = collect([
                'Computer Engineering',
                'Electrical Engineering',
                'Mechanical Engineering',
                'Civil Engineering',
                'Applied Science & Humanities',
            ]);
        }

        $userBatches = User::whereNotNull('graduation_year')->pluck('graduation_year');
        $profileBatches = \App\Models\Profile::whereNotNull('graduation_year')->pluck('graduation_year');
        $batches = $userBatches->merge($profileBatches)->unique()->filter()->sortDesc()->values();

        if ($batches->isEmpty()) {
            $batches = collect(range((int)date('Y'), (int)date('Y') - 10));
        }

        // 4. Recent logs
        $logs = WhatsAppBroadcastLog::latest()->paginate(15);

        return view('admin.alerts.index', compact(
            'mailConfig',
            'whatsappConfig',
            'stats',
            'departments',
            'batches',
            'logs'
        ));
    }

    /**
     * Dispatch test email to currently logged in admin.
     */
    public function sendTestEmail(Request $request)
    {
        $admin = auth()->user();
        $targetEmail = $request->input('test_email', $admin->email) ?: $admin->email;

        try {
            $successStories = SuccessStory::latest()->take(2)->get();
            $jobs = JobPosting::where('is_active', true)->latest()->take(2)->get();
            $notices = Notice::latest()->take(2)->get();

            Mail::to($targetEmail)->send(
                new WeeklyNewsletter($successStories, $jobs, $notices)
            );

            return back()->with('success', "Test email successfully dispatched to {$targetEmail}!");
        } catch (\Throwable $e) {
            return back()->with('error', "Mail dispatch error: " . $e->getMessage());
        }
    }

    /**
     * Dispatch Community WhatsApp Alert Broadcast.
     */
    public function dispatchWhatsAppBroadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
            'link' => 'nullable|url|max:255',
            'target_audience' => 'required|string|in:all,department,batch_year,single_phone',
            'department' => 'nullable|string',
            'batch_year' => 'nullable|integer',
            'single_phone' => 'nullable|string',
        ]);

        $messageText = WhatsAppAlertService::communityBroadcastMessage(
            $validated['title'],
            $validated['message'],
            $validated['link'] ?? null
        );

        // 1. Single phone direct dispatch
        if ($validated['target_audience'] === 'single_phone') {
            if (!$request->filled('single_phone')) {
                return back()->with('error', 'Please provide a valid recipient phone number.');
            }

            $res = WhatsAppAlertService::send(
                $validated['single_phone'],
                $messageText,
                'admin_broadcast',
                'Member',
                auth()->id()
            );

            return back()->with([
                'success' => "WhatsApp Alert prepared for {$validated['single_phone']}! Logged to the dispatch registry.",
                'sample_wa_url' => $res['url'],
                'sample_wa_phone' => $validated['single_phone'],
                'whatsapp_dispatch' => true,
            ]);
        }

        // 2. Query target audience members
        $query = User::where('is_approved', true)
            ->whereNotNull('phone')
            ->where('phone', '!=', '');

        if ($validated['target_audience'] === 'department' && $request->filled('department')) {
            $dept = $request->department;
            $query->where(function($q) use ($dept) {
                $q->where('department', $dept)
                  ->orWhereHas('profile', function($pq) use ($dept) {
                      $pq->where('department', $dept);
                  });
            });
        }

        if ($validated['target_audience'] === 'batch_year' && $request->filled('batch_year')) {
            $year = $request->batch_year;
            $query->where(function($q) use ($year) {
                $q->where('graduation_year', $year)
                  ->orWhereHas('profile', function($pq) use ($year) {
                      $pq->where('graduation_year', $year);
                  });
            });
        }

        $recipients = $query->get();

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No approved members found with registered phone numbers matching your filter.');
        }

        // Log and queue alert for each recipient
        foreach ($recipients as $recipient) {
            WhatsAppAlertService::send(
                $recipient->phone,
                $messageText,
                'admin_broadcast',
                $recipient->name,
                auth()->id()
            );
        }

        // If direct wa.me, redirect to first recipient click-to-chat as demonstration
        $firstRecipient = $recipients->first();
        $sampleUrl = WhatsAppAlertService::generateClickToChatUrl($firstRecipient->phone, $messageText);

        return back()->with([
            'success' => "WhatsApp Broadcast prepared for {$recipients->count()} members! Logged to the dispatch registry.",
            'sample_wa_url' => $sampleUrl,
            'sample_wa_phone' => $firstRecipient->phone,
            'whatsapp_dispatch' => true,
        ]);
    }
}
