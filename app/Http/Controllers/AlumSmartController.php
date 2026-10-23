<?php

namespace App\Http\Controllers;

use App\Models\CampusMemorySpot;
use App\Models\DocumentRequest;
use App\Models\JobPosting;
use App\Models\RegionalChapter;
use App\Models\Reunion;
use App\Models\ToolDonation;
use App\Models\User;
use App\Services\WhatsAppAlertService;
use Illuminate\Http\Request;

class AlumSmartController extends Controller
{
    /**
     * Natural Language Search & Smart Concierge Endpoint.
     */
    public function search(Request $request)
    {
        $rawQuery = trim($request->input('q', ''));

        if (strlen($rawQuery) < 2) {
            return response()->json([
                'query' => $rawQuery,
                'total_results' => 0,
                'alumni' => [],
                'jobs' => [],
                'tools' => [],
                'quick_actions' => $this->getDefaultQuickActions(),
                'suggestions' => $this->getSuggestedQueries(),
            ]);
        }

        $queryLower = strtolower($rawQuery);
        $tokens = array_filter(explode(' ', $queryLower), fn($t) => strlen($t) > 1);
        $matchedDept = null;

        // Match department keywords
        $deptKeywords = [
            'mechanical' => 'Mechanical Engineering',
            'electrical' => 'Electrical Engineering',
            'civil' => 'Civil Engineering',
            'computer' => 'Computer Engineering',
            'science' => 'Applied Science & Humanities',
        ];

        foreach ($deptKeywords as $key => $dept) {
            if (str_contains($queryLower, $key)) {
                $matchedDept = $dept;
                $queryLower = trim(str_replace($key, '', $queryLower));
                break;
            }
        }

        $remainingTokens = array_filter(explode(' ', $queryLower), fn($t) => strlen($t) > 1);

        // 1. MATCH ALUMNI / STUDENTS
        $alumniQuery = User::where('is_approved', true);

        if ($matchedDept) {
            $alumniQuery->where(function($q) use ($matchedDept) {
                $q->where('department', $matchedDept)
                  ->orWhereHas('profile', fn($pq) => $pq->where('department', $matchedDept));
            });
        }

        if (!empty($remainingTokens)) {
            $alumniQuery->where(function($q) use ($remainingTokens) {
                foreach ($remainingTokens as $token) {
                    $q->where(function($sub) use ($token) {
                        $sub->where('name', 'like', "%{$token}%")
                            ->orWhere('company', 'like', "%{$token}%")
                            ->orWhere('designation', 'like', "%{$token}%")
                            ->orWhere('skills', 'like', "%{$token}%")
                            ->orWhereHas('profile', function($pq) use ($token) {
                                $pq->where('current_company', 'like', "%{$token}%")
                                   ->orWhere('city', 'like', "%{$token}%")
                                   ->orWhere('job_title', 'like', "%{$token}%");
                            });
                    });
                }
            });
        } elseif (!$matchedDept) {
            $alumniQuery->where(function($q) use ($rawQuery) {
                $q->where('name', 'like', "%{$rawQuery}%")
                  ->orWhere('company', 'like', "%{$rawQuery}%")
                  ->orWhere('designation', 'like', "%{$rawQuery}%")
                  ->orWhere('skills', 'like', "%{$rawQuery}%")
                  ->orWhereHas('profile', function($pq) use ($rawQuery) {
                      $pq->where('current_company', 'like', "%{$rawQuery}%")
                         ->orWhere('city', 'like', "%{$rawQuery}%")
                         ->orWhere('job_title', 'like', "%{$rawQuery}%");
                  });
            });
        }

        $alumniResults = $alumniQuery->take(6)->get()->map(function($user) {
            $phone = $user->phone;
            $waUrl = null;
            if ($phone) {
                $waMsg = "Namaste {$user->name}! I discovered your profile on the K.D. Polytechnic Alumni Portal and wanted to connect.";
                $waUrl = WhatsAppAlertService::generateClickToChatUrl($phone, $waMsg);
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => ucfirst($user->role),
                'department' => $user->department ?? ($user->profile->department ?? 'Engineering'),
                'graduation_year' => $user->graduation_year ?? ($user->profile->graduation_year ?? 'N/A'),
                'company' => $user->company ?? ($user->profile->current_company ?? 'KDP Alumnus'),
                'designation' => $user->designation ?? ($user->profile->job_title ?? ''),
                'city' => $user->profile->city ?? '',
                'avatar' => $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1c3272&color=fff',
                'profile_url' => route('alumni.show', $user->id),
                'whatsapp_url' => $waUrl,
            ];
        });

        // 2. MATCH OPPORTUNITIES / JOBS
        $jobQuery = JobPosting::where('is_active', true);
        $jobQuery->where(function($q) use ($rawQuery, $tokens) {
            $q->where('title', 'like', "%{$rawQuery}%")
              ->orWhere('company', 'like', "%{$rawQuery}%")
              ->orWhere('location', 'like', "%{$rawQuery}%")
              ->orWhere('description', 'like', "%{$rawQuery}%");

            foreach ($tokens as $token) {
                $q->orWhere('title', 'like', "%{$token}%")
                  ->orWhere('company', 'like', "%{$token}%");
            }
        });

        $jobResults = $jobQuery->take(4)->get()->map(fn($job) => [
            'id' => $job->id,
            'title' => $job->title,
            'company' => $job->company,
            'location' => $job->location,
            'type' => $job->type,
            'url' => route('jobs.index', ['search' => $job->title]),
        ]);

        // 3. MATCH TOOL & BOOK BANK ITEMS
        $toolQuery = ToolDonation::query();
        $toolQuery->where(function($q) use ($rawQuery, $tokens) {
            $q->where('title', 'like', "%{$rawQuery}%")
              ->orWhere('category', 'like', "%{$rawQuery}%")
              ->orWhere('department', 'like', "%{$rawQuery}%")
              ->orWhere('description', 'like', "%{$rawQuery}%");

            foreach ($tokens as $token) {
                $q->orWhere('title', 'like', "%{$token}%")
                  ->orWhere('category', 'like', "%{$token}%");
            }
        });

        $toolResults = $toolQuery->take(4)->get()->map(fn($tool) => [
            'id' => $tool->id,
            'title' => $tool->title,
            'category' => $tool->category,
            'condition' => $tool->condition,
            'status' => $tool->status,
            'department' => $tool->department,
            'url' => route('tools.show', $tool->slug),
        ]);

        // 4. SMART CONTEXTUAL QUICK ACTIONS
        $quickActions = [];

        // Document request detection
        if (preg_match('/(transcript|bonafide|passing|certificate|marksheet|document|degree)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Request Official College Document',
                'subtitle' => 'Apply for Transcripts, Bonafide, or Passing Certificate online',
                'icon' => '🏛️',
                'url' => route('documents.index'),
                'action_label' => 'Open Document Desk',
            ];
        }

        // Campus Time-Machine / Memory detection
        if (preg_match('/(history|memory|vintage|campus|time machine|1985|workshop|photo|gallery)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Explore Campus Time-Machine (Then vs Now)',
                'subtitle' => 'Interactive archival blueprint and historical comparison slider',
                'icon' => '⏳',
                'url' => route('gallery.index'),
                'action_label' => 'Launch Time-Machine',
            ];
        }

        // Job Referral pipeline detection
        if (preg_match('/(referral|refer|resume|apply|job|hiring)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Alumni Job Board & Referral Desk',
                'subtitle' => 'Browse engineering openings or request an alumni referral',
                'icon' => '💼',
                'url' => route('jobs.index'),
                'action_label' => 'Browse Jobs',
            ];
        }

        // Awards & Hall of Fame detection
        if (preg_match('/(award|fame|hall of fame|nomination|trophy|honors)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Annual Alumni Awards & Virtual Hall of Fame',
                'subtitle' => 'Cast votes for distinguished alumni or view permanent inductees',
                'icon' => '🏆',
                'url' => route('awards.index'),
                'action_label' => 'View Hall of Fame',
            ];
        }

        // Tool Bank detection
        if (preg_match('/(tool|book|drafter|caliper|calculator|donate)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Student Tool & Book Bank',
                'subtitle' => 'Pay-it-forward engineering equipment & study book catalog',
                'icon' => '📚',
                'url' => route('tools.index'),
                'action_label' => 'Explore Tool Bank',
            ];
        }

        // Regional Chapter detection
        if (preg_match('/(chapter|surat|ahmedabad|vadodara|patan|reunion|map)/i', $queryLower)) {
            $quickActions[] = [
                'title' => 'Regional Alumni Chapters & Geo-Map',
                'subtitle' => 'Connect with batchmates in your city or regional cluster',
                'icon' => '📍',
                'url' => route('chapters.index'),
                'action_label' => 'Open Chapter Map',
            ];
        }

        $totalResults = count($alumniResults) + count($jobResults) + count($toolResults) + count($quickActions);

        return response()->json([
            'query' => $rawQuery,
            'total_results' => $totalResults,
            'alumni' => $alumniResults,
            'jobs' => $jobResults,
            'tools' => $toolResults,
            'quick_actions' => $quickActions,
            'suggestions' => $this->getSuggestedQueries(),
        ]);
    }

