<?php

namespace App\Http\Controllers;

use App\Models\AwardCategory;
use App\Models\AwardCycle;
use App\Models\AwardNomination;
use App\Models\AwardVote;
use App\Models\HallOfFameInductee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardController extends Controller
{
    /**
     * Display the Annual Awards & Honors Hub.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->get('year');
        $cycle = $selectedYear 
            ? AwardCycle::where('year', $selectedYear)->first() 
            : AwardCycle::getActive();

        if (!$cycle) {
            $cycle = AwardCycle::latest('year')->first();
        }

        $allCycles = AwardCycle::orderBy('year', 'desc')->get();

        $categories = $cycle 
            ? $cycle->categories()->withCount([
                'nominations',
                'approvedNominations',
                'votes'
            ])->get() 
            : collect();

        // Dynamic user votes in this cycle
        $userVotes = collect();
        if (Auth::check() && $cycle) {
            $userVotes = AwardVote::where('award_cycle_id', $cycle->id)
                ->where('user_id', Auth::id())
                ->pluck('award_nomination_id', 'award_category_id');
        }

        // Summary stats
        $stats = [
            'total_nominations' => AwardNomination::count(),
            'total_votes' => AwardVote::count(),
            'hall_of_fame_count' => HallOfFameInductee::count(),
            'active_categories' => $categories->count(),
        ];

        // Featured inductees for spotlight
        $featuredInductees = HallOfFameInductee::where('is_featured', true)
            ->latest('induction_year')
            ->take(4)
            ->get();

        if ($featuredInductees->isEmpty()) {
            $featuredInductees = HallOfFameInductee::latest('induction_year')->take(4)->get();
        }

        return view('awards.index', compact('cycle', 'allCycles', 'categories', 'userVotes', 'stats', 'featuredInductees'));
    }

    /**
     * Show nomination form.
     */
    public function createNomination(Request $request)
    {
        $cycle = AwardCycle::getActive();

        if (!$cycle) {
            return redirect()->route('awards.index')->with('error', 'No active award cycle is currently open for nominations.');
        }

        // If nominations closed and user is not admin, notify user
        if (!$cycle->isNominationsOpen() && !Auth::user()->isAdmin()) {
            return redirect()->route('awards.index')->with('error', 'The nomination period for this cycle has concluded. Please explore finalists and cast your vote!');
        }

        $categories = $cycle->categories;
        $preselectedCategory = $request->get('category_id');

        // Dynamic list of registered alumni
        $alumniList = User::where('role', 'alumni')
            ->where('is_approved', true)
            ->orderBy('name')
            ->select('id', 'name', 'department', 'graduation_year', 'company', 'designation')
            ->get();

        // Dynamic list of departments
        $departments = User::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->pluck('department')
            ->merge([
                'Mechanical Engineering',
                'Electrical Engineering',
                'Civil Engineering',
                'Computer Engineering',
                'Information Technology',
                'Chemical Engineering',
            ])
            ->unique()
            ->sort()
            ->values();

        return view('awards.nominate', compact('cycle', 'categories', 'preselectedCategory', 'alumniList', 'departments'));
    }

    /**
     * Store submitted nomination.
     */
    public function storeNomination(Request $request)
    {
        $cycle = AwardCycle::getActive();
        if (!$cycle) {
            return back()->with('error', 'No active award cycle available.');
        }

        $validated = $request->validate([
            'award_category_id' => 'required|exists:award_categories,id',
            'nominee_user_id' => 'nullable|exists:users,id',
            'nominee_name' => 'required|string|max:255',
            'nominee_email' => 'nullable|email|max:255',
            'nominee_phone' => 'nullable|string|max:30',
            'nominee_department' => 'required|string|max:100',
            'nominee_batch_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'current_organization' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'achievement_summary' => 'required|string|min:20',
            'impact_statement' => 'nullable|string',
            'portfolio_url' => 'nullable|url|max:255',
            'photo' => 'nullable|image|max:3072',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('awards/nominations', 'public');
        }

        $nomination = AwardNomination::create([
            'award_cycle_id' => $cycle->id,
            'award_category_id' => $validated['award_category_id'],
            'nominee_user_id' => $validated['nominee_user_id'] ?? null,
            'nominator_user_id' => Auth::id(),
            'nominee_name' => $validated['nominee_name'],
            'nominee_email' => $validated['nominee_email'] ?? null,
            'nominee_phone' => $validated['nominee_phone'] ?? null,
            'nominee_department' => $validated['nominee_department'],
            'nominee_batch_year' => $validated['nominee_batch_year'],
            'current_organization' => $validated['current_organization'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'achievement_summary' => $validated['achievement_summary'],
            'impact_statement' => $validated['impact_statement'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'pending',
            'is_winner' => false,
        ]);

        return redirect()->route('awards.index')->with('success', 'Nomination submitted successfully! The KDP Awards Committee will review the achievements prior to voting.');
    }

    /**
     * Show a category with approved finalists and live voting.
     */
    public function showCategory(Request $request, string $slug)
    {
        $selectedYear = $request->get('year');
        $cycle = $selectedYear 
            ? AwardCycle::where('year', $selectedYear)->first() 
            : AwardCycle::getActive();

        $category = null;
        if ($cycle) {
            $category = AwardCategory::where('award_cycle_id', $cycle->id)
                ->where('slug', $slug)
                ->first();
        }

        if (!$category) {
            $category = AwardCategory::where('slug', $slug)->firstOrFail();
            $cycle = $category->cycle;
        }

        $nominees = $category->approvedNominations()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        $userVote = null;
        if (Auth::check()) {
            $userVote = AwardVote::where('award_cycle_id', $cycle->id)
                ->where('award_category_id', $category->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $totalVotesInCategory = $nominees->sum('votes_count');

        return view('awards.category', compact('category', 'cycle', 'nominees', 'userVote', 'totalVotesInCategory'));
    }

    /**
     * Cast vote for an approved nominee.
     */
    public function vote(Request $request)
    {
        $validated = $request->validate([
            'award_category_id' => 'required|exists:award_categories,id',
            'award_nomination_id' => 'required|exists:award_nominations,id',
        ]);

        $category = AwardCategory::with('cycle')->findOrFail($validated['award_category_id']);
        $cycle = $category->cycle;

        // Check if voting is open
        if (!$cycle->isVotingOpen()) {
            return back()->with('error', 'Voting is not currently open for this award cycle.');
        }

        $nomination = AwardNomination::where('id', $validated['award_nomination_id'])
            ->where('award_category_id', $category->id)
            ->where('status', 'approved')
            ->firstOrFail();

        // Prevent self-voting
        if ($nomination->nominee_user_id === Auth::id()) {
            return back()->with('error', 'Self-voting is not permitted under KDP Awards Committee guidelines.');
        }

        // Check duplicate vote in this category
        $existingVote = AwardVote::where('award_cycle_id', $cycle->id)
            ->where('award_category_id', $category->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            return back()->with('error', 'You have already cast your vote in this category.');
        }

        AwardVote::create([
            'award_cycle_id' => $cycle->id,
            'award_category_id' => $category->id,
            'award_nomination_id' => $nomination->id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Vote recorded successfully! Thank you for supporting ' . $nomination->nominee_name . '.');
    }

    /**
     * Display Virtual Hall of Fame & Wall of Honor.
     */
    public function hallOfFame(Request $request)
    {
        $query = HallOfFameInductee::query();

        if ($request->filled('year')) {
            $query->where('induction_year', $request->year);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('award_title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('citation', 'like', "%{$search}%");
            });
        }

        $inductees = $query->orderBy('induction_year', 'desc')
            ->orderBy('is_featured', 'desc')
            ->paginate(12)
            ->withQueryString();

        $years = HallOfFameInductee::distinct()->pluck('induction_year')->sortDesc();
        $departments = HallOfFameInductee::distinct()->pluck('department')->sort();

        return view('awards.hall-of-fame', compact('inductees', 'years', 'departments'));
    }
}
