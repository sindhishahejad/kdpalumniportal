@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 text-slate-100 font-sans pb-20">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0a1428] via-[#0f2042] to-[#020617] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-2xl border-b border-amber-500/20">
        
        <!-- Watermark Backgrounds -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-80 h-80 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
        <div class="absolute -left-12 -top-12 opacity-5 pointer-events-none">
            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-64 h-64 object-contain filter invert" alt="GTU Seal Watermark">
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center max-w-3xl mx-auto space-y-4">
            <!-- Institutional Badges -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-bold uppercase tracking-widest font-heading">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Permanent Institutional Enshrinement Wall
            </div>

            <p class="text-amber-400 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </p>

            <h1 class="font-heading text-3xl sm:text-5xl font-black uppercase tracking-wide text-white leading-tight">
                Virtual Hall of Fame &amp; Wall of Honor
            </h1>

            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed font-sans max-w-2xl mx-auto">
                Enshrining our most accomplished diploma graduates who have engineered breakthrough industries, modernized civic infrastructure, and brought international distinction to Kilachand Devchand Polytechnic Patan.
            </p>

            <div class="pt-2 flex items-center justify-center gap-4 text-xs font-bold uppercase tracking-wider text-amber-400 font-heading">
                <span>🏆 Highest Institutional Honor</span>
                <span>&bull;</span>
                <span>🏛️ Official College Registry</span>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 space-y-8">

        <!-- Top Breadcrumbs Bar -->
        <div class="bg-slate-800/90 rounded-2xl border border-slate-700/80 shadow-md p-3 flex items-center justify-between gap-4 backdrop-blur-md">
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 font-heading uppercase tracking-wider">
                <a href="{{ route('awards.index') }}" class="hover:text-amber-400 transition-colors">Awards Hub</a>
                <span>&rsaquo;</span>
                <span class="text-amber-400 font-bold">Virtual Hall of Fame</span>
            </nav>

            <a href="{{ route('awards.index') }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 uppercase tracking-wider font-heading">
                &larr; Return to Awards Hub
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-slate-800/90 rounded-3xl p-6 border border-slate-700/80 shadow-xl backdrop-blur-md">
            <form action="{{ route('awards.hall-of-fame') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="sm:col-span-2">
                    <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">
                        Search Inductee or Keyword
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, company, or citation..." class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 text-xs sm:text-sm py-2 px-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-sans shadow-xs">
                </div>

                <!-- Year Filter -->
                <div>
                    <label for="year" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">
                        Induction Year
                    </label>
                    <select name="year" id="year" class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm py-2 px-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-heading shadow-xs">
                        <option value="">All Years</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                Class of {{ $yr }} Inductions
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Filter -->
                <div>
                    <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">
                        Department
                    </label>
                    <select name="department" id="department" class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm py-2 px-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-heading shadow-xs">
                        <option value="">All Branches</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit & Clear Buttons -->
                <div class="sm:col-span-4 flex items-center justify-end gap-3 pt-2">
                    @if(request()->anyFilled(['search', 'year', 'department']))
                        <a href="{{ route('awards.hall-of-fame') }}" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-wider font-heading">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs uppercase tracking-wider transition-colors shadow-md font-heading cursor-pointer">
                        Filter Inductees
                    </button>
                </div>
            </form>
        </div>

        <!-- Inductees Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($inductees as $inductee)
                <div class="relative bg-gradient-to-b from-slate-800 to-slate-850 rounded-3xl p-7 border border-amber-500/30 hover:border-amber-400 shadow-xl flex flex-col justify-between group transition-all hover:-translate-y-1">
                    <!-- Laurel Wreath Badge -->
                    <div class="absolute -top-3.5 right-6 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 text-[10px] font-black uppercase tracking-widest shadow-md font-heading">
                        Inducted {{ $inductee->induction_year }}
                    </div>

                    <div class="space-y-5">
                        <!-- Portrait & Names -->
                        <div class="flex items-center gap-4 pt-2">
                            <div class="relative">
                                <img src="{{ $inductee->photo_url }}" alt="{{ $inductee->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-400 shadow-lg">
                                <span class="absolute -bottom-1 -right-1 text-base">🏅</span>
                            </div>
                            <div class="space-y-0.5 min-w-0">
                                <h3 class="text-lg font-black text-white group-hover:text-amber-400 transition-colors font-heading truncate">
                                    {{ $inductee->name }}
                                </h3>
                                <p class="text-xs text-amber-300/90 font-semibold truncate font-sans">
                                    {{ $inductee->department }}
                                </p>
                                <p class="text-[11px] text-slate-400 font-mono">
                                    Graduation Batch: {{ $inductee->batch_year }}
                                </p>
                            </div>
                        </div>

                        <!-- Award Title Pill -->
                        <div class="px-3 py-2 rounded-xl bg-amber-500/10 border border-amber-400/20 text-amber-200 text-xs font-bold font-heading">
                            🏆 {{ $inductee->award_title }}
                        </div>

                        <!-- Official Citation -->
                        <div class="space-y-1">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 font-heading">
                                Official Citation:
                            </div>
                            <p class="text-xs text-slate-300 italic leading-relaxed font-sans bg-slate-900/50 p-3 rounded-2xl border border-slate-750">
                                "{{ $inductee->citation }}"
                            </p>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    @if($inductee->company)
                        <div class="pt-4 mt-6 border-t border-slate-700/60 text-xs text-slate-400 flex items-center justify-between font-sans">
                            <div class="truncate mr-2">
                                <span class="text-slate-500 font-medium">Enterprise:</span>
                                <span class="text-slate-200 font-semibold">{{ $inductee->company }}</span>
                            </div>
                            @if($inductee->current_role)
                                <div class="text-[11px] text-amber-300/80 font-medium shrink-0">
                                    {{ $inductee->current_role }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-slate-800/50 rounded-3xl border-2 border-dashed border-slate-700">
                    <span class="text-5xl inline-block mb-2">🏛️</span>
                    <h3 class="text-lg font-bold text-white font-heading uppercase">No Inductees Found</h3>
                    <p class="text-xs text-slate-400 mt-1 font-sans">Try adjusting your filters or search keywords.</p>
                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-block mt-4 px-5 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider font-heading">
                        Reset Filters
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($inductees->hasPages())
            <div class="pt-6">
                {{ $inductees->links() }}
            </div>
        @endif

        <!-- Back to Hub -->
        <div class="text-center pt-8 border-t border-slate-800">
            <a href="{{ route('awards.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-400 hover:text-amber-300 font-heading">
                &larr; Return to Annual Awards Hub &amp; Live Voting
            </a>
        </div>

    </div>
</div>
@endsection
