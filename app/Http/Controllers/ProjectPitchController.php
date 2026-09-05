<?php

namespace App\Http\Controllers;

use App\Models\ProjectPitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPitchController extends Controller
{
    public function index()
    {
        $pitches = ProjectPitch::with('student')->latest()->get();
        return view('pitches.index', compact('pitches'));
    }

    public function create()
    {
        return view('pitches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'required|string|max:255',
            'assistance_needed' => 'required|string|max:255',
        ]);

        ProjectPitch::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
            'assistance_needed' => $request->assistance_needed,
            'status' => 'open',
        ]);

        return redirect()->route('pitches.index')->with('success', 'Project pitch submitted successfully!');
    }
}