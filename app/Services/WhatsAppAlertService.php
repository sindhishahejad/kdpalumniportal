<?php

namespace App\Services;

use App\Models\DocumentRequest;
use App\Models\EventRsvp;
use App\Models\JobReferral;
use App\Models\ToolDonation;
use App\Models\User;
use App\Models\WhatsAppBroadcastLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppAlertService
{
    /**
     * Sanitize and format phone number for international WhatsApp (+91 for India).
     */
    public static function cleanPhoneNumber(?string $phone): string
    {
        if (!$phone) {
            return '';
        }

        // Remove non-digit characters
        $digits = preg_replace('/\D/', '', $phone);

        // If 10 digits (Standard Indian mobile), prepend 91
        if (strlen($digits) === 10) {
            return '91' . $digits;
        }

        // If 11 digits starting with 0, replace 0 with 91
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return '91' . substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Generate dynamic WhatsApp click-to-chat URL.
     */
    public static function generateClickToChatUrl(string $phone, string $message): string
    {
        $cleanPhone = self::cleanPhoneNumber($phone);
        return 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);
    }

    /**
     * Dispatch or prepare WhatsApp alert and log to database.
     */
    public static function send(
        string $phone,
        string $message,
        string $alertType = 'admin_broadcast',
        ?string $recipientName = null,
        ?int $senderId = null
    ): array {
        $cleanPhone = self::cleanPhoneNumber($phone);
        $provider = config('services.whatsapp.provider', env('WHATSAPP_PROVIDER', 'direct_click'));
        $channel = 'direct_click';
        $status = 'sent';

        // 1. Meta WhatsApp Cloud API (Automated background dispatch if token set)
        if ($provider === 'cloud_api' && env('WHATSAPP_API_TOKEN') && env('WHATSAPP_PHONE_NUMBER_ID')) {
            try {
                $response = Http::withToken(env('WHATSAPP_API_TOKEN'))->post(
                    'https://graph.facebook.com/v18.0/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages',
                    [
                        'messaging_product' => 'whatsapp',
                        'to' => $cleanPhone,
                        'type' => 'text',
                        'text' => ['body' => $message],
                    ]
                );

                $channel = 'cloud_api';
                $status = $response->successful() ? 'delivered' : 'failed';
            } catch (\Throwable $e) {
                Log::error('WhatsApp Cloud API Exception: ' . $e->getMessage());
                $status = 'failed';
            }
        }

        // 2. Log record to database
        $log = WhatsAppBroadcastLog::create([
            'sender_id' => $senderId ?? auth()->id(),
            'recipient_phone' => $cleanPhone,
            'recipient_name' => $recipientName,
            'alert_type' => $alertType,
            'message' => $message,
            'channel' => $channel,
            'status' => $status,
        ]);

        $clickUrl = self::generateClickToChatUrl($cleanPhone, $message);

        return [
            'success' => true,
            'url' => $clickUrl,
            'phone' => $cleanPhone,
            'log_id' => $log->id,
            'channel' => $channel,
        ];
    }

    // =========================================================================
    // DYNAMIC NOTIFICATION TEMPLATES (English + Gujarati Touch)
    // =========================================================================

    /**
     * Template: Official Document Fulfilled & Ready for Download.
     */
    public static function documentReadyMessage(User $user, DocumentRequest $document): string
    {
        $college = 'K.D. Polytechnic, Patan';
        $docType = $document->document_type;
        $downloadUrl = route('documents.download', $document->id);

        return "🏛️ *{$college} — Alumni Association*\n\n"
            . "Namaste *{$user->name}*!\n\n"
            . "Your requested official document (*{$docType}*) has been processed and approved by the college administration.\n\n"
            . "📥 *Download Document:* {$downloadUrl}\n\n"
            . "For any inquiries, contact the Admin Office at 02766-220419.\n"
            . "_Proud to support our alumni!_";
    }

    /**
     * Template: Job Referral Candidate Status Update.
     */
    public static function jobReferralStatusMessage(JobReferral $referral, string $status, ?string $feedback = null): string
    {
        $statusLabel = strtoupper(str_replace('_', ' ', $status));
        $jobTitle = $referral->jobPosting->title ?? 'Engineering Position';
        $company = $referral->jobPosting->company ?? 'GIDC Enterprise';
        $posterName = $referral->jobPosting->user->name ?? 'KDP Alumni Recruiter';

        $msg = "💼 *KDP Alumni Career Referral Pipeline*\n\n"
            . "Hello *{$referral->candidate_name}*,\n\n"
            . "Your referral application for *{$jobTitle}* at *{$company}* has been updated by alumni mentor *{$posterName}*:\n\n"
            . "📌 *Status:* [ {$statusLabel} ]\n";

        if ($feedback) {
            $msg .= "💬 *Recruiter Note:* \"{$feedback}\"\n\n";
        }

        $msg .= "Check your full status & updates on the portal: " . route('jobs.my-applications') . "\n"
            . "_Best of luck for your career progression!_";

        return $msg;
    }

    /**
     * Template: Tool & Book Bank Claim Accepted.
     */
    public static function toolClaimAcceptedMessage(ToolDonation $donation, User $student, ?string $pickupNotes = null): string
    {
        $donorName = $donation->donor->name ?? 'Alumni Mentor';
        $itemTitle = $donation->title;
        $venue = $pickupNotes ?: ($donation->pickup_location ?: 'Campus Mechanical Workshop Office Locker');

        return "📚 *KDP Student Tool & Book Bank*\n\n"
            . "Congratulations *{$student->name}*!\n\n"
            . "Alumnus *{$donorName}* has accepted your request for:\n"
            . "🛠️ *{$itemTitle}*\n\n"
            . "📍 *Pickup Venue:* {$venue}\n"
            . "💸 *Cost:* 100% Free (Pay-It-Forward Initiative)\n\n"
            . "Please collect the item and post a thank-you note once received: " . route('tools.my-requests') . "\n"
            . "_Study hard and pass it forward!_";
    }

    /**
     * Template: Mentorship Guidance Request Alert.
     */
    public static function mentorshipRequestMessage(User $mentor, User $student, string $note): string
    {
        return "🤝 *KDP Alumni Mentorship Navigator*\n\n"
            . "Namaste Er. *{$mentor->name}*,\n\n"
            . "Junior student *{$student->name}* ({$student->department}) has requested your professional mentorship guidance:\n\n"
            . "💬 *Message:* \"{$note}\"\n\n"
            . "View & respond to this request in your KDP Inbox: " . route('messages.inbox', $student->id) . "\n"
            . "_Thank you for guiding the next generation of KDP diploma engineers!_";
    }

    /**
     * Template: Event Digital Ticket Confirmation.
     */
    public static function eventTicketMessage(EventRsvp $rsvp): string
    {
        $eventName = $rsvp->event->title ?? 'KDP Alumni Event';
        $eventDate = $rsvp->event->date ? \Carbon\Carbon::parse($rsvp->event->date)->format('d M, Y') : 'Upcoming';
        $ticketUrl = route('events.ticket', $rsvp->id);

        return "🎟️ *KDP Alumni Digital Event Ticket*\n\n"
            . "Namaste *{$rsvp->user->name}*!\n\n"
            . "Your registration is confirmed for *{$eventName}*.\n"
            . "📅 *Date:* {$eventDate}\n"
            . "👥 *Guests:* {$rsvp->guests_count}\n\n"
            . "📲 *View Your Digital QR Entry Ticket:* {$ticketUrl}\n\n"
            . "Show this QR pass at the college main gate for entry. Looking forward to welcoming you home!";
    }

    /**
     * Template: General Admin Community Broadcast.
     */
    public static function communityBroadcastMessage(string $title, string $messageBody, ?string $link = null): string
    {
        $msg = "📢 *K.D. Polytechnic Patan — Alumni Broadcast*\n\n"
            . "*{$title}*\n\n"
            . "{$messageBody}\n\n";

        if ($link) {
            $msg .= "🔗 *Details / Action Link:* {$link}\n\n";
        }

        $msg .= "_KDP Alumni Association, Patan_";
        return $msg;
    }
}
