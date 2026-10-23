@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('labs.show', $lab->id) }}" class="text-sm font-semibold text-blue-600 hover:underline inline-flex items-center gap-1 mb-2">
                &larr; Back to Lab Details
            </a>
            <h1 class="text-2xl font-serif font-bold text-[#0f172a]">Edit Department Lab</h1>
            <p class="text-xs text-gray-500 mt-0.5">Update equipment inventory, room location, or faculty coordinator assignment.</p>
        </div>
        <form action="{{ route('labs.destroy', $lab->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this lab record?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold py-2 px-3.5 rounded-xl border border-red-200 transition-colors">
                Delete Lab
            </button>
        </form>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('labs.update', $lab->id) }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Lab Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $lab->name) }}" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                <select name="department" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="Computer Engineering" {{ old('department', $lab->department) == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                    <option value="Electrical Engineering" {{ old('department', $lab->department) == 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                    <option value="Mechanical Engineering" {{ old('department', $lab->department) == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                    <option value="Civil Engineering" {{ old('department', $lab->department) == 'Civil Engineering' ? 'selected' : '' }}>Civil Engineering</option>
                    <option value="Science & Humanities" {{ old('department', $lab->department) == 'Science & Humanities' ? 'selected' : '' }}>Science &amp; Humanities</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Location / Room No. <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location', $lab->location) }}" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Faculty In-Charge <span class="text-red-500">*</span></label>
            <input type="text" name="faculty_in_charge" value="{{ old('faculty_in_charge', $lab->faculty_in_charge) }}" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Equipment List (comma-separated) <span class="text-red-500">*</span></label>
            <input type="text" name="equipment_list" value="{{ old('equipment_list', $lab->equipment_list) }}" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>
            <span class="text-[11px] text-gray-400 mt-1 block">List key hardware, kits, and tools separated by commas.</span>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $lab->description) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
            <a href="{{ route('labs.show', $lab->id) }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-[#1c3272] hover:bg-[#152554] text-white py-2.5 px-6 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                Save Changes &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
