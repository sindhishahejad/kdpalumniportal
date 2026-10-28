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

        <div class="max-w-7xl mx-auto relative z-10 space-y-4">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-semibold text-blue-200 uppercase tracking-wider font-heading">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Portal Home</a>
                <span>&rarr;</span>
                <a href="{{ route('elibrary.index') }}" class="hover:text-white transition-colors">Digital E-Library</a>
                <span>&rarr;</span>
                <span class="text-white truncate max-w-sm">{{ $item->title }}</span>
            </div>

            <!-- Badges Row -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wide border border-amber-500/30 font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    {{ $item->category_label }}
                </span>
                <span class="px-2.5 py-1 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-xs font-bold font-heading">
                    {{ $item->department_icon }} {{ $item->department }} Engineering
                </span>
                @if($item->semester)
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20 text-xs font-bold font-heading">
                        Semester {{ $item->semester }}
                    </span>
                @endif
                @if($item->academic_year)
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20 text-xs font-bold font-heading">
                        Academic Year: {{ $item->academic_year }}
                    </span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-black uppercase tracking-wide text-white leading-snug max-w-4xl">
                {{ $item->title }}
            </h1>

            @if($item->subject_code)
                <p class="text-xs sm:text-sm text-blue-200 font-mono font-bold">
                    GTU Course Code: {{ $item->subject_code }} &bull; {{ $item->subject_name }}
                </p>
            @endif
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN CONTENT GRID                           -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Main Column (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Abstract & Synopsis Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h2 class="font-heading font-black text-lg text-slate-900 uppercase tracking-wide flex items-center gap-2">
                            <span>📋</span>
                            <span>Abstract &amp; Technical Overview</span>
                        </h2>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-heading">
                            Verified Repository Item
                        </span>
                    </div>

                    <div class="prose max-w-none text-slate-700 leading-relaxed font-sans text-sm sm:text-base space-y-4 whitespace-pre-line">
                        {{ $item->description }}
                    </div>

                    <!-- Tech Stack / Methodologies -->
                    @if($item->tech_stack)
                        <div class="pt-4 border-t border-slate-100">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2 font-heading">
                                Technical Tools, Hardware &amp; Libraries:
                            </span>
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach(explode(',', $item->tech_stack) as $tech)
                                    <span class="bg-slate-50 border border-slate-200 text-slate-800 text-xs px-3 py-1 rounded-xl font-mono font-semibold">
                                        {{ trim($tech) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Academic Team & Faculty Guide Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
                    <h3 class="font-heading font-black text-base text-slate-900 uppercase tracking-wide flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span>👥</span>
                        <span>Authors, Project Team &amp; Faculty Mentorship</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                            <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider font-heading block mb-1">
                                Author / Student Capstone Team:
                            </span>
                            <p class="font-bold text-slate-900 text-sm font-heading">
                                {{ $item->author_or_team ?: 'KDP Polytechnic Academic Cell' }}
                            </p>
                            <p class="text-slate-500 mt-1 font-sans">Department of {{ $item->department }} Engineering</p>
                        </div>

                        <div class="bg-amber-50/60 rounded-2xl p-4 border border-amber-200/80">
                            <span class="text-[10px] font-bold uppercase text-amber-800 tracking-wider font-heading block mb-1">
                                Institutional Faculty Guide:
                            </span>
                            <p class="font-bold text-amber-950 text-sm font-heading">
                                {{ $item->guide_faculty ?: 'Department Head & Lab Faculty Committee' }}
                            </p>
                            <p class="text-amber-800/80 mt-1 font-sans">K. D. Polytechnic, Patan &bull; Code: 622</p>
                        </div>
                    </div>
                </div>

                <!-- Related Resources -->
                @if($relatedItems->isNotEmpty())
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                        <h3 class="font-heading font-black text-sm text-slate-900 uppercase tracking-wide">
                            Related {{ $item->department }} Materials
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($relatedItems as $related)
                                <a href="{{ route('elibrary.show', $related->slug) }}" class="p-4 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200 hover:border-[#1e3a8a]/40 transition-all flex flex-col justify-between group">
                                    <div>
                                        <span class="text-[10px] font-black uppercase text-[#1e3a8a] font-heading block">
                                            {{ $related->category_label }}
                                        </span>
                                        <h4 class="font-heading font-bold text-xs text-slate-900 group-hover:text-[#1e3a8a] mt-1 line-clamp-2">
                                            {{ $related->title }}
                                        </h4>
                                    </div>
                                    <div class="mt-3 text-[10px] text-slate-400 font-medium flex items-center justify-between">
                                        <span>📥 {{ $related->downloads_count }} downloads</span>
                                        <span class="text-[#1e3a8a] font-bold group-hover:translate-x-1 transition-transform">&rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sidebar Column (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Action & Download Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-lg space-y-5 sticky top-6">
                    <div class="text-center space-y-1 pb-4 border-b border-slate-100">
                        <span class="text-4xl block mb-1">📥</span>
                        <div class="text-2xl font-black text-slate-900 font-heading">
                            {{ $item->downloads_count }}
                        </div>
                        <p class="text-xs text-slate-400 font-medium">Verified Student Downloads</p>
                    </div>

                    <div class="space-y-2.5">
                        <a href="{{ route('elibrary.download', $item->id) }}" 
                           class="w-full bg-[#0f2042] hover:bg-[#1e3a8a] text-white font-heading font-bold py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Download Full Document</span>
                        </a>

                        @if($item->external_url)
                            <a href="{{ $item->external_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-heading font-bold py-3 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 cursor-pointer border border-slate-200">
                                <span>🌐</span>
                                <span>Open External Link / Drive</span>
                            </a>
                        @endif
                    </div>

                    <!-- Document Metadata List -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="font-medium">File Format:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $item->file_format }}</span>
                        </div>
                        @if($item->file_size)
                            <div class="flex items-center justify-between text-slate-600">
                                <span class="font-medium">File Size:</span>
                                <span class="font-bold text-slate-900 font-mono">{{ $item->file_size }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="font-medium">Added to Vault:</span>
                            <span class="font-bold text-slate-900">{{ $item->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="font-medium">GTU Code:</span>
                            <span class="font-bold text-slate-900 font-mono">622</span>
                        </div>
                    </div>

                    <!-- Delete button if Owner or Admin -->
                    @auth
                        @if(auth()->id() === $item->user_id || auth()->user()->isAdmin())
                            <div class="pt-4 border-t border-slate-100">
                                <form action="{{ route('elibrary.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this resource from the E-Library?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 py-2.5 px-3 rounded-xl text-xs font-bold font-heading transition-colors cursor-pointer">
                                        Delete from Vault
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Institutional Support Box -->
                <div class="bg-gradient-to-br from-[#0f2042] via-[#1e3a8a] to-[#0f172a] rounded-3xl p-6 text-white shadow-md border border-blue-900/40 space-y-3">
                    <span class="text-xl">🏛️</span>
                    <h4 class="font-heading font-black text-sm uppercase tracking-wide">
                        Academic Guidelines
                    </h4>
                    <p class="text-xs text-blue-100 leading-relaxed font-light">
                        Materials published in this vault are open educational resources intended for personal study, laboratory experiments, and capstone research for diploma engineers of K. D. Polytechnic, Patan.
                    </p>
                    <div class="pt-2 border-t border-white/10 text-[10px] text-amber-300 font-semibold flex items-center justify-between">
                        <span>કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ</span>
                        <span>Estd. 1961</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
