<?php

namespace App\Http\Controllers;

use App\Models\PlacementPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacementController extends Controller
{
    /**
     * Public Training & Placement (TPO) Wall & Report
     */
    public function index(Request $request)
    {
        $query = PlacementPartner::where('is_active', true)->orderBy('order_num')->orderBy('name');

        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('hiring_departments', 'like', '%' . $request->department . '%');
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('sector', 'like', $term)
                  ->orWhere('roles_offered', 'like', $term)
                  ->orWhere('hiring_departments', 'like', $term);
            });
        }

        $partners = $query->get();

        $stats = [
            'placement_rate' => '85%+',
            'highest_package' => '₹4.50 LPA',
            'average_package' => '₹2.80 LPA',
            'corporate_partners' => PlacementPartner::where('is_active', true)->count(),
            'd2d_transitions' => '200+',
            'govt_apprenticeship' => '100% NATS',
        ];

        return view('placement.index', compact('partners', 'stats'));
    }

    /**
     * Admin: Store a new corporate recruiter
     */
    public function store(Request $request)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'package_range' => 'nullable|string|max:100',
            'roles_offered' => 'nullable|string|max:255',
            'hiring_departments' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'order_num' => 'nullable|integer',
        ]);

        PlacementPartner::create([
            'name' => $request->name,
            'sector' => $request->sector,
            'package_range' => $request->package_range,
            'roles_offered' => $request->roles_offered,
            'hiring_departments' => $request->hiring_departments,
            'website_url' => $request->website_url,
            'order_num' => $request->order_num ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Corporate recruiter added successfully to TPO wall.');
    }

    /**
     * Admin: Update an existing corporate recruiter
     */
    public function update(Request $request, PlacementPartner $partner)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'package_range' => 'nullable|string|max:100',
            'roles_offered' => 'nullable|string|max:255',
            'hiring_departments' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'order_num' => 'nullable|integer',
        ]);

        $partner->update([
            'name' => $request->name,
            'sector' => $request->sector,
            'package_range' => $request->package_range,
            'roles_offered' => $request->roles_offered,
            'hiring_departments' => $request->hiring_departments,
            'website_url' => $request->website_url,
            'order_num' => $request->order_num ?? $partner->order_num,
            'is_active' => $request->has('is_active') ? true : $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Recruiter information updated successfully.');
    }

    /**
     * Admin: Delete a corporate recruiter
     */
    public function destroy(PlacementPartner $partner)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $partner->delete();

        return back()->with('success', 'Recruiter removed from TPO wall.');
    }
}
