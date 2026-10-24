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
                    Institutional Alumni Network
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>👥</span> {{ $alumni->total() }} Members in community
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                        Alumni Directory &amp; Member Network
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                        Connect with six decades of K. D. Polytechnic diploma engineers across government sectors, multinational enterprises, and entrepreneurial frontiers.
                    </p>
                </div>

                <!-- Batchmates Shortcut Button -->
                @auth
                    <div class="shrink-0 flex flex-wrap items-center gap-3">
                        @if(request('batchmates'))
                            <a href="{{ route('alumni.index') }}" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl border border-white/25 transition-all font-heading">
                                <span>&larr;</span>
                                <span>Show All Alumni</span>
                            </a>
                        @else
                            <a href="{{ route('alumni.index', ['batchmates' => 1]) }}" class="inline-flex items-center gap-2 bg-[#f97316] hover:bg-orange-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl shadow-md transition-all font-heading">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                <span>Find My Batchmates</span>
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN DIRECTORY LAYOUT (FILTERS + GRID)      -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- LEFT SIDEBAR: FILTERS -->
            <div class="w-full lg:w-72 shrink-0">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden sticky top-6">
                    
                    <div class="bg-[#1e3a8a] text-white p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🔍</span>
                            <h3 class="font-heading font-black text-xs uppercase tracking-wider">
                                Directory Filters
                            </h3>
                        </div>
                        @if(request()->anyFilled(['search', 'role', 'graduation_year', 'degree', 'department', 'company', 'location', 'designation', 'work_industry', 'skills']) || request()->has('batchmates'))
                            <a href="{{ route('alumni.index') }}" class="text-[11px] font-bold text-orange-300 hover:text-white underline font-heading">
                                Reset All
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('alumni.index') }}" method="GET" id="filter-form" class="p-5 space-y-4">
                        
                        <!-- Search Bar -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Keyword Search
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, skills, company..."
                                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans transition-all">
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Role / Member Type
                            </label>
                            <select name="role" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Roles</option>
                                @if(isset($roles))
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Department Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Department
                            </label>
                            <select name="department" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Departments</option>
                                @if(isset($departments))
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Graduation Year Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Graduation Year
                            </label>
                            <select name="graduation_year" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Batches</option>
                                @if(isset($gradYears))
                                    @foreach($gradYears as $year)
                                        <option value="{{ $year }}" {{ request('graduation_year') == $year ? 'selected' : '' }}>Class of {{ $year }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Degree Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Course / Degree
                            </label>
                            <select name="degree" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Courses</option>
                                @if(isset($degrees))
                                    @foreach($degrees as $degree)
                                        <option value="{{ $degree }}" {{ request('degree') == $degree ? 'selected' : '' }}>{{ $degree }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- City / Location Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                City / Region
                            </label>
                            <select name="location" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Locations</option>
                                @if(isset($locations))
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Company Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Company / Recruiter
                            </label>
                            <select name="company" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Companies</option>
                                @if(isset($companies))
                                    @foreach($companies as $company)
                                        <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>{{ $company }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Designation Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Designation
                            </label>
                            <select name="designation" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Designations</option>
                                @if(isset($designations))
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig }}" {{ request('designation') == $desig ? 'selected' : '' }}>{{ $desig }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Industry Filter -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Industry Sector
                            </label>
                            <select name="work_industry" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans cursor-pointer">
                                <option value="">All Industries</option>
                                @if(isset($industries))
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry }}" {{ request('work_industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Skills Filter Input -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Technical Skills
                            </label>
                            <div class="flex gap-1.5">
                                <input type="text" name="skills" value="{{ request('skills') }}" placeholder="AutoCAD, Python..."
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-900 font-sans">
                                <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white px-3 rounded-xl text-xs font-bold font-heading cursor-pointer">
                                    Go
                                </button>
                            </div>
                        </div>

                        <!-- Submit Filter Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                                <span>Apply Filters</span>
                                <span>&rarr;</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- RIGHT MAIN CONTENT: DIRECTORY GRID -->
            <div class="w-full lg:flex-1">
                
                <!-- Status Top Bar -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-4 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-[#f97316]"></span>
                        <h2 class="font-heading font-black text-sm uppercase tracking-wide text-[#0f172a]">
                            @if(request('batchmates'))
                                My Batchmates
                                @php
                                    $batchYear = auth()->user()->graduation_year ?? optional(auth()->user()->profile)->graduation_year;
                                    $batchDept = auth()->user()->department ?? optional(auth()->user()->profile)->department;
                                @endphp
                                @if($batchYear || $batchDept)
                                    <span class="text-xs text-slate-500 font-normal">({{ $batchYear }} {{ $batchDept }})</span>
                                @endif
                            @else
                                All Profiles ({{ $alumni->total() }})
                            @endif
                        </h2>
                    </div>

                    <div class="text-xs text-slate-500 font-medium">
                        Showing {{ $alumni->firstItem() ?? 0 }} - {{ $alumni->lastItem() ?? 0 }} of {{ $alumni->total() }} verified records
                    </div>
                </div>

                <!-- Alumni Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    @forelse($alumni as $alum)
                        @php
                            $profile = $alum->profile;
                            $desig = $alum->designation ?? optional($profile)->job_title;
                            $comp = $alum->company ?? optional($profile)->current_company;
                            $city = optional($profile)->location;
                            $gradYear = $alum->graduation_year ?? optional($profile)->graduation_year;
                            $degree = $alum->degree ?? optional($profile)->degree;
                            $dept = $alum->department ?? optional($profile)->department;
                        @endphp

                        <!-- Institutional Alumni Card -->
                        <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border border-slate-200/90 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col group">
                            
                            <!-- Card Top Decorative Header Strip with Crest Watermark -->
                            <div class="h-20 bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] relative overflow-hidden flex items-start justify-between p-3.5">
                                <div class="absolute -right-3 -bottom-3 opacity-15 pointer-events-none">
                                    <img src="{{ asset('images/kdp_crest.png') }}" class="w-20 h-20 object-contain filter invert" alt="Crest">
                                </div>
                                
                                @if($gradYear)
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-orange-200 backdrop-blur-xs border border-white/20 font-heading">
                                        Class of {{ $gradYear }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-blue-100 backdrop-blur-xs border border-white/20 font-heading">
                                        {{ ucfirst($alum->role ?? 'Alumni') }}
                                    </span>
                                @endif

                                <span class="text-xs" title="Verified GTU Alumni">
                                    🛡️
                                </span>
                            </div>

                            <!-- Avatar & Profile Intro -->
                            <div class="px-5 pt-0 pb-5 flex-grow flex flex-col relative">
                                
                                <!-- Floating Avatar with Gold Ring -->
                                <div class="-mt-10 mb-3 flex items-center justify-between">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white border-2 border-white shadow-md flex items-center justify-center shrink-0">
                                        @if(!empty($alum->photo_path))
                                            <img src="{{ filter_var($alum->photo_path, FILTER_VALIDATE_URL) ? $alum->photo_path : asset('storage/' . $alum->photo_path) }}" 
                                                 alt="{{ $alum->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#1e3a8a] text-white flex items-center justify-center font-heading font-black text-xl">
                                                {{ substr($alum->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    @if($alum->department)
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 font-heading max-w-[150px] truncate" title="{{ $alum->department }}">
                                            {{ $alum->department }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Alumnus Name & Degree -->
                                <h3 class="font-heading font-black text-base text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors leading-snug">
                                    <a href="{{ route('alumni.show', $alum->id) }}" class="hover:underline">
                                        {{ $alum->name }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-500 font-sans mt-0.5 mb-3 line-clamp-1">
                                    {{ $degree ?? 'Diploma Engineering' }} &bull; KD Polytechnic
                                </p>

                                <!-- Professional Details (Company & Designation) -->
                                <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-xs text-slate-700 space-y-1.5 mb-4">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="text-slate-400 shrink-0">🏢</span>
                                        <span class="font-bold truncate text-slate-800">{{ $comp ?? 'Industry Engineering' }}</span>
                                    </div>
                                    @if($desig)
                                        <div class="flex items-center gap-2 truncate text-[11px] text-slate-600">
                                            <span class="text-slate-400 shrink-0">🏷️</span>
                                            <span class="truncate">{{ $desig }}</span>
                                        </div>
                                    @endif
                                    @if($city)
                                        <div class="flex items-center gap-2 truncate text-[11px] text-slate-500">
                                            <span class="text-slate-400 shrink-0">📍</span>
                                            <span class="truncate">{{ $city }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Contact Information with Masking -->
                                <div class="mt-auto pt-3 border-t border-slate-100 space-y-1.5 text-xs">
                                    <!-- Email -->
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-slate-400 font-medium">✉️ Email:</span>
                                        @if(optional($profile)->is_email_public || (auth()->check() && auth()->user()->role === 'admin'))
                                            <a href="mailto:{{ $alum->email }}" class="text-[#1e3a8a] font-semibold hover:underline truncate max-w-[160px] text-right" title="{{ $alum->email }}">
                                                {{ $alum->email }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic font-medium">Hidden</span>
                                        @endif
                                    </div>

                                    <!-- Phone -->
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-slate-400 font-medium">📞 Phone:</span>
                                        @if(optional($profile)->is_phone_public || (auth()->check() && auth()->user()->role === 'admin'))
                                            @if($alum->phone)
                                                <a href="tel:{{ $alum->phone }}" class="text-[#1e3a8a] font-semibold hover:underline">
                                                    {{ $alum->phone }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">Not Provided</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic font-medium">Hidden</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Action Button -->
                                <div class="pt-4 mt-3 border-t border-slate-100">
                                    <a href="{{ route('alumni.show', $alum->id) }}" class="w-full bg-slate-100 hover:bg-[#1e3a8a] hover:text-white text-slate-700 font-heading font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl transition-all flex items-center justify-center gap-1.5">
                                        <span>View Member Profile</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>

                            </div>
                        </div>

                    @empty
                        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                            <span class="text-4xl block mb-2">🔍</span>
                            <h3 class="font-heading font-black text-lg text-[#0f172a] uppercase">No Alumni Profiles Found</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                                No verified member records match the selected filter criteria. Try clearing search keywords or department filters.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('alumni.index') }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl shadow-md hover:bg-blue-900 font-heading">
                                    <span>Reset Filters</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $alumni->appends(request()->query())->links() }}
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
