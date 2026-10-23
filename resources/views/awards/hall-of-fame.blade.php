@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-900 text-slate-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- Header Hero -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-bold uppercase tracking-widest">
                <span>👑</span> K.D. Polytechnic Patan • Permanent Archives
            </div>

            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Virtual Hall of Fame &amp; Wall of Honor
            </h1>

            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                Enshrining our most accomplished diploma graduates who have engineered breakthrough industries, modernized civic infrastructure, and brought international distinction to K.D. Polytechnic Patan.
            </p>

            <div class="pt-2 flex items-center justify-center gap-4 text-xs font-bold uppercase tracking-wider text-amber-400">
                <span>🏆 Highest Institutional Honor</span>
                <span>•</span>
                <span>🏛️ Official College Registry</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-slate-800/90 rounded-2xl p-6 border border-slate-700/80 shadow-xl">
            <form action="{{ route('awards.hall-of-fame') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="sm:col-span-2">
                    <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                        Search Inductee or Keyword
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, company, or citation..." class="w-full rounded-xl bg-slate-900 border-slate-700 text-white placeholder-slate-500 text-xs sm:text-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Year Filter -->
                <div>
                    <label for="year" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                        Induction Year
                    </label>
                    <select name="year" id="year" class="w-full rounded-xl bg-slate-900 border-slate-700 text-white text-xs sm:text-sm focus:border-amber-500 focus:ring-amber-500">
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
                    <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                        Department
                    </label>
                    <select name="department" id="department" class="w-full rounded-xl bg-slate-900 border-slate-700 text-white text-xs sm:text-sm focus:border-amber-500 focus:ring-amber-500">
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
                        <a href="{{ route('awards.hall-of-fame') }}" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-wider">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs uppercase tracking-wider transition-colors shadow-md">
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
                    <div class="absolute -top-3.5 right-6 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 text-[10px] font-black uppercase tracking-widest shadow-md">
                        Inducted {{ $inductee->induction_year }}
                    </div>

                    <div class="space-y-5">
                        <!-- Portrait & Names -->
                        <div class="flex items-center gap-4 pt-2">
                            <div class="relative">
                                <img src="{{ $inductee->photo_url }}" alt="{{ $inductee->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-400 shadow-lg">
                                <span class="absolute -bottom-1 -right-1 text-base">🏅</span>
                            </div>
                            <div class="space-y-0.5">
                                <h3 class="text-lg font-black text-white group-hover:text-amber-400 transition-colors">
                                    {{ $inductee->name }}
                                </h3>
                                <p class="text-xs text-amber-300/90 font-semibold">
                                    {{ $inductee->department }}
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    Graduation Batch: {{ $inductee->batch_year }}
                                </p>
                            </div>
                        </div>

                        <!-- Award Title Pill -->
                        <div class="px-3 py-2 rounded-xl bg-amber-500/10 border border-amber-400/20 text-amber-200 text-xs font-bold">
                            🏆 {{ $inductee->award_title }}
                        </div>

                        <!-- Official Citation -->
                        <div class="space-y-1">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Official Citation
                            </div>
                            <p class="text-xs text-slate-300 italic leading-relaxed">
                                "{{ $inductee->citation }}"
                            </p>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    @if($inductee->company)
                        <div class="pt-4 mt-6 border-t border-slate-700/60 text-xs text-slate-400 flex items-center justify-between">
                            <div>
                                <span class="text-slate-500 font-medium">Enterprise:</span>
                                <span class="text-slate-200 font-semibold">{{ $inductee->company }}</span>
                            </div>
                            @if($inductee->current_role)
                                <div class="text-[11px] text-amber-300/80">
                                    {{ $inductee->current_role }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-slate-800/50 rounded-3xl border border-dashed border-slate-700">
                    <span class="text-5xl">🏛️</span>
                    <h3 class="text-lg font-bold text-white mt-3">No Inductees Found</h3>
                    <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search keywords.</p>
                    <a href="{{ route('awards.hall-of-fame') }}" class="inline-block mt-4 px-5 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider">
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
            <a href="{{ route('awards.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-400 hover:text-amber-300">
                &larr; Return to Annual Awards Hub &amp; Live Voting
            </a>
        </div>

    </div>
</div>
@endsection
