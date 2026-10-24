<?php

namespace App\Http\Controllers;

use App\Models\FacultyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    /**
     * Public Faculty & HOD Directory
     */
    public function index(Request $request)
    {
        $query = FacultyMember::orderBy('is_hod', 'desc')->orderBy('order_num')->orderBy('name');

        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('designation', 'like', $term)
                  ->orWhere('qualification', 'like', $term)
                  ->orWhere('specialization', 'like', $term)
                  ->orWhere('department', 'like', $term);
            });
        }

        $allMembers = $query->get();
        $hods = $allMembers->where('is_hod', true);
        $faculty = $allMembers->where('is_hod', false);

        $departmentStats = [
            'total_faculty' => FacultyMember::count(),
            'hods_count' => FacultyMember::where('is_hod', true)->count(),
            'departments_count' => FacultyMember::distinct('department')->count('department'),
        ];

        return view('faculty.index', compact('allMembers', 'hods', 'faculty', 'departmentStats'));
    }

    /**
     * Admin: Store new faculty member
     */
    public function store(Request $request)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'nullable|integer',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'order_num' => 'nullable|integer',
        ]);

        FacultyMember::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'department' => $request->department,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'specialization' => $request->specialization,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_hod' => $request->has('is_hod'),
            'order_num' => $request->order_num ?? 0,
        ]);

        return back()->with('success', 'Faculty member added successfully.');
    }

    /**
     * Admin: Update faculty member
     */
    public function update(Request $request, FacultyMember $faculty)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'nullable|integer',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'order_num' => 'nullable|integer',
        ]);

        $faculty->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'department' => $request->department,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'specialization' => $request->specialization,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_hod' => $request->has('is_hod'),
            'order_num' => $request->order_num ?? $faculty->order_num,
        ]);

        return back()->with('success', 'Faculty details updated successfully.');
    }

    /**
     * Admin: Delete faculty member
     */
    public function destroy(FacultyMember $faculty)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $faculty->delete();

        return back()->with('success', 'Faculty member removed from directory.');
    }
}
