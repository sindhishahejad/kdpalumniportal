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
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200 text-[11px] font-bold font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316]"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Administrative &amp; Student Section
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Government of Gujarat &bull; CTE
                </span>
            </div>

            <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white leading-tight">
                Connect with K. D. Polytechnic, Patan
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-2 leading-relaxed font-sans">
                Get in touch with the college administration, student affairs section, alumni committee, or training and placement office. We are here to support your engineering journey.
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN CONTACT WORKSPACE                      -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Official Campus Contact Details (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Campus Address Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm space-y-5">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#f97316] font-heading block">
                            Campus Location &amp; Secretariat
                        </span>
                        <h3 class="font-heading font-black text-lg text-slate-900 uppercase tracking-wide mt-0.5">
                            Official Address
                        </h3>
                    </div>

                    <div class="space-y-4 text-xs font-sans text-slate-700">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center text-base shrink-0 mt-0.5">
                                📍
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 block font-heading text-xs uppercase tracking-wide">Campus Venue</span>
                                <p class="text-slate-600 mt-0.5 leading-relaxed">
                                    K. D. Polytechnic (Kilachand Devchand Polytechnic),<br>
                                    Opposite T.B. Hospital, Near Railway Station,<br>
                                    Patan - 384265, Gujarat, India.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#f97316] flex items-center justify-center text-base shrink-0 mt-0.5">
                                📞
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 block font-heading text-xs uppercase tracking-wide">Phone (Office)</span>
                                <a href="tel:02766220419" class="text-[#1e3a8a] hover:text-[#f97316] font-bold font-mono transition-colors">
                                    02766-220419
                                </a>
                                <p class="text-[11px] text-slate-400 mt-0.5">Direct Administrative Exchange</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-base shrink-0 mt-0.5">
                                ✉️
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 block font-heading text-xs uppercase tracking-wide">Official Email</span>
                                <a href="mailto:kdp-patan-dte@gujarat.gov.in" class="text-[#1e3a8a] hover:text-[#f97316] font-semibold transition-colors">
                                    kdp-patan-dte@gujarat.gov.in
                                </a>
                                <p class="text-[11px] text-slate-400 mt-0.5">Department of Technical Education, Gujarat</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-base shrink-0 mt-0.5">
                                🕒
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 block font-heading text-xs uppercase tracking-wide">Office Hours</span>
                                <p class="text-slate-600 mt-0.5 leading-relaxed">
                                    <strong>Monday – Friday:</strong> 10:30 AM – 05:00 PM<br>
                                    <strong>Student Section Window:</strong> 11:00 AM – 02:00 PM<br>
                                    <span class="text-slate-400 text-[11px]">Closed on 2nd &amp; 4th Saturdays, Sundays &amp; Public Holidays</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instant AI & Document Desk Shortcuts -->
                <div class="bg-gradient-to-br from-[#0f2042] to-[#1e3a8a] rounded-3xl p-6 text-white shadow-md border border-blue-900/50 space-y-4">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-orange-300 font-heading block">
                            Fast Digital Resolution
                        </span>
                        <h4 class="font-heading font-black text-sm text-white uppercase tracking-wide mt-0.5">
                            Need Quick Assistance?
                        </h4>
                    </div>

                    <div class="space-y-2.5">
                        <a href="{{ route('chatbot.index') }}" class="block p-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 transition-all group">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-xs text-white flex items-center gap-2">
                                    <span>🤖</span> KDP Mitra (AI Counselor)
                                </span>
                                <span class="text-xs text-orange-300 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                            <p class="text-[11px] text-blue-100 mt-1 font-sans">
                                24/7 instant answers for GTU old papers, results date, transcripts, and syllabus.
                            </p>
                        </a>

                        <a href="{{ route('documents.index') }}" class="block p-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 transition-all group">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-xs text-white flex items-center gap-2">
                                    <span>📜</span> Official Document Desk
                                </span>
                                <span class="text-xs text-orange-300 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                            <p class="text-[11px] text-blue-100 mt-1 font-sans">
                                Apply online for official transcripts, bonafide, and letters of recommendation.
                            </p>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Column: Direct Contact Form (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#f97316] font-heading block">
                        Direct Inquiries
                    </span>
                    <h3 class="font-heading font-black text-lg text-slate-900 uppercase tracking-wide mt-0.5">
                        Send a Message to College Secretariat
                    </h3>
                    <p class="text-xs text-slate-500 font-sans mt-1">
                        Fill in your details below and our administrative office will reply via email within 1-2 working days.
                    </p>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Your Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required 
                                   placeholder="e.g. Patel Rajeshbhai" 
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans">
                            @error('name') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required 
                                   placeholder="e.g. rajesh@example.com" 
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans">
                            @error('email') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Subject / Purpose <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required 
                               placeholder="e.g. Inquiry regarding transcript verification or alumni meet" 
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans">
                        @error('subject') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Detailed Message -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Detailed Message <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" rows="5" required 
                                  placeholder="Please describe your inquiry, enrollment number (if applicable), and any specific assistance required..." 
                                  class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans leading-relaxed">{{ old('message') }}</textarea>
                        @error('message') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-bold text-xs uppercase tracking-wider py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                            <span>✉️</span>
                            <span>Send Official Message to Secretariat</span>
                        </button>
                    </div>

                    <p class="text-[10px] text-slate-400 text-center font-sans">
                        Official correspondence &bull; Monitored by KDP Patan Student Affairs Section
                    </p>
                </form>

            </div>

        </div>

    </div>

</div>
@endsection
