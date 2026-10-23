@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-orange-100 text-orange-800">
                        Student Innovation Hub
                    </span>
                    <span class="text-xs text-gray-500">KD Polytechnic Incubation Cell</span>
                </div>
                <h1 class="text-3xl font-serif font-bold text-[#0f172a] mt-1">Project Incubation Board</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Connect aspiring student innovators with alumni industry veterans and faculty mentors.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                @if(auth()->user()->role === 'student' || auth()->user()->role === 'faculty' || auth()->user()->isAdmin())
                    <a href="{{ route('pitches.create') }}" class="inline-flex items-center gap-2 bg-[#8b0000] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#6b0000] shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Pitch a Project
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-bold">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 font-bold">&times;</button>
            </div>
        @endif

        <!-- Stats Metric Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('pitches.index') }}" class="bg-white p-4 rounded-xl border {{ !request('status') && !request('my_pitches') ? 'border-[#8b0000] ring-1 ring-[#8b0000]' : 'border-gray-200' }} shadow-sm hover:border-[#8b0000] transition-colors">
                <span class="text-xs uppercase font-bold text-gray-500">All Pitches</span>
                <p class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total'] }}</p>
            </a>
            <a href="{{ route('pitches.index', ['status' => 'open']) }}" class="bg-white p-4 rounded-xl border {{ request('status') === 'open' ? 'border-emerald-600 ring-1 ring-emerald-600' : 'border-gray-200' }} shadow-sm hover:border-emerald-600 transition-colors">
                <span class="text-xs uppercase font-bold text-emerald-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Open for Mentorship
                </span>
                <p class="text-2xl font-black text-emerald-800 mt-1">{{ $stats['open'] }}</p>
            </a>
            <a href="{{ route('pitches.index', ['status' => 'in_progress']) }}" class="bg-white p-4 rounded-xl border {{ request('status') === 'in_progress' ? 'border-blue-600 ring-1 ring-blue-600' : 'border-gray-200' }} shadow-sm hover:border-blue-600 transition-colors">
                <span class="text-xs uppercase font-bold text-blue-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    In Progress
                </span>
                <p class="text-2xl font-black text-blue-900 mt-1">{{ $stats['in_progress'] }}</p>
            </a>
            <a href="{{ route('pitches.index', ['status' => 'completed']) }}" class="bg-white p-4 rounded-xl border {{ request('status') === 'completed' ? 'border-purple-600 ring-1 ring-purple-600' : 'border-gray-200' }} shadow-sm hover:border-purple-600 transition-colors">
                <span class="text-xs uppercase font-bold text-purple-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    Completed / Incubated
                </span>
                <p class="text-2xl font-black text-purple-900 mt-1">{{ $stats['completed'] }}</p>
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('pitches.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('my_pitches'))
                    <input type="hidden" name="my_pitches" value="1">
                @endif

                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, student, tech stack (e.g. IoT, Laravel)..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#8b0000]">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <button type="submit" class="bg-[#8b0000] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#6b0000] transition-colors shrink-0">
                    Search
                </button>
                @if(request()->anyFilled(['search', 'status', 'my_pitches']))
                    <a href="{{ route('pitches.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors text-center shrink-0">
                        Clear Filters
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                @auth
                    @if(request('my_pitches'))
                        <a href="{{ route('pitches.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition-colors">
                            View All Pitches
                        </a>
                    @else
                        <a href="{{ route('pitches.index', ['my_pitches' => 1]) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-orange-50 text-orange-800 hover:bg-orange-100 border border-orange-200 transition-colors">
                            Show My Pitches
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Pitches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pitches as $pitch)
                @php
                    $statusStyles = [
                        'open' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Open for Mentorship'],
                        'in_progress' => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'In Progress'],
                        'completed' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'dot' => 'bg-purple-500', 'label' => 'Completed / Incubated'],
                    ][$pitch->status] ?? ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'dot' => 'bg-gray-400', 'label' => ucfirst($pitch->status)];
                @endphp

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between overflow-hidden group">
                    <div class="p-6">
                        
                        <!-- Top Row: Status & Date -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusStyles['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $statusStyles['label'] }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $pitch->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Title -->
                        <h2 class="text-lg font-bold text-gray-900 group-hover:text-[#8b0000] transition-colors mb-2 line-clamp-2">
                            <a href="{{ route('pitches.show', $pitch->id) }}">
                                {{ $pitch->title }}
                            </a>
                        </h2>

                        <!-- Student Creator Byline -->
                        <div class="flex items-center gap-2 mb-4 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Pitch by: <strong class="text-gray-800">{{ $pitch->student->name ?? 'Student Innovator' }}</strong></span>
                            @if(optional($pitch->student)->department)
                                <span>• {{ $pitch->student->department }}</span>
                            @endif
                        </div>

                        <!-- Description Snippet -->
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                            {{ $pitch->description }}
                        </p>

                        <!-- Tech Stack Tags -->
                        <div class="mb-3">
                            <span class="text-[11px] uppercase font-bold text-gray-400 tracking-wider block mb-1">Tech Stack</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(array_map('trim', explode(',', $pitch->tech_stack)) as $tag)
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded font-mono">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Assistance Needed -->
                        <div>
                            <span class="text-[11px] uppercase font-bold text-orange-600 tracking-wider block mb-1">Assistance Needed</span>
                            <p class="text-xs text-gray-700 bg-orange-50/70 border border-orange-100 rounded-lg p-2 font-medium">
                                {{ $pitch->assistance_needed }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-100 flex items-center justify-between gap-2">
                        <a href="{{ route('pitches.show', $pitch->id) }}" class="text-xs font-bold text-[#8b0000] hover:underline inline-flex items-center gap-1">
                            <span>View Lifecycle &amp; Details</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                        @if(auth()->user()->role === 'alumni' || auth()->user()->role === 'faculty')
                            @if($pitch->user_id !== auth()->id())
                                <a href="{{ route('pitches.show', $pitch->id) }}#guidance" class="bg-white hover:bg-gray-100 text-[#1C3661] border border-[#1C3661]/30 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-colors">
                                    Offer Guidance &rarr;
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    <h3 class="text-base font-semibold text-gray-800">No project pitches found</h3>
                    <p class="text-sm text-gray-500 mt-1">Try clearing your search query or submit the first student project pitch.</p>
                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('pitches.create') }}" class="inline-block mt-4 bg-[#8b0000] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#6b0000]">
                            Pitch Your Project
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pitches->links() }}
        </div>

    </div>
</div>
@endsection
