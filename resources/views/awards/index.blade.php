@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Watermark Backgrounds -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-80 h-80 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
        <div class="absolute -left-12 -top-12 opacity-5 pointer-events-none">
            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-64 h-64 object-contain filter invert" alt="GTU Seal Watermark">
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Annual Honors &bull; Hall of Fame Registry
                </span>
                @if($cycle && $cycle->isVotingOpen())
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                        <span>🗳️</span> Community Voting Active
                    </span>
                @elseif($cycle && $cycle->isNominationsOpen())
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                        <span>✨</span> Nominations Open
                    </span>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-3 max-w-3xl">
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-wide text-white leading-tight">
                        {{ $cycle ? $cycle->title : 'KDP Annual Alumni Awards' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 leading-relaxed font-sans max-w-2xl">
                        {{ $cycle && $cycle->theme ? $cycle->theme : 'Honoring four decades of engineering resilience, industrial leadership, and technological innovation from Kilachand Devchand Polytechnic Patan.' }}
                    </p>

                    <!-- Cycle Stage Pill -->
                    <div class="pt-1 flex flex-wrap items-center gap-3">
                        @if($cycle)
                            @if($cycle->isNominationsOpen())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-heading">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Stage 1: Open Nominations Active
                                </span>
                            @elseif($cycle->isJuryReview())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-sky-500/20 text-sky-300 border border-sky-500/40 font-heading">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                                    Stage 2: Jury Review &amp; Shortlisting
                                </span>
                            @elseif($cycle->isVotingOpen())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40 font-heading">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                    Stage 3: Community Voting Active
                                </span>
                            @elseif($cycle->isConcluded())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-200 border border-indigo-400/30 font-heading">
                                    ✓ Stage 4: Concluded &amp; Hall of Fame Enshrined
                                </span>
                            @endif

                            @if($cycle->ceremony_date)
                                <span class="text-xs text-blue-200 flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full border border-white/15 font-mono">
                                    📅 Ceremony: {{ \Carbon\Carbon::parse($cycle->ceremony_date)->format('M d, Y') }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[240px] shrink-0">
                    <a href="{{ route('awards.nominate') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#f97316] hover:bg-orange-600 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all font-heading">
                        <span>✨</span>
                        <span>Nominate an Alumnus</span>
                    </a>
                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs uppercase tracking-wider backdrop-blur transition-all font-heading">
                        <span>🏛️</span>
                        <span>Virtual Hall of Fame</span>
                    </a>
                </div>
            </div>

            <!-- Stage Tracker Progress Bar -->
            <div class="mt-10 pt-6 border-t border-white/15">
                @if(Auth::check() && Auth::user()->isAdmin() && $cycle)
                    <div class="mb-3 px-3 py-2 bg-amber-400/10 border border-amber-400/30 rounded-xl flex flex-wrap items-center justify-between gap-2 text-xs text-amber-300 font-bold">
                        <span class="flex items-center gap-2">
                            <span>🛡️</span>
                            <span>ADMIN CONTROLS: Click any stage below to dynamically advance or switch the active awards step in real-time</span>
                        </span>
                        <a href="{{ route('admin.awards.index', ['tab' => 'cycles']) }}" class="text-[11px] underline text-blue-200 hover:text-white transition-colors">
                            Manage All Cycles &rarr;
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                    <!-- Step 1: Open Nominations -->
                    <div class="p-3.5 rounded-2xl transition-all relative flex flex-col justify-between {{ $cycle && $cycle->status === 'nominations_open' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle && $cycle->status === 'nominations_open' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 1</span>
                                @if($cycle && $cycle->status === 'nominations_open')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-white mt-0.5 font-heading">Open Nominations</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Community Submissions</div>
                        </div>

                        @if(Auth::check() && Auth::user()->isAdmin() && $cycle)
                            <div class="mt-2.5 pt-2 border-t border-white/10">
                                @if($cycle->status === 'nominations_open')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                        ● Active Stage
                                    </span>
                                @else
                                    <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="nominations_open">
                                        <button type="submit" class="w-full py-1 px-2 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                            Activate Step 1
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Step 2: Jury Shortlisting -->
                    <div class="p-3.5 rounded-2xl transition-all relative flex flex-col justify-between {{ $cycle && $cycle->isJuryReview() ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle && $cycle->isJuryReview() ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 2</span>
                                @if($cycle && $cycle->isJuryReview())
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-white mt-0.5 font-heading">Jury Shortlisting</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Faculty &amp; Admin Review</div>
                        </div>

                        @if(Auth::check() && Auth::user()->isAdmin() && $cycle)
                            <div class="mt-2.5 pt-2 border-t border-white/10">
                                @if($cycle->isJuryReview())
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                        ● Active Stage
                                    </span>
                                @else
                                    <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="draft">
                                        <button type="submit" class="w-full py-1 px-2 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                            Activate Step 2
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Step 3: Live Voting -->
                    <div class="p-3.5 rounded-2xl transition-all relative flex flex-col justify-between {{ $cycle && $cycle->status === 'voting_open' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle && $cycle->status === 'voting_open' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 3</span>
                                @if($cycle && $cycle->status === 'voting_open')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-white mt-0.5 font-heading">Live Voting</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">1 Vote Per Category</div>
                        </div>

                        @if(Auth::check() && Auth::user()->isAdmin() && $cycle)
                            <div class="mt-2.5 pt-2 border-t border-white/10">
                                @if($cycle->status === 'voting_open')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                        ● Active Stage
                                    </span>
                                @else
                                    <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="voting_open">
                                        <button type="submit" class="w-full py-1 px-2 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                            Activate Step 3
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Step 4: Convocation Enshrinement -->
                    <div class="p-3.5 rounded-2xl transition-all relative flex flex-col justify-between {{ $cycle && $cycle->status === 'concluded' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle && $cycle->status === 'concluded' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 4</span>
                                @if($cycle && $cycle->status === 'concluded')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-white mt-0.5 font-heading">Convocation Enshrinement</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Hall of Fame Permanence</div>
                        </div>

                        @if(Auth::check() && Auth::user()->isAdmin() && $cycle)
                            <div class="mt-2.5 pt-2 border-t border-white/10">
                                @if($cycle->status === 'concluded')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                        ● Active Stage
                                    </span>
                                @else
                                    <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="concluded">
                                        <button type="submit" class="w-full py-1 px-2 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                            Activate Step 4
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 space-y-8">

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">🎉</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-base">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-base">&times;</button>
            </div>
        @endif

        <!-- Metric Counter Strip -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm text-center">
                <div class="text-2xl sm:text-3xl font-black text-[#1e3a8a] font-heading">{{ $stats['active_categories'] }}</div>
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1 font-heading">Award Categories</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm text-center">
                <div class="text-2xl sm:text-3xl font-black text-[#f97316] font-heading">{{ $stats['total_nominations'] }}</div>
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1 font-heading">Alumni Nominated</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm text-center">
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-heading">{{ $stats['total_votes'] }}</div>
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1 font-heading">Community Votes Cast</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm text-center">
                <div class="text-2xl sm:text-3xl font-black text-purple-700 font-heading">{{ $stats['hall_of_fame_count'] }}</div>
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1 font-heading">Hall of Fame Inductees</div>
            </div>
        </div>

        <!-- Award Categories & Voting Section -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0f172a] uppercase tracking-wide font-heading">
                        Award Categories ({{ $categories->count() }})
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-sans">
                        Explore finalists across engineering disciplines and cast your official community ballot.
                    </p>
                </div>

                @if($allCycles->count() > 1)
                    <form method="GET" action="{{ route('awards.index') }}" class="flex items-center gap-2">
                        <label for="year" class="text-xs font-bold text-slate-600 uppercase font-heading">Award Edition:</label>
                        <select name="year" id="year" onchange="this.form.submit()" class="rounded-xl border border-slate-300 text-xs font-bold text-slate-800 py-1.5 px-3 bg-white focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            @foreach($allCycles as $c)
                                <option value="{{ $c->year }}" {{ $cycle && $cycle->year == $c->year ? 'selected' : '' }}>
                                    {{ $c->year }} Edition ({{ ucfirst(str_replace('_', ' ', $c->status)) }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($categories as $category)
                    @php
                        $userVotedNominationId = $userVotes[$category->id] ?? null;
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        <div class="p-6 space-y-4">
                            <!-- Category Header -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-xs">
                                    {{ $category->icon ?: '🏆' }}
                                </div>

                                @if($userVotedNominationId)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 font-heading">
                                        ✓ Voted
                                    </span>
                                @elseif($cycle && $cycle->isVotingOpen())
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-200 font-heading">
                                        🗳️ Vote Open
                                    </span>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-base sm:text-lg font-black text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors font-heading leading-snug">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-3 leading-relaxed font-sans">
                                    {{ $category->description }}
                                </p>
                            </div>

                            @if($category->eligibility_criteria)
                                <div class="bg-slate-50/70 rounded-2xl p-3 border border-slate-100 text-xs text-slate-600 font-sans">
                                    <strong class="text-slate-800 font-bold font-heading">Eligibility:</strong>
                                    {{ $category->eligibility_criteria }}
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                            <div class="text-xs text-slate-500 font-medium">
                                <span class="font-bold text-slate-800 font-heading">{{ $category->approved_nominations_count }}</span> Finalists
                                <span class="mx-1">&bull;</span>
                                <span class="font-bold text-slate-800 font-heading">{{ $category->votes_count }}</span> Votes
                            </div>

                            <a href="{{ route('awards.category', $category->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider font-heading transition-colors">
                                <span>{{ $cycle && $cycle->isVotingOpen() ? 'View & Vote' : 'View Finalists' }}</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200 p-6">
                        <span class="text-4xl mb-2 inline-block">🏛️</span>
                        <h4 class="text-base font-bold text-slate-800 font-heading uppercase">No Categories Configured</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 font-sans">Categories for this award cycle have not been published yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Featured Hall of Fame Spotlight -->
        @if($featuredInductees->count() > 0)
            <div class="rounded-3xl bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 border border-amber-200/80 p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-amber-200/60">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-900 uppercase tracking-widest font-heading">
                            <span>👑</span> Legacy of Distinction
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-heading uppercase">
                            Virtual Hall of Fame Spotlight
                        </h2>
                        <p class="text-xs text-slate-600 font-sans">
                            Distinguished alumni permanently enshrined for remarkable lifelong contributions to industry and governance.
                        </p>
                    </div>

                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider shadow-xs transition-colors font-heading shrink-0">
                        <span>View All Inductees &rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($featuredInductees as $inductee)
                        <div class="bg-white/95 backdrop-blur rounded-2xl p-5 border border-amber-200 shadow-xs flex flex-col justify-between hover:-translate-y-1 transition-all">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $inductee->photo_url }}" alt="{{ $inductee->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 text-sm leading-tight font-heading truncate">{{ $inductee->name }}</h4>
                                        <p class="text-[11px] text-amber-800 font-semibold truncate">{{ $inductee->department }} (Class of {{ $inductee->batch_year }})</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200 font-heading">
                                            Inducted {{ $inductee->induction_year }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-xs font-bold text-[#0f172a] font-heading">
                                    🏆 {{ $inductee->award_title }}
                                </div>

                                <p class="text-xs text-slate-600 italic line-clamp-3 leading-relaxed font-sans bg-amber-50/50 p-2.5 rounded-xl border border-amber-100">
                                    "{{ $inductee->citation }}"
                                </p>
                            </div>

                            @if($inductee->company)
                                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium truncate font-sans">
                                    🏢 {{ $inductee->current_role ? $inductee->current_role . ', ' : '' }}{{ $inductee->company }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
