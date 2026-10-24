@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" x-data="{ openUploadModal: false }">

    <!-- Breadcrumb & Top Action -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">Academic Resources &amp; GTU Papers</span>
        </nav>

        <button @click="openUploadModal = true" 
                class="bg-[#f97316] hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow-md transition-all text-xs flex items-center gap-2 font-heading cursor-pointer transform hover:scale-102">
            <span class="text-base font-black">+</span>
            <span>Upload Notes / Exam Paper</span>
        </button>
    </div>

    <!-- Institutional Hero Banner -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] rounded-2xl text-white p-6 sm:p-10 shadow-lg relative overflow-hidden mb-8 border-b-4 border-[#f97316]">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 mb-3 font-heading">
                GTU Diploma Engineering • K. D. Polytechnic, Patan
            </span>
            <h1 class="text-2xl sm:text-4xl font-black font-heading tracking-tight leading-tight">
                GTU Study Resources &amp; Past Exam Papers Vault
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Access branch-wise and semester-wise GTU question papers, handwritten lecture notes, laboratory manuals, and curated MOOCs contributed by alumni, senior students, and faculty.
            </p>
        </div>

        <!-- Crest Watermark -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Alert Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Category Switcher Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none">
        <a href="{{ route('resources.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs tracking-wider whitespace-nowrap transition-all font-heading flex items-center gap-1.5 {{ !request('category') || request('category') === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <span>All Materials</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="'{{ !request('category') || request('category') === 'all' }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $totalCount ?? $resources->total() }}</span>
        </a>

        <a href="{{ route('resources.index', array_merge(request()->except('category', 'page'), ['category' => 'exam_paper'])) }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs tracking-wider whitespace-nowrap transition-all font-heading flex items-center gap-1.5 {{ request('category') === 'exam_paper' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <span>📝 GTU Exam Papers</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="'{{ request('category') === 'exam_paper' }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $examCount ?? 0 }}</span>
        </a>

        <a href="{{ route('resources.index', array_merge(request()->except('category', 'page'), ['category' => 'study_notes'])) }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs tracking-wider whitespace-nowrap transition-all font-heading flex items-center gap-1.5 {{ request('category') === 'study_notes' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <span>📚 Lecture Notes &amp; Labs</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="'{{ request('category') === 'study_notes' }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $notesCount ?? 0 }}</span>
        </a>

        <a href="{{ route('resources.index', array_merge(request()->except('category', 'page'), ['category' => 'mooc'])) }}" 
           class="px-4 py-2 rounded-xl font-bold text-xs tracking-wider whitespace-nowrap transition-all font-heading flex items-center gap-1.5 {{ request('category') === 'mooc' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
            <span>🎥 MOOCs &amp; Video Courses</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="'{{ request('category') === 'mooc' }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $moocCount ?? 0 }}</span>
        </a>
    </div>

    <!-- Filter Control Bar (Department, Semester, Keyword Search) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-8">
        <form method="GET" action="{{ route('resources.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <!-- Hidden Category if active -->
            @if(request('category') && request('category') !== 'all')
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <!-- Department Filter -->
            <div class="lg:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Engineering Branch</label>
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Engineering Departments</option>
                    <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                    <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                    <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                    <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                    <option value="General" {{ request('department') === 'General' ? 'selected' : '' }}>🔬 General (Science &amp; Humanities)</option>
                </select>
            </div>

            <!-- Semester Filter -->
            <div class="lg:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">GTU Semester</label>
                <select name="semester" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Semesters (1 - 6)</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1 (Sem 1)</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2 (Sem 2)</option>
                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Semester 3 (Sem 3)</option>
                    <option value="4" {{ request('semester') == '4' ? 'selected' : '' }}>Semester 4 (Sem 4)</option>
                    <option value="5" {{ request('semester') == '5' ? 'selected' : '' }}>Semester 5 (Sem 5)</option>
                    <option value="6" {{ request('semester') == '6' ? 'selected' : '' }}>Semester 6 (Sem 6)</option>
                </select>
            </div>

            <!-- Search Keyword -->
            <div class="lg:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Subject / Keyword</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="e.g. DBMS, Python, Thermo..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                </div>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-3 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Filter
                </button>
                @if(request()->anyFilled(['department', 'semester', 'search', 'category']))
                    <a href="{{ route('resources.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold transition-colors" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filter Indicators -->
    @if(request()->anyFilled(['department', 'semester', 'search']))
        <div class="flex items-center gap-2 mb-6 flex-wrap text-xs text-slate-500">
            <span class="font-bold">Active Filters:</span>
            @if(request('department'))
                <span class="inline-flex items-center gap-1 bg-blue-50 text-[#1e3a8a] border border-blue-200 px-2.5 py-1 rounded-lg font-bold">
                    Dept: {{ request('department') }}
                </span>
            @endif
            @if(request('semester'))
                <span class="inline-flex items-center gap-1 bg-orange-50 text-[#f97316] border border-orange-200 px-2.5 py-1 rounded-lg font-bold">
                    Semester {{ request('semester') }}
                </span>
            @endif
            @if(request('search'))
                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 border border-slate-300 px-2.5 py-1 rounded-lg font-bold">
                    Query: "{{ request('search') }}"
                </span>
            @endif
            <a href="{{ route('resources.index') }}" class="text-xs text-red-600 font-bold hover:underline ml-2">Clear all</a>
        </div>
    @endif

    <!-- Resource Listings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($resources as $resource)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300 p-5 sm:p-6 flex flex-col justify-between group">
                <div>
                    <!-- Badges Row -->
                    <div class="flex items-center justify-between gap-2 mb-3 flex-wrap">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <!-- Category Badge -->
                            @php
                                $catClass = match($resource->category) {
                                    'exam_paper' => 'bg-red-50 text-red-700 border-red-200',
                                    'mooc' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'study_notes' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    default => 'bg-blue-50 text-blue-700 border-blue-200'
                                };
                                $catLabel = match($resource->category) {
                                    'exam_paper' => 'GTU Exam Paper',
                                    'mooc' => 'MOOC / Course',
                                    'study_notes' => 'Study Notes',
                                    default => 'Other'
                                };
                            @endphp
                            <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $catClass }} font-heading">
                                {{ $catLabel }}
                            </span>

                            <!-- Semester Badge -->
                            @if($resource->semester)
                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                    Sem {{ $resource->semester }}
                                </span>
                            @endif
                        </div>

                        <span class="text-[10px] font-semibold text-slate-400 font-mono">
                            {{ $resource->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <!-- Department & Subject Row -->
                    @if($resource->department || $resource->subject)
                        <div class="mb-2 flex items-center gap-2 text-xs font-bold text-slate-500">
                            @if($resource->department)
                                <span class="text-[#1e3a8a]">{{ $resource->department }}</span>
                            @endif
                            @if($resource->department && $resource->subject)
                                <span>&bull;</span>
                            @endif
                            @if($resource->subject)
                                <span class="text-slate-700 font-semibold">{{ $resource->subject }}</span>
                            @endif
                        </div>
                    @endif

                    <!-- Title -->
                    <h3 class="text-base font-bold font-heading text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors mb-2 leading-snug line-clamp-2">
                        {{ $resource->title }}
                    </h3>

                    <!-- Description -->
                    @if($resource->description)
                        <p class="text-xs text-slate-600 mb-4 line-clamp-3 leading-relaxed">
                            {{ $resource->description }}
                        </p>
                    @endif
                </div>

                <!-- Footer Action Row -->
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <div class="text-[11px] text-slate-500 font-medium">
                        Uploaded by <strong class="text-slate-800">{{ $resource->user->name ?? 'KDP Member' }}</strong>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($resource->url)
                            <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] border border-blue-200 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors font-heading" title="Open Link">
                                <span>🌐</span>
                                <span>Open Link</span>
                            </a>
                        @endif

                        @if($resource->file_path)
                            <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#1e3a8a] hover:bg-blue-900 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-2xs font-heading" title="Download File">
                                <span>{{ $resource->is_pdf ? '📄' : '💾' }}</span>
                                <span>{{ $resource->file_extension ?: 'Download' }}</span>
                            </a>
                        @endif

                        @auth
                            @if(auth()->id() === $resource->user_id || auth()->user()->role === 'admin')
                                <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Delete this study resource?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Resource">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white border border-dashed border-slate-200 rounded-2xl p-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                    📚
                </div>
                <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Study Resources Found</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-5 font-sans leading-relaxed">
                    No resources match the selected department, semester, or search query. Help fellow K. D. Polytechnic students by sharing exam papers or notes!
                </p>
                <button @click="openUploadModal = true" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    + Upload the First Resource
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $resources->links() }}
    </div>

    <!-- Upload Resource Modal Popup -->
    <div x-show="openUploadModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.outside="openUploadModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-xl w-full overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📤</span>
                    <h3 class="text-base font-bold font-heading">Share Study Material / GTU Paper</h3>
                </div>
                <button type="button" @click="openUploadModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">
                    &times;
                </button>
            </div>
            
            <form method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Resource Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. GTU Winter 2025 Database Management Exam Paper" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Material Category <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="exam_paper">GTU Exam Paper</option>
                            <option value="study_notes">Study Notes / Lab Manual</option>
                            <option value="mooc">MOOC / Video Course</option>
                            <option value="other">Other Academic Reference</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">GTU Semester</label>
                        <select name="semester" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="">General / All Semesters</option>
                            <option value="1">Semester 1 (Sem 1)</option>
                            <option value="2">Semester 2 (Sem 2)</option>
                            <option value="3">Semester 3 (Sem 3)</option>
                            <option value="4">Semester 4 (Sem 4)</option>
                            <option value="5">Semester 5 (Sem 5)</option>
                            <option value="6">Semester 6 (Sem 6)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department / Branch</label>
                        <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="">Common / All Departments</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="General">General (Science &amp; Humanities)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subject Name / Code</label>
                        <input type="text" name="subject" placeholder="e.g. DBMS (4330703)" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Brief Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Mention key topics covered, exam session, or book reference..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">External Course / Drive URL</label>
                        <input type="url" name="url" placeholder="https://..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Attach File (PDF, DOCX, ZIP)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx,.jpg,.jpeg,.png" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" @click="openUploadModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Upload to Vault
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
