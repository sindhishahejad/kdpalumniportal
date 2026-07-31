<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        // Query the User model directly since we added all fields to the users table
        $query = User::query();

        // 1. Keyword Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // 2. Apply Sidebar Filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $userFilters = [
            'year_joining', 'graduation_year', 'degree', 
            'department', 'company', 'designation', 'work_industry'
        ];

        foreach ($userFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        if ($request->filled('skills')) {
            $query->where('skills', 'like', "%{$request->skills}%");
        }

        $alumni = $query->paginate(12);

        // 3. Fetch unique data for sidebar dropdowns directly from the Users table
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');
        $joinYears = User::select('year_joining')->distinct()->whereNotNull('year_joining')->orderBy('year_joining', 'desc')->pluck('year_joining');
        $gradYears = User::select('graduation_year')->distinct()->whereNotNull('graduation_year')->orderBy('graduation_year', 'desc')->pluck('graduation_year');
        $degrees = User::select('degree')->distinct()->whereNotNull('degree')->pluck('degree');
        $departments = User::select('department')->distinct()->whereNotNull('department')->pluck('department');
        $companies = User::select('company')->distinct()->whereNotNull('company')->pluck('company');
        $designations = User::select('designation')->distinct()->whereNotNull('designation')->pluck('designation');
        $industries = User::select('work_industry')->distinct()->whereNotNull('work_industry')->pluck('work_industry');

        return view('alumni.index', compact(
            'alumni', 'roles', 'joinYears', 'gradYears', 'degrees', 
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