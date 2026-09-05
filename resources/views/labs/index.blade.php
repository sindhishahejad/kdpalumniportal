@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#0f172a]">KDP Department Labs & Workshops</h1>
                <p class="text-gray-500 mt-1 text-sm">Explore institutional infrastructure, hardware inventory, and faculty leads.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('labs.index') }}">
                    <select name="department" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-sm">
                        <option value="">All Departments</option>
                        <option value="Computer Engineering" {{ request('department') == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                        <option value="Electrical Engineering" {{ request('department') == 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                        <option value="Mechanical Engineering" {{ request('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                        <option value="Civil Engineering" {{ request('department') == 'Civil Engineering' ? 'selected' : '' }}>Civil Engineering</option>
                    </select>
                </form>

                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'faculty')
                    <a href="{{ route('labs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-sm">+ Add Lab</a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($labs as $lab)
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 px-3 py-1 rounded-full">{{ $lab->department }}</span>
                            <span class="text-xs text-gray-400 font-medium">{{ $lab->location }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-[#0f172a] mb-2">{{ $lab->name }}</h2>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $lab->description }}</p>
                        
                        <div class="mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase">Faculty In-Charge</p>
                            <p class="text-sm font-semibold text-[#0f172a]">{{ $lab->faculty_in_charge }}</p>
                        </div>
                    </div>

                    <a href="{{ route('labs.show', $lab->id) }}" class="w-full text-center bg-gray-900 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-black transition-colors">
                        View Equipment Inventory &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <p class="text-gray-500 text-sm">No laboratories found for this filter.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection