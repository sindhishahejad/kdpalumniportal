@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" x-data="{ addModal: false, editFaculty: null }">

    <!-- Breadcrumb & Top Action -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">Faculty &amp; HOD Directory</span>
        </nav>

        @auth
            @if(auth()->user()->role === 'admin')
                <button type="button" @click="addModal = true" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all font-heading cursor-pointer">
                    <span>➕</span>
                    <span>Add Faculty Member</span>
                </button>
            @endif
        @endauth
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-2xs mb-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Institutional Hero Banner (KDP Patan Navy & Saffron) -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] rounded-3xl text-white p-6 sm:p-10 shadow-lg relative overflow-hidden mb-8 border-b-4 border-[#f97316]">
        <div class="relative z-10 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/15 border border-orange-400/30 text-amber-200 text-xs font-bold tracking-wide font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 font-heading">
                    GTU Code: 622
                </span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black font-heading tracking-tight leading-tight">
                Department Faculty &amp; HOD Directory
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Distinguished professors, lecturers, and Heads of Departments mentoring future diploma engineers across Civil, Mechanical, Electrical, Computer, and Applied Sciences.
            </p>
        </div>

        <!-- Crest Watermark -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Department Statistics Counters -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs text-center">
            <span class="text-2xl sm:text-3xl font-black text-[#1e3a8a] font-heading block">{{ $departmentStats['total_faculty'] }}</span>
            <span class="text-xs text-slate-500 font-semibold mt-1">Total Academic Faculty &amp; Lecturers</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs text-center">
            <span class="text-2xl sm:text-3xl font-black text-[#f97316] font-heading block">{{ $departmentStats['hods_count'] }}</span>
            <span class="text-xs text-slate-500 font-semibold mt-1">Heads of Departments (HOD)</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs text-center col-span-2 md:col-span-1">
            <span class="text-2xl sm:text-3xl font-black text-emerald-700 font-heading block">{{ $departmentStats['departments_count'] }} Core</span>
            <span class="text-xs text-slate-500 font-semibold mt-1">GTU Diploma Disciplines</span>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-10">
        <form method="GET" action="{{ route('faculty.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Search Faculty &amp; HODs</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by professor name, qualification, or specialization..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Engineering Department</label>
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Departments</option>
                    <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                    <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                    <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                    <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                    <option value="Applied" {{ request('department') === 'Applied' ? 'selected' : '' }}>🔬 Applied Mechanics</option>
                    <option value="General" {{ request('department') === 'General' ? 'selected' : '' }}>📖 General (Science &amp; Humanities)</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'department']))
                    <a href="{{ route('faculty.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold" title="Reset Filters">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- SECTION 1: HEADS OF DEPARTMENTS (HODs) -->
    @if($hods->isNotEmpty())
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-xl">👑</span>
                <h2 class="text-xl sm:text-2xl font-black font-heading text-[#0f172a] uppercase tracking-tight">
                    Heads of Departments (HOD)
                </h2>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                    {{ $hods->count() }} Leaders
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hods as $hod)
                    <div class="bg-white rounded-2xl shadow-xs border-2 border-amber-300/80 p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative overflow-hidden group">
                        <!-- Top Accent Banner -->
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-400 via-orange-400 to-[#1e3a8a]"></div>

                        <div>
                            <div class="flex items-start justify-between gap-3 mb-4 mt-1">
                                <div class="w-14 h-14 rounded-xl bg-[#1e3a8a] text-white flex items-center justify-center text-xl font-black font-heading shrink-0 shadow-xs">
                                    {{ substr($hod->name, 0, 1) }}
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full font-heading flex items-center gap-1">
                                    <span>👑</span> Head of Dept.
                                </span>
                            </div>

                            <h3 class="font-heading font-black text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors leading-snug">
                                {{ $hod->name }}
                            </h3>

                            <p class="text-xs font-bold text-[#f97316] mb-1 font-heading">
                                {{ $hod->designation }}
                            </p>

                            <span class="inline-block text-[11px] font-semibold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200 mb-3">
                                {{ $hod->department }}
                            </span>

                            <div class="space-y-2 text-xs text-slate-600 mb-4 font-sans">
                                <p><strong class="text-slate-800 font-heading">Qualification:</strong> {{ $hod->qualification }}</p>
                                @if($hod->experience_years)
                                    <p><strong class="text-slate-800 font-heading">Experience:</strong> {{ $hod->experience_years }} Years Academic &amp; Industry</p>
                                @endif
                                @if($hod->specialization)
                                    <p><strong class="text-slate-800 font-heading">Focus Area:</strong> {{ $hod->specialization }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Contact & Admin Controls -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="text-xs text-slate-500 truncate">
                                @if($hod->email)
                                    <a href="mailto:{{ $hod->email }}" class="text-[#1e3a8a] hover:underline font-bold flex items-center gap-1">
                                        <span>✉️</span>
                                        <span>{{ $hod->email }}</span>
                                    </a>
                                @endif
                            </div>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" @click="editFaculty = {{ json_encode($hod) }}" class="p-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer" title="Edit HOD">
                                            ✏️
                                        </button>
                                        <form action="{{ route('admin.faculty.destroy', $hod) }}" method="POST" onsubmit="return confirm('Remove HOD from directory?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer" title="Delete">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- SECTION 2: ACADEMIC LECTURERS & PROFESSORS -->
    @if($faculty->isNotEmpty())
        <div class="mb-14">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-xl">📚</span>
                <h2 class="text-xl sm:text-2xl font-black font-heading text-[#0f172a] uppercase tracking-tight">
                    Academic Lecturers &amp; Teaching Faculty
                </h2>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                    {{ $faculty->count() }} Faculty Members
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($faculty as $member)
                    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-6 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#1e3a8a] flex items-center justify-center text-lg font-black font-heading shrink-0 border border-slate-200">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-[#1e3a8a] border border-blue-100">
                                    {{ $member->department }}
                                </span>
                            </div>

                            <h3 class="font-heading font-black text-base text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors leading-snug mb-1">
                                {{ $member->name }}
                            </h3>

                            <p class="text-xs font-semibold text-slate-600 mb-2">
                                {{ $member->designation }}
                            </p>

                            <div class="space-y-1.5 text-xs text-slate-500 mb-4 font-sans">
                                <p><strong class="text-slate-700">Qualification:</strong> {{ $member->qualification }}</p>
                                @if($member->experience_years)
                                    <p><strong class="text-slate-700">Experience:</strong> {{ $member->experience_years }} Years</p>
                                @endif
                                @if($member->specialization)
                                    <p class="line-clamp-2"><strong class="text-slate-700">Teaching Focus:</strong> {{ $member->specialization }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="text-xs text-slate-500 truncate">
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="text-[#1e3a8a] hover:underline font-semibold flex items-center gap-1 text-[11px]">
                                        <span>✉️</span>
                                        <span>{{ $member->email }}</span>
                                    </a>
                                @endif
                            </div>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" @click="editFaculty = {{ json_encode($member) }}" class="p-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer" title="Edit">
                                            ✏️
                                        </button>
                                        <form action="{{ route('admin.faculty.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove faculty member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer" title="Delete">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($allMembers->isEmpty())
        <div class="py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 p-8">
            <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                👥
            </div>
            <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Faculty Members Found</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto font-sans">
                No faculty profiles match the selected department or query criteria.
            </p>
        </div>
    @endif

    <!-- Admin Add Faculty Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="addModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">👥</span>
                    <h3 class="font-bold text-base font-heading">Add Faculty Member</h3>
                </div>
                <button type="button" @click="addModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.faculty.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Faculty Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Prof. R. K. Patel" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Designation <span class="text-red-500">*</span></label>
                        <input type="text" name="designation" required placeholder="e.g. Lecturer in Computer" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Applied Mechanics">Applied Mechanics</option>
                            <option value="General (Science & Humanities)">General (Science & Humanities)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Qualification <span class="text-red-500">*</span></label>
                        <input type="text" name="qualification" required placeholder="e.g. M.E. (Computer Engg)" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Experience (Years)</label>
                        <input type="number" name="experience_years" placeholder="e.g. 12" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Specialization / Subjects Taught</label>
                    <input type="text" name="specialization" placeholder="e.g. Operating Systems, Network Security" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Official Email</label>
                        <input type="email" name="email" placeholder="faculty@kdppatan.ac.in" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Office Phone</label>
                        <input type="text" name="phone" placeholder="+91 2766 220419" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_hod" value="1" class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                        <span class="text-xs font-bold text-amber-900">👑 Mark as Head of Department (HOD)</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Add Faculty
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Edit Faculty Modal -->
    <div x-show="editFaculty !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editFaculty = null" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✏️</span>
                    <h3 class="font-bold text-base font-heading">Edit Faculty Member</h3>
                </div>
                <button type="button" @click="editFaculty = null" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/admin/faculty/' + (editFaculty ? editFaculty.id : '')" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Faculty Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editFaculty.name" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Designation <span class="text-red-500">*</span></label>
                        <input type="text" name="designation" x-model="editFaculty.designation" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department" x-model="editFaculty.department" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Applied Mechanics">Applied Mechanics</option>
                            <option value="General (Science & Humanities)">General (Science & Humanities)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Qualification <span class="text-red-500">*</span></label>
                        <input type="text" name="qualification" x-model="editFaculty.qualification" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Experience (Years)</label>
                        <input type="number" name="experience_years" x-model="editFaculty.experience_years" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Specialization</label>
                    <input type="text" name="specialization" x-model="editFaculty.specialization" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editFaculty.email" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone</label>
                        <input type="text" name="phone" x-model="editFaculty.phone" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_hod" value="1" :checked="editFaculty && editFaculty.is_hod" class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                        <span class="text-xs font-bold text-amber-900">👑 Mark as Head of Department (HOD)</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editFaculty = null" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
