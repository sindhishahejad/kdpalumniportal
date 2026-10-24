<?php

namespace App\Http\Controllers;

use App\Models\AcademicSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicCalendarController extends Controller
{
    /**
     * Public GTU Academic Calendar & Circulars Hub
     */
    public function index(Request $request)
    {
        $query = AcademicSchedule::where('is_active', true)->orderBy('is_important', 'desc')->orderBy('start_date', 'asc');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhere('semester_scope', 'like', $term);
            });
        }

        $schedules = $query->get();

        $stats = [
            'total_events' => AcademicSchedule::where('is_active', true)->count(),
            'upcoming_exams' => AcademicSchedule::where('is_active', true)->where('category', 'exam')->count(),
            'active_circulars' => AcademicSchedule::where('is_active', true)->where('category', 'circular')->count(),
            'term_milestones' => AcademicSchedule::where('is_active', true)->where('category', 'term')->count(),
        ];

        return view('academic-calendar.index', compact('schedules', 'stats'));
    }

    /**
     * Admin: Store new GTU schedule / circular
     */
    public function store(Request $request)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:exam,term,circular,holiday',
            'semester_scope' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'attachment_url' => 'nullable|url|max:255',
        ]);

        AcademicSchedule::create([
            'title' => $request->title,
            'category' => $request->category,
            'semester_scope' => $request->semester_scope,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'attachment_url' => $request->attachment_url,
            'is_important' => $request->has('is_important'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Academic calendar entry added successfully.');
    }

    /**
     * Admin: Update GTU schedule / circular
     */
    public function update(Request $request, AcademicSchedule $schedule)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:exam,term,circular,holiday',
            'semester_scope' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'attachment_url' => 'nullable|url|max:255',
        ]);

        $schedule->update([
            'title' => $request->title,
            'category' => $request->category,
            'semester_scope' => $request->semester_scope,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'attachment_url' => $request->attachment_url,
            'is_important' => $request->has('is_important'),
        ]);

        return back()->with('success', 'Academic calendar entry updated successfully.');
    }

    /**
     * Admin: Delete GTU schedule / circular
     */
    public function destroy(AcademicSchedule $schedule)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403);

        $schedule->delete();

        return back()->with('success', 'Academic calendar entry removed.');
    }
}
