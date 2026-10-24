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

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Student Innovation &bull; SSIP Proposal
                </span>
            </div>

            <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </p>
            <h1 class="font-heading text-2xl sm:text-3xl font-black uppercase tracking-wide text-white">
                Pitch Your Engineering Project
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                Submit your diploma final year project, capstone prototype, or startup concept to receive technical mentoring, hardware guidance, or financial sponsorship from KDP alumni.
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. SUBMISSION FORM CONTAINER                   -->
    <!-- ============================================== -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Back Link & Top Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-3 mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('pitches.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#1e3a8a] transition-colors font-heading uppercase tracking-wider">
                <span>&larr;</span>
                <span>Back to Incubation Board</span>
            </a>

            <span class="text-xs text-slate-500 font-sans">
                Logged in as: <strong class="text-slate-800 font-heading">{{ auth()->user()->name }}</strong>
            </span>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-slate-200/90">
            <div class="mb-6 pb-4 border-b border-slate-100">
                <span class="text-xs font-bold text-[#f97316] uppercase tracking-wider font-heading block mb-1">SSIP Incubation Proposal</span>
                <h2 class="text-xl sm:text-2xl font-black font-heading text-[#0f172a] uppercase tracking-wide">
                    New Project Pitch Application
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-sans">
                    Please provide detailed specifications so alumni mentors can quickly assess how best to assist your project.
                </p>
            </div>

            <form action="{{ route('pitches.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Project Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                        Project Title / Headline <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Smart IoT Agricultural Soil Moisture &amp; Drone Monitoring System" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 py-2.5 px-3.5 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                    @error('title') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Technology Stack -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                        Technology Stack / Hardware &amp; Software Used <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="tech_stack" value="{{ old('tech_stack') }}" placeholder="e.g. ESP32, Arduino Nano, Python, MQTT, SolidWorks 3D CAD" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 py-2.5 px-3.5 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-mono">
                    <span class="text-[10px] text-slate-400 mt-1 block font-sans">Separate key frameworks, microcontrollers, and languages with commas.</span>
                    @error('tech_stack') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Assistance Needed -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                        Specific Assistance Requested from Alumni <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="assistance_needed" value="{{ old('assistance_needed') }}" placeholder="e.g. Motor driver heat sinking, PCB Gerber file review, relay wiring guidance" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 py-2.5 px-3.5 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                    <span class="text-[10px] text-slate-400 mt-1 block font-sans">Be specific about what guidance, equipment, or sponsorship is needed.</span>
                    @error('assistance_needed') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Project Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                        Project Description &amp; Problem Statement <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required placeholder="Describe the problem your project solves, target users, current progress, and future scope..." class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 py-2.5 px-3.5 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-sans leading-relaxed">{{ old('description') }}</textarea>
                    @error('description') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Informational Alert -->
                <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200/80 text-xs text-blue-900 leading-relaxed font-sans">
                    <strong>💡 What happens next:</strong> Once submitted, your pitch will be indexed on the Incubation Board with status <em>Open for Mentorship</em>. K. D. Polytechnic alumni and faculty can offer guidance, code reviews, and message you directly.
                </div>

                <!-- Action Controls -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('pitches.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors font-heading uppercase tracking-wider">
                        Cancel
                    </a>
                    <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md font-heading cursor-pointer">
                        Submit Project Pitch &rarr;
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
