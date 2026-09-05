<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->get('department');
        
        $labs = Lab::when($department, function ($query, $department) {
            return $query->where('department', $department);
        })->get();

        return view('labs.index', compact('labs', 'department'));
    }

    public function show(Lab $lab)
    {
        return view('labs.show', compact('lab'));
    }

    public function create()
    {
        return view('labs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'faculty_in_charge' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'equipment_list' => 'required|string',
            'description' => 'required|string',
        ]);

        Lab::create($request->all());

        return redirect()->route('labs.index')->with('success', 'Lab added successfully!');
    }
}