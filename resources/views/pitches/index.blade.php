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
                    Student Innovation Hub &bull; SSIP Cell
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>💡</span> {{ $stats['open'] }} Open for Mentorship
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                        Project Incubation &amp; Pitch Board
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                        Connect student innovators with distinguished alumni industry veterans and faculty mentors for hardware support, code reviews, CAD design feedback, and project sponsorship.
                    </p>
                </div>

                <!-- Action Button & Incubation Info Box -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-2xl p-4 text-xs max-w-xs text-blue-100 hidden sm:block">
                        <div class="flex items-center gap-2 font-bold text-white uppercase font-heading mb-1 text-[11px]">
                            <span>🏛️</span> SSIP &amp; Innovation Cell
                        </div>
                        <p class="text-[11px] leading-relaxed text-blue-200/90">
                            Empowering GTU diploma engineering students with direct access to alumni mentors and real-world project incubation.
                        </p>
                    </div>

                    @if(auth()->user()->role === 'student' || auth()->user()->role === 'faculty' || auth()->user()->isAdmin())
                        <a href="{{ route('pitches.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#f97316] hover:bg-orange-600 text-white text-xs font-bold py-2.5 px-5 rounded-xl shadow-md transition-all font-heading uppercase tracking-wider">
                            <span>+ Pitch a Project</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-base">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-base">&times;</button>
            </div>
        @endif

        <!-- Stats Metric Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- All Pitches -->
            <a href="{{ route('pitches.index') }}" 
               class="bg-white p-5 rounded-2xl border transition-all duration-300 shadow-sm hover:shadow-md {{ !request('status') && !request('my_pitches') ? 'border-[#1e3a8a] ring-2 ring-[#1e3a8a]/20' : 'border-slate-200/90 hover:border-[#1e3a8a]' }}">
                <span class="text-[11px] uppercase font-bold text-slate-500 font-heading block">All Innovations</span>
                <p class="text-2xl sm:text-3xl font-black text-[#0f172a] mt-1 font-heading">{{ $stats['total'] }}</p>
                <span class="text-[10px] text-slate-400 mt-1 block">Full student pitch registry</span>
            </a>

            <!-- Open for Mentorship -->
            <a href="{{ route('pitches.index', ['status' => 'open']) }}" 
               class="bg-white p-5 rounded-2xl border transition-all duration-300 shadow-sm hover:shadow-md {{ request('status') === 'open' ? 'border-emerald-600 ring-2 ring-emerald-600/20' : 'border-slate-200/90 hover:border-emerald-500' }}">
                <span class="text-[11px] uppercase font-bold text-emerald-700 flex items-center gap-1.5 font-heading">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Open for Mentorship
                </span>
                <p class="text-2xl sm:text-3xl font-black text-emerald-800 mt-1 font-heading">{{ $stats['open'] }}</p>
                <span class="text-[10px] text-emerald-600 mt-1 block">Seeking guidance &amp; help</span>
            </a>

            <!-- In Progress -->
            <a href="{{ route('pitches.index', ['status' => 'in_progress']) }}" 
               class="bg-white p-5 rounded-2xl border transition-all duration-300 shadow-sm hover:shadow-md {{ request('status') === 'in_progress' ? 'border-blue-600 ring-2 ring-blue-600/20' : 'border-slate-200/90 hover:border-blue-500' }}">
                <span class="text-[11px] uppercase font-bold text-[#1e3a8a] flex items-center gap-1.5 font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#1e3a8a]"></span>
                    In Progress
                </span>
                <p class="text-2xl sm:text-3xl font-black text-[#1e3a8a] mt-1 font-heading">{{ $stats['in_progress'] }}</p>
                <span class="text-[10px] text-blue-600 mt-1 block">Active mentoring underway</span>
            </a>

            <!-- Completed / Incubated -->
            <a href="{{ route('pitches.index', ['status' => 'completed']) }}" 
               class="bg-white p-5 rounded-2xl border transition-all duration-300 shadow-sm hover:shadow-md {{ request('status') === 'completed' ? 'border-purple-600 ring-2 ring-purple-600/20' : 'border-slate-200/90 hover:border-purple-500' }}">
                <span class="text-[11px] uppercase font-bold text-purple-700 flex items-center gap-1.5 font-heading">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    Incubated / Complete
                </span>
                <p class="text-2xl sm:text-3xl font-black text-purple-900 mt-1 font-heading">{{ $stats['completed'] }}</p>
                <span class="text-[10px] text-purple-600 mt-1 block">Successfully finalized</span>
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('pitches.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('my_pitches'))
                    <input type="hidden" name="my_pitches" value="1">
                @endif

                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, student, tech stack (e.g. IoT, Python, PLC)..." class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] shadow-xs font-sans">
                </div>
                
                <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading cursor-pointer shrink-0">
                    Search Pitches
                </button>
                @if(request()->anyFilled(['search', 'status', 'my_pitches']))
                    <a href="{{ route('pitches.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold transition-colors text-center shrink-0" title="Clear Filters">
                        ✕ Clear
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                @auth
                    @if(request('my_pitches'))
                        <a href="{{ route('pitches.index') }}" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors font-heading">
                            View All Pitches
                        </a>
                    @else
                        <a href="{{ route('pitches.index', ['my_pitches' => 1]) }}" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-orange-50 text-[#f97316] hover:bg-orange-100 border border-orange-200 transition-colors font-heading flex items-center gap-1.5">
                            <span>👤</span>
                            <span>Show My Pitches</span>
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Pitches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($pitches as $pitch)
                @php
                    $statusStyles = [
                        'open' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Open for Mentorship'],
                        'in_progress' => ['bg' => 'bg-blue-50 text-[#1e3a8a] border-blue-200', 'dot' => 'bg-[#1e3a8a]', 'label' => 'In Progress / Mentoring'],
                        'completed' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'dot' => 'bg-purple-500', 'label' => 'Completed / Incubated'],
                    ][$pitch->status] ?? ['bg' => 'bg-slate-100 text-slate-800 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($pitch->status)];
                @endphp

                <div class="bg-white border border-slate-200/90 rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div>
                        <!-- Header Strip -->
                        <div class="h-16 bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] p-4 flex items-center justify-between text-white relative">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusStyles['bg'] }} font-heading">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $statusStyles['label'] }}
                            </span>
                            <span class="text-[11px] text-blue-200 font-mono">{{ $pitch->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="p-6">
                            <!-- Title -->
                            <h2 class="font-heading font-black text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors mb-2 line-clamp-2 leading-snug">
                                <a href="{{ route('pitches.show', $pitch->id) }}">
                                    {{ $pitch->title }}
                                </a>
                            </h2>

                            <!-- Student Creator Byline -->
                            <div class="flex items-center gap-2 mb-4 text-xs text-slate-500 flex-wrap">
                                <span class="w-6 h-6 rounded-full bg-[#1e3a8a] text-white flex items-center justify-center font-bold text-[10px] font-heading shrink-0">
                                    {{ substr(optional($pitch->student)->name ?? 'S', 0, 1) }}
                                </span>
                                <span>Pitch by: <strong class="text-slate-800 font-heading">{{ $pitch->student->name ?? 'Student Innovator' }}</strong></span>
                                @if(optional($pitch->student)->department)
                                    <span>&bull;</span>
                                    <span class="text-slate-600 font-medium">{{ $pitch->student->department }}</span>
                                @endif
                            </div>

                            <!-- Description Snippet -->
                            <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed font-sans bg-slate-50/50 p-3 rounded-2xl border border-slate-100">
                                {{ $pitch->description }}
                            </p>

                            <!-- Tech Stack Tags -->
                            <div class="mb-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5 font-heading">Technologies &amp; Frameworks</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(array_map('trim', explode(',', $pitch->tech_stack)) as $tag)
                                        <span class="bg-blue-50/70 border border-blue-200/80 text-[#1e3a8a] text-[10px] font-bold px-2 py-0.5 rounded-lg font-mono">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Assistance Needed -->
                            <div>
                                <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1 font-heading">Specific Assistance Needed:</span>
                                <p class="text-xs text-slate-800 bg-orange-50/70 border border-orange-200/80 rounded-xl p-2.5 font-medium leading-relaxed">
                                    {{ $pitch->assistance_needed }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('pitches.show', $pitch->id) }}" class="text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] inline-flex items-center gap-1 font-heading transition-colors uppercase tracking-wider">
                            <span>View Details</span>
                            <span>&rarr;</span>
                        </a>

                        @if(auth()->user()->role === 'alumni' || auth()->user()->role === 'faculty')
                            @if($pitch->user_id !== auth()->id())
                                <a href="{{ route('pitches.show', $pitch->id) }}#guidance" class="bg-[#1e3a8a] hover:bg-blue-900 text-white px-3 py-1.5 rounded-xl text-xs font-bold shadow-xs transition-colors font-heading uppercase tracking-wider">
                                    🤝 Offer Guidance
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 px-6 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-50 text-[#1e3a8a] rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-blue-100">
                        💡
                    </div>
                    <h3 class="text-lg font-black text-[#0f172a] mb-1 font-heading uppercase tracking-wide">No Project Pitches Found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-5 font-sans leading-relaxed">
                        Try clearing your search query or submit the first student project pitch to connect with alumni mentors.
                    </p>
                    @if(auth()->user()->role === 'student' || auth()->user()->role === 'faculty' || auth()->user()->isAdmin())
                        <a href="{{ route('pitches.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition-colors font-heading cursor-pointer">
                            + Pitch Your Project
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pitches->links() }}
        </div>

    </div>
</div>
@endsection
