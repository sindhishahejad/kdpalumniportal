@extends('layouts.app')

@section('content')
@php
    $upcomingList = $upcomingEvents ?? $events ?? collect();
    $pastList = $pastEvents ?? collect();
    $allList = $upcomingList->concat($pastList);
    $categories = $allList->pluck('category')->unique()->filter()->values();
@endphp

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans space-y-8" 
     x-data="{ 
         viewTab: 'upcoming', 
         activeCategory: 'all',
         searchQuery: '',
         matches(event, isUpcoming) {
             if (this.viewTab === 'upcoming' && !isUpcoming) return false;
             if (this.viewTab === 'past' && isUpcoming) return false;
             if (this.activeCategory !== 'all' && (event.category || '').toUpperCase() !== this.activeCategory.toUpperCase()) return false;
             if (!this.searchQuery) return true;
             const q = this.searchQuery.toLowerCase();
             return (event.title && event.title.toLowerCase().includes(q)) ||
                    (event.category && event.category.toLowerCase().includes(q)) ||
                    (event.time_display && event.time_display.toLowerCase().includes(q)) ||
                    (event.description && event.description.toLowerCase().includes(q));
         }
     }">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-heading font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">Campus Activities &amp; Events</span>
        </nav>

        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}#events-section" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-[#0f2042] text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all font-heading">
                    <span>⚙️</span>
                    <span>Admin Events Control Center</span>
                </a>
            @endif
        @endauth
    </div>

    <!-- Institutional Hero Banner with College Crest Watermark -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white shadow-2xl p-8 sm:p-12 border border-blue-900/40">
        <!-- Background Glows -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Crest Watermark -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none flex items-center justify-end pr-8 overflow-hidden">
            <img src="{{ asset('images/kdp_crest.png') }}" class="h-80 w-auto object-contain filter grayscale contrast-200" alt="KDP Crest Watermark">
        </div>

        <div class="relative z-10 max-w-3xl space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wide border border-amber-500/30">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-wider border border-white/20">
                    Government of Gujarat • CTE
                </span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black font-heading tracking-tight leading-tight text-white">
                Campus Activities, Seminars &amp; Alumni Events
            </h1>

            <p class="text-blue-100 text-sm sm:text-base leading-relaxed max-w-2xl font-light">
                Stay engaged with K. D. Polytechnic. Join hands-on engineering workshops, technical symposiums, annual alumni reunions, and student-industry networking sessions.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-2 text-xs font-semibold text-blue-200">
                <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                    <span>🏛️</span> Official Campus Activities
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                    <span>🎟️</span> Instant Digital Entry Pass
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                    <span>🤝</span> Alumni Networking &amp; Reunions
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Status -->
    @if(session('event_status'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('event_status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Main Navigation Tabs: Upcoming vs Past Highlights -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <!-- Tab Switcher -->
            <div class="flex items-center gap-2.5">
                <button type="button" 
                        @click="viewTab = 'upcoming'" 
                        :class="viewTab === 'upcoming' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading">
                    <span>📅 Upcoming Events &amp; RSVPs</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="viewTab === 'upcoming' ? 'bg-white/20 text-white' : 'bg-white text-slate-700 shadow-2xs'">
                        {{ $upcomingList->count() }}
                    </span>
                </button>

                <button type="button" 
                        @click="viewTab = 'past'" 
                        :class="viewTab === 'past' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading">
                    <span>🏛️ Past Activities &amp; Archives</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="viewTab === 'past' ? 'bg-white/20 text-white' : 'bg-white text-slate-700 shadow-2xs'">
                        {{ $pastList->count() }}
                    </span>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    🔍
                </span>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Search events, keyword, venue..." 
                       class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl border-slate-300 bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-xs font-sans">
                <button x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    &times;
                </button>
            </div>
        </div>

        <!-- Category Filter Pills -->
        @if($categories->count() > 0)
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button @click="activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-[#f97316] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold transition-all shrink-0 cursor-pointer">
                    All Categories
                </button>
                @foreach($categories as $cat)
                    <button @click="activeCategory = '{{ strtoupper($cat) }}'"
                            :class="activeCategory === '{{ strtoupper($cat) }}' ? 'bg-[#f97316] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold transition-all uppercase tracking-wider shrink-0 cursor-pointer">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- UPCOMING EVENTS LOOP -->
        @foreach($upcomingList as $event)
            <div x-show="matches({{ json_encode($event) }}, true)"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group hover:border-[#1e3a8a]/40">
                
                <div>
                    <!-- Event Cover Image Banner -->
                    <div class="h-52 bg-slate-900 relative overflow-hidden">
                        @if($event->image_path)
                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#0f2042] via-[#1e3a8a] to-[#0f172a] flex flex-col items-center justify-center text-white/90 p-4 text-center">
                                <span class="text-3xl mb-1">🏛️</span>
                                <span class="text-xs uppercase tracking-widest font-black font-heading">{{ $event->category ?? 'Campus Event' }}</span>
                                <span class="text-[10px] text-blue-200 mt-1">K. D. Polytechnic, Patan</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                        <!-- Category Pill -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-[#0f2042]/90 backdrop-blur-md text-white text-[10px] font-heading font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-xs border border-white/20">
                                {{ $event->category }}
                            </span>
                        </div>

                        <!-- Date Badge -->
                        <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md text-center rounded-2xl px-3.5 py-1.5 shadow-md border border-slate-100">
                            <span class="block text-[10px] font-heading font-black uppercase text-[#f97316] tracking-widest leading-none">{{ $event->event_date->format('M') }}</span>
                            <span class="block text-2xl font-heading font-black text-[#0f2042] leading-tight mt-0.5">{{ $event->event_date->format('d') }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 leading-none">{{ $event->event_date->format('Y') }}</span>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="p-6 space-y-3">
                        <!-- Date & Time Row -->
                        <div class="flex items-center text-xs text-slate-500 font-medium gap-3 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 text-[#1e3a8a] font-bold">
                                📅 <span>{{ $event->event_date->format('D, M j, Y') }}</span>
                            </span>
                            @if($event->time_display)
                                <span class="inline-flex items-center gap-1 text-slate-600">
                                    ⏰ <span>{{ $event->time_display }}</span>
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="font-heading font-bold text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors line-clamp-2 leading-snug">
                            <a href="{{ route('events.show', $event->id) }}">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <!-- Description Snippet -->
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $event->description ?: 'No detailed description provided for this campus activity.' }}
                        </p>
                    </div>
                </div>

                <!-- Card Footer & Action Buttons -->
                <div class="px-6 pb-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3 bg-slate-50/50">
                    <span class="text-[11px] font-bold text-slate-500 flex items-center gap-1">
                        🎟️ <span>{{ $event->rsvps_count ?? 0 }} Registered</span>
                    </span>

                    @auth
                        @php
                            $userRsvp = \App\Models\EventRsvp::where('event_id', $event->id)->where('user_id', auth()->id())->first();
                        @endphp

                        @if($userRsvp)
                            <a href="{{ route('events.ticket', $userRsvp->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-xs font-bold py-2 px-3.5 rounded-xl border border-emerald-200 transition-colors shadow-2xs font-heading">
                                <span>✓</span>
                                <span>Entry Pass Ready</span>
                            </a>
                        @else
                            <a href="{{ route('events.rsvp', $event->id) }}" class="inline-flex items-center gap-1 bg-[#1e3a8a] hover:bg-[#0f2042] text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-colors font-heading uppercase tracking-wider">
                                <span>RSVP &amp; Get Pass &rarr;</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 bg-[#1e3a8a] hover:bg-[#0f2042] text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-colors font-heading uppercase tracking-wider">
                            Sign In to RSVP &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        @endforeach

        <!-- PAST EVENTS LOOP -->
        @foreach($pastList as $event)
            <div x-show="matches({{ json_encode($event) }}, false)"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col justify-between group opacity-90 hover:opacity-100">
                
                <div>
                    <!-- Event Cover Image Banner -->
                    <div class="h-48 bg-slate-800 relative overflow-hidden filter grayscale-20 group-hover:grayscale-0 transition-all">
                        @if($event->image_path)
                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-800 flex flex-col items-center justify-center text-white/80 p-4 text-center">
                                <span class="text-2xl mb-1">📷</span>
                                <span class="text-xs uppercase tracking-widest font-bold">{{ $event->category ?? 'Past Activity' }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                        <!-- Past Archive Pill -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-heading font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-xs">
                                Concluded • {{ $event->category }}
                            </span>
                        </div>

                        <!-- Date Badge -->
                        <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md text-center rounded-2xl px-3 py-1.5 shadow-md border border-slate-100">
                            <span class="block text-[9px] font-heading font-bold uppercase text-slate-500 tracking-widest leading-none">{{ $event->event_date->format('M') }}</span>
                            <span class="block text-xl font-heading font-black text-slate-800 leading-tight mt-0.5">{{ $event->event_date->format('d') }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 leading-none">{{ $event->event_date->format('Y') }}</span>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="p-6 space-y-3">
                        <div class="flex items-center text-xs text-slate-500 font-medium gap-2">
                            <span>📅 {{ $event->event_date->format('M j, Y') }}</span>
                            <span>•</span>
                            <span>⏰ {{ $event->time_display }}</span>
                        </div>

                        <h3 class="font-heading font-bold text-base text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors line-clamp-2 leading-snug">
                            <a href="{{ route('events.show', $event->id) }}">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $event->description ?: 'Activity successfully completed at K. D. Polytechnic Patan campus.' }}
                        </p>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-6 pb-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3 bg-slate-50/50">
                    <span class="text-[11px] font-bold text-slate-500">
                        🎟️ {{ $event->rsvps_count ?? 0 }} Alumni Attended
                    </span>

                    <a href="{{ route('events.show', $event->id) }}" class="text-xs font-heading font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider flex items-center gap-1 transition-colors">
                        <span>View Details</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Empty State for Upcoming -->
    <div x-show="viewTab === 'upcoming' && {{ $upcomingList->count() }} === 0" 
         class="py-16 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 p-8 space-y-3">
        <div class="w-16 h-16 mx-auto bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center text-3xl">
            📅
        </div>
        <h3 class="text-base font-heading font-bold text-[#0f172a]">No Upcoming Events Scheduled</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto font-sans leading-relaxed">
            There are currently no scheduled upcoming activities. Check the past archives or check back soon for campus updates.
        </p>
        <button type="button" @click="viewTab = 'past'" class="text-xs font-heading font-bold text-[#1e3a8a] hover:underline uppercase tracking-wider">
            View Past Campus Activities &rarr;
        </button>
    </div>

    <!-- Empty State for Past -->
    <div x-show="viewTab === 'past' && {{ $pastList->count() }} === 0" 
         class="py-16 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 p-8 space-y-3">
        <div class="w-16 h-16 mx-auto bg-slate-100 text-slate-600 rounded-full flex items-center justify-center text-3xl">
            🏛️
        </div>
        <h3 class="text-base font-heading font-bold text-[#0f172a]">No Past Activity Archives Found</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto font-sans">
            Past symposiums, seminars, and reunions will appear here after their scheduled dates.
        </p>
    </div>

</div>
@endsection
