<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;

class AlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        // Query approved alumni users who have a profile
        $query = User::where('is_approved', true)
                     ->where('role', 'alumni')
                     ->has('profile')
                     ->with('profile');

        // 1. Keyword Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('department', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Apply Sidebar Filters via Profile Relationship
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $profileFilters = [
            'graduation_year', 'degree', 'department'
        ];

        foreach ($profileFilters as $filter) {
            if ($request->filled($filter)) {
                $query->whereHas('profile', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter);
                });
            }
        }

        if ($request->filled('skills')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('skills', 'like', "%{$request->skills}%");
            });
        }

        $alumni = $query->paginate(12);

        // 3. Fetch unique filter options safely from available columns
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');
        $gradYears = Profile::select('graduation_year')->distinct()->whereNotNull('graduation_year')->orderBy('graduation_year', 'desc')->pluck('graduation_year');
        $degrees = Profile::select('degree')->distinct()->whereNotNull('degree')->pluck('degree');
        $departments = Profile::select('department')->distinct()->whereNotNull('department')->pluck('department');
        
        // Empty collections for missing columns to prevent view errors
        $companies = collect();
        $designations = collect();
        $industries = collect();

        return view('alumni.index', compact(
            'alumni', 'roles', 'gradYears', 'degrees', 
            'departments', 'companies', 'designations', 'industries'
        ));
    }

    /**
     * Display the specified user's public profile.
     */
    public function show(User $user)
    {
        return view('alumni.show', compact('user'));
    }
}