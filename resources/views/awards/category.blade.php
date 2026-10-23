@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center gap-3 text-emerald-800">
                <span class="text-2xl">🎉</span>
                <div class="font-medium text-sm sm:text-base">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-center gap-3 text-rose-800">
                <span class="text-2xl">⚠️</span>
                <div class="font-medium text-sm sm:text-base">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('awards.index') }}" class="hover:text-indigo-600 transition-colors">Awards Hub</a>
            <span>&rarr;</span>
            <span class="text-slate-800">{{ $category->name }}</span>
        </div>

        <!-- Category Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-8 sm:p-10 text-white shadow-xl border border-amber-500/30 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">{{ $category->icon ?: '🏆' }}</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-300">
                        {{ $cycle->title }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-white">
                    {{ $category->name }}
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    {{ $category->description }}
                </p>
                @if($category->eligibility_criteria)
                    <div class="pt-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-slate-200 border border-white/15">
                            <strong>Criteria:</strong> {{ $category->eligibility_criteria }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Category Stage & Stats -->
            <div class="bg-white/10 backdrop-blur rounded-2xl p-5 border border-white/15 text-center min-w-[200px] space-y-2">
                <div class="text-3xl font-black text-amber-400">{{ $nominees->count() }}</div>
                <div class="text-xs font-bold text-slate-300 uppercase tracking-wider">Approved Finalists</div>
                <div class="text-xs font-semibold text-emerald-300">
                    {{ $totalVotesInCategory }} Total Votes Cast
                </div>
                @if($cycle->isVotingOpen())
                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-500 text-slate-950 uppercase tracking-wide">
                        Voting Live
                    </span>
                @else
                    <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-700 text-slate-300 uppercase tracking-wide">
                        {{ ucfirst(str_replace('_', ' ', $cycle->status)) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- User Vote Status Notification -->
        @if($userVote)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-lg">
                        ✓
                    </div>
                    <div>
                        <div class="text-sm font-bold text-emerald-950">You have cast your official vote in this category!</div>
                        <div class="text-xs text-emerald-700">
                            Your vote was counted for <strong>{{ $userVote->nomination->nominee_name ?? 'Finalist' }}</strong>. Thank you for participating.
                        </div>
                    </div>
                </div>
                <span class="hidden sm:inline-block text-xs font-semibold text-emerald-800 bg-emerald-100 px-3 py-1.5 rounded-full">
                    1 Vote / Category Limit Enforced
                </span>
            </div>
        @elseif($cycle->isVotingOpen())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 shadow-sm flex items-center gap-3 text-amber-900 text-xs sm:text-sm">
                <span class="text-xl">🗳️</span>
                <div>
                    <strong>Voting is active:</strong> You are entitled to <strong>one vote</strong> in this category. Review the credentials and achievements below before casting your ballot.
                </div>
            </div>
        @endif

        <!-- Finalists List -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">
                    Official Finalist Ballots ({{ $nominees->count() }})
                </h2>
                <a href="{{ route('awards.nominate', ['category_id' => $category->id]) }}" class="text-xs font-bold text-indigo-700 hover:text-indigo-900 uppercase tracking-wider">
                    + Nominate Another Candidate
                </a>
            </div>

            @if($nominees->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-300 space-y-3">
                    <span class="text-5xl">📋</span>
                    <h3 class="text-lg font-bold text-slate-800">No Finalists Published Yet</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Nominations are currently being reviewed by the KDP Awards Committee. Shortlisted candidates will appear here for voting.
                    </p>
                    <a href="{{ route('awards.nominate', ['category_id' => $category->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider">
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
                        <div class="bg-white rounded-3xl border {{ $isMyVote ? 'border-2 border-emerald-500 shadow-md ring-4 ring-emerald-50' : 'border-slate-200/90 shadow-sm' }} p-6 sm:p-8 transition-all space-y-6">
                            
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                                <!-- Nominee Bio & Avatar -->
                                <div class="flex items-start gap-5">
                                    <img src="{{ $nominee->photo_url }}" alt="{{ $nominee->nominee_name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-indigo-100 shadow-sm flex-shrink-0">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                                                {{ $nominee->nominee_name }}
                                            </h3>
                                            @if($isMyVote)
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ✓ Your Vote
                                                </span>
                                            @endif
                                            @if($nominee->is_winner)
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                                    🏆 Declared Winner
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-indigo-800 font-semibold">
                                            {{ $nominee->nominee_department }} • Class of {{ $nominee->nominee_batch_year }}
                                        </p>

                                        @if($nominee->designation || $nominee->current_organization)
                                            <p class="text-xs text-slate-600 font-medium">
                                                🏢 {{ $nominee->designation ? $nominee->designation . ', ' : '' }}{{ $nominee->current_organization }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Voting Action Button -->
                                <div class="flex-shrink-0 sm:text-right">
                                    @if($isMyVote)
                                        <button disabled class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider cursor-default shadow-sm">
                                            ✓ Voted For
                                        </button>
                                    @elseif(!$cycle->isVotingOpen())
                                        <button disabled class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            Voting Closed
                                        </button>
                                    @elseif($userVote)
                                        <button disabled class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            Ballot Cast
                                        </button>
                                    @elseif($isSelf)
                                        <button disabled title="Cannot vote for yourself" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            Self Nominee
                                        </button>
                                    @else
                                        <form action="{{ route('awards.vote') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="award_category_id" value="{{ $category->id }}">
                                            <input type="hidden" name="award_nomination_id" value="{{ $nominee->id }}">
                                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-indigo-600/30 transition-all">
                                                <span>🗳️</span> Vote for {{ Str::before($nominee->nominee_name, ' ') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Achievements & Impact Text -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-100 text-xs">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">
                                        Key Engineering Accomplishments:
                                    </div>
                                    <p class="text-slate-600 leading-relaxed">
                                        {{ $nominee->achievement_summary }}
                                    </p>
                                </div>

                                @if($nominee->impact_statement)
                                    <div class="space-y-1">
                                        <div class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">
                                            Ecosystem &amp; Mentorship Impact:
                                        </div>
                                        <p class="text-slate-600 leading-relaxed">
                                            {{ $nominee->impact_statement }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Live Tally & External Evidence -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-slate-100">
                                <!-- Progress Bar -->
                                <div class="flex-grow max-w-md space-y-1">
                                    <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                                        <span>Community Support: {{ $nominee->votes_count }} {{ Str::plural('vote', $nominee->votes_count) }}</span>
                                        <span class="text-indigo-800 font-bold">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                @if($nominee->portfolio_url)
                                    <a href="{{ $nominee->portfolio_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider">
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
