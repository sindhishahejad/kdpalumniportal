<?php

namespace App\Http\Controllers;

use App\Models\AcademicSchedule;
use App\Models\BatchReunion;
use App\Models\CampusMemorySpot;
use App\Models\DocumentRequest;
use App\Models\ELibraryItem;
use App\Models\Event;
use App\Models\FacultyMember;
use App\Models\JobPosting;
use App\Models\Lab;
use App\Models\MentorshipListing;
use App\Models\Notice;
use App\Models\PlacementPartner;
use App\Models\ProjectPitch;
use App\Models\RegionalChapter;
use App\Models\ToolDonation;
use App\Models\User;
use App\Services\WhatsAppAlertService;
use Illuminate\Http\Request;

class AlumSmartController extends Controller
{
    /**
     * Universal Natural Language Search & Smart Concierge Endpoint.
     */
    public function search(Request $request)
    {
        $rawQuery = trim($request->input('q', ''));
        $allServices = $this->getPortalServicesRegistry();

        // When query is empty, return initial master catalog view
        if ($rawQuery === '') {
            return response()->json([
                'query' => $rawQuery,
                'total_results' => 0,
                'spotlight' => null,
                'services' => $allServices,
                'alumni' => [],
                'jobs' => [],
                'tools' => [],
                'events' => [],
                'labs' => [],
                'mentorships' => [],
                'pitches' => [],
                'landmarks' => [],
                'notices' => [],
                'elibrary' => [],
                'faculty' => [],
                'placements' => [],
                'calendar' => [],
                'quick_actions' => $this->getDefaultQuickActions(),
                'suggestions' => $this->getSuggestedQueries(),
            ]);
        }

        $queryLower = mb_strtolower($rawQuery, 'UTF-8');
        $qLen = mb_strlen($queryLower);
        $tokens = array_values(array_filter(explode(' ', $queryLower), fn($t) => $t !== ''));

        // 1. MATCH PORTAL SERVICES & FEATURES (Supports case variations, half-words, acronyms, prefixes)
        $matchedServicesData = $this->matchPortalServices($rawQuery, $queryLower, $tokens);
        $matchedServices = $matchedServicesData['services'];
        $spotlightService = $matchedServicesData['spotlight'];

        // Department extraction (supports full branch names and short/half forms: mech, elec, civ, comp)
        $matchedDept = null;
        $deptKeywords = [
            'mechanical' => 'Mechanical Engineering',
            'mech' => 'Mechanical Engineering',
            'electrical' => 'Electrical Engineering',
            'elec' => 'Electrical Engineering',
            'civil' => 'Civil Engineering',
            'civ' => 'Civil Engineering',
            'computer' => 'Computer Engineering',
            'comp' => 'Computer Engineering',
            'science' => 'Applied Science & Humanities',
        ];
        uksort($deptKeywords, fn($a, $b) => strlen($b) <=> strlen($a));

        $strippedQuery = $queryLower;
        foreach ($deptKeywords as $key => $dept) {
            if (str_contains($queryLower, $key)) {
                $matchedDept = $dept;
                $strippedQuery = trim(str_replace($key, '', $strippedQuery));
                break;
            }
        }

        $remainingTokens = array_values(array_filter(explode(' ', $strippedQuery), fn($t) => $t !== ''));

        // 2. MATCH ALUMNI / STUDENTS
        $alumniQuery = User::where('is_approved', true);

        if ($matchedDept) {
            $alumniQuery->where(function($q) use ($matchedDept) {
                $q->where('department', $matchedDept)
                  ->orWhereHas('profile', fn($pq) => $pq->where('department', $matchedDept));
            });
        }

        $pattern = ($qLen === 1) ? "{$rawQuery}%" : "%{$rawQuery}%";

        // 2. MATCH ALUMNI / STUDENTS
        $alumniResults = [];
        try {
            $alumniQuery = User::where('is_approved', true);

            if ($matchedDept) {
                $alumniQuery->where(function($q) use ($matchedDept) {
                    $q->where('department', $matchedDept)
                      ->orWhereHas('profile', fn($pq) => $pq->where('department', $matchedDept));
                });
            }

            if (!empty($remainingTokens)) {
                $alumniQuery->where(function($q) use ($remainingTokens, $qLen) {
                    foreach ($remainingTokens as $token) {
                        $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                        $q->where(function($sub) use ($tokenPattern) {
                            $sub->where('name', 'like', $tokenPattern)
                                ->orWhere('company', 'like', $tokenPattern)
                                ->orWhere('designation', 'like', $tokenPattern)
                                ->orWhere('skills', 'like', $tokenPattern)
                                ->orWhere('department', 'like', $tokenPattern)
                                ->orWhereHas('profile', function($pq) use ($tokenPattern) {
                                    $pq->where('current_company', 'like', $tokenPattern)
                                       ->orWhere('location', 'like', $tokenPattern)
                                       ->orWhere('job_title', 'like', $tokenPattern)
                                       ->orWhere('department', 'like', $tokenPattern);
                                });
                        });
                    }
                });
            } elseif (!$matchedDept) {
                $alumniQuery->where(function($q) use ($pattern) {
                    $q->where('name', 'like', $pattern)
                      ->orWhere('company', 'like', $pattern)
                      ->orWhere('designation', 'like', $pattern)
                      ->orWhere('skills', 'like', $pattern)
                      ->orWhere('department', 'like', $pattern)
                      ->orWhereHas('profile', function($pq) use ($pattern) {
                          $pq->where('current_company', 'like', $pattern)
                             ->orWhere('location', 'like', $pattern)
                             ->orWhere('job_title', 'like', $pattern)
                             ->orWhere('department', 'like', $pattern);
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
                    'city' => $user->profile->location ?? '',
                    'avatar' => $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1c3272&color=fff',
                    'profile_url' => route('alumni.show', $user->id),
                    'whatsapp_url' => $waUrl,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart alumni search error: ' . $e->getMessage());
        }

        // 3. MATCH OPPORTUNITIES / JOBS
        $jobResults = [];
        try {
            $jobQuery = JobPosting::where('is_active', true);
            $jobQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('company', 'like', $pattern)
                  ->orWhere('location', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern)
                      ->orWhere('company', 'like', $tokenPattern);
                }
            });

            $jobResults = $jobQuery->take(4)->get()->map(fn($job) => [
                'id' => $job->id,
                'title' => $job->title,
                'company' => $job->company,
                'location' => $job->location,
                'type' => $job->employment_type ?? $job->type ?? 'Full-time',
                'url' => route('jobs.index', ['search' => $job->title]),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart job search error: ' . $e->getMessage());
        }

        // 4. MATCH TOOL & BOOK BANK ITEMS
        $toolResults = [];
        try {
            $toolQuery = ToolDonation::query();
            $toolQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('item_type', 'like', $pattern)
                  ->orWhere('department', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern)
                      ->orWhere('item_type', 'like', $tokenPattern);
                }
            });

            $toolResults = $toolQuery->take(4)->get()->map(fn($tool) => [
                'id' => $tool->id,
                'title' => $tool->title,
                'category' => $tool->item_type,
                'condition' => $tool->condition,
                'status' => $tool->status,
                'department' => $tool->department,
                'url' => route('tools.show', $tool->slug),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart tool search error: ' . $e->getMessage());
        }

        // 5. MATCH CAMPUS EVENTS & BATCH REUNIONS
        $eventResults = [];
        try {
            $events = Event::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('category', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern);
                }
            })->take(3)->get();

            foreach ($events as $ev) {
                $eventResults[] = [
                    'type' => 'event',
                    'title' => $ev->title,
                    'category' => $ev->category,
                    'subtitle' => $ev->event_date ? $ev->event_date->format('M d, Y') . ' • ' . $ev->time_display : 'Upcoming Event',
                    'url' => route('events.show', $ev->id),
                    'icon' => '📅',
                ];
            }

            $reunions = BatchReunion::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('department', 'like', $pattern)
                  ->orWhere('city', 'like', $pattern)
                  ->orWhere('milestone_tag', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern);
                }
            })->take(3)->get();

            foreach ($reunions as $reunion) {
                $eventResults[] = [
                    'type' => 'reunion',
                    'title' => $reunion->title,
                    'category' => $reunion->milestone_tag ?? 'Batch Reunion',
                    'subtitle' => $reunion->city . ' • ' . ($reunion->event_date ? $reunion->event_date->format('M d, Y') : 'Announced'),
                    'url' => route('reunions.show', $reunion->slug),
                    'icon' => '🎉',
                ];
            }
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart event/reunion search error: ' . $e->getMessage());
        }

        // 6. MATCH DEPARTMENT LABS & WORKSHOPS
        $labResults = [];
        try {
            $labResults = Lab::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('name', 'like', $pattern)
                  ->orWhere('department', 'like', $pattern)
                  ->orWhere('equipment_list', 'like', $pattern)
                  ->orWhere('location', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('name', 'like', $tokenPattern)
                      ->orWhere('department', 'like', $tokenPattern);
                }
            })->take(3)->get()->map(fn($lab) => [
                'id' => $lab->id,
                'title' => $lab->name,
                'department' => $lab->department,
                'location' => $lab->location,
                'faculty' => $lab->faculty_in_charge,
                'url' => route('labs.show', $lab->id),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart lab search error: ' . $e->getMessage());
        }

        // 7. MATCH MENTORSHIP GUIDANCE LISTINGS
        $mentorshipResults = [];
        try {
            $mentorshipResults = MentorshipListing::where('is_available', true)
                ->where(function($q) use ($pattern, $tokens, $qLen) {
                    $q->where('title', 'like', $pattern)
                      ->orWhere('expertise_areas', 'like', $pattern)
                      ->orWhere('description', 'like', $pattern);
                    foreach ($tokens as $token) {
                        $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                        $q->orWhere('expertise_areas', 'like', $tokenPattern);
                    }
                })->with('user')->take(3)->get()->map(fn($m) => [
                    'id' => $m->id,
                    'title' => $m->title,
                    'mentor_name' => $m->user->name ?? 'Senior Mentor',
                    'expertise' => $m->expertise_areas,
                    'url' => route('mentorship.index'),
                ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart mentorship search error: ' . $e->getMessage());
        }

        // 8. MATCH PROJECT INNOVATION PITCHES
        $pitchResults = [];
        try {
            $pitchResults = ProjectPitch::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('tech_stack', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern);
                }
            })->with('student')->take(3)->get()->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'student_name' => $p->student->name ?? 'Student Innovator',
                'tech_stack' => $p->tech_stack,
                'status' => ucfirst($p->status),
                'url' => route('pitches.show', $p->id),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart pitch search error: ' . $e->getMessage());
        }

        // 9. MATCH CAMPUS TIME-MACHINE LANDMARKS
        $landmarkResults = [];
        try {
            $landmarkResults = CampusMemorySpot::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('name', 'like', $pattern)
                  ->orWhere('category', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('name', 'like', $tokenPattern);
                }
            })->take(3)->get()->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'category' => $s->category,
                'years' => $s->then_year . ' vs ' . $s->now_year,
                'url' => route('gallery.index'),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart landmark search error: ' . $e->getMessage());
        }

        // 10. MATCH OFFICIAL COLLEGE NOTICES
        $noticeResults = [];
        try {
            $noticeResults = Notice::where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('body', 'like', $pattern);
                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern);
                }
            })->take(3)->get()->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'date' => $n->created_at ? $n->created_at->format('M d, Y') : '',
                'url' => route('dashboard'),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart notice search error: ' . $e->getMessage());
        }

        // 11. MATCH E-LIBRARY RESOURCES & CAPSTONE VAULT
        $elibraryResults = [];
        try {
            $elibQuery = ELibraryItem::where('is_approved', true);
            $elibQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('department', 'like', $pattern)
                  ->orWhere('category', 'like', $pattern)
                  ->orWhere('author_or_team', 'like', $pattern)
                  ->orWhere('subject_name', 'like', $pattern)
                  ->orWhere('tech_stack', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern)
                      ->orWhere('subject_name', 'like', $tokenPattern)
                      ->orWhere('category', 'like', $tokenPattern);
                }
            });

            $elibraryResults = $elibQuery->take(4)->get()->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'category' => $item->category_label ?? ucfirst(str_replace('_', ' ', $item->category)),
                'department' => $item->department,
                'semester' => $item->semester ? "Sem {$item->semester}" : 'All Semesters',
                'author' => $item->author_or_team,
                'format' => strtoupper($item->file_format ?? 'PDF'),
                'downloads' => $item->downloads_count,
                'url' => route('elibrary.show', $item->slug),
                'download_url' => route('elibrary.download', $item->id),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart elibrary search error: ' . $e->getMessage());
        }

        // 12. MATCH FACULTY & HOD DIRECTORY
        $facultyResults = [];
        try {
            $facultyQuery = FacultyMember::query();
            $facultyQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('name', 'like', $pattern)
                  ->orWhere('designation', 'like', $pattern)
                  ->orWhere('department', 'like', $pattern)
                  ->orWhere('qualification', 'like', $pattern)
                  ->orWhere('specialization', 'like', $pattern)
                  ->orWhere('email', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('name', 'like', $tokenPattern)
                      ->orWhere('department', 'like', $tokenPattern)
                      ->orWhere('specialization', 'like', $tokenPattern);
                }
            });

            $facultyResults = $facultyQuery->orderBy('is_hod', 'desc')->take(4)->get()->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'designation' => $f->designation,
                'department' => $f->department,
                'qualification' => $f->qualification,
                'specialization' => $f->specialization,
                'is_hod' => (bool) $f->is_hod,
                'email' => $f->email,
                'url' => route('faculty.index', ['search' => $f->name]),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart faculty search error: ' . $e->getMessage());
        }

        // 13. MATCH TPO CORPORATE HIRING PARTNERS
        $placementResults = [];
        try {
            $placementQuery = PlacementPartner::where('is_active', true);
            $placementQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('name', 'like', $pattern)
                  ->orWhere('sector', 'like', $pattern)
                  ->orWhere('roles_offered', 'like', $pattern)
                  ->orWhere('hiring_departments', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('name', 'like', $tokenPattern)
                      ->orWhere('sector', 'like', $tokenPattern);
                }
            });

            $placementResults = $placementQuery->take(4)->get()->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sector' => $p->sector,
                'package_range' => $p->package_range,
                'roles' => $p->roles_offered,
                'departments' => $p->departments_list,
                'website' => $p->website_url,
                'url' => route('placement.index', ['search' => $p->name]),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart placement search error: ' . $e->getMessage());
        }

        // 14. MATCH GTU ACADEMIC CALENDAR & CIRCULARS
        $calendarResults = [];
        try {
            $calendarQuery = AcademicSchedule::where('is_active', true);
            $calendarQuery->where(function($q) use ($pattern, $tokens, $qLen) {
                $q->where('title', 'like', $pattern)
                  ->orWhere('description', 'like', $pattern)
                  ->orWhere('category', 'like', $pattern)
                  ->orWhere('semester_scope', 'like', $pattern);

                foreach ($tokens as $token) {
                    $tokenPattern = ($qLen === 1) ? "{$token}%" : "%{$token}%";
                    $q->orWhere('title', 'like', $tokenPattern)
                      ->orWhere('category', 'like', $tokenPattern);
                }
            });

            $calendarResults = $calendarQuery->take(4)->get()->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'category' => $c->category_badge['label'] ?? ucfirst($c->category),
                'icon' => $c->category_badge['icon'] ?? '🗓️',
                'semester_scope' => $c->semester_scope,
                'date_range' => $c->start_date ? $c->start_date->format('M d, Y') . ($c->end_date ? ' - ' . $c->end_date->format('M d, Y') : '') : 'Scheduled',
                'is_important' => (bool) $c->is_important,
                'url' => route('calendar.index', ['search' => $c->title]),
            ])->toArray();
        } catch (\Throwable $e) {
            \Log::warning('AlumSmart calendar search error: ' . $e->getMessage());
        }

        // Map matched services into quick_actions for backwards test compatibility
        $quickActions = array_map(fn($s) => [
            'title' => $s['title'],
            'subtitle' => $s['description'],
            'icon' => $s['icon'],
            'url' => $s['url'],
            'action_label' => $s['action_label'],
        ], array_slice($matchedServices, 0, 4));

        if (empty($quickActions)) {
            $quickActions = $this->getDefaultQuickActions();
        }

        $totalResults = count($matchedServices) 
            + count($alumniResults) 
            + count($jobResults) 
            + count($toolResults) 
            + count($eventResults) 
            + count($labResults) 
            + count($mentorshipResults) 
            + count($pitchResults) 
            + count($landmarkResults) 
            + count($noticeResults)
            + count($elibraryResults)
            + count($facultyResults)
            + count($placementResults)
            + count($calendarResults);

        return response()->json([
            'query' => $rawQuery,
            'total_results' => $totalResults,
            'spotlight' => $spotlightService,
            'services' => $matchedServices,
            'alumni' => $alumniResults,
            'jobs' => $jobResults,
            'tools' => $toolResults,
            'events' => $eventResults,
            'labs' => $labResults,
            'mentorships' => $mentorshipResults,
            'pitches' => $pitchResults,
            'landmarks' => $landmarkResults,
            'notices' => $noticeResults,
            'elibrary' => $elibraryResults,
            'faculty' => $facultyResults,
            'placements' => $placementResults,
            'calendar' => $calendarResults,
            'quick_actions' => $quickActions,
            'suggestions' => $this->getSuggestedQueries(),
        ]);
    }

    /**
     * Match queries against the complete 18 Portal Services Catalog.
     * Supports case-insensitivity, single-letter queries, half-words (prefixes),
     * partial substring matching, acronyms, and multi-word token scoring.
     */
    private function matchPortalServices(string $rawQuery, string $queryLower, array $tokens): array
    {
        $allServices = $this->getPortalServicesRegistry();
        $matched = [];
        $topScore = 0;
        $spotlight = null;
        $qLen = mb_strlen($queryLower, 'UTF-8');
        $compactQuery = str_replace([' ', '-', '_', '.', '/', '(', ')'], '', $queryLower);

        foreach ($allServices as $service) {
            $score = 0;
            $serviceTitleLower = mb_strtolower($service['title'], 'UTF-8');
            $serviceDescLower = mb_strtolower($service['description'], 'UTF-8');
            $serviceCatLower = mb_strtolower($service['category'], 'UTF-8');
            $compactTitle = str_replace([' ', '-', '_', '.', '/', '(', ')'], '', $serviceTitleLower);

            $titleWords = preg_split('/[\s\-\/\(\),:;.]+/u', $serviceTitleLower, -1, PREG_SPLIT_NO_EMPTY);
            $catWords = preg_split('/[\s\-\/\(\),:;.]+/u', $serviceCatLower, -1, PREG_SPLIT_NO_EMPTY);

            // 1. EXACT TITLE MATCH
            if ($serviceTitleLower === $queryLower) {
                $score += 300;
            }

            // 2. EXACT KEYWORD MATCH (Highest precision for terms like "id", "transcript", "mentor", "jobs")
            $exactKwMatched = false;
            foreach ($service['keywords'] as $kw) {
                $kwLower = mb_strtolower($kw, 'UTF-8');
                if ($kwLower === $queryLower) {
                    $score += 220;
                    $exactKwMatched = true;
                    break;
                }
            }

            // 3. TITLE WORD EXACT MATCH (e.g. "id" in "Digital Alumni Smart ID Card", "jobs" in "Jobs Board")
            foreach ($titleWords as $tw) {
                if ($tw === $queryLower) {
                    $score += 160;
                    break;
                }
            }

            // 4. TITLE WORD PREFIX / HALF-WORD MATCH (e.g. "tra" for "transcript", "car" for "card", "men" for "mentorship")
            foreach ($titleWords as $tw) {
                if (str_starts_with($tw, $queryLower)) {
                    $score += 110;
                    break;
                }
            }

            // 5. KEYWORD WORD PREFIX / HALF-WORD MATCH (e.g. "tra" for "transcript", "ver" for "vernier", "lat" for "lathe")
            if (!$exactKwMatched) {
                foreach ($service['keywords'] as $kw) {
                    $kwLower = mb_strtolower($kw, 'UTF-8');
                    $kwWords = preg_split('/[\s\-\/\(\),:;.]+/u', $kwLower, -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($kwWords as $kwWord) {
                        if (str_starts_with($kwWord, $queryLower)) {
                            $score += 90;
                            break 2;
                        }
                    }
                }
            }

            // 6. COMPACT STRING MATCH (e.g. "idcard" -> "ID Card", "timemachine" -> "Time-Machine", "smartid" -> "Smart ID")
            if ($qLen >= 3 && str_contains($compactTitle, $compactQuery)) {
                $score += 80;
            }

            // 7. TITLE CONTAINS PHRASE (if query is at least 2 chars)
            if ($qLen >= 2 && str_contains($serviceTitleLower, $queryLower)) {
                $score += 60;
            }

            // 8. CATEGORY PREFIX / CONTAINS
            foreach ($catWords as $cw) {
                if (str_starts_with($cw, $queryLower)) {
                    $score += 50;
                    break;
                }
            }
            if ($qLen >= 2 && str_contains($serviceCatLower, $queryLower)) {
                $score += 30;
            }

            // 9. SUBSTRING MATCH IN KEYWORDS & DESCRIPTION (for queries >= 3 chars to avoid false positives on 1-2 letter short forms)
            if ($qLen >= 3) {
                foreach ($service['keywords'] as $kw) {
                    $kwLower = mb_strtolower($kw, 'UTF-8');
                    if (str_contains($kwLower, $queryLower)) {
                        $score += 40;
                        break;
                    }
                }
                if (str_contains($serviceDescLower, $queryLower)) {
                    $score += 25;
                }
            }

            // 10. MULTI-TOKEN SCORING
            if (count($tokens) > 1) {
                $matchedTokenCount = 0;
                foreach ($tokens as $token) {
                    $tokenHit = false;
                    foreach ($titleWords as $tw) {
                        if ($tw === $token || str_starts_with($tw, $token)) {
                            $score += 25;
                            $tokenHit = true;
                            break;
                        }
                    }
                    if (!$tokenHit) {
                        foreach ($service['keywords'] as $kw) {
                            $kwLower = mb_strtolower($kw, 'UTF-8');
                            if ($kwLower === $token || str_starts_with($kwLower, $token)) {
                                $score += 20;
                                $tokenHit = true;
                                break;
                            }
                        }
                    }
                    if ($tokenHit) {
                        $matchedTokenCount++;
                    }
                }
                if ($matchedTokenCount >= 2) {
                    $score += ($matchedTokenCount * 15);
                }
            }

            if ($score > 0) {
                $service['relevance_score'] = $score;
                $matched[] = $service;

                if ($score > $topScore) {
                    $topScore = $score;
                    $spotlight = $service;
                }
            }
        }

        // Sort matched services by score descending
        usort($matched, fn($a, $b) => $b['relevance_score'] <=> $a['relevance_score']);

        return [
            'services' => $matched,
            'spotlight' => ($topScore >= 40) ? $spotlight : null,
        ];
    }

    /**
     * Comprehensive Master Registry of all 18 KDP Portal Services & Features.
     */
    public static function getPortalServicesRegistry(): array
    {
        return [
            [
                'id' => 'smart-id-card',
                'title' => 'Digital Alumni Smart ID Card',
                'category' => 'Identity & Credentials',
                'icon' => '🪪',
                'description' => 'Generate, view, and download your official QR-verified alumni smart digital identity card.',
                'keywords' => [
                    'id', 'card', 'icard', 'smart card', 'smart id', 'identity', 'qr', 'digital id', 'badge', 'download id', 'my card',
                    'આઈડી', 'કાર્ડ', 'ઓળખપત્ર', 'ડીજીટલ કાર્ડ', 'ઓળખ',
                    'आईडी', 'कार्ड', 'पहचान पत्र', 'पहचान', 'स्मार्ट कार्ड'
                ],
                'url' => route('id-card.show'),
                'action_label' => 'Open Smart I-Card',
                'badge' => 'Instant Digital',
                'spotlight_tip' => 'Your official KDP alumni ID card features a cryptographically verifiable QR code linked to your college record.',
            ],
            [
                'id' => 'document-desk',
                'title' => 'Request Official College Document',
                'category' => 'Academic Services',
                'icon' => '🏛️',
                'description' => 'Apply online for official transcripts, bonafide letters, leaving certificates, and degree verifications.',
                'keywords' => [
                    'document', 'documents', 'transcript', 'transcripts', 'bonafide', 'certificate', 'certificates', 'marksheet', 'degree', 'leaving', 'verification', 'duplicate', 'migration', 'apply document', 'gtu verification',
                    'પ્રમાણપત્ર', 'ટ્રાન્સક્રિપ્ટ', 'બોનાફાઈડ', 'દસ્તાવેજ', 'માર્કશીટ', 'ડિગ્રી', 'સર્ટિફિકેટ', 'અરજી',
                    'प्रमाण पत्र', 'दस्तावेज', 'मार्कशीट', 'डिग्री', 'ट्रांसक्रिप्ट', 'सर्टिफिकेट'
                ],
                'url' => route('documents.index'),
                'action_label' => 'Apply for Documents',
                'badge' => 'Online Portal',
                'spotlight_tip' => 'Transcripts and bonafide certificates are verified and signed by the K. D. Polytechnic administrative office within 2-3 business days.',
            ],
            [
                'id' => 'campus-time-machine',
                'title' => 'Campus Time-Machine & Nostalgia Blueprint Map',
                'category' => 'Campus Life & Heritage',
                'icon' => '⏳',
                'description' => 'Interactive historical 1961 vs 2026 "Then vs Now" split slider and authentic Patan blueprint nostalgia map.',
                'keywords' => [
                    'time machine', 'timemachine', 'then now', 'then vs now', 'vintage', 'history', 'nostalgia', 'blueprint', 'map', 'campus map', 'patan campus', 'photos', 'gallery', 'heritage', 'landmarks', '1961', '1985', 'admin porch', 'old photos', 'memories',
                    'નકશો', 'કેમ્પસ', 'જૂના ફોટા', 'ગેલેરી', 'ઇતિહાસ', 'યાદો', 'બ્લુપ્રિન્ટ', 'ટાઈમ મશીન',
                    'नक्शा', 'तस्वीरें', 'इतिहास', 'यादें', 'टाइम मशीन', 'गैलरी'
                ],
                'url' => route('gallery.index'),
                'action_label' => 'Explore Time-Machine',
                'badge' => 'Interactive Map',
                'spotlight_tip' => 'Drag the interactive split-screen slider to witness 60+ years of architectural evolution across the 10 landmark spots of KDP Patan.',
            ],
            [
                'id' => 'alumni-directory',
                'title' => 'Alumni Directory & Senior Network',
                'category' => 'Community & Networking',
                'icon' => '🎓',
                'description' => 'Search thousands of verified KDP diploma alumni across companies, engineering branches, and graduation batches.',
                'keywords' => [
                    'directory', 'alumni', 'senior', 'seniors', 'batchmates', 'classmates', 'graduates', 'find alumni', 'search alumni', 'batch', 'alumnus',
                    'ડિરેક્ટરી', 'ભૂતપૂર્વ વિદ્યાર્થીઓ', 'મિત્રો', 'બેચ', 'શોધ',
                    'पूर्व छात्र', 'छात्र', 'मित्र', 'बैच', 'डायरेक्टरी'
                ],
                'url' => route('alumni.index'),
                'action_label' => 'Browse Alumni',
                'badge' => 'Verified Network',
                'spotlight_tip' => 'Filter by engineering department and company to discover alumni working at Adani, L&T, Tata, Reliance, and government sectors.',
            ],
            [
                'id' => 'job-board',
                'title' => 'Engineering Career Board & Referral Desk',
                'category' => 'Career & Jobs',
                'icon' => '💼',
                'description' => 'Browse verified diploma engineering openings or request direct alumni job referrals with attached resumes.',
                'keywords' => [
                    'job', 'jobs', 'career', 'careers', 'hiring', 'vacancy', 'opening', 'openings', 'referral', 'referrals', 'resume', 'apply', 'interview', 'salary', 'placement', 'internship', 'trainee',
                    'નોકરી', 'જોબ', 'ભરતી', 'કામ', 'ઇન્ટર્નશિપ', 'રેફરલ', 'પ્લેસમેન્ટ',
                    'नौकरी', 'रोजगार', 'भर्ती', 'करियर', 'रेफरल', 'इंटर्नशिप'
                ],
                'url' => route('jobs.index'),
                'action_label' => 'View Job Openings',
                'badge' => 'Active Hiring',
                'spotlight_tip' => 'Alumni can directly refer diploma graduates for entry-level engineering and trainee positions with one-click resume forwarding.',
            ],
            [
                'id' => 'tool-bank',
                'title' => 'Student Tool & Book Donation Bank',
                'category' => 'Pay-it-Forward',
                'icon' => '🛠️',
                'description' => 'Borrow drafting kits, vernier calipers, scientific calculators, and GTU engineering textbooks donated by seniors.',
                'keywords' => [
                    'tool', 'tools', 'book', 'books', 'drafter', 'mini drafter', 'vernier', 'caliper', 'calculator', 'drawing kit', 'textbook', 'donate tool', 'borrow tool', 'equipment', 'pay it forward',
                    'સાધન', 'સાધનો', 'પુસ્તક', 'પુસ્તકો', 'દાન', 'કેલ્ક્યુલેટર', 'ડ્રાફ્ટર', 'ચોપડી',
                    'उपकरण', 'किताबें', 'पुस्तक', 'दान', 'कैलकुलेटर'
                ],
                'url' => route('tools.index'),
                'action_label' => 'Explore Tool Bank',
                'badge' => 'Free Borrowing',
                'spotlight_tip' => 'Junior diploma students can claim available engineering tools and books completely free from graduating alumni.',
            ],
            [
                'id' => 'mentorship-hub',
                'title' => '1-on-1 Alumni Mentorship & Career Guidance',
                'category' => 'Guidance & Mentorship',
                'icon' => '🤝',
                'description' => 'Book 1-on-1 guidance slots with senior alumni for GTU D2D admission, interview prep, and industry skill navigation.',
                'keywords' => [
                    'mentorship', 'mentor', 'mentors', 'guidance', 'career advice', 'd2d', 'gtu d2d', 'diploma to degree', 'counseling', 'session', 'advice', 'guide', 'consult',
                    'માર્ગદર્શન', 'મેન્ટર', 'સલાહ', 'ડીટુડી', 'કરિયર',
                    'मेंटरशिप', 'मार्गदर्शन', 'सलाह', 'परामर्श'
                ],
                'url' => route('mentorship.index'),
                'action_label' => 'Find a Mentor',
                'badge' => '1-on-1 Sessions',
                'spotlight_tip' => 'Get insider advice from alumni who cracked GTU D2D admissions into top engineering colleges across Gujarat.',
            ],
            [
                'id' => 'project-incubation',
                'title' => 'Student Project Incubation & Innovation Pitches',
                'category' => 'Innovation & R&D',
                'icon' => '💡',
                'description' => 'Pitch final-year engineering projects, seek alumni technical guidance, prototype components, and industry sponsorship.',
                'keywords' => [
                    'incubation', 'pitch', 'pitches', 'project', 'projects', 'startup', 'innovation', 'prototype', 'funding', 'sponsor', 'r&d', 'idea', 'final year project',
                    'પ્રોજેક્ટ', 'ઇનોવેશન', 'સ્ટાર્ટઅપ', 'નવીનતા', 'આઇડિયા',
                    'प्रोजेक्ट', 'नवाचार', 'स्टार्टअप', 'आईडिया'
                ],
                'url' => route('pitches.index'),
                'action_label' => 'View Project Pitches',
                'badge' => 'Student Innovation',
                'spotlight_tip' => 'Senior alumni in industry can offer technical mentorship and sponsor components for promising diploma innovation projects.',
            ],
            [
                'id' => 'awards-hall-of-fame',
                'title' => 'Annual Alumni Awards & Virtual Hall of Fame',
                'category' => 'Recognition & Honors',
                'icon' => '🏆',
                'description' => 'Vote for annual alumni excellence awards and explore permanent inductees in the KDP Virtual Hall of Fame.',
                'keywords' => [
                    'award', 'awards', 'hall of fame', 'fame', 'trophy', 'vote', 'voting', 'nominate', 'nomination', 'inductee', 'inductees', 'legends', 'honors', 'distinguished alumni',
                    'એવોર્ડ', 'હોલ ઓફ ફેમ', 'ગૌરવ', 'પુરસ્કાર', 'વોટ', 'નોમિનેશન',
                    'पुरस्कार', 'अवार्ड', 'हॉल ऑफ फेम', 'वोटिंग'
                ],
                'url' => route('awards.index'),
                'action_label' => 'View Hall of Fame',
                'badge' => 'Alumni Honors',
                'spotlight_tip' => 'Celebrate the historic achievements of Kilachand Devchand Polytechnic graduates across 6 decades of engineering leadership.',
            ],
            [
                'id' => 'regional-chapters',
                'title' => 'Regional Alumni Chapters & Geo-Map',
                'category' => 'Regional Network',
                'icon' => '📍',
                'description' => 'Connect with batchmates living in your region across Ahmedabad, Surat, Vadodara, Patan, and international clusters.',
                'keywords' => [
                    'chapter', 'chapters', 'regional', 'geo map', 'map', 'surat', 'ahmedabad', 'vadodara', 'patan', 'rajkot', 'gandhinagar', 'meetup', 'city', 'location',
                    'શાખાઓ', 'ચેપ્ટર', 'પ્રાદેશિક', 'સુરત', 'અમદાવાદ', 'પાટણ', 'વડોદરા',
                    'चैप्टर', 'क्षेत्रीय', 'सूरत', 'अहमदाबाद', 'पाटन'
                ],
                'url' => route('chapters.index'),
                'action_label' => 'Explore Chapter Map',
                'badge' => 'Geo-Clustered',
                'spotlight_tip' => 'Join your city\'s regional alumni chapter to participate in local tech meetups, networking dinners, and family gatherings.',
            ],
            [
                'id' => 'batch-reunions',
                'title' => 'Batch Reunion Planner & RSVP Desk',
                'category' => 'Events & Gatherings',
                'icon' => '🎉',
                'description' => 'RSVP for upcoming milestone reunions, view attendee lists, and share throwback photos on the Batch Memory Wall.',
                'keywords' => [
                    'reunion', 'reunions', 'batch reunion', 'get together', 'gathering', 'meet', 'rsvp', 'silver jubilee', 'golden jubilee', 'memory wall',
                    'સ્નેહમિલન', 'રિયુનિયન', 'મેળાવડો', 'યાદો',
                    'रीयूनियन', 'मिलन', 'बैठक', 'आयोजन'
                ],
                'url' => route('reunions.index'),
                'action_label' => 'View Reunions',
                'badge' => 'Milestone Meets',
                'spotlight_tip' => 'Reunion organizers can collect RSVPs and organize campus visits with official college permissions.',
            ],
            [
                'id' => 'department-labs',
                'title' => 'Department Labs & Workshop Directory',
                'category' => 'Campus Infrastructure',
                'icon' => '🔬',
                'description' => 'Explore laboratory machinery, equipment inventories, machine shops, and faculty coordinator records.',
                'keywords' => [
                    'lab', 'labs', 'workshop', 'workshops', 'lathe', 'machine shop', 'foundry', 'cnc', 'equipment', 'instruments', 'computer lab', 'mechanical lab', 'electrical lab', 'civil lab',
                    'લેબ', 'વર્કશોપ', 'પ્રયોગશાળા', 'મશીન', 'સાધન',
                    'प्रयोगशाला', 'लैब', 'वर्कशॉप', 'मशीन'
                ],
                'url' => route('labs.index'),
                'action_label' => 'Inspect Labs',
                'badge' => 'Campus Labs',
                'spotlight_tip' => 'View room numbers, safety regulations, and equipment lists across Mechanical, Electrical, Civil, and Computer departments.',
            ],
            [
                'id' => 'giving-back',
                'title' => 'Giving Back & Campus Development Fund',
                'category' => 'Alumni Contribution',
                'icon' => '🤝',
                'description' => 'Support modern laboratory upgrades, smart classrooms, and merit scholarships for needy polytechnic students.',
                'keywords' => [
                    'giving back', 'donate', 'donation', 'contribute', 'contribution', 'fund', 'sponsorship', 'scholarship', 'support', '80g', 'receipt',
                    'દાન', 'ફાળો', 'સહાય', 'સ્કોલરશીપ',
                    'दान', 'सहयोग', 'मदद', 'छात्रवृत्ति'
                ],
                'url' => route('donations.index'),
                'action_label' => 'Contribute Fund',
                'badge' => 'Tax Exempted',
                'spotlight_tip' => 'Every contribution directly funds laboratory modernization and needy student tuition with instant downloadable tax receipts.',
            ],
            [
                'id' => 'knowledge-feed',
                'title' => 'Community Knowledge Feed & Tech Challenges',
                'category' => 'Discussion Forum',
                'icon' => '💬',
                'description' => 'Participate in engineering discussions, ask technical questions, and solve weekly polytechnic coding & design challenges.',
                'keywords' => [
                    'feed', 'post', 'posts', 'discussion', 'discussions', 'forum', 'question', 'questions', 'challenge', 'coding challenge', 'community',
                    'ચર્ચા', 'પ્રશ્ન', 'પોસ્ટ', 'ફોરમ',
                    'चर्चा', 'पोस्ट', 'प्रश्न', 'मंच'
                ],
                'url' => route('posts.index'),
                'action_label' => 'Open Knowledge Feed',
                'badge' => 'Active Forum',
                'spotlight_tip' => 'Post engineering queries and get answers from practicing alumni engineers and faculty professors.',
            ],
            [
                'id' => 'campus-events',
                'title' => 'Campus Activities, Tech Fests & Seminars',
                'category' => 'Campus Activities',
                'icon' => '📅',
                'description' => 'Browse upcoming seminars, guest lectures, annual technical symposiums, and get digital event tickets.',
                'keywords' => [
                    'event', 'events', 'symposium', 'seminar', 'workshop', 'tech fest', 'sports week', 'convocation', 'ticket', 'rsvp', 'activity',
                    'કાર્યક્રમ', 'ઇવેન્ટ્સ', 'સેમિનાર', 'સ્પોર્ટ્સ',
                    'कार्यक्रम', 'इवेंट', 'सेमिनार', 'उत्सव'
                ],
                'url' => route('events.index'),
                'action_label' => 'View Events Calendar',
                'badge' => 'College Events',
                'spotlight_tip' => 'RSVP for campus guest lectures and alumni expert sessions with automated WhatsApp confirmations.',
            ],
            [
                'id' => 'study-resources',
                'title' => 'Engineering Study Resources & Notes',
                'category' => 'Study Materials',
                'icon' => '📚',
                'description' => 'Download semester-wise engineering lecture notes, GTU previous question papers, and lab manuals.',
                'keywords' => [
                    'resource', 'resources', 'notes', 'study material', 'papers', 'previous papers', 'syllabus', 'gtu', 'lab manual', 'ebook', 'pdf',
                    'મટિરિયલ', 'નોટ્સ', 'પેપર્સ', 'સિલેબસ',
                    'नोट्स', 'अध्ययन सामग्री', 'पाठ्यक्रम', 'पेपर'
                ],
                'url' => route('resources.index'),
                'action_label' => 'Download Resources',
                'badge' => 'GTU Syllabus',
                'spotlight_tip' => 'Curated by top faculty and high-ranking alumni for 1st to 6th semester GTU diploma examinations.',
            ],
            [
                'id' => 'direct-whatsapp-alerts',
                'title' => 'Direct WhatsApp & Email Alert System',
                'category' => 'Alerts & Notifications',
                'icon' => '📱',
                'description' => 'Zero-cost instant WhatsApp notifications for document readiness, meeting confirmations, and college circulars.',
                'keywords' => [
                    'whatsapp', 'alert', 'alerts', 'email', 'broadcast', 'notification', 'notifications', 'message', 'sms',
                    'વોટ્સએપ', 'એલર્ટ', 'મેસેજ', 'નોટિફિકેશન',
                    'व्हाट्सएप', 'अलर्ट', 'संदेश', 'सूचना'
                ],
                'url' => route('admin.alerts.index'),
                'action_label' => 'Open Alert Hub',
                'badge' => 'Instant Alerts',
                'spotlight_tip' => 'Configured with 1-click WhatsApp web links and live SMTP mail delivery for real-time community engagement.',
            ],
            [
                'id' => 'contact-desk',
                'title' => 'Administration Contact Desk & Helpdesk',
                'category' => 'Support & Help',
                'icon' => '📞',
                'description' => 'Get in touch with college administration, principal\'s office, and heads of departments.',
                'keywords' => [
                    'contact', 'help', 'phone', 'email', 'address', 'principal', 'hod', 'helpline', 'inquiry', 'office', 'reach us', 'map location',
                    'સંપર્ક', 'ફોન', 'ઈમેલ', 'સરનામું', 'મદદ',
                    'संपर्क', 'फोन', 'पता', 'मदद', 'सहायता'
                ],
                'url' => route('contact'),
                'action_label' => 'Contact College',
                'badge' => 'Official Desk',
                'spotlight_tip' => 'Opp. T. B. Hospital, H.N.G.U. University Road, Patan - 384265. Tel: 02766-220419.',
            ],
            [
                'id' => 'elibrary-vault',
                'title' => 'Digital E-Library & Capstone Project Vault',
                'category' => 'Academic Resources & Projects',
                'icon' => '📖',
                'description' => 'Download GTU diploma engineering textbooks, previous final-year capstone project synopses, and lab reference sheets.',
                'keywords' => [
                    'elibrary', 'e-library', 'ebook', 'ebooks', 'book', 'books', 'capstone', 'synopsis', 'project report', 'gtu books', 'reference books', 'lab manual', 'previous papers', 'question papers', 'study', 'notes', 'library', 'vault',
                    'ઈ-લાઈબ્રેરી', 'પુસ્તકાલય', 'પ્રોજેક્ટ', 'પુસ્તકો', 'ચોપડી',
                    'किताबें', 'ई-लाइब्रेरी', 'प्रोजेक्ट रिपोर्ट', 'पुस्तकालय'
                ],
                'url' => route('elibrary.index'),
                'action_label' => 'Browse E-Library',
                'badge' => 'Downloadable Vault',
                'spotlight_tip' => 'Instant downloadable repository of GTU curriculum e-books, lab reference manuals, and previous year capstone project synopses.',
            ],
            [
                'id' => 'faculty-directory',
                'title' => 'Department Faculty & HOD Directory',
                'category' => 'Academic Faculty & Staff',
                'icon' => '👥',
                'description' => 'Distinguished professors, lecturers, and Heads of Departments mentoring future diploma engineers across all disciplines.',
                'keywords' => [
                    'faculty', 'prof', 'professor', 'hod', 'head of department', 'lecturer', 'teachers', 'staff', 'shahejad sindhi', 'computer faculty', 'mechanical faculty', 'electrical faculty', 'civil faculty', 'department heads',
                    'શિક્ષકો', 'પ્રોફેસર', 'અધ્યાપક', 'એચઓડી', 'ફેકલ્ટી',
                    'शिक्षक', 'प्रोफेसर', 'एचओडी', 'फैकल्टी'
                ],
                'url' => route('faculty.index'),
                'action_label' => 'View Faculty Directory',
                'badge' => 'Verified Faculty',
                'spotlight_tip' => 'Connect with HODs and teaching faculty across Civil, Mechanical, Electrical, Computer, and Applied Mechanics for academic mentoring.',
            ],
            [
                'id' => 'placement-cell',
                'title' => 'Training & Placement (TPO) Partner Cell',
                'category' => 'Career & Placements',
                'icon' => '🏢',
                'description' => 'Corporate recruitment partners, salary benchmarks, GTU diploma placement records, and NATS apprenticeship programs.',
                'keywords' => [
                    'placement', 'tpo', 'recruiter', 'recruiters', 'campus drive', 'companies', 'l&t', 'adani', 'tata', 'siemens', 'getco', 'package', 'highest package', 'salary', 'nats', 'apprenticeship', 'corporate hiring',
                    'પ્લેસમેન્ટ', 'નોકરી મેળો', 'કંપનીઓ',
                    'प्लेसमेंट', 'टीपीओ', 'कंपनी', 'भर्ती'
                ],
                'url' => route('placement.index'),
                'action_label' => 'Explore Placement Cell',
                'badge' => 'Corporate Partners',
                'spotlight_tip' => 'Connecting K. D. Polytechnic diploma engineers with premier corporate recruiters including L&T, Adani, Tata Motors, Siemens, and GETCO.',
            ],
            [
                'id' => 'academic-calendar',
                'title' => 'GTU Academic Calendar & Circulars Hub',
                'category' => 'University Schedules',
                'icon' => '🗓️',
                'description' => 'Official GTU term start dates, mid-semester exam timetables, practical submission deadlines, and university circulars.',
                'keywords' => [
                    'calendar', 'academic calendar', 'exam dates', 'mid sem', 'term', 'semester schedule', 'submission', 'gtu schedule', 'circular', 'dates', 'exam', 'exams', 'time table',
                    'શેડ્યુલ', 'કેલેન્ડર', 'પરીક્ષા', 'પરીક્ષા તારીખો',
                    'कैलेंडर', 'परीक्षा', 'टाइम टेबल'
                ],
                'url' => route('calendar.index'),
                'action_label' => 'Inspect GTU Calendar',
                'badge' => 'Official GTU Schedule',
                'spotlight_tip' => 'Keep track of key GTU semester term milestones, examination phases, and official circular announcements.',
            ],
            [
                'id' => 'kdp-mitra-ai',
                'title' => 'KDP Mitra (AI Counselor & Academic Assistant)',
                'category' => 'AI Guidance & Helpdesk',
                'icon' => '🤖',
                'description' => '24/7 intelligent college AI counselor powered by Groq LLaMA 3.3 for admissions, syllabus queries, GTU rules, and D2D guidance.',
                'keywords' => [
                    'ai', 'chatbot', 'counselor', 'kdp mitra', 'mitra', 'ask ai', 'llama', 'guidance', 'virtual assistant', 'help bot', 'bot', 'chat with ai',
                    'આઈ કાઉન્સિલર', 'મિત્ર', 'હેલ્પબોટ',
                    'एआई', 'काउंसलर', 'मित्र', 'बॉट'
                ],
                'url' => route('chatbot.index'),
                'action_label' => 'Chat with KDP Mitra',
                'badge' => 'AI Powered',
                'spotlight_tip' => 'Ask in English, Gujarati, or Hindi about GTU diploma engineering courses, D2D merit calculations, and campus facilities.',
            ],
            [
                'id' => 'campus-messenger',
                'title' => 'Campus Messenger & Direct Inbox',
                'category' => 'Community & Messaging',
                'icon' => '💬',
                'description' => 'Direct real-time 1-on-1 private messaging hub to connect and chat with alumni mentors, faculty professors, and classmates.',
                'keywords' => [
                    'message', 'messages', 'inbox', 'chat', 'direct message', 'messenger', 'dm', 'talk', 'conversation', 'chatting',
                    'મેસેજ', 'ઈનબોક્સ', 'વાતચીત', 'સંદેશ',
                    'इनबॉक्स', 'मैसेज', 'चैट'
                ],
                'url' => route('messages.inbox'),
                'action_label' => 'Open Messenger',
                'badge' => 'Real-time Chat',
                'spotlight_tip' => 'Message alumni mentors directly regarding project advice, job referrals, or GTU D2D admission tips.',
            ],
        ];
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
                'title' => 'Digital E-Library & Capstone Vault',
                'subtitle' => 'Download GTU e-books, capstone synopses, and lab manuals',
                'icon' => '📖',
                'url' => route('elibrary.index'),
                'action_label' => 'Browse Vault',
            ],
            [
                'title' => 'Faculty & HOD Directory',
                'subtitle' => 'Connect with professors, lecturers, and department heads',
                'icon' => '👥',
                'url' => route('faculty.index'),
                'action_label' => 'View Faculty',
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
                'title' => 'Corporate Placement Partners (TPO)',
                'subtitle' => 'Explore campus recruitment partners, packages, and hiring drives',
                'icon' => '🏢',
                'url' => route('placement.index'),
                'action_label' => 'View Placements',
            ],
        ];
    }

    private function getSuggestedQueries(): array
    {
        return [
            'Digital Alumni Smart ID Card',
            'GTU Diploma E-Books & Capstone Synopses',
            'Department Faculty & HOD Directory',
            'L&T & Adani Campus Placement Drives',
            'GTU Academic Calendar & Exam Dates',
            'Ask KDP Mitra AI Counselor',
            'Mechanical Alumni in Adani',
            'Apply for Official Transcript',
            'Vernier Caliper in Tool Bank',
            '1-on-1 GTU D2D Mentorship',
            'Jobs in Ahmedabad',
            'Mechanical Lathe Workshop & Labs',
            'Campus Time-Machine & Nostalgia Map',
            'Batch Reunions in Surat',
            'Giving Back to KDP College',
        ];
    }
}
