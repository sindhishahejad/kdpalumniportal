@extends('layouts.app')

@section('content')
<div class="py-12 max-w-xl mx-auto px-4 font-sans space-y-6" x-data="{ copied: false }">
    
    <!-- Action Bar (Hidden on Print) -->
    <div class="flex items-center justify-between gap-4 print:hidden">
        <a href="{{ route('events.index') }}" class="text-xs font-heading font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider flex items-center gap-1 transition-colors">
            &larr; Back to Events Hub
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-[#1e3a8a] hover:bg-[#0f2042] text-white rounded-xl text-xs font-heading font-bold uppercase tracking-wider transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <span>🖨️</span>
                <span>Print Entry Pass</span>
            </button>
            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-heading font-bold uppercase tracking-wider transition-colors">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Official Entry Pass Card -->
    <div class="bg-white border-2 border-slate-300 rounded-3xl shadow-xl overflow-hidden print:border-black print:shadow-none print:rounded-none">
        
        <!-- Institutional Header -->
        <div class="bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white p-6 text-center relative border-b-4 border-[#f97316]">
            <!-- Logos Row -->
            <div class="flex items-center justify-center gap-4 mb-3">
                <img src="{{ asset('images/kdp_crest.png') }}" class="w-14 h-14 object-contain filter brightness-110" alt="KDP Crest">
                <div class="text-center">
                    <span class="block text-[9px] uppercase tracking-widest text-[#f97316] font-extrabold font-heading">Govt. of Gujarat • CTE</span>
                    <h2 class="text-xs sm:text-sm font-black font-heading tracking-wide uppercase">કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ</h2>
                    <span class="block text-[11px] font-bold text-white tracking-wide font-heading">K. D. Polytechnic, Patan</span>
                    <span class="block text-[10px] text-blue-200 font-sans">Alumni Association &amp; Event Cell</span>
                </div>
                <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-12 h-12 object-contain rounded-full bg-white p-0.5 shadow-sm" alt="GTU CTE Logo">
            </div>

            <!-- Banner Ribbon -->
            <div class="inline-block bg-[#f97316] text-white text-[10px] font-black uppercase tracking-widest px-4 py-1 rounded-full shadow-md font-heading">
                Official Digital Event Entry Pass
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Event Title & Date Row -->
            <div class="border-b border-slate-100 pb-5 text-center sm:text-left space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider bg-blue-50 text-[#1e3a8a] border border-blue-200">
                    {{ $rsvp->event->category ?? 'Campus Event' }}
                </span>
                <h1 class="text-xl sm:text-2xl font-black font-heading text-[#0f2042] leading-tight">
                    {{ $rsvp->event->title ?? 'Campus Activity' }}
                </h1>
                
                <div class="pt-1 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-semibold text-slate-600">
                    <span class="flex items-center gap-1.5 text-[#1e3a8a]">
                        📅 <span>{{ optional($rsvp->event->event_date)->format('l, F j, Y') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-slate-600">
                        ⏰ <span>{{ $rsvp->event->time_display }}</span>
                    </span>
                </div>
            </div>

            <!-- Attendee Details -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80">
                <div>
                    <p class="text-[10px] text-slate-400 font-heading font-bold uppercase tracking-wider">Attendee Name</p>
                    <p class="text-xs sm:text-sm font-heading font-bold text-[#0f2042] mt-0.5">{{ $rsvp->user->name }}</p>
                    <p class="text-[11px] text-slate-500 font-sans truncate">{{ $rsvp->user->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-heading font-bold uppercase tracking-wider">Designation / Role</p>
                    <p class="text-xs sm:text-sm font-heading font-bold text-[#0f2042] mt-0.5">{{ ucfirst($rsvp->user->role) }}</p>
                    <p class="text-[11px] text-slate-500 font-sans">
                        {{ optional($rsvp->user->profile)->department ? optional($rsvp->user->profile)->department . ' Dept.' : 'KDP Alumni' }}
                    </p>
                </div>
            </div>

            <!-- Real-Time QR Code & Pass Code -->
            <div class="bg-gradient-to-b from-blue-50/40 via-orange-50/20 to-white border-2 border-dashed border-blue-200 p-6 rounded-2xl text-center space-y-4">
                <div class="inline-block bg-white p-3.5 rounded-2xl shadow-sm border border-slate-200">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('events.ticket', $rsvp->id)) }}" 
                         alt="Entry Pass QR Code" 
                         class="w-32 h-32 mx-auto">
                </div>

                <div class="space-y-1">
                    <p class="text-[10px] font-heading font-bold text-slate-500 uppercase tracking-widest mb-1">Unique Entry Ticket Passcode</p>
                    <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-300 shadow-2xs">
                        <span class="text-base sm:text-lg font-mono font-black text-[#1e3a8a] tracking-wider">{{ $rsvp->ticket_code }}</span>
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ $rsvp->ticket_code }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="text-slate-400 hover:text-[#1e3a8a] cursor-pointer text-xs transition-colors" 
                                title="Copy Passcode">
                            <span x-show="!copied">📋</span>
                            <span x-show="copied" class="text-emerald-600 font-bold text-[10px]">Copied!</span>
                        </button>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 max-w-sm mx-auto font-sans leading-relaxed">
                    Present this official digital pass or QR code at the campus gate / seminar entrance for verified attendee clearance.
                </p>
            </div>

            <!-- Footer Badge -->
            <div class="text-center pt-2">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-heading font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Verified Entry Clearance • Authorized by KDP Patan
                </span>
            </div>

        </div>
    </div>
</div>
@endsection
