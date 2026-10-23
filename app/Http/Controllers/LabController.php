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
        abort_unless(in_array(auth()->user()->role, ['faculty', 'admin']), 403, 'Unauthorized. Only faculty and administrators can register laboratories.');

        return view('labs.create');
    }

    public function store(Request $request)
    {
        abort_unless(in_array(auth()->user()->role, ['faculty', 'admin']), 403, 'Unauthorized. Only faculty and administrators can register laboratories.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'faculty_in_charge' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'equipment_list' => 'required|string',
            'description' => 'required|string',
        ]);

        $lab = Lab::create($validated);

        return redirect()->route('labs.show', $lab)->with('success', 'Lab added successfully!');
    }

    public function edit(Lab $lab)
    {
        abort_unless(in_array(auth()->user()->role, ['faculty', 'admin']), 403, 'Unauthorized. Only faculty and administrators can edit laboratories.');

        return view('labs.edit', compact('lab'));
    }

    public function update(Request $request, Lab $lab)
    {
        abort_unless(in_array(auth()->user()->role, ['faculty', 'admin']), 403, 'Unauthorized. Only faculty and administrators can update laboratories.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'faculty_in_charge' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'equipment_list' => 'required|string',
            'description' => 'required|string',
        ]);

        $lab->update($validated);

        return redirect()->route('labs.show', $lab)->with('success', 'Lab details updated successfully!');
    }

    public function destroy(Lab $lab)
    {
        abort_unless(in_array(auth()->user()->role, ['faculty', 'admin']), 403, 'Unauthorized. Only faculty and administrators can delete laboratories.');

        $lab->delete();

        return redirect()->route('labs.index')->with('success', 'Lab entry deleted successfully.');
    }
}
