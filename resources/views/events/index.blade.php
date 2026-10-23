@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeCategory: 'all' }">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 pb-6 border-b border-gray-200 gap-4">
        <div>
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-[#f97316] mb-1">KD Polytechnic Patan</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-[#0f172a]">Campus & Alumni Events</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base max-w-2xl">
                Stay connected with KD Polytechnic. Join technical fests, reunions, guest lectures, and student-alumni networking sessions.
            </p>
        </div>

        @auth
            @if(auth()->user()->role === 'admin')
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}#events-section" class="inline-flex items-center gap-2 bg-[#1c3272] hover:bg-[#152554] text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Manage Events
                    </a>
                </div>
            @endif
        @endauth
    </div>

    <!-- Alert Status -->
    @if(session('event_status'))
        <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('event_status') }}</span>
        </div>
    @endif

    <!-- Category Filter Tabs -->
    @php
        $categories = $events->pluck('category')->unique()->filter()->values();
    @endphp

    @if($categories->count() > 0)
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                All Events ({{ $events->count() }})
            </button>
            @foreach($categories as $cat)
                <button @click="activeCategory = '{{ strtoupper($cat) }}'"
                        :class="activeCategory === '{{ strtoupper($cat) }}' ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ strtoupper($event->category) }}'"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                
                <!-- Event Image / Banner -->
                <div class="h-48 bg-gradient-to-br from-[#1c3272] to-[#2e53a3] relative overflow-hidden">
                    @if($event->image_path)
                        <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-white/80 p-4">
                            <svg class="w-12 h-12 mb-2 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs uppercase tracking-widest font-bold">{{ $event->category ?? 'Event' }}</span>
                        </div>
                    @endif

                    <!-- Category Pill -->
                    <div class="absolute top-4 left-4">
                        <span class="bg-[#0f172a]/80 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full shadow-sm">
                            {{ $event->category }}
                        </span>
                    </div>

                    <!-- Date Badge -->
                    <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md text-center rounded-xl px-3 py-1.5 shadow-md">
                        <span class="block text-[10px] font-black uppercase text-[#f97316] tracking-widest">{{ $event->event_date->format('M') }}</span>
                        <span class="block text-lg font-black text-[#0f172a] leading-none">{{ $event->event_date->format('d') }}</span>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <!-- Date & Time Row -->
                        <div class="flex items-center text-xs text-gray-500 font-medium mb-3 gap-3">
                            <span class="inline-flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $event->event_date->format('D, M j, Y') }}
                            </span>
                            @if($event->time_display)
                                <span class="inline-flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $event->time_display }}
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="font-serif font-bold text-lg text-[#0f172a] mb-2 leading-snug line-clamp-2">
                            {{ $event->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                            {{ $event->description ?: 'No description provided for this event.' }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                        @auth
                            @php
                                $userRsvp = \App\Models\EventRsvp::where('event_id', $event->id)->where('user_id', auth()->id())->first();
                            @endphp

                            <a href="{{ route('events.show', $event->id) }}" class="text-xs font-bold text-gray-600 hover:text-[#1c3272] transition-colors">
                                Details &rarr;
                            </a>

                            @if($userRsvp)
                                <a href="{{ route('events.ticket', $userRsvp->id) }}" class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 hover:bg-green-100 text-xs font-bold py-2 px-3.5 rounded-xl border border-green-200 transition-colors">
                                    <span>&#10003;</span>
                                    <span>Pass Ready</span>
                                </a>
                            @else
                                <a href="{{ route('events.rsvp', $event->id) }}" class="inline-flex items-center gap-1 bg-[#1c3272] hover:bg-[#152554] text-white text-xs font-bold py-2 px-4 rounded-xl shadow-sm transition-colors">
                                    RSVP Now
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full text-center bg-[#0f172a] hover:bg-black text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-sm transition-colors">
                                Sign In to View & RSVP
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Upcoming Events</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">There are currently no scheduled events. Check back soon for announcements on upcoming reunions, seminars, and webinars.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
