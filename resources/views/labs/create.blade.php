@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-6">Register New Department Lab</h1>
        
        <form action="{{ route('labs.store') }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Lab Name</label>
                <input type="text" name="name" class="w-full border-gray-300 rounded-xl text-sm" placeholder="e.g., Microprocessor & Embedded Systems Lab" required>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Department</label>
                    <select name="department" class="w-full border-gray-300 rounded-xl text-sm" required>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Electrical Engineering">Electrical Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Location / Room No.</label>
                    <input type="text" name="location" class="w-full border-gray-300 rounded-xl text-sm" placeholder="e.g., Room 204, CS Block" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Faculty In-Charge</label>
                <input type="text" name="faculty_in_charge" class="w-full border-gray-300 rounded-xl text-sm" placeholder="e.g., Prof. Rajesh Patel" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Equipment List (comma-separated)</label>
                <input type="text" name="equipment_list" class="w-full border-gray-300 rounded-xl text-sm" placeholder="ESP32 Kits, Oscilloscopes, Cisco Switches, DMMs" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-xl text-sm" placeholder="Describe the practical applications performed here..." required></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">Save Lab Entry</button>
        </form>
    </div>
@endsection