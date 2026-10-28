@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" x-data="{ addModal: false, editSchedule: null }">

    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">GTU Academic Calendar &amp; Circulars</span>
        </nav>

        @auth
            @if(auth()->user()->role === 'admin')
                <button type="button" @click="addModal = true" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all font-heading cursor-pointer">
                    <span>➕</span>
                    <span>Add Schedule / Circular</span>
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
                GTU Academic Calendar &amp; Examination Circulars
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Official Gujarat Technological University diploma examination schedules, progressive assessment term dates, re-check circulars, and lateral D2D engineering admissions notices.
            </p>
        </div>

        <!-- Crest Watermark -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Official GTU Portals Quick Access Strip -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs mb-8">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3 font-heading">
            🔗 Official GTU University Fast Links
        </span>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="https://student.gtu.ac.in" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-[#1e3a8a] transition-all flex items-center gap-2 group">
                <span class="text-xl">🎓</span>
                <div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-[#1e3a8a] block font-heading">GTU Student Portal</span>
                    <span class="text-[10px] text-slate-400">student.gtu.ac.in &nearr;</span>
                </div>
            </a>

            <a href="https://result.gtu.ac.in" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-600 transition-all flex items-center gap-2 group">
                <span class="text-xl">📊</span>
                <div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block font-heading">GTU Exam Results</span>
                    <span class="text-[10px] text-slate-400">result.gtu.ac.in &nearr;</span>
                </div>
            </a>

            <a href="https://www.gtu.ac.in" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-[#f97316] transition-all flex items-center gap-2 group">
                <span class="text-xl">📜</span>
                <div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-[#f97316] block font-heading">Circulars &amp; Timetables</span>
                    <span class="text-[10px] text-slate-400">gtu.ac.in/circular.aspx &nearr;</span>
                </div>
            </a>

            <a href="https://jacpcldce.ac.in" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-600 transition-all flex items-center gap-2 group">
                <span class="text-xl">🏛️</span>
                <div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-purple-700 block font-heading">ACPC D2D Degree</span>
                    <span class="text-[10px] text-slate-400">jacpcldce.ac.in &nearr;</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Category Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-8">
        <form method="GET" action="{{ route('calendar.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Search Schedule &amp; Circulars</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exam title, term date, or circular topic..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Schedule Category</label>
                <select name="category" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Categories</option>
                    <option value="exam" {{ request('category') === 'exam' ? 'selected' : '' }}>📝 GTU Examinations</option>
                    <option value="term" {{ request('category') === 'term' ? 'selected' : '' }}>🗓️ Academic Term Dates</option>
                    <option value="circular" {{ request('category') === 'circular' ? 'selected' : '' }}>📜 University Circulars</option>
                    <option value="holiday" {{ request('category') === 'holiday' ? 'selected' : '' }}>🏖️ Academic Holidays</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'category']))
                    <a href="{{ route('calendar.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold" title="Reset Filters">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Schedules Timeline Feed -->
    <div class="space-y-4 mb-14">
        @forelse($schedules as $item)
            @php $badge = $item->category_badge; @endphp
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 hover:shadow-md transition-all flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $item->is_important ? 'border-l-4 border-l-[#f97316]' : 'border-l-4 border-l-[#1e3a8a]' }}">
                
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <!-- Date Box -->
                    <div class="shrink-0 w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-center p-1">
                        <span class="text-[10px] font-bold uppercase text-slate-400 font-heading">
                            {{ $item->start_date->format('M') }}
                        </span>
                        <span class="text-xl font-black text-[#0f172a] font-heading leading-none">
                            {{ $item->start_date->format('d') }}
                        </span>
                        <span class="text-[9px] text-slate-500 font-medium">
                            {{ $item->start_date->format('Y') }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $badge['color'] }} font-heading flex items-center gap-1">
                                <span>{{ $badge['icon'] }}</span>
                                <span>{{ $badge['label'] }}</span>
                            </span>

                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-heading">
                                🎓 {{ $item->semester_scope }}
                            </span>

                            @if($item->is_important)
                                <span class="text-[9px] font-black uppercase tracking-wider text-[#f97316] bg-orange-50 px-2 py-0.5 rounded border border-orange-200 font-heading animate-pulse">
                                    ★ Urgent Notice
                                </span>
                            @endif
                        </div>

                        <h3 class="font-heading font-black text-base sm:text-lg text-[#0f172a] leading-snug">
                            {{ $item->title }}
                        </h3>

                        @if($item->end_date)
                            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                🗓️ Duration: <span class="text-slate-700">{{ $item->start_date->format('d M, Y') }}</span> to <span class="text-slate-700">{{ $item->end_date->format('d M, Y') }}</span>
                            </p>
                        @endif

                        @if($item->description)
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed font-sans line-clamp-2">
                                {{ $item->description }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Action Button & Admin Controls -->
                <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                    @if($item->attachment_url)
                        <a href="{{ $item->attachment_url }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors font-heading flex items-center gap-1">
                            <span>📄 View Circular</span>
                            <span>&nearr;</span>
                        </a>
                    @endif

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <button type="button" @click="editSchedule = {{ json_encode($item) }}" class="p-2 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors cursor-pointer" title="Edit">
                                ✏️
                            </button>
                            <form action="{{ route('admin.calendar.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this schedule entry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer" title="Delete">
                                    🗑️
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

            </div>
        @empty
            <div class="py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                    🗓️
                </div>
                <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Academic Schedules Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto font-sans">
                    No active examinations or academic circulars match the selected category.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Admin Add Schedule Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="addModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🗓️</span>
                    <h3 class="font-bold text-base font-heading">Add GTU Schedule / Circular</h3>
                </div>
                <button type="button" @click="addModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.calendar.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Title / Circular Headline <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. GTU Diploma Winter 2026 Theory Exam Schedule" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="exam">📝 GTU Examination</option>
                            <option value="term">🗓️ Academic Term Date</option>
                            <option value="circular">📜 University Circular</option>
                            <option value="holiday">🏖️ Academic Holiday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Semester Scope <span class="text-red-500">*</span></label>
                        <input type="text" name="semester_scope" required placeholder="e.g. Sem 1, 3, 5 or All Semesters" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">End Date (Optional)</label>
                        <input type="date" name="end_date" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description / Key Notes</label>
                    <textarea name="description" rows="3" placeholder="Provide instructions for hall tickets, fee deadlines, or reporting..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Circular Link / URL (Optional)</label>
                    <input type="url" name="attachment_url" placeholder="https://www.gtu.ac.in/..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="p-3 bg-orange-50 rounded-xl border border-orange-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_important" value="1" class="h-4 w-4 text-[#f97316] border-slate-300 rounded focus:ring-[#f97316]">
                        <span class="text-xs font-bold text-orange-900">★ Mark as High-Priority / Urgent Announcement</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Save Entry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Edit Schedule Modal -->
    <div x-show="editSchedule !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editSchedule = null" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✏️</span>
                    <h3 class="font-bold text-base font-heading">Edit GTU Schedule / Circular</h3>
                </div>
                <button type="button" @click="editSchedule = null" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/admin/academic-calendar/' + (editSchedule ? editSchedule.id : '')" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Headline <span class="text-red-500">*</span></label>
                    <input type="text" name="title" x-model="editSchedule.title" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="category" x-model="editSchedule.category" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="exam">📝 GTU Examination</option>
                            <option value="term">🗓️ Academic Term Date</option>
                            <option value="circular">📜 University Circular</option>
                            <option value="holiday">🏖️ Academic Holiday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Semester Scope <span class="text-red-500">*</span></label>
                        <input type="text" name="semester_scope" x-model="editSchedule.semester_scope" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" x-model="editSchedule.start_date ? editSchedule.start_date.substring(0, 10) : ''" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">End Date</label>
                        <input type="date" name="end_date" x-model="editSchedule.end_date ? editSchedule.end_date.substring(0, 10) : ''" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="3" x-model="editSchedule.description" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Circular URL</label>
                    <input type="url" name="attachment_url" x-model="editSchedule.attachment_url" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="p-3 bg-orange-50 rounded-xl border border-orange-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_important" value="1" :checked="editSchedule && editSchedule.is_important" class="h-4 w-4 text-[#f97316] border-slate-300 rounded focus:ring-[#f97316]">
                        <span class="text-xs font-bold text-orange-900">★ Mark as High-Priority / Urgent Announcement</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editSchedule = null" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
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
