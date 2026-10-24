@extends('layouts.app')

@section('content')
<div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-sans space-y-6">
    
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-heading font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('events.index') }}" class="hover:text-[#1e3a8a] transition-colors">Campus Events</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800 line-clamp-1 truncate max-w-xs">{{ $event->title }}</span>
        </nav>

        <a href="{{ route('events.index') }}" class="text-xs font-heading font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider flex items-center gap-1 transition-colors">
            &larr; Back to Events Hub
        </a>
    </div>

    <!-- Event Detail Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Large Cover Image Banner -->
        <div class="w-full h-72 sm:h-96 bg-slate-900 relative overflow-hidden">
            @if($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-[#0f2042] via-[#1e3a8a] to-[#0f172a] flex flex-col items-center justify-center text-white/90 p-6 text-center">
                    <span class="text-5xl mb-2">🏛️</span>
                    <h2 class="text-2xl font-black font-heading">{{ $event->category ?? 'Campus Event' }}</h2>
                    <p class="text-xs text-blue-200 mt-1 font-sans">K. D. Polytechnic, Patan • Government of Gujarat</p>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

            <!-- Category & Status Badge -->
            <div class="absolute top-4 left-4 flex items-center gap-2">
                <span class="bg-[#0f2042]/90 backdrop-blur-md text-white text-[11px] font-black uppercase tracking-widest px-3.5 py-1.5 rounded-full shadow-xs border border-white/20 font-heading">
                    {{ $event->category }}
                </span>

                @if($event->event_date >= today())
                    <span class="bg-emerald-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-xs">
                        Upcoming Activity
                    </span>
                @else
                    <span class="bg-slate-800/90 backdrop-blur-md text-slate-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-xs">
                        Concluded Archive
                    </span>
                @endif
            </div>

            <!-- Date Box -->
            <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-md text-center rounded-2xl px-4 py-2 shadow-md border border-slate-100">
                <span class="block text-[10px] font-heading font-black uppercase text-[#f97316] tracking-widest leading-none">{{ $event->event_date->format('M') }}</span>
                <span class="block text-2xl font-heading font-black text-[#0f2042] leading-tight mt-0.5">{{ $event->event_date->format('d') }}</span>
                <span class="block text-[10px] font-bold text-slate-400 leading-none">{{ $event->event_date->format('Y') }}</span>
            </div>
        </div>

        <div class="p-6 sm:p-10 space-y-8">
            <!-- Event Title & Institutional Badge -->
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-900 text-xs font-semibold tracking-wide border border-amber-500/20">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    <span>કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-black text-[#0f2042] leading-tight">
                    {{ $event->title }}
                </h1>
            </div>

            <!-- Meta Quick Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-200/80">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <span class="text-[10px] uppercase font-heading font-bold text-slate-400 tracking-wider block">Scheduled Date</span>
                        <span class="text-xs font-bold text-[#0f2042]">{{ $event->event_date->format('l, F j, Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-2xl">⏰</span>
                    <div>
                        <span class="text-[10px] uppercase font-heading font-bold text-slate-400 tracking-wider block">Timing &amp; Venue</span>
                        <span class="text-xs font-bold text-[#0f2042]">{{ $event->time_display }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎟️</span>
                    <div>
                        <span class="text-[10px] uppercase font-heading font-bold text-slate-400 tracking-wider block">RSVPs &amp; Attendees</span>
                        <span class="text-xs font-bold text-[#0f2042]">{{ $event->rsvps_count ?? $event->rsvps()->count() }} Alumni Registered</span>
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="space-y-3">
                <h3 class="text-xs font-heading font-bold uppercase tracking-wider text-slate-700">About This Campus Activity</h3>
                <div class="text-slate-700 leading-relaxed text-sm sm:text-base space-y-4 font-light whitespace-pre-line">
                    @if($event->description)
                        {{ $event->description }}
                    @else
                        <p class="italic text-slate-400 text-sm">No additional details provided for this campus event.</p>
                    @endif
                </div>
            </div>

            <!-- Action Area / Digital Entry Pass -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 p-6 sm:p-8 rounded-2xl border border-slate-200">
                <div class="space-y-1">
                    <h4 class="text-sm font-heading font-bold text-[#0f2042] uppercase tracking-wider">
                        @if($event->event_date >= today())
                            Attending this event?
                        @else
                            Event concluded
                        @endif
                    </h4>
                    <p class="text-xs text-slate-500 font-sans">
                        @if($event->event_date >= today())
                            Confirm your presence to generate your official digital entry pass for campus security.
                        @else
                            This activity has concluded. Digital passes for completed events are archived.
                        @endif
                    </p>
                </div>

                @auth
                    @php
                        $userRsvp = \App\Models\EventRsvp::where('event_id', $event->id)->where('user_id', auth()->id())->first();
                    @endphp

                    @if($userRsvp)
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="inline-flex items-center gap-1 text-emerald-800 bg-emerald-100 px-3 py-2 rounded-xl text-xs font-heading font-bold">
                                <span>✓</span> RSVP Confirmed
                            </span>
                            <a href="{{ route('events.ticket', $userRsvp->id) }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-[#0f2042] text-white px-5 py-2.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider transition-colors shadow-xs">
                                <span>🪪</span> View Digital Pass &rarr;
                            </a>
                        </div>
                    @elseif($event->event_date >= today())
                        <a href="{{ route('events.rsvp', $event->id) }}" class="inline-flex items-center gap-2 bg-[#f97316] hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider transition-all shadow-md flex-shrink-0">
                            <span>🎟️</span> RSVP &amp; Generate Pass
                        </a>
                    @else
                        <span class="text-xs font-heading font-bold text-slate-400 bg-slate-100 px-4 py-2 rounded-xl flex-shrink-0 uppercase tracking-wider">
                            Registration Closed
                        </span>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-[#1e3a8a] hover:bg-[#0f2042] text-white px-6 py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider transition-colors shadow-xs flex-shrink-0">
                        Sign In to RSVP &rarr;
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection
