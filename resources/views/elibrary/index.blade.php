@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20" x-data="{ openUploadModal: false }">

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
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wide border border-amber-500/30 font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <span class="px-2.5 py-1 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    GTU Institute Code: 622 &bull; Academic Repository
                </span>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>📚</span> {{ $totalCount }} Verified Materials
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="font-heading text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-wide text-white leading-tight">
                        Digital E-Library &amp; Project Vault
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-2 leading-relaxed font-sans">
                        Downloadable repository of GTU diploma engineering e-books, previous final-year capstone project synopses, and department laboratory reference manuals curated for KDP polytechnic engineers.
                    </p>
                </div>

                <!-- Contribute CTA Button -->
                <div class="shrink-0">
                    <button type="button" 
                            @click="openUploadModal = true" 
                            class="inline-flex items-center gap-2 bg-[#f97316] hover:bg-orange-600 text-white text-xs sm:text-sm font-bold uppercase tracking-wider px-5 py-3 rounded-xl shadow-lg transition-all font-heading cursor-pointer hover:scale-102">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Upload Book or Project</span>
                    </button>
                </div>
            </div>

            <!-- Key Metric Ribbons -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8 pt-6 border-t border-white/10 text-xs">
                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/10">
                    <span class="text-blue-200 block text-[10px] uppercase font-bold tracking-wider font-heading">Total Repository</span>
                    <span class="text-xl font-black text-white font-heading">{{ $totalCount }}</span>
                    <span class="text-[10px] text-blue-300 block">Across all branches</span>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/10">
                    <span class="text-amber-200 block text-[10px] uppercase font-bold tracking-wider font-heading">GTU E-Books</span>
                    <span class="text-xl font-black text-amber-300 font-heading">{{ $ebookCount }}</span>
                    <span class="text-[10px] text-blue-200 block">Core engineering texts</span>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/10">
                    <span class="text-emerald-200 block text-[10px] uppercase font-bold tracking-wider font-heading">Capstone Synopses</span>
                    <span class="text-xl font-black text-emerald-300 font-heading">{{ $capstoneCount }}</span>
                    <span class="text-[10px] text-blue-200 block">Final-year innovations</span>
                </div>
                <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/10">
                    <span class="text-purple-200 block text-[10px] uppercase font-bold tracking-wider font-heading">Lab Manuals</span>
                    <span class="text-xl font-black text-purple-300 font-heading">{{ $labCount }}</span>
                    <span class="text-[10px] text-blue-200 block">Experiment protocols</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 space-y-6">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm font-semibold rounded-r-xl shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        <!-- Category Tabs Ribbon -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none font-heading text-xs">
            <a href="{{ route('elibrary.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}" 
               class="px-4 py-2.5 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ !request('category') || request('category') === 'all' ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                <span>All Materials</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('category') || request('category') === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $totalCount }}</span>
            </a>

            <a href="{{ route('elibrary.index', array_merge(request()->except('category', 'page'), ['category' => 'ebook'])) }}" 
               class="px-4 py-2.5 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ request('category') === 'ebook' ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                <span>📖 GTU E-Books &amp; Textbooks</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('category') === 'ebook' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $ebookCount }}</span>
            </a>

            <a href="{{ route('elibrary.index', array_merge(request()->except('category', 'page'), ['category' => 'capstone_project'])) }}" 
               class="px-4 py-2.5 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ request('category') === 'capstone_project' ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                <span>🚀 Capstone Project Synopses</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('category') === 'capstone_project' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $capstoneCount }}</span>
            </a>

            <a href="{{ route('elibrary.index', array_merge(request()->except('category', 'page'), ['category' => 'lab_manual'])) }}" 
               class="px-4 py-2.5 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ request('category') === 'lab_manual' ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                <span>🔬 Lab Reference Manuals</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('category') === 'lab_manual' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $labCount }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5">
            <form method="GET" action="{{ route('elibrary.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                @if(request('category') && request('category') !== 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Department Filter -->
                <div class="lg:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Engineering Department</label>
                    <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 bg-slate-50/60 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                        <option value="">All Engineering Branches</option>
                        <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                        <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                        <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                        <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                        <option value="General" {{ request('department') === 'General' ? 'selected' : '' }}>🔬 General (Sciences &amp; Humanities)</option>
                    </select>
                </div>

                <!-- Semester Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">GTU Semester</label>
                    <select name="semester" class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 bg-slate-50/60 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                        <option value="">All Semesters (Sem 1 - 6)</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Search Input -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Subject, Stack, or Author</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            🔍
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="e.g. Data Structures, ESP32, CNC, Khurmi..." 
                               class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full bg-[#0f2042] hover:bg-[#1e3a8a] text-white font-bold py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                        Filter
                    </button>
                    @if(request()->anyFilled(['department', 'semester', 'search', 'category']))
                        <a href="{{ route('elibrary.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold transition-colors" title="Clear Filters">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Active Filter Indicator Bar -->
        @if(request()->anyFilled(['department', 'semester', 'search']))
            <div class="flex items-center gap-2 flex-wrap text-xs text-slate-500">
                <span class="font-bold">Active Filters:</span>
                @if(request('department'))
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-[#1e3a8a] border border-blue-200 px-2.5 py-1 rounded-lg font-bold">
                        Dept: {{ request('department') }}
                    </span>
                @endif
                @if(request('semester'))
                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-lg font-bold">
                        Semester {{ request('semester') }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 border border-slate-300 px-2.5 py-1 rounded-lg font-bold">
                        Query: "{{ request('search') }}"
                    </span>
                @endif
                <a href="{{ route('elibrary.index') }}" class="text-xs text-rose-600 font-bold hover:underline ml-2">Reset all</a>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- 3. REPOSITORY ITEMS GRID                       -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col justify-between group">
                    <div class="space-y-3.5">
                        <!-- Badges Row -->
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $item->category_badge_class }} font-heading">
                                <span>{{ $item->category_icon }}</span>
                                <span>{{ $item->category_label }}</span>
                            </span>

                            <div class="flex items-center gap-1.5 text-[10px] font-bold">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-heading">
                                    {{ $item->department_icon }} {{ $item->department }}
                                </span>
                                @if($item->semester)
                                    <span class="px-2 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                        Sem {{ $item->semester }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Title -->
                        <div>
                            @if($item->subject_code)
                                <span class="text-[10px] font-mono font-bold text-slate-400 block uppercase">
                                    GTU Code: {{ $item->subject_code }} &bull; {{ $item->subject_name }}
                                </span>
                            @endif
                            <h3 class="font-heading font-bold text-base text-slate-900 group-hover:text-[#1e3a8a] transition-colors leading-snug mt-0.5">
                                <a href="{{ route('elibrary.show', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                        </div>

                        <!-- Abstract / Description snippet -->
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-sans">
                            {{ $item->description }}
                        </p>

                        <!-- Tech Stack / Keywords if available -->
                        @if($item->tech_stack)
                            <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                @foreach(array_slice(explode(',', $item->tech_stack), 0, 3) as $tech)
                                    <span class="text-[10px] bg-slate-50 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-md font-mono">
                                        {{ trim($tech) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Contributor / Team / Faculty Credit -->
                        <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                            @if($item->author_or_team)
                                <div class="truncate">
                                    <span class="font-semibold text-slate-700">Team/Author:</span> {{ $item->author_or_team }}
                                </div>
                            @endif
                            @if($item->guide_faculty)
                                <div class="truncate text-amber-900/90 font-medium">
                                    <span>Faculty Guide:</span> {{ $item->guide_faculty }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer: Downloads Metric & Action Buttons -->
                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div class="text-[11px] text-slate-400 font-medium">
                            <span class="flex items-center gap-1">
                                <span>📥</span> 
                                <strong class="text-slate-700 font-heading">{{ $item->downloads_count }}</strong> downloads
                            </span>
                            @if($item->file_size)
                                <span class="text-[10px] text-slate-400 block font-mono">{{ $item->file_format }} &bull; {{ $item->file_size }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('elibrary.show', $item->slug) }}" 
                               class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors font-heading">
                                Details
                            </a>

                            <a href="{{ route('elibrary.download', $item->id) }}" 
                               class="inline-flex items-center gap-1.5 bg-[#0f2042] hover:bg-[#1e3a8a] text-white px-3.5 py-1.5 rounded-xl text-xs font-bold font-heading shadow-xs transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Download</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <span class="text-4xl block mb-2">📚</span>
                    <h3 class="font-heading font-black text-slate-800 text-lg uppercase tracking-wide">No Materials Found</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                        No e-books, capstones, or lab manuals match your current search criteria. Try clearing filters or contribute a resource below.
                    </p>
                    <button type="button" 
                            @click="openUploadModal = true" 
                            class="mt-4 inline-flex items-center gap-2 bg-[#1e3a8a] text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm hover:bg-[#0f2042] transition-colors font-heading">
                        <span>+</span>
                        <span>Upload First Material</span>
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="mt-8">
                {{ $items->links() }}
            </div>
        @endif

    </div>

    <!-- ============================================== -->
    <!-- 4. CONTRIBUTE RESOURCE MODAL                   -->
    <!-- ============================================== -->
    <div x-show="openUploadModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openUploadModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="openUploadModal = false" 
                 class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openUploadModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-2xl p-6 sm:p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-100">
                
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200 text-[10px] font-bold uppercase tracking-wider font-heading">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#f97316]"></span>
                            Academic Knowledge Share
                        </span>
                        <h3 class="text-xl font-heading font-black text-slate-900 mt-1 uppercase tracking-wide">
                            Contribute to E-Library &amp; Vault
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Share e-textbooks, capstone project synopses, or department lab sheets for KDP students.
                        </p>
                    </div>
                    <button type="button" @click="openUploadModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('elibrary.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Resource Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. Smart Campus Solar Irrigation Capstone Synopsis or C Programming Textbook" class="w-full text-xs sm:text-sm border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Resource Category <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                                <option value="ebook">📖 GTU E-Book / Text</option>
                                <option value="capstone_project" selected>🚀 Capstone Synopsis</option>
                                <option value="lab_manual">🔬 Lab Manual &amp; Sheet</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Department <span class="text-red-500">*</span></label>
                            <select name="department" required class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                                <option value="Computer">💻 Computer Engineering</option>
                                <option value="Mechanical">⚙️ Mechanical Engineering</option>
                                <option value="Electrical">⚡ Electrical Engineering</option>
                                <option value="Civil">🏗️ Civil Engineering</option>
                                <option value="General">🔬 General Sciences</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Semester</label>
                            <select name="semester" class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                                <option value="">General / All</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Author / Student Team</label>
                            <input type="text" name="author_or_team" placeholder="e.g. Parth Patel, Hiren Dave (Class of 2024)" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Faculty Guide (For Capstones)</label>
                            <input type="text" name="guide_faculty" placeholder="e.g. Prof. N. D. Modi" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">GTU Subject Code</label>
                            <input type="text" name="subject_code" placeholder="e.g. 4360701" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Subject / Topic Name</label>
                            <input type="text" name="subject_name" placeholder="e.g. Capstone Project II" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Academic Year</label>
                            <input type="text" name="academic_year" placeholder="e.g. 2023-24" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Tech Stack / Key Methodologies</label>
                        <input type="text" name="tech_stack" placeholder="e.g. ESP32, Python, Flask, LoRaWAN, AutoCAD, STAAD.Pro" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Abstract / Synopsis / Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" required placeholder="Provide a summary of the project scope, problem statement, outcomes, or book contents..." class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">Upload Document (PDF, EPUB, DOCX, ZIP - Max 20MB)</label>
                            <input type="file" name="file" accept=".pdf,.epub,.docx,.zip,.txt" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 border border-slate-200 rounded-xl cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1 font-heading">External Drive / GitHub Link</label>
                            <input type="url" name="external_url" placeholder="https://drive.google.com/... or GitHub link" class="w-full text-xs border-slate-300 rounded-xl shadow-xs focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="openUploadModal = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors font-heading cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-[#0f2042] hover:bg-[#1e3a8a] rounded-xl shadow-sm transition-colors font-heading cursor-pointer">
                            Publish to E-Library &rarr;
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
