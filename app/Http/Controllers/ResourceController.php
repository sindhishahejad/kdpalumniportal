<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('user.profile')->latest();

        // Filter by Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by Department (Computer, Electrical, Mechanical, Civil, General)
        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        // Filter by Semester (1 - 6)
        if ($request->filled('semester') && $request->semester !== 'all') {
            $query->where('semester', $request->semester);
        }

        // Keyword Search across title, subject, and description
        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('subject', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        $resources = $query->paginate(12)->withQueryString();

        // Aggregate statistics for quick filtering counts
        $totalCount = Resource::count();
        $examCount = Resource::where('category', 'exam_paper')->count();
        $notesCount = Resource::where('category', 'study_notes')->count();
        $moocCount = Resource::where('category', 'mooc')->count();

        return view('resources.index', compact('resources', 'totalCount', 'examCount', 'notesCount', 'moocCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:study_notes,exam_paper,mooc,other'],
            'department' => ['nullable', 'string', 'max:100'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:6'],
            'subject' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,ppt,pptx,jpg,jpeg,png', 'max:15360'], // Max 15MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('resource_vault', 'public');
        }

        $request->user()->resources()->create([
            'title' => $request->title,
            'category' => $request->category,
            'department' => $request->department,
            'semester' => $request->semester,
            'subject' => $request->subject,
            'description' => $request->description,
            'url' => $request->url,
            'file_path' => $filePath,
        ]);

        return redirect()->route('resources.index')->with('status', 'Study material shared successfully with KDP students!');
    }

    public function destroy(Resource $resource)
    {
        abort_if(!Auth::check() || (Auth::id() !== $resource->user_id && Auth::user()->role !== 'admin'), 403, 'Unauthorized action.');

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return back()->with('status', 'Resource removed successfully.');
    }
}