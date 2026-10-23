@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-200 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Campus Infrastructure</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a] mt-1">Department Labs & Workshops</h1>
                <p class="text-gray-500 mt-1 text-sm max-w-2xl">Explore institutional engineering laboratories, hardware inventories, room allocations, and faculty in-charge leads.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('labs.index') }}">
                    <select name="department" onchange="this.form.submit()" class="border-gray-200 rounded-xl text-xs font-semibold focus:border-[#1c3272] focus:ring-[#1c3272] py-2 px-3">
                        <option value="">All Departments</option>
                        <option value="Computer Engineering" {{ request('department') == 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                        <option value="Electrical Engineering" {{ request('department') == 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                        <option value="Mechanical Engineering" {{ request('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                        <option value="Civil Engineering" {{ request('department') == 'Civil Engineering' ? 'selected' : '' }}>Civil Engineering</option>
                        <option value="Science & Humanities" {{ request('department') == 'Science & Humanities' ? 'selected' : '' }}>Science &amp; Humanities</option>
                    </select>
                </form>

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'faculty']))
                        <a href="{{ route('labs.create') }}" class="inline-flex items-center gap-1.5 bg-[#1c3272] hover:bg-[#152554] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Laboratory
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($labs as $lab)
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 px-3 py-1 rounded-full">{{ $lab->department }}</span>
                            <span class="text-xs text-gray-400 font-medium">{{ $lab->location }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-[#0f172a] mb-2 leading-snug">{{ $lab->name }}</h2>
                        <p class="text-gray-600 text-xs mb-4 line-clamp-2 leading-relaxed">{{ $lab->description }}</p>
                        
                        <div class="mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Faculty Coordinator</p>
                            <p class="text-xs font-semibold text-[#0f172a] mt-0.5">{{ $lab->faculty_in_charge }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center gap-2">
                        <a href="{{ route('labs.show', $lab->id) }}" class="flex-grow text-center bg-[#0f172a] hover:bg-black text-white py-2 rounded-xl text-xs font-bold transition-colors">
                            View Equipment Inventory &rarr;
                        </a>
                        @auth
                            @if(in_array(auth()->user()->role, ['admin', 'faculty']))
                                <a href="{{ route('labs.edit', $lab->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-xl text-xs font-bold transition-colors" title="Edit Lab">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">No laboratories found matching this department filter.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
