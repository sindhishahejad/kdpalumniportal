@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ tab: '{{ $activeTab }}' }">

    <!-- Header & Dashboard Link -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Administration Portal</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#1C3661] mt-0.5">
                Alumni Awards &amp; Hall of Fame Control Center
            </h2>
            <p class="text-xs text-gray-500 mt-1">Configure award editions, manage categories, review ballots, and enshrine college legends.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('awards.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider shadow-sm transition-colors">
                Public Awards Hub &rarr;
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider shadow-sm transition-colors">
                Admin Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Dynamic Metrics Counter Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-indigo-900">{{ $stats['total_cycles'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Award Cycles</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-amber-600">{{ $stats['total_nominations'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Total Nominees</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-rose-600">{{ $stats['pending_nominations'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Pending Review</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-emerald-600">{{ $stats['approved_nominations'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Finalists</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-blue-700">{{ $stats['total_votes'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Ballots Cast</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm text-center">
            <div class="text-2xl font-black text-purple-700">{{ $stats['hall_of_fame_count'] }}</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Hall of Fame</div>
        </div>
    </div>

    <!-- Dedicated Active Cycle Live Stepper & Stage Controller -->
    @if($cycle)
        <div class="bg-gradient-to-r from-[#1C3661] via-[#1e3a8a] to-[#0f172a] rounded-2xl p-6 text-white shadow-xl border border-white/10 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#f97316] text-white font-heading">
                            Active Cycle Controller
                        </span>
                        <h2 class="text-base sm:text-lg font-black tracking-wide font-heading">
                            {{ $cycle->year }} Edition: {{ $cycle->title }}
                        </h2>
                    </div>
                    <p class="text-xs text-blue-200 mt-1">
                        Current Live Stage: <strong class="text-amber-300 font-bold uppercase">{{ $cycle->stageLabel() }}</strong>
                        @if($cycle->ceremony_date)
                            • 📅 Ceremony: {{ \Carbon\Carbon::parse($cycle->ceremony_date)->format('M d, Y') }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('awards.index') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors flex items-center gap-1.5 border border-white/15 font-heading">
                        <span>👁️ View Live Awards Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <!-- 4-Step Interactive Stepper Controller -->
            <div>
                <p class="text-[11px] font-bold text-blue-200 uppercase tracking-wider mb-2 font-heading">
                    ⚡ 1-Click Stage Controller — Click any step to advance or switch the live stage:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-center">
                    
                    <!-- STEP 1 -->
                    <div class="p-4 rounded-xl border flex flex-col justify-between transition-all {{ $cycle->status === 'nominations_open' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle->status === 'nominations_open' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 1</span>
                                @if($cycle->status === 'nominations_open')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-white mt-0.5 font-heading">Open Nominations</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Community Submissions</div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-white/10">
                            @if($cycle->status === 'nominations_open')
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                    ● Current Live Stage
                                </span>
                            @else
                                <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="nominations_open">
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                        ⚡ Activate Step 1
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="p-4 rounded-xl border flex flex-col justify-between transition-all {{ $cycle->isJuryReview() ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle->isJuryReview() ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 2</span>
                                @if($cycle->isJuryReview())
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-white mt-0.5 font-heading">Jury Shortlisting</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Faculty &amp; Admin Review</div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-white/10">
                            @if($cycle->isJuryReview())
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                    ● Current Live Stage
                                </span>
                            @else
                                <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="draft">
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                        ⚡ Activate Step 2
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="p-4 rounded-xl border flex flex-col justify-between transition-all {{ $cycle->status === 'voting_open' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle->status === 'voting_open' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 3</span>
                                @if($cycle->status === 'voting_open')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-white mt-0.5 font-heading">Live Voting</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">1 Vote Per Category</div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-white/10">
                            @if($cycle->status === 'voting_open')
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                    ● Current Live Stage
                                </span>
                            @else
                                <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="voting_open">
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                        ⚡ Activate Step 3
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- STEP 4 -->
                    <div class="p-4 rounded-xl border flex flex-col justify-between transition-all {{ $cycle->status === 'concluded' ? 'bg-amber-500/25 border-2 border-amber-400 shadow-lg ring-2 ring-amber-400/30' : 'bg-white/5 border-white/10 hover:bg-white/10' }}">
                        <div>
                            <div class="flex items-center justify-center gap-1.5">
                                <span class="text-[10px] font-black {{ $cycle->status === 'concluded' ? 'text-amber-300' : 'text-orange-300' }} uppercase font-heading">Step 4</span>
                                @if($cycle->status === 'concluded')
                                    <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-white mt-0.5 font-heading">Convocation Enshrinement</div>
                            <div class="text-[10px] text-blue-200/80 mt-0.5">Hall of Fame Permanence</div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-white/10">
                            @if($cycle->status === 'concluded')
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider font-heading">
                                    ● Current Live Stage
                                </span>
                            @else
                                <form action="{{ route('admin.awards.cycles.update-status', $cycle->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="concluded">
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-white/15 hover:bg-amber-500 text-white hover:text-slate-950 text-[10px] font-black uppercase tracking-wider transition-all font-heading cursor-pointer shadow-xs">
                                        ⚡ Activate Step 4
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Tab Navigation Bar -->
    <div class="border-b border-gray-200 flex flex-wrap gap-2 sm:gap-4 text-xs font-bold uppercase tracking-wider">
        <button @click="tab = 'nominations'" :class="tab === 'nominations' ? 'border-[#1C3661] text-[#1C3661] bg-white border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-3 px-4 rounded-t-xl transition-all">
            🗳️ Nominations &amp; Ballots ({{ $nominations->total() }})
        </button>
        <button @click="tab = 'cycles'" :class="tab === 'cycles' ? 'border-[#1C3661] text-[#1C3661] bg-white border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-3 px-4 rounded-t-xl transition-all">
            📅 Award Cycles ({{ $allCycles->count() }})
        </button>
        <button @click="tab = 'categories'" :class="tab === 'categories' ? 'border-[#1C3661] text-[#1C3661] bg-white border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-3 px-4 rounded-t-xl transition-all">
            🏷️ Categories Manager ({{ $categories->count() }})
        </button>
        <button @click="tab = 'hall-of-fame'" :class="tab === 'hall-of-fame' ? 'border-[#1C3661] text-[#1C3661] bg-white border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-3 px-4 rounded-t-xl transition-all">
            👑 Hall of Fame Registry ({{ $inductees->total() }})
        </button>
    </div>

    <!-- ============================================== -->
    <!-- TAB 1: NOMINATIONS & BALLOTS                   -->
    <!-- ============================================== -->
    <div x-show="tab === 'nominations'" x-cloak class="space-y-6">
        
        <!-- Filter Bar -->
        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
            <form action="{{ route('admin.awards.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <input type="hidden" name="tab" value="nominations">
                
                <div>
                    <label for="filter_cycle" class="block text-xs font-bold text-gray-600 uppercase mb-1">Award Cycle</label>
                    <select name="cycle_id" id="filter_cycle" onchange="this.form.submit()" class="w-full rounded-xl border-gray-300 text-xs font-semibold">
                        @foreach($allCycles as $c)
                            <option value="{{ $c->id }}" {{ $cycle && $cycle->id == $c->id ? 'selected' : '' }}>
                                {{ $c->year }} - {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_category" class="block text-xs font-bold text-gray-600 uppercase mb-1">Category</label>
                    <select name="category_id" id="filter_category" onchange="this.form.submit()" class="w-full rounded-xl border-gray-300 text-xs">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_status" class="block text-xs font-bold text-gray-600 uppercase mb-1">Review Status</label>
                    <select name="status" id="filter_status" onchange="this.form.submit()" class="w-full rounded-xl border-gray-300 text-xs">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Finalists)</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="flex items-end">
                    @if(request()->anyFilled(['category_id', 'status']))
                        <a href="{{ route('admin.awards.index', ['tab' => 'nominations']) }}" class="text-xs font-bold text-gray-600 hover:text-black uppercase tracking-wider pb-2.5">
                            Reset Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Nominations Table -->
        <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden border border-gray-200">
            @if($nominations->isEmpty())
                <div class="p-12 text-center text-gray-500 italic">
                    No nominations match the specified filters for this cycle.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm divide-y divide-gray-200">
                        <thead class="bg-[#1C3661] text-white">
                            <tr>
                                <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Nominee Details</th>
                                <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Category &amp; Nominator</th>
                                <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Achievements &amp; Impact</th>
                                <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Votes</th>
                                <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Review &amp; Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($nominations as $nom)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-gray-900 text-base">{{ $nom->nominee_name }}</div>
                                        <div class="text-xs text-blue-800 font-semibold mt-0.5">
                                            {{ $nom->nominee_department }} (Class of {{ $nom->nominee_batch_year }})
                                        </div>
                                        @if($nom->current_organization)
                                            <div class="text-xs text-gray-600 mt-1">
                                                🏢 {{ $nom->designation ? $nom->designation . ' - ' : '' }}{{ $nom->current_organization }}
                                            </div>
                                        @endif
                                        @if($nom->portfolio_url)
                                            <a href="{{ $nom->portfolio_url }}" target="_blank" class="inline-block mt-1 text-[11px] text-indigo-600 font-bold hover:underline">
                                                🔗 Verification Proof
                                            </a>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-bold text-gray-800 text-xs">{{ $nom->category->name }}</div>
                                        <div class="text-[11px] text-gray-500 mt-1">
                                            Nominated by: <strong>{{ $nom->nominator->name }}</strong>
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            {{ $nom->created_at->format('d M, Y') }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 max-w-xs">
                                        <div class="text-xs text-gray-700 line-clamp-3" title="{{ $nom->achievement_summary }}">
                                            {{ $nom->achievement_summary }}
                                        </div>
                                        @if($nom->impact_statement)
                                            <div class="text-[11px] text-slate-500 italic mt-1 line-clamp-2" title="{{ $nom->impact_statement }}">
                                                "{{ $nom->impact_statement }}"
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $nom->votes_count > 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
                                            🗳️ {{ $nom->votes_count }}
                                        </span>
                                        @if($nom->is_winner)
                                            <span class="block mt-1 text-[10px] font-bold uppercase tracking-wider text-amber-600">
                                                👑 Winner
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 space-y-2">
                                        <form action="{{ route('admin.awards.nominations.update-status', $nom->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="rounded-lg text-xs font-semibold {{ $nom->status === 'approved' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : ($nom->status === 'rejected' ? 'bg-rose-50 text-rose-800 border-rose-300' : 'bg-amber-50 text-amber-800 border-amber-300') }}">
                                                <option value="pending" {{ $nom->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $nom->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $nom->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-black text-white text-[11px] font-bold transition-colors">
                                                Save
                                            </button>
                                        </form>

                                        @if($nom->status === 'approved' && !$nom->is_winner)
                                            <details class="text-xs">
                                                <summary class="cursor-pointer font-bold text-amber-700 hover:text-amber-900 pt-1">
                                                    + Induct into Hall of Fame
                                                </summary>
                                                <form action="{{ route('admin.awards.nominations.induct', $nom->id) }}" method="POST" class="mt-2 p-3 bg-amber-50/70 rounded-xl border border-amber-200 space-y-2">
                                                    @csrf
                                                    <label class="block text-[10px] font-bold text-amber-950 uppercase">Official Citation</label>
                                                    <textarea name="citation" rows="2" required placeholder="Official citation for the convocation enshrinement..." class="w-full rounded text-xs border-amber-300">{{ $nom->achievement_summary }}</textarea>
                                                    <div class="flex items-center justify-between">
                                                        <label class="flex items-center gap-1.5 text-[11px] text-amber-950">
                                                            <input type="checkbox" name="is_featured" value="1" class="rounded text-amber-600"> Featured
                                                        </label>
                                                        <button type="submit" class="px-3 py-1 rounded bg-amber-600 hover:bg-amber-700 text-white font-bold text-[11px]">
                                                            Induct
                                                        </button>
                                                    </div>
                                                </form>
                                            </details>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($nominations->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $nominations->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB 2: AWARD CYCLES MANAGER                    -->
    <!-- ============================================== -->
    <div x-show="tab === 'cycles'" x-cloak class="space-y-6">
        
        <!-- Create New Cycle Form Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span>➕</span> Create New Award Cycle
            </h3>

            <form action="{{ route('admin.awards.cycles.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Year *</label>
                    <input type="number" name="year" value="{{ date('Y') }}" required min="1980" max="{{ date('Y') + 10 }}" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Edition Title *</label>
                    <input type="text" name="title" placeholder="e.g. KDP Annual Alumni Honors 2027" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Theme / Slogan</label>
                    <input type="text" name="theme" placeholder="e.g. Celebrating Green Energy, Industrial Grit & Modernization" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Initial Status *</label>
                    <select name="status" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                        <option value="nominations_open" selected>Step 1: Open Nominations</option>
                        <option value="draft">Step 2: Jury Shortlisting &amp; Review</option>
                        <option value="voting_open">Step 3: Live Community Voting</option>
                        <option value="concluded">Step 4: Convocation Enshrinement &amp; Hall of Fame</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ceremony Date</label>
                    <input type="date" name="ceremony_date" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 text-xs font-bold text-indigo-900 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        Set as Active Cycle (Default on portal)
                    </label>
                </div>

                <div class="flex items-end justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-colors">
                        Save Cycle
                    </button>
                </div>
            </form>
        </div>

        <!-- List of Existing Cycles -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-5 bg-gray-50 border-b border-gray-200 font-bold text-sm text-gray-800">
                Registered Award Cycles ({{ $allCycles->count() }})
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($allCycles as $c)
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-gray-50/60 transition-colors">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-black text-gray-900">{{ $c->year }} Edition: {{ $c->title }}</span>
                                @if($c->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ★ Active Cycle
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $c->status) }}
                                </span>
                            </div>

                            @if($c->theme)
                                <p class="text-xs text-gray-600 italic">"{{ $c->theme }}"</p>
                            @endif

                            <div class="text-[11px] text-gray-500 flex items-center gap-3 pt-1">
                                <span>🏷️ {{ $c->categories_count }} Categories</span>
                                <span>•</span>
                                <span>🗳️ {{ $c->nominations_count }} Nominees</span>
                                <span>•</span>
                                <span>✓ {{ $c->votes_count }} Votes Cast</span>
                                @if($c->ceremony_date)
                                    <span>•</span>
                                    <span>📅 Ceremony: {{ $c->ceremony_date->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Stage Change & Active Toggle -->
                        <form action="{{ route('admin.awards.cycles.update-status', $c->id) }}" method="POST" class="flex items-center gap-2 flex-shrink-0">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded-xl border-gray-300 text-xs font-semibold">
                                <option value="nominations_open" {{ $c->status === 'nominations_open' ? 'selected' : '' }}>Step 1: Open Nominations</option>
                                <option value="draft" {{ in_array($c->status, ['draft', 'jury_review']) ? 'selected' : '' }}>Step 2: Jury Shortlisting</option>
                                <option value="voting_open" {{ $c->status === 'voting_open' ? 'selected' : '' }}>Step 3: Live Voting</option>
                                <option value="concluded" {{ $c->status === 'concluded' ? 'selected' : '' }}>Step 4: Convocation Enshrinement</option>
                            </select>
                            <button type="submit" class="px-3 py-2 rounded-xl bg-gray-800 hover:bg-black text-white text-xs font-bold transition-colors">
                                Update
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- TAB 3: CATEGORIES MANAGER                      -->
    <!-- ============================================== -->
    <div x-show="tab === 'categories'" x-cloak class="space-y-6">
        
        <!-- Add Category Form Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span>➕</span> Add Category to {{ $cycle ? $cycle->title : 'Current Cycle' }}
            </h3>

            @if($cycle)
                <form action="{{ route('admin.awards.categories.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    <input type="hidden" name="award_cycle_id" value="{{ $cycle->id }}">

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Category Name *</label>
                        <input type="text" name="name" placeholder="e.g. Industrial Automation &amp; Robotics Pioneer" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Icon / Emoji</label>
                        <input type="text" name="icon" placeholder="🤖 or ⚡" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                        <input type="text" name="description" placeholder="Short description of what achievements this category honors..." class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Display Order</label>
                        <input type="number" name="display_order" value="{{ $categories->count() + 1 }}" min="1" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Eligibility Criteria</label>
                        <input type="text" name="eligibility_criteria" placeholder="e.g. Graduated within last 10 years, deployed automation in GIDC units" class="w-full rounded-xl border-gray-300 text-xs focus:ring-indigo-600 focus:border-indigo-600">
                    </div>

                    <div class="flex items-end justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-colors">
                            Add Category
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <span class="font-bold text-sm text-gray-800">
                    Active Categories for Cycle {{ $cycle ? $cycle->year : '' }} ({{ $categories->count() }})
                </span>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($categories as $cat)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">{{ $cat->icon ?: '🏆' }}</span>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $cat->name }}</h4>
                                <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded">/{{ $cat->slug }}</span>
                            </div>
                            <p class="text-xs text-gray-600">{{ $cat->description }}</p>
                            @if($cat->eligibility_criteria)
                                <p class="text-[11px] text-gray-500 font-medium"><strong>Criteria:</strong> {{ $cat->eligibility_criteria }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $cat->approved_nominations_count }} Finalists • {{ $cat->votes_count }} Votes
                            </span>

                            <form action="{{ route('admin.awards.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500 italic">
                        No categories created for this cycle yet. Use the form above to add one dynamically.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- TAB 4: HALL OF FAME REGISTRY                   -->
    <!-- ============================================== -->
    <div x-show="tab === 'hall-of-fame'" x-cloak class="space-y-6">
        
        <!-- Direct Induction Form Card -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span>👑</span> Directly Enshrine Alumnus into Hall of Fame
            </h3>
            <p class="text-xs text-gray-500">Honor foundational alumni, past principals, and industry pioneers from prior decades without needing an active voting cycle.</p>

            <form action="{{ route('admin.awards.inductees.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Full Name *</label>
                    <input type="text" name="name" placeholder="e.g. Er. Pravin M. Patel" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Engineering Branch *</label>
                    <select name="department" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                        <option value="">-- Select Branch --</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Graduation Batch Year *</label>
                    <input type="number" name="batch_year" value="1992" min="1970" max="{{ date('Y') + 1 }}" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Induction Year *</label>
                    <input type="number" name="induction_year" value="{{ date('Y') }}" min="1980" max="{{ date('Y') + 5 }}" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Award Title *</label>
                    <input type="text" name="award_title" placeholder="e.g. Lifetime Industrial Pioneer Award" required class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Official College Citation * (Min 20 characters)</label>
                    <textarea name="citation" rows="3" required placeholder="Detailed citation describing their foundational engineering leadership and societal impact..." class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Company / Enterprise</label>
                    <input type="text" name="company" placeholder="e.g. Torrent Power, L&amp;T" class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Current Role / Designation</label>
                    <input type="text" name="current_role" placeholder="e.g. Managing Director" class="w-full rounded-xl border-gray-300 text-xs focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Portrait Photo</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-900">
                </div>

                <div class="sm:col-span-2 flex items-center">
                    <label class="flex items-center gap-2 text-xs font-bold text-amber-900 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-amber-600 focus:ring-amber-500">
                        Feature in Public Spotlight Carousel
                    </label>
                </div>

                <div class="flex items-end justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-colors">
                        Enshrine Inductee
                    </button>
                </div>
            </form>
        </div>

        <!-- Enshrined Inductees Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-5 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <span class="font-bold text-sm text-gray-800">
                    Enshrined Legends ({{ $inductees->total() }})
                </span>
                <a href="{{ route('awards.hall-of-fame') }}" target="_blank" class="text-xs font-bold text-amber-800 hover:underline">
                    View Public Gallery &rarr;
                </a>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($inductees as $ind)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <img src="{{ $ind->photo_url }}" alt="{{ $ind->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-amber-400 shadow-sm">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $ind->name }}</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                        Inducted {{ $ind->induction_year }}
                                    </span>
                                    @if($ind->is_featured)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">
                                            ★ Spotlight
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-blue-900 font-semibold">
                                    {{ $ind->department }} (Class of {{ $ind->batch_year }})
                                </div>
                                <div class="text-xs font-bold text-gray-800">
                                    {{ $ind->award_title }}
                                </div>
                                <p class="text-xs text-gray-600 italic line-clamp-2 max-w-xl">
                                    "{{ $ind->citation }}"
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if($ind->company)
                                <div class="text-right text-xs text-gray-500 hidden sm:block">
                                    <div class="font-bold text-gray-700">{{ $ind->company }}</div>
                                    <div class="text-[11px]">{{ $ind->current_role }}</div>
                                </div>
                            @endif

                            <form action="{{ route('admin.awards.inductees.destroy', $ind->id) }}" method="POST" onsubmit="return confirm('Remove {{ $ind->name }} from Hall of Fame?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($inductees->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $inductees->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
