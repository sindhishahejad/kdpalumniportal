@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Pitch Your Project</h1>
        
        <form action="{{ route('pitches.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-4 border border-gray-100">
            @csrf
            <div>
                <label class="block font-medium text-gray-700">Project Title</label>
                <input type="text" name="title" class="w-full border-gray-300 rounded mt-1" placeholder="e.g., Smart Digital Bell System" required>
            </div>
            
            <div>
                <label class="block font-medium text-gray-700">Technology Stack</label>
                <input type="text" name="tech_stack" class="w-full border-gray-300 rounded mt-1" placeholder="e.g., ESP32, Blynk IoT, C++" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Assistance Needed</label>
                <input type="text" name="assistance_needed" class="w-full border-gray-300 rounded mt-1" placeholder="e.g., Relay Module Wiring, Code Review" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Project Description</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded mt-1" required></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Submit Pitch</button>
        </form>
    </div>
@endsection