@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Flash Notifications -->
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

        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 text-white shadow-2xl p-8 sm:p-12 border border-amber-500/30">
            <!-- Decorative Golden Orbs -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-semibold tracking-wide uppercase">
                        <span>🏆</span> KDP Annual Alumni Honors &amp; Recognition
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-white">
                        {{ $cycle ? $cycle->title : 'KDP Annual Alumni Awards' }}
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                        {{ $cycle && $cycle->theme ? $cycle->theme : 'Honoring four decades of engineering resilience, industrial leadership, and technological innovation from K.D. Polytechnic Patan.' }}
                    </p>

                    <!-- Cycle Stage Pill -->
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        @if($cycle)
                            @if($cycle->isNominationsOpen())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Stage 1: Nominations Open
                                </span>
                            @elseif($cycle->isVotingOpen())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                    Stage 2: Community Voting Active
                                </span>
                            @elseif($cycle->isConcluded())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-200 border border-indigo-400/30">
                                    ✓ Concluded &amp; Hall of Fame Inducted
                                </span>
                            @endif

                            @if($cycle->ceremony_date)
                                <span class="text-xs text-slate-300 flex items-center gap-1 bg-white/10 px-3 py-1 rounded-full">
                                    📅 Ceremony Date: {{ \Carbon\Carbon::parse($cycle->ceremony_date)->format('M d, Y') }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[240px]">
                    <a href="{{ route('awards.nominate') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-amber-500/30 transition-all">
                        <span>✨</span> Nominate an Alumnus
                    </a>
                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm uppercase tracking-wider backdrop-blur transition-all">
                        <span>🏛️</span> Virtual Hall of Fame
                    </a>
                </div>
            </div>

            <!-- Stage Tracker Progress Bar -->
            <div class="mt-10 pt-8 border-t border-white/10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="p-3 rounded-xl {{ $cycle && $cycle->status === 'nominations_open' ? 'bg-amber-500/20 border border-amber-400/50' : 'bg-white/5' }}">
                        <div class="text-xs font-semibold text-slate-400 uppercase">Step 1</div>
                        <div class="text-sm font-bold text-white mt-1">Open Nominations</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Community Submissions</div>
                    </div>
                    <div class="p-3 rounded-xl {{ $cycle && $cycle->status === 'draft' ? 'bg-amber-500/20 border border-amber-400/50' : 'bg-white/5' }}">
                        <div class="text-xs font-semibold text-slate-400 uppercase">Step 2</div>
                        <div class="text-sm font-bold text-white mt-1">Jury Shortlisting</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Faculty &amp; Admin Review</div>
                    </div>
                    <div class="p-3 rounded-xl {{ $cycle && $cycle->status === 'voting_open' ? 'bg-amber-500/20 border border-amber-400/50' : 'bg-white/5' }}">
                        <div class="text-xs font-semibold text-slate-400 uppercase">Step 3</div>
                        <div class="text-sm font-bold text-white mt-1">Live Voting</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">1 Vote Per Category</div>
                    </div>
                    <div class="p-3 rounded-xl {{ $cycle && $cycle->status === 'concluded' ? 'bg-amber-500/20 border border-amber-400/50' : 'bg-white/5' }}">
                        <div class="text-xs font-semibold text-slate-400 uppercase">Step 4</div>
                        <div class="text-sm font-bold text-white mt-1">Convocation Induction</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Hall of Fame Permanence</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Counter Strip -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-indigo-900">{{ $stats['active_categories'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Award Categories</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-amber-600">{{ $stats['total_nominations'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Alumni Nominated</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-emerald-600">{{ $stats['total_votes'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Community Votes Cast</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-purple-700">{{ $stats['hall_of_fame_count'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Hall of Fame Inductees</div>
            </div>
        </div>

        <!-- Award Categories & Voting Section -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Award Categories ({{ $categories->count() }})
                    </h2>
                    <p class="text-sm text-slate-600 mt-0.5">
                        Explore finalists across engineering disciplines and cast your official vote.
                    </p>
                </div>

                @if($allCycles->count() > 1)
                    <form method="GET" action="{{ route('awards.index') }}" class="flex items-center gap-2">
                        <label for="year" class="text-xs font-bold text-slate-500 uppercase">Award Cycle:</label>
                        <select name="year" id="year" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm font-semibold text-slate-800 focus:border-indigo-600 focus:ring-indigo-600">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    @php
                        $userVotedNominationId = $userVotes[$category->id] ?? null;
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                        <div class="p-6 space-y-4">
                            <!-- Category Header -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                    {{ $category->icon ?: '🏆' }}
                                </div>

                                @if($userVotedNominationId)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✓ Voted
                                    </span>
                                @elseif($cycle && $cycle->isVotingOpen())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        🗳️ Vote Open
                                    </span>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-900 transition-colors">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-3 leading-relaxed">
                                    {{ $category->description }}
                                </p>
                            </div>

                            @if($category->eligibility_criteria)
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-xs text-slate-600">
                                    <strong class="text-slate-800 font-semibold">Eligibility:</strong>
                                    {{ $category->eligibility_criteria }}
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                            <div class="text-xs text-slate-500 font-medium">
                                <span class="font-bold text-slate-800">{{ $category->approved_nominations_count }}</span> Finalists
                                <span class="mx-1">•</span>
                                <span class="font-bold text-slate-800">{{ $category->votes_count }}</span> Votes
                            </div>

                            <a href="{{ route('awards.category', $category->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-700 hover:text-indigo-900 uppercase tracking-wider group-hover:translate-x-0.5 transition-transform">
                                {{ $cycle && $cycle->isVotingOpen() ? 'View & Vote' : 'View Finalists' }} &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                        <span class="text-4xl">🏛️</span>
                        <h4 class="text-lg font-bold text-slate-800 mt-2">No Categories Configured</h4>
                        <p class="text-sm text-slate-500 mt-1">Categories for this cycle have not been published yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Featured Hall of Fame Spotlight -->
        @if($featuredInductees->count() > 0)
            <div class="rounded-3xl bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 border border-amber-200/80 p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-800 uppercase tracking-widest">
                            <span>👑</span> Legacy of Distinction
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                            Virtual Hall of Fame Spotlight
                        </h2>
                        <p class="text-sm text-slate-600">
                            Distinguished alumni permanently enshrined for remarkable lifelong contributions.
                        </p>
                    </div>

                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-colors">
                        View All Inductees &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($featuredInductees as $inductee)
                        <div class="bg-white/90 backdrop-blur rounded-2xl p-5 border border-amber-200 shadow-sm flex flex-col justify-between hover:-translate-y-1 transition-all">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $inductee->photo_url }}" alt="{{ $inductee->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-400 shadow-sm">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $inductee->name }}</h4>
                                        <p class="text-[11px] text-amber-700 font-semibold">{{ $inductee->department }} (Class of {{ $inductee->batch_year }})</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                            Inducted {{ $inductee->induction_year }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-xs font-bold text-slate-800">
                                    {{ $inductee->award_title }}
                                </div>

                                <p class="text-xs text-slate-600 italic line-clamp-3 leading-relaxed">
                                    "{{ $inductee->citation }}"
                                </p>
                            </div>

                            @if($inductee->company)
                                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 font-medium">
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