    private function getDefaultQuickActions(): array
    {
        return [
            [
                'title' => 'Official Document Desk',
                'subtitle' => 'Request transcripts, bonafide letters, or verified certificates',
                'icon' => '🏛️',
                'url' => route('documents.index'),
                'action_label' => 'Apply Online',
            ],
            [
                'title' => 'Campus Time-Machine (Then vs Now)',
                'subtitle' => 'Explore vintage vs 2026 interactive campus photo comparison',
                'icon' => '⏳',
                'url' => route('gallery.index'),
                'action_label' => 'Open Time-Machine',
            ],
            [
                'title' => 'Tool & Book Bank Catalog',
                'subtitle' => 'Borrow drafting kits, instruments, or donate items to juniors',
                'icon' => '🛠️',
                'url' => route('tools.index'),
                'action_label' => 'Browse Items',
            ],
            [
                'title' => 'Virtual Hall of Fame',
                'subtitle' => 'Explore distinguished KDP alumni and annual award winners',
                'icon' => '🏆',
                'url' => route('awards.hall-of-fame'),
                'action_label' => 'Explore Legends',
            ],
        ];
    }

    private function getSuggestedQueries(): array
    {
        return [
            'Mechanical Alumni in Adani',
            'Vernier Caliper in Tool Bank',
            'Jobs in Ahmedabad',
            'Request Transcript',
            'Electrical Engineering 2020',
            'Civil Engineers in Surat',
        ];
    }
}
