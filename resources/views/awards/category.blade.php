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

        <div class="max-w-6xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    {{ $cycle->title }}
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl sm:text-4xl">{{ $category->icon ?: '🏆' }}</span>
                        <h1 class="font-heading text-2xl sm:text-4xl font-black uppercase tracking-wide text-white leading-tight">
                            {{ $category->name }}
                        </h1>
                    </div>
                    <p class="text-xs sm:text-sm text-blue-100 leading-relaxed font-sans">
                        {{ $category->description }}
                    </p>
                    @if($category->eligibility_criteria)
                        <div class="pt-1">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-blue-200 border border-white/15 font-sans">
                                <strong class="text-white font-bold font-heading">Criteria:</strong> {{ $category->eligibility_criteria }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Category Stage & Stats -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/15 text-center min-w-[200px] space-y-2 shrink-0">
                    <div class="text-3xl font-black text-amber-300 font-heading">{{ $nominees->count() }}</div>
                    <div class="text-[11px] font-bold text-blue-200 uppercase tracking-wider font-heading">Approved Finalists</div>
                    <div class="text-xs font-semibold text-emerald-300 font-sans">
                        {{ $totalVotesInCategory }} Total Votes Cast
                    </div>
                    @if($cycle->isVotingOpen())
                        <span class="inline-block px-3 py-0.5 rounded-full text-[10px] font-black bg-[#f97316] text-white uppercase tracking-wider font-heading shadow-xs">
                            Voting Live
                        </span>
                    @else
                        <span class="inline-block px-3 py-0.5 rounded-full text-[10px] font-black bg-slate-700 text-slate-300 uppercase tracking-wider font-heading">
                            {{ ucfirst(str_replace('_', ' ', $cycle->status)) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 space-y-6">

        <!-- Top Breadcrumbs Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-3 flex items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 font-heading uppercase tracking-wider">
                <a href="{{ route('awards.index') }}" class="hover:text-[#1e3a8a] transition-colors">Awards Hub</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-bold">{{ $category->name }}</span>
            </nav>

            <a href="{{ route('awards.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider font-heading">
                &larr; Back to Categories
            </a>
        </div>

        <!-- Flash Messages -->
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

        <!-- User Vote Status Notification -->
        @if($userVote)
            <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-5 shadow-sm flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg font-heading shadow-xs">
                        ✓
                    </div>
                    <div>
                        <div class="text-sm font-bold text-emerald-950 font-heading">You have cast your official vote in this category!</div>
                        <div class="text-xs text-emerald-700 font-sans mt-0.5">
                            Your ballot was counted for <strong>{{ $userVote->nomination->nominee_name ?? 'Finalist' }}</strong>. Thank you for participating.
                        </div>
                    </div>
                </div>
                <span class="hidden sm:inline-block text-[11px] font-bold text-emerald-800 bg-emerald-100 px-3 py-1.5 rounded-full border border-emerald-200 font-heading uppercase tracking-wider">
                    1 Vote / Category Limit Enforced
                </span>
            </div>
        @elseif($cycle->isVotingOpen())
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-5 shadow-sm flex items-center gap-3 text-amber-950 text-xs sm:text-sm font-sans">
                <span class="text-2xl">🗳️</span>
                <div>
                    <strong class="font-heading">Voting is active:</strong> You are entitled to <strong>one vote</strong> in this category. Review the credentials and accomplishments below before casting your ballot.
                </div>
            </div>
        @endif

        <!-- Finalists List -->
        <div class="space-y-6">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h2 class="text-lg sm:text-xl font-black text-[#0f172a] uppercase tracking-wide font-heading">
                    Official Finalist Ballots ({{ $nominees->count() }})
                </h2>
                <a href="{{ route('awards.nominate', ['category_id' => $category->id]) }}" class="text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider font-heading transition-colors">
                    + Nominate Another Candidate
                </a>
            </div>

            @if($nominees->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200 space-y-3 shadow-sm">
                    <span class="text-5xl inline-block mb-1">📋</span>
                    <h3 class="text-base font-bold text-slate-800 font-heading uppercase">No Finalists Published Yet</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto font-sans leading-relaxed">
                        Nominations are currently being reviewed by the KDP Awards Committee. Shortlisted candidates will appear here for voting.
                    </p>
                    <a href="{{ route('awards.nominate', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-wider font-heading shadow-xs transition-colors">
                        Submit a Nomination Now &rarr;
                    </a>
                </div>
            @else
                <div class="space-y-5">
                    @foreach($nominees as $nominee)
                        @php
                            $percentage = $totalVotesInCategory > 0 ? round(($nominee->votes_count / $totalVotesInCategory) * 100) : 0;
                            $isMyVote = $userVote && $userVote->award_nomination_id === $nominee->id;
                            $isSelf = auth()->check() && $nominee->nominee_user_id === auth()->id();
                        @endphp
                        <div class="bg-white rounded-3xl border {{ $isMyVote ? 'border-2 border-emerald-500 shadow-md ring-4 ring-emerald-50' : 'border-slate-200/90 shadow-sm hover:shadow-md' }} p-6 sm:p-8 transition-all space-y-6">
                            
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                                <!-- Nominee Bio & Avatar -->
                                <div class="flex items-start gap-5">
                                    <img src="{{ $nominee->photo_url }}" alt="{{ $nominee->nominee_name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-slate-200 shadow-sm flex-shrink-0">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-lg sm:text-xl font-bold text-[#0f172a] font-heading">
                                                {{ $nominee->nominee_name }}
                                            </h3>
                                            @if($isMyVote)
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 font-heading">
                                                    ✓ Your Vote
                                                </span>
                                            @endif
                                            @if($nominee->is_winner)
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 font-heading">
                                                    🏆 Declared Winner
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-[#1e3a8a] font-bold font-heading">
                                            {{ $nominee->nominee_department }} &bull; Class of {{ $nominee->nominee_batch_year }}
                                        </p>

                                        @if($nominee->designation || $nominee->current_organization)
                                            <p class="text-xs text-slate-600 font-medium font-sans">
                                                🏢 {{ $nominee->designation ? $nominee->designation . ', ' : '' }}{{ $nominee->current_organization }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Voting Action Button -->
                                <div class="flex-shrink-0 sm:text-right">
                                    @if($isMyVote)
                                        <button disabled class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider cursor-default shadow-xs font-heading">
                                            ✓ Voted For
                                        </button>
                                    @elseif(!$cycle->isVotingOpen())
                                        <button disabled class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed font-heading">
                                            Voting Closed
                                        </button>
                                    @elseif($userVote)
                                        <button disabled class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed font-heading">
                                            Ballot Cast
                                        </button>
                                    @elseif($isSelf)
                                        <button disabled title="Cannot vote for yourself" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed font-heading">
                                            Self Nominee
                                        </button>
                                    @else
                                        <form action="{{ route('awards.vote') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="award_category_id" value="{{ $category->id }}">
                                            <input type="hidden" name="award_nomination_id" value="{{ $nominee->id }}">
                                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider shadow-xs transition-all font-heading cursor-pointer">
                                                <span>🗳️</span> Vote for {{ Str::before($nominee->nominee_name, ' ') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Achievements & Impact Text -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-100 text-xs">
                                <div class="space-y-1">
                                    <div class="font-bold text-[#0f172a] uppercase tracking-wider text-[11px] font-heading">
                                        Key Engineering Accomplishments:
                                    </div>
                                    <p class="text-slate-600 leading-relaxed font-sans bg-slate-50/50 p-3 rounded-2xl border border-slate-100">
                                        {{ $nominee->achievement_summary }}
                                    </p>
                                </div>

                                @if($nominee->impact_statement)
                                    <div class="space-y-1">
                                        <div class="font-bold text-[#0f172a] uppercase tracking-wider text-[11px] font-heading">
                                            Ecosystem &amp; Mentorship Impact:
                                        </div>
                                        <p class="text-slate-600 leading-relaxed font-sans bg-slate-50/50 p-3 rounded-2xl border border-slate-100">
                                            {{ $nominee->impact_statement }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Live Tally & External Evidence -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-slate-100">
                                <!-- Progress Bar -->
                                <div class="flex-grow max-w-md space-y-1">
                                    <div class="flex items-center justify-between text-xs font-semibold text-slate-600 font-sans">
                                        <span>Community Support: {{ $nominee->votes_count }} {{ Str::plural('vote', $nominee->votes_count) }}</span>
                                        <span class="text-[#1e3a8a] font-bold font-mono">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-[#1e3a8a] h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                @if($nominee->portfolio_url)
                                    <a href="{{ $nominee->portfolio_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider font-heading transition-colors">
                                        🔗 Portfolio / Credential Proof &rarr;
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
