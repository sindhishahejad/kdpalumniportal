<?php

namespace App\Http\Controllers;

use App\Models\AwardCategory;
use App\Models\AwardCycle;
use App\Models\AwardNomination;
use App\Models\AwardVote;
use App\Models\HallOfFameInductee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAwardController extends Controller
{
    /**
     * Display admin award management dashboard with tabs.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'nominations');

        $selectedCycleId = $request->get('cycle_id');
        $cycle = $selectedCycleId ? AwardCycle::find($selectedCycleId) : AwardCycle::getActive();
        if (!$cycle) {
            $cycle = AwardCycle::latest('year')->first();
        }

        $allCycles = AwardCycle::withCount(['categories', 'nominations', 'votes'])->orderBy('year', 'desc')->get();

        // Dynamic categories for selected cycle
        $categories = $cycle 
            ? $cycle->categories()->withCount(['nominations', 'approvedNominations', 'votes'])->get() 
            : collect();

        // 1. Nominations query
        $nominationsQuery = AwardNomination::with(['category', 'cycle', 'nominator', 'nomineeUser'])
            ->withCount('votes');

        if ($cycle) {
            $nominationsQuery->where('award_cycle_id', $cycle->id);
        }

        if ($request->filled('status')) {
            $nominationsQuery->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $nominationsQuery->where('award_category_id', $request->category_id);
        }

        $nominations = $nominationsQuery->latest()->paginate(15, ['*'], 'nom_page')->withQueryString();

        // 2. Hall of Fame Inductees query
        $inducteesQuery = HallOfFameInductee::latest('induction_year');
        if ($request->filled('inductee_year')) {
            $inducteesQuery->where('induction_year', $request->inductee_year);
        }
        $inductees = $inducteesQuery->paginate(12, ['*'], 'hof_page')->withQueryString();

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

        // Dynamic metrics
        $stats = [
            'total_cycles' => $allCycles->count(),
            'total_nominations' => AwardNomination::count(),
            'pending_nominations' => AwardNomination::where('status', 'pending')->count(),
            'approved_nominations' => AwardNomination::where('status', 'approved')->count(),
            'total_votes' => AwardVote::count(),
            'hall_of_fame_count' => HallOfFameInductee::count(),
        ];

        return view('admin.awards.index', compact(
            'activeTab',
            'cycle',
            'allCycles',
            'nominations',
            'categories',
            'inductees',
            'departments',
            'stats'
        ));
    }

    /**
     * Create a new award cycle dynamically.
     */
    public function storeCycle(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1980|max:' . (date('Y') + 10) . '|unique:award_cycles,year',
            'title' => 'required|string|max:255',
            'theme' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,jury_review,nominations_open,voting_open,concluded',
            'nominations_start_at' => 'nullable|date',
            'nominations_end_at' => 'nullable|date|after_or_equal:nominations_start_at',
            'voting_start_at' => 'nullable|date',
            'voting_end_at' => 'nullable|date|after_or_equal:voting_start_at',
            'ceremony_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');
        $validated['is_active'] = $isActive;

        $cycle = AwardCycle::create($validated);

        if ($isActive) {
            AwardCycle::makeActive($cycle);
        }

        return redirect()->route('admin.awards.index', ['cycle_id' => $cycle->id, 'tab' => 'cycles'])
            ->with('success', "Award cycle '{$cycle->title}' created successfully!");
    }

    /**
     * Update an existing award cycle dynamically.
     */
    public function updateCycle(Request $request, AwardCycle $cycle)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1980|max:' . (date('Y') + 10) . '|unique:award_cycles,year,' . $cycle->id,
            'title' => 'required|string|max:255',
            'theme' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,jury_review,nominations_open,voting_open,concluded',
            'nominations_start_at' => 'nullable|date',
            'nominations_end_at' => 'nullable|date',
            'voting_start_at' => 'nullable|date',
            'voting_end_at' => 'nullable|date',
            'ceremony_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');
        $validated['is_active'] = $isActive;

        $cycle->update($validated);

        if ($isActive) {
            AwardCycle::makeActive($cycle);
        }

        return redirect()->route('admin.awards.index', ['cycle_id' => $cycle->id, 'tab' => 'cycles'])
            ->with('success', "Award cycle '{$cycle->title}' updated successfully!");
    }

    /**
     * Quick status update for a cycle.
     */
    public function updateCycleStatus(Request $request, AwardCycle $cycle)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,jury_review,nominations_open,voting_open,concluded',
        ]);

        $cycle->update(['status' => $validated['status']]);

        $label = match ($validated['status']) {
            'nominations_open' => 'Step 1: Open Nominations',
            'draft', 'jury_review' => 'Step 2: Jury Shortlisting',
            'voting_open' => 'Step 3: Live Community Voting',
            'concluded' => 'Step 4: Convocation Enshrinement',
            default => $validated['status'],
        };

        return back()->with('success', "Award cycle stage updated to '{$label}' successfully!");
    }

    /**
     * Create a new category dynamically for any cycle.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'award_cycle_id' => 'required|exists:award_cycles,id',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'eligibility_criteria' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (AwardCategory::where('award_cycle_id', $validated['award_cycle_id'])->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['icon'] = $validated['icon'] ?: '🏆';

        $category = AwardCategory::create($validated);

        return redirect()->route('admin.awards.index', ['cycle_id' => $validated['award_cycle_id'], 'tab' => 'categories'])
            ->with('success', "Award category '{$category->name}' created dynamically!");
    }

    /**
     * Update an award category dynamically.
     */
    public function updateCategory(Request $request, AwardCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'eligibility_criteria' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $category->update($validated);

        return redirect()->route('admin.awards.index', ['cycle_id' => $category->award_cycle_id, 'tab' => 'categories'])
            ->with('success', "Category '{$category->name}' updated successfully!");
    }

    /**
     * Delete an award category dynamically.
     */
    public function destroyCategory(AwardCategory $category)
    {
        $cycleId = $category->award_cycle_id;
        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.awards.index', ['cycle_id' => $cycleId, 'tab' => 'categories'])
            ->with('success', "Category '{$name}' deleted successfully.");
    }

    /**
     * Approve or reject a nomination.
     */
    public function updateNominationStatus(Request $request, AwardNomination $nomination)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $nomination->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $nomination->admin_notes,
        ]);

        return back()->with('success', "Nomination for {$nomination->nominee_name} marked as {$validated['status']}.");
    }

    /**
     * Induct winner from nomination into Hall of Fame.
     */
    public function inductWinner(Request $request, AwardNomination $nomination)
    {
        $validated = $request->validate([
            'citation' => 'required|string|min:20',
            'is_featured' => 'nullable|boolean',
        ]);

        $nomination->update(['is_winner' => true]);

        HallOfFameInductee::create([
            'award_cycle_id' => $nomination->award_cycle_id,
            'award_category_id' => $nomination->award_category_id,
            'award_nomination_id' => $nomination->id,
            'user_id' => $nomination->nominee_user_id,
            'name' => $nomination->nominee_name,
            'department' => $nomination->nominee_department,
            'batch_year' => $nomination->nominee_batch_year,
            'induction_year' => $nomination->cycle->year,
            'award_title' => $nomination->category->name,
            'citation' => $validated['citation'],
            'current_role' => $nomination->designation,
            'company' => $nomination->current_organization,
            'photo_path' => $nomination->photo_path,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.awards.index', ['tab' => 'hall-of-fame'])
            ->with('success', "{$nomination->nominee_name} has been inducted into the KDP Hall of Fame!");
    }

    /**
     * Directly induct any alumnus / legend into Hall of Fame.
     */
    public function storeInductee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'batch_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'induction_year' => 'required|integer|min:1980|max:' . (date('Y') + 5),
            'award_title' => 'required|string|max:255',
            'citation' => 'required|string|min:20',
            'company' => 'nullable|string|max:255',
            'current_role' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'photo' => 'nullable|image|max:3072',
            'award_cycle_id' => 'nullable|exists:award_cycles,id',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('awards/inductees', 'public');
        }

        $inductee = HallOfFameInductee::create([
            'award_cycle_id' => $validated['award_cycle_id'] ?? null,
            'name' => $validated['name'],
            'department' => $validated['department'],
            'batch_year' => $validated['batch_year'],
            'induction_year' => $validated['induction_year'],
            'award_title' => $validated['award_title'],
            'citation' => $validated['citation'],
            'company' => $validated['company'] ?? null,
            'current_role' => $validated['current_role'] ?? null,
            'photo_path' => $photoPath,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.awards.index', ['tab' => 'hall-of-fame'])
            ->with('success', "{$inductee->name} directly inducted into the Hall of Fame!");
    }

    /**
     * Update Hall of Fame inductee details.
     */
    public function updateInductee(Request $request, HallOfFameInductee $inductee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'batch_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'induction_year' => 'required|integer|min:1980|max:' . (date('Y') + 5),
            'award_title' => 'required|string|max:255',
            'citation' => 'required|string|min:20',
            'company' => 'nullable|string|max:255',
            'current_role' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'photo' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('awards/inductees', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $inductee->update($validated);

        return redirect()->route('admin.awards.index', ['tab' => 'hall-of-fame'])
            ->with('success', "Inductee '{$inductee->name}' updated successfully.");
    }

    /**
     * Delete Hall of Fame inductee.
     */
    public function destroyInductee(HallOfFameInductee $inductee)
    {
        $name = $inductee->name;
        $inductee->delete();

        return redirect()->route('admin.awards.index', ['tab' => 'hall-of-fame'])
            ->with('success', "Inductee '{$name}' removed from Hall of Fame.");
    }
}
