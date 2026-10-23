<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;

class AlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        // Query approved alumni users with their profiles (without forcing has('profile') so newly approved alumni still appear)
        $query = User::where('is_approved', true)
                     ->where('role', 'alumni')
                     ->with('profile');

        // 1. One-Click Batchmates Shortcut
        $isBatchmatesFilter = false;
        if ($request->boolean('batchmates') && auth()->check()) {
            $currentUser = auth()->user();
            $batchYear = $currentUser->graduation_year ?? optional($currentUser->profile)->graduation_year;
            $batchDept = $currentUser->department ?? optional($currentUser->profile)->department;

            if ($batchYear) {
                $query->where(function ($q) use ($batchYear) {
                    $q->where('graduation_year', $batchYear)
                      ->orWhereHas('profile', function ($pq) use ($batchYear) {
                          $pq->where('graduation_year', $batchYear);
                      });
                });
                $isBatchmatesFilter = true;
            }

            if ($batchDept) {
                $query->where(function ($q) use ($batchDept) {
                    $q->where('department', $batchDept)
                      ->orWhereHas('profile', function ($pq) use ($batchDept) {
                          $pq->where('department', $batchDept);
                      });
                });
                $isBatchmatesFilter = true;
            }
        }

        // 2. Keyword Search (expanded across Name, Department, Company, Skills, and Profile Location/Job Title)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('department', 'like', "%{$search}%")
                        ->orWhere('current_company', 'like', "%{$search}%")
                        ->orWhere('job_title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Geographic / City Filter ("Alumni Nearby")
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('profile', function ($q) use ($location) {
                $q->where('location', 'like', "%{$location}%");
            });
        }

        // 4. Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 5. Dynamic Academic & Professional Filters
        if ($request->filled('graduation_year')) {
            $year = $request->graduation_year;
            $query->where(function ($q) use ($year) {
                $q->where('graduation_year', $year)
                  ->orWhereHas('profile', function ($pq) use ($year) {
                      $pq->where('graduation_year', $year);
                  });
            });
        }

        if ($request->filled('department')) {
            $dept = $request->department;
            $query->where(function ($q) use ($dept) {
                $q->where('department', $dept)
                  ->orWhereHas('profile', function ($pq) use ($dept) {
                      $pq->where('department', $dept);
                  });
            });
        }

        if ($request->filled('degree')) {
            $degree = $request->degree;
            $query->where(function ($q) use ($degree) {
                $q->where('degree', $degree)
                  ->orWhereHas('profile', function ($pq) use ($degree) {
                      $pq->where('degree', $degree);
                  });
            });
        }

        if ($request->filled('company')) {
            $company = $request->company;
            $query->where(function ($q) use ($company) {
                $q->where('company', $company)
                  ->orWhereHas('profile', function ($pq) use ($company) {
                      $pq->where('current_company', $company);
                  });
            });
        }

        if ($request->filled('designation')) {
            $desig = $request->designation;
            $query->where(function ($q) use ($desig) {
                $q->where('designation', $desig)
                  ->orWhereHas('profile', function ($pq) use ($desig) {
                      $pq->where('job_title', $desig);
                  });
            });
        }

        if ($request->filled('work_industry')) {
            $query->where('work_industry', $request->work_industry);
        }

        if ($request->filled('skills')) {
            $skills = $request->skills;
            $query->where(function ($q) use ($skills) {
                $q->where('skills', 'like', "%{$skills}%")
                  ->orWhereHas('profile', function ($pq) use ($skills) {
                      $pq->where('bio', 'like', "%{$skills}%");
                  });
            });
        }

        $alumni = $query->paginate(12)->withQueryString();

        // 6. Fetch unique filter options dynamically from users and profiles
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');
        
        $gradYears = Profile::select('graduation_year')->distinct()->whereNotNull('graduation_year')->where('graduation_year', '!=', '')->pluck('graduation_year')
            ->merge(User::select('graduation_year')->distinct()->whereNotNull('graduation_year')->where('graduation_year', '!=', '')->pluck('graduation_year'))
            ->unique()->sortDesc()->values();

        $degrees = Profile::select('degree')->distinct()->whereNotNull('degree')->where('degree', '!=', '')->pluck('degree')
            ->merge(User::select('degree')->distinct()->whereNotNull('degree')->where('degree', '!=', '')->pluck('degree'))
            ->unique()->values();

        $departments = Profile::select('department')->distinct()->whereNotNull('department')->where('department', '!=', '')->pluck('department')
            ->merge(User::select('department')->distinct()->whereNotNull('department')->where('department', '!=', '')->pluck('department'))
            ->unique()->values();

        $companies = Profile::select('current_company')->distinct()->whereNotNull('current_company')->where('current_company', '!=', '')->pluck('current_company')
            ->merge(User::select('company')->distinct()->whereNotNull('company')->where('company', '!=', '')->pluck('company'))
            ->unique()->values();

        $locations = Profile::select('location')->distinct()->whereNotNull('location')->where('location', '!=', '')->pluck('location')
            ->unique()->values();

        $designations = Profile::select('job_title')->distinct()->whereNotNull('job_title')->where('job_title', '!=', '')->pluck('job_title')
            ->merge(User::select('designation')->distinct()->whereNotNull('designation')->where('designation', '!=', '')->pluck('designation'))
            ->unique()->values();

        $industries = User::select('work_industry')->distinct()->whereNotNull('work_industry')->where('work_industry', '!=', '')->pluck('work_industry')->unique()->values();

        return view('alumni.index', compact(
            'alumni', 'roles', 'gradYears', 'degrees', 
            'departments', 'companies', 'designations', 'industries', 'locations', 'isBatchmatesFilter'
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
