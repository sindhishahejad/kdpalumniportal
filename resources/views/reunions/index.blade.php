@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showPlanModal: false }">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316] font-heading">Alumni Milestone Gatherings</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight mt-1">Batch Reunion Planner &amp; Homecomings</h1>
            <p class="text-slate-600 mt-2 text-sm max-w-2xl leading-relaxed">
                Reconnect with your diploma classmates, celebrate milestone anniversaries (Silver Jubilee, 10-Year, 5-Year), track attendee RSVPs, and share throwback campus memories.
            </p>
        </div>

        @auth
            @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
                <button type="button" 
                        @click="showPlanModal = true" 
                        class="inline-flex items-center gap-2 bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs transition-all whitespace-nowrap font-heading">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Plan a Batch Reunion
                </button>
            @endif
        @endauth
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 space-y-4">
        <form method="GET" action="{{ route('reunions.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <!-- Batch Year Selector -->
            <div class="sm:col-span-3">
                <select name="batch_year" class="w-full text-xs font-medium border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                    <option value="">All Graduation Batches</option>
                    @foreach($batchYears as $year)
                        <option value="{{ $year }}" {{ request('batch_year') == $year ? 'selected' : '' }}>
                            Class of {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department Selector -->
            <div class="sm:col-span-4">
                <select name="department" class="w-full text-xs font-medium border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Keyword Search -->
            <div class="sm:col-span-5 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reunion title, venue, or city..." 
                       class="w-full text-xs border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                <button type="submit" class="bg-[#0f172a] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>
                @if(request('batch_year') || request('department') || request('search'))
                    <a href="{{ route('reunions.index') }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Batch Reunions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reunions as $reunion)
            @php
                $myStatus = $userRsvps[$reunion->id] ?? null;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Milestone & Status Badge Row -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-orange-50 text-[#ea580c] border border-orange-200">
                            {{ $reunion->milestone_tag ?: 'Batch Reunion' }}
                        </span>

                        @if($myStatus)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $myStatus === 'attending' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($myStatus === 'maybe' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-100 text-gray-500') }}">
                                {{ $myStatus === 'attending' ? '✓ Attending' : ($myStatus === 'maybe' ? '🤔 Interested' : 'Declined') }}
                            </span>
                        @endif
                    </div>

                    <!-- Title & Batch Details -->
                    <h3 class="text-lg font-bold text-gray-900 leading-snug">
                        <a href="{{ route('reunions.show', $reunion->slug) }}" class="hover:text-[#1c3272] transition-colors">
                            {{ $reunion->title }}
                        </a>
                    </h3>

                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mt-1">
                        <span>🎓 Class of {{ $reunion->batch_year }}</span>
                        <span>&bull;</span>
                        <span class="truncate">{{ $reunion->department }}</span>
                    </div>

                    <!-- Date & Venue Callout -->
                    <div class="mt-4 p-3 bg-blue-50/60 rounded-xl border border-blue-100 space-y-1.5 text-xs text-gray-700">
                        <div class="flex items-center gap-2 font-bold text-[#1c3272]">
                            <svg class="w-4 h-4 text-[#f97316] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ $reunion->event_date->format('l, M d, Y \a\t h:i A') }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-[11px] text-gray-600">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $reunion->venue }}, {{ $reunion->city }}</span>
                        </div>
                    </div>

                    <!-- Description Preview -->
                    <p class="text-xs text-gray-600 line-clamp-2 mt-4 leading-relaxed">
                        {{ $reunion->description }}
                    </p>

                    <!-- Stats Row -->
                    <div class="mt-4 flex items-center gap-3 text-xs text-gray-500 pt-3 border-t border-gray-100">
                        <span class="flex items-center gap-1 font-bold text-gray-700">
                            <span>👥</span> {{ $reunion->rsvps_count }} {{ Str::plural('RSVP', $reunion->rsvps_count) }}
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1 font-bold text-[#f97316]">
                            <span>📸</span> {{ $reunion->memories_count }} Memories
                        </span>
                        @if($reunion->fee_per_person > 0)
                            <span class="ml-auto text-[11px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                ₹{{ number_format($reunion->fee_per_person) }}
                            </span>
                        @else
                            <span class="ml-auto text-[11px] font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                Free Entry
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <span class="text-[11px] text-gray-400 truncate">
                        Lead: {{ $reunion->organizer->name ?? 'Class Committee' }}
                    </span>

                    <a href="{{ route('reunions.show', $reunion->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] py-2 px-4 rounded-xl shadow-sm transition-colors">
                        RSVP & Memories &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center font-bold text-2xl">
                    🎉
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Batch Reunions Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-6">No reunions match your current batch or department filter. Plan one for your graduation class now!</p>
                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
                        <button type="button" @click="showPlanModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1c3272] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-[#152554] transition-colors">
                            <span>+</span> Plan a Reunion for Your Class
                        </button>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>

    @if($reunions->hasPages())
        <div class="mt-8">
            {{ $reunions->links() }}
        </div>
    @endif

    <!-- Plan Batch Reunion Modal (Alpine.js) -->
    @auth
        @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
            <div x-show="showPlanModal" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showPlanModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showPlanModal = false" 
                         class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showPlanModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block w-full max-w-xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-gray-100">
                        
                        <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316]">Class Committee</span>
                                <h3 class="text-xl font-serif font-bold text-gray-900 mt-0.5">Plan a Batch Reunion</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Organize a milestone reunion for your class and coordinate RSVPs.</p>
                            </div>
                            <button type="button" @click="showPlanModal = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('reunions.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Reunion Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required placeholder="e.g. Class of 2018 Mechanical 8-Year Reunion" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Graduation Batch Year <span class="text-red-500">*</span></label>
                                    <input type="text" name="batch_year" value="{{ auth()->user()->graduation_year ?? auth()->user()->profile->graduation_year ?? '' }}" required placeholder="e.g. 2018 or 2015-2018" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Department</label>
                                    <select name="department" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                        <option value="All Departments">All Departments (Combined)</option>
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        <option value="Electrical Engineering">Electrical Engineering</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Event Date & Time <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="event_date" required class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Milestone Tag</label>
                                    <input type="text" name="milestone_tag" placeholder="e.g. 5-Year Reunion, Silver Jubilee, Annual Catchup" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Venue / Gathering Place <span class="text-red-500">*</span></label>
                                    <input type="text" name="venue" required placeholder="e.g. KDP Central Lawn / Sidhpur Resort" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" value="Patan" required class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Fee / Contribution per Person (₹)</label>
                                    <input type="number" name="fee_per_person" value="0" min="0" placeholder="0 for Free entry" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">WhatsApp Organizing Group Link</label>
                                    <input type="url" name="whatsapp_group_link" placeholder="https://chat.whatsapp.com/invite/..." class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Description, Schedule & Nostalgia Note <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="3" required placeholder="Describe the reunion agenda, dinner arrangements, campus tour plans, or nostalgia note to your classmates..." class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]"></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                                <button type="button" @click="showPlanModal = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-lg shadow-sm transition-colors">
                                    Launch Batch Reunion &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

</div>
@endsection
