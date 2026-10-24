@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" x-data="{ expandedStory: null }">

    <!-- Breadcrumb & Top Action -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">Alumni Success Stories</span>
        </nav>

        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.stories.index') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all font-heading">
                    <span>⚙️</span>
                    <span>Manage Stories (Admin Panel)</span>
                </a>
            @endif
        @endauth
    </div>

    <!-- Institutional Hero Banner -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] rounded-2xl text-white p-6 sm:p-10 shadow-lg relative overflow-hidden mb-8 border-b-4 border-[#f97316]">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 mb-3 font-heading">
                K. D. Polytechnic Patan • Hall of Achievements
            </span>
            <h1 class="text-2xl sm:text-4xl font-black font-heading tracking-tight leading-tight">
                Alumni Success Stories &amp; Global Impact
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Discover how diploma graduates from K. D. Polytechnic are excelling across multinationals, establishing cutting-edge tech startups, and leading engineering projects across India and the globe.
            </p>
        </div>

        <!-- Crest Watermark -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-8">
        <form method="GET" action="{{ route('stories.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-5">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Search Stories &amp; Alumni</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, or achievement..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Engineering Branch</label>
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Engineering Departments</option>
                    <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                    <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                    <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                    <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Filter Stories
                </button>
                @if(request()->anyFilled(['search', 'department']))
                    <a href="{{ route('stories.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold" title="Reset Filters">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- FEATURED STORY SPOTLIGHT (If available) -->
    @if(isset($featuredStory) && $featuredStory && !request()->anyFilled(['search', 'department']))
        <div class="bg-gradient-to-r from-blue-950 via-[#1c3272] to-[#0f172a] rounded-2xl p-6 sm:p-8 text-white shadow-md mb-10 border border-blue-900/50 flex flex-col lg:flex-row items-center gap-8 relative overflow-hidden">
            <div class="w-full lg:w-1/3 shrink-0 h-64 sm:h-72 rounded-xl overflow-hidden shadow-lg border-2 border-white/20 bg-slate-800">
                @if($featuredStory->image_path)
                    <img src="{{ asset('storage/' . $featuredStory->image_path) }}" alt="{{ $featuredStory->alumni_name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-4 bg-gradient-to-br from-[#1e3a8a] to-[#2e53a3]">
                        <span class="text-4xl mb-2">⭐</span>
                        <span class="text-xs font-black uppercase tracking-widest text-[#f97316] font-heading">Featured Alumnus</span>
                        <span class="text-base font-bold text-white mt-1">{{ $featuredStory->alumni_name }}</span>
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="bg-[#f97316] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-2xs font-heading">
                        ★ Featured Spotlight
                    </span>
                    <span class="text-xs font-bold text-blue-200 uppercase tracking-wider">
                        {{ $featuredStory->department }} • Batch {{ $featuredStory->batch_year }}
                    </span>
                </div>

                <h2 class="text-xl sm:text-2xl font-black font-heading tracking-tight mb-2 text-white leading-snug">
                    {{ $featuredStory->title }}
                </h2>

                <p class="text-sm font-bold text-orange-400 mb-3">
                    — {{ $featuredStory->alumni_name }}
                </p>

                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed font-sans line-clamp-4 mb-4">
                    {{ $featuredStory->story }}
                </p>

                <button type="button" @click="expandedStory = {{ json_encode($featuredStory) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/30 px-4 py-2 rounded-xl transition-all cursor-pointer font-heading">
                    <span>Read Full Spotlight</span>
                    <span>&rarr;</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Stories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @forelse($stories as $story)
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <!-- Photo Header Banner -->
                    <div class="h-52 bg-slate-100 relative overflow-hidden">
                        @if($story->image_path)
                            <img src="{{ asset('storage/' . $story->image_path) }}" alt="{{ $story->alumni_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#1e3a8a] via-[#1c3272] to-[#0f172a] flex flex-col items-center justify-center text-white text-center p-4">
                                <span class="text-3xl mb-1">🎓</span>
                                <span class="text-xs uppercase tracking-widest font-black font-heading">{{ $story->department }}</span>
                                <span class="text-[10px] text-blue-200 font-sans mt-0.5">K. D. Polytechnic Patan</span>
                            </div>
                        @endif

                        <!-- Department Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-[#0f172a]/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full shadow-2xs border border-white/20 font-heading">
                                {{ $story->department }}
                            </span>
                        </div>

                        <!-- Batch Year Badge -->
                        <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md text-[#0f172a] text-[10px] font-black px-2.5 py-1 rounded-lg shadow-sm border border-slate-200 font-heading">
                            Batch {{ $story->batch_year }}
                        </div>
                    </div>

                    <!-- Story Info -->
                    <div class="p-5 sm:p-6">
                        <h3 class="font-heading font-bold text-base sm:text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors mb-1 leading-snug line-clamp-2">
                            {{ $story->title }}
                        </h3>

                        <p class="text-xs font-bold text-[#f97316] mb-3 flex items-center gap-1 font-heading">
                            <span>— {{ $story->alumni_name }}</span>
                            @if($story->is_featured)
                                <span class="text-[9px] bg-orange-100 text-[#f97316] px-1.5 py-0.2 rounded font-bold">★ Featured</span>
                            @endif
                        </p>

                        <p class="text-xs text-slate-600 line-clamp-4 leading-relaxed font-sans mb-4">
                            {{ $story->story }}
                        </p>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="px-5 sm:px-6 pb-5 pt-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-2">
                    <button type="button" @click="expandedStory = {{ json_encode($story) }}" class="text-xs font-bold text-[#1e3a8a] hover:underline flex items-center gap-1 font-heading cursor-pointer">
                        <span>Read Full Story</span>
                        <span>&rarr;</span>
                    </button>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Delete this success story?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Story">
                                    🗑️
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                    🌟
                </div>
                <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Success Stories Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-sans">
                    No alumni stories match the selected criteria. Check back soon or submit your story to the admin office!
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $stories->links() }}
    </div>

    <!-- Full Story Reader Modal -->
    <div x-show="expandedStory !== null" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.outside="expandedStory = null" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-2xl w-full overflow-hidden transform transition-all max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="p-6 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#f97316] font-heading" x-text="expandedStory ? (expandedStory.department + ' • Class of ' + expandedStory.batch_year) : ''"></span>
                    <h3 class="text-lg sm:text-xl font-bold font-heading mt-1" x-text="expandedStory ? expandedStory.title : ''"></h3>
                    <p class="text-xs text-blue-200 mt-0.5" x-text="expandedStory ? ('By ' + expandedStory.alumni_name) : ''"></p>
                </div>
                <button type="button" @click="expandedStory = null" class="text-white/70 hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto space-y-4">
                <template x-if="expandedStory && expandedStory.image_path">
                    <div class="w-full h-64 rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <img :src="'/storage/' + expandedStory.image_path" :alt="expandedStory.alumni_name" class="w-full h-full object-cover">
                    </div>
                </template>

                <div class="prose max-w-none text-slate-700 leading-relaxed text-sm whitespace-pre-line font-sans" x-text="expandedStory ? expandedStory.story : ''"></div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button type="button" @click="expandedStory = null" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white font-heading transition-colors cursor-pointer">
                    Close Story
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
