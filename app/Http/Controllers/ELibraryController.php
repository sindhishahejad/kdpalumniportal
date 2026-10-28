<?php

namespace App\Http\Controllers;

use App\Models\ELibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ELibraryController extends Controller
{
    /**
     * Display the digital e-library, capstone vault, and lab manual repository.
     */
    public function index(Request $request)
    {
        $query = ELibraryItem::with('user')->where('is_approved', true)->latest();

        // 1. Filter by Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 2. Filter by Engineering Department
        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department', $request->department);
        }

        // 3. Filter by Semester (1 - 6)
        if ($request->filled('semester') && $request->semester !== 'all') {
            $query->where('semester', (int) $request->semester);
        }

        // 4. Keyword Search
        if ($request->filled('search')) {
            $term = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('subject_name', 'like', $term)
                  ->orWhere('subject_code', 'like', $term)
                  ->orWhere('tech_stack', 'like', $term)
                  ->orWhere('author_or_team', 'like', $term)
                  ->orWhere('guide_faculty', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        $items = $query->paginate(12)->withQueryString();

        // Aggregated category counts for tab ribbons
        $totalCount = ELibraryItem::where('is_approved', true)->count();
        $ebookCount = ELibraryItem::where('is_approved', true)->where('category', 'ebook')->count();
        $capstoneCount = ELibraryItem::where('is_approved', true)->where('category', 'capstone_project')->count();
        $labCount = ELibraryItem::where('is_approved', true)->where('category', 'lab_manual')->count();

        // Spotlight featured items
        $featuredItems = ELibraryItem::where('is_approved', true)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        return view('elibrary.index', compact(
            'items',
            'totalCount',
            'ebookCount',
            'capstoneCount',
            'labCount',
            'featuredItems'
        ));
    }

    /**
     * Display a specific e-library resource or project synopsis details.
     */
    public function show($identifier)
    {
        $item = is_numeric($identifier)
            ? ELibraryItem::findOrFail($identifier)
            : ELibraryItem::where('slug', $identifier)->firstOrFail();

        $relatedItems = ELibraryItem::where('id', '!=', $item->id)
            ->where('is_approved', true)
            ->where(function ($q) use ($item) {
                $q->where('department', $item->department)
                  ->orWhere('category', $item->category);
            })
            ->take(4)
            ->get();

        return view('elibrary.show', compact('item', 'relatedItems'));
    }

    /**
     * Increment download metric and stream/download the file.
     */
    public function download(ELibraryItem $item)
    {
        $item->incrementDownloads();

        // If local file exists, download it
        if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
            $ext = pathinfo($item->file_path, PATHINFO_EXTENSION) ?: 'pdf';
            $downloadName = Str::slug($item->title) . '.' . $ext;
            return Storage::disk('public')->download($item->file_path, $downloadName);
        }

        // If external URL is provided, redirect to online source / Google Drive
        if ($item->external_url) {
            return redirect()->away($item->external_url);
        }

        // Fallback: Generate structured institutional text/synopsis sheet if file is pending upload
        $content = "=================================================================\n"
            . "KILACHAND DEVCHAND POLYTECHNIC, PATAN (ESTD. 1961)\n"
            . "GTU Institute Code: 622 | Digital E-Library & Project Vault\n"
            . "=================================================================\n\n"
            . "Title: {$item->title}\n"
            . "Category: {$item->category_label}\n"
            . "Department: {$item->department} Engineering\n"
            . ($item->semester ? "Semester: {$item->semester}\n" : "")
            . ($item->academic_year ? "Academic Year: {$item->academic_year}\n" : "")
            . ($item->subject_code ? "GTU Subject Code: {$item->subject_code} - {$item->subject_name}\n" : "")
            . ($item->author_or_team ? "Author / Project Team: {$item->author_or_team}\n" : "")
            . ($item->guide_faculty ? "Faculty Guide: {$item->guide_faculty}\n" : "")
            . ($item->tech_stack ? "Technical Stack / Methodologies: {$item->tech_stack}\n" : "")
            . "\nSYNOPSIS & ABSTRACT:\n"
            . "-----------------------------------------------------------------\n"
            . "{$item->description}\n\n"
            . "=================================================================\n"
            . "Downloaded from: " . route('elibrary.show', $item->slug) . "\n"
            . "કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961\n"
            . "=================================================================\n";

        $filename = Str::slug($item->title) . '-synopsis.txt';

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Store a new e-book, capstone synopsis, or lab reference sheet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:ebook,capstone_project,lab_manual'],
            'department' => ['required', 'string', 'max:100'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:6'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'author_or_team' => ['nullable', 'string', 'max:255'],
            'guide_faculty' => ['nullable', 'string', 'max:255'],
            'subject_code' => ['nullable', 'string', 'max:20'],
            'subject_name' => ['nullable', 'string', 'max:255'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:15'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'file' => ['nullable', 'file', 'mimes:pdf,epub,docx,zip,txt', 'max:20480'], // 20MB max
        ]);

        $filePath = null;
        $fileName = null;
        $fileFormat = 'PDF';
        $fileSize = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $filePath = $uploaded->store('e_library', 'public');
            $fileName = $uploaded->getClientOriginalName();
            $fileFormat = strtoupper($uploaded->getClientOriginalExtension());
            $bytes = $uploaded->getSize();
            $fileSize = $bytes >= 1048576 
                ? number_format($bytes / 1048576, 1) . ' MB' 
                : number_format($bytes / 1024, 0) . ' KB';
        }

        $item = ELibraryItem::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'department' => $validated['department'],
            'semester' => $validated['semester'] ?? null,
            'academic_year' => $validated['academic_year'] ?? null,
            'author_or_team' => $validated['author_or_team'] ?? null,
            'guide_faculty' => $validated['guide_faculty'] ?? null,
            'subject_code' => $validated['subject_code'] ?? null,
            'subject_name' => $validated['subject_name'] ?? null,
            'tech_stack' => $validated['tech_stack'] ?? null,
            'description' => $validated['description'],
            'external_url' => $validated['external_url'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_format' => $fileFormat,
            'file_size' => $fileSize,
            'is_featured' => false,
            'is_approved' => true,
        ]);

        return redirect()->route('elibrary.index')
            ->with('success', "{$item->category_label} '{$item->title}' published successfully to the KDP Digital E-Library!");
    }

    /**
     * Delete an e-library item (Owner or Admin).
     */
    public function destroy(ELibraryItem $item)
    {
        $user = Auth::user();
        if (!$user || ($item->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Unauthorized to delete this resource.');
        }

        if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
            Storage::disk('public')->delete($item->file_path);
        }

        $title = $item->title;
        $item->delete();

        return redirect()->route('elibrary.index')
            ->with('success', "Resource '{$title}' removed from the E-Library.");
    }
}
