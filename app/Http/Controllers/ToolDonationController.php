<?php

namespace App\Http\Controllers;

use App\Models\ToolDonation;
use App\Models\ToolDonationGratitude;
use App\Models\ToolDonationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ToolDonationController extends Controller
{
    /**
     * Display the dynamic Book & Engineering Tool Donation Bank Catalog.
     */
    public function index(Request $request)
    {
        $query = ToolDonation::with(['donor', 'claimedBy', 'gratitude.user'])
            ->withCount('requests');

        // Dynamic Filters
        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('semester')) {
            $query->where('semester_suitability', $request->semester);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
        } else {
            // Default show available first, but also claimed/handed over for community inspiration
            $query->orderByRaw("CASE WHEN status = 'available' THEN 1 WHEN status = 'claimed' THEN 2 ELSE 3 END");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('donor_note', 'like', "%{$search}%")
                  ->orWhereHas('donor', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->latest()->paginate(12)->withQueryString();

        // Dynamic departments
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
                'General / All Branches',
            ])
            ->unique()
            ->sort()
            ->values();

        // Dynamic Impact Metrics
        $stats = [
            'total_items' => ToolDonation::count(),
            'available_count' => ToolDonation::where('status', 'available')->count(),
            'total_value_saved' => ToolDonation::whereIn('status', ['claimed', 'handed_over'])->sum('estimated_value') ?: ToolDonation::sum('estimated_value'),
            'beneficiaries_count' => ToolDonation::whereNotNull('claimed_by_user_id')->distinct('claimed_by_user_id')->count('claimed_by_user_id'),
            'gratitudes_count' => ToolDonationGratitude::count(),
        ];

        // Recent Gratitude Stories for community wall
        $gratitudes = ToolDonationGratitude::with(['donation.donor', 'user'])
            ->latest()
            ->take(4)
            ->get();

        return view('tools.index', compact('items', 'departments', 'stats', 'gratitudes'));
    }

    /**
     * Show form to donate a book or engineering tool.
     */
    public function create()
    {
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
                'General / All Branches',
            ])
            ->unique()
            ->sort()
            ->values();

        return view('tools.create', compact('departments'));
    }

    /**
     * Store new donation item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'item_type' => 'required|string|in:textbook,drawing_tools,calculators_electronics,lab_hardware,workshop_safety,other',
            'department' => 'required|string|max:100',
            'semester_suitability' => 'required|string|max:50',
            'condition' => 'required|string|in:brand_new,like_new,gently_used,fair',
            'description' => 'required|string|min:20',
            'pickup_method' => 'required|string|in:campus_locker,handover_patan,courier',
            'pickup_location' => 'nullable|string|max:255',
            'estimated_value' => 'required|integer|min:50|max:100000',
            'photo' => 'nullable|image|max:3072',
            'donor_note' => 'nullable|string',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('tools/donations', 'public');
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $count = 1;
        while (ToolDonation::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $donation = ToolDonation::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'item_type' => $validated['item_type'],
            'department' => $validated['department'],
            'semester_suitability' => $validated['semester_suitability'],
            'condition' => $validated['condition'],
            'description' => $validated['description'],
            'pickup_method' => $validated['pickup_method'],
            'pickup_location' => $validated['pickup_location'] ?? null,
            'estimated_value' => $validated['estimated_value'],
            'photo_path' => $photoPath,
            'donor_note' => $validated['donor_note'] ?? null,
            'status' => 'available',
        ]);

        return redirect()->route('tools.show', $donation->slug)
            ->with('success', 'Thank you for paying it forward! Your item is now live in the Tool Bank for juniors.');
    }

    /**
     * Show single donation item details.
     */
    public function show(string $slug)
    {
        $donation = ToolDonation::where('slug', $slug)
            ->with(['donor.profile', 'claimedBy', 'requests.user', 'gratitude.user'])
            ->firstOrFail();

        $userRequest = null;
        if (Auth::check()) {
            $userRequest = $donation->userRequest(Auth::id());
        }

        return view('tools.show', compact('donation', 'userRequest'));
    }

    /**
     * Submit request to claim an item.
     */
    public function requestItem(Request $request, ToolDonation $toolDonation)
    {
        if (!$toolDonation->isAvailable()) {
            return back()->with('error', 'This item is no longer available for claiming.');
        }

        if ($toolDonation->user_id === Auth::id()) {
            return back()->with('error', 'You cannot request your own donated item.');
        }

        if ($toolDonation->userHasRequested(Auth::id())) {
            return back()->with('error', 'You have already submitted a request for this item.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:15|max:1000',
        ]);

        ToolDonationRequest::create([
            'tool_donation_id' => $toolDonation->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your request has been delivered to the donor! They will review and coordinate pickup.');
    }

    /**
     * Manage items donated by current user.
     */
    public function myDonations()
    {
        $donations = ToolDonation::where('user_id', Auth::id())
            ->with(['claimedBy', 'requests.user', 'gratitude.user'])
            ->withCount('requests')
            ->latest()
            ->paginate(10);

        return view('tools.my-donations', compact('donations'));
    }

    /**
     * Manage items requested or claimed by current student.
     */
    public function myRequests()
    {
        $requests = ToolDonationRequest::where('user_id', Auth::id())
            ->with(['donation.donor', 'donation.gratitude'])
            ->latest()
            ->paginate(10);

        return view('tools.my-requests', compact('requests'));
    }

    /**
     * Donor accepts a student's claim request.
     */
    public function acceptRequest(Request $request, ToolDonationRequest $donationRequest)
    {
        $donation = $donationRequest->donation;

        if ($donation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$donation->isAvailable()) {
            return back()->with('error', 'This item has already been assigned or claimed.');
        }

        // Accept this request
        $donationRequest->update([
            'status' => 'accepted',
            'donor_response' => $request->donor_response ?? 'Request accepted. Please see pickup details to collect the item.',
        ]);

        // Mark donation as claimed
        $donation->update([
            'status' => 'claimed',
            'claimed_by_user_id' => $donationRequest->user_id,
            'claimed_at' => now(),
        ]);

        // Decline other requests
        ToolDonationRequest::where('tool_donation_id', $donation->id)
            ->where('id', '!=', $donationRequest->id)
            ->update(['status' => 'declined']);

        return back()->with('success', "Request accepted! {$donationRequest->user->name} has been assigned this item.");
    }

    /**
     * Mark donation item as handed over.
     */
    public function markHandedOver(ToolDonation $toolDonation)
    {
        if ($toolDonation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $toolDonation->update([
            'status' => 'handed_over',
            'handed_over_at' => now(),
        ]);

        return back()->with('success', 'Item marked as successfully handed over! The student can now post a Pay-It-Forward gratitude note.');
    }

    /**
     * Post a gratitude thank-you note.
     */
    public function storeGratitude(Request $request, ToolDonation $toolDonation)
    {
        if ($toolDonation->claimed_by_user_id !== Auth::id()) {
            return back()->with('error', 'Only the recipient student can submit a thank-you note.');
        }

        if ($toolDonation->gratitude) {
            return back()->with('error', 'You have already posted a gratitude message for this item.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:20|max:1000',
            'photo' => 'nullable|image|max:3072',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('tools/gratitudes', 'public');
        }

        ToolDonationGratitude::create([
            'tool_donation_id' => $toolDonation->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'likes_count' => 0,
        ]);

        return back()->with('success', 'Thank you note published! Your gratitude inspires other alumni to donate.');
    }

    /**
     * Delete a donation item.
     */
    public function destroy(ToolDonation $toolDonation)
    {
        if ($toolDonation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $toolDonation->delete();

        return redirect()->route('tools.index')->with('success', 'Donation listing removed.');
    }
}
