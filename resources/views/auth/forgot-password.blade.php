@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden">

    <!-- Subtle Background Top Gradient Strip -->
    <div class="absolute top-0 inset-x-0 h-72 bg-gradient-to-b from-[#0f2042] via-[#1e3a8a] to-transparent opacity-95 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto w-full">

        <!-- Top Header Institutional Badge -->
        <div class="text-center mb-6 text-white drop-shadow-md">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-200 text-[11px] font-bold uppercase tracking-widest font-heading mb-2">
                <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                <span>Government Polytechnic • Estd. 1961</span>
            </div>
            <h1 class="font-heading text-xl sm:text-2xl font-black uppercase tracking-wide">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </h1>
            <p class="text-blue-200 text-xs sm:text-sm font-medium">
                K. D. Polytechnic Alumni &amp; Student Portal &bull; Account Security
            </p>
        </div>

        <!-- Main Recovery Card (Dual Split Government Parity) -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden grid grid-cols-1 md:grid-cols-12">
            
            <!-- Left Panel: Institutional Credentials & Crest -->
            <div class="md:col-span-5 bg-gradient-to-br from-[#1e3a8a] via-[#1c3272] to-[#0f172a] text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
                <!-- Watermark Background -->
                <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                    <img src="{{ asset('images/kdp_crest.png') }}" class="w-56 h-56 object-contain filter invert" alt="KDP Crest Watermark">
                </div>

                <div class="relative z-10">
                    <!-- College Crest & Seal -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-white p-1.5 shadow-md shrink-0 border border-amber-300">
                            <img src="{{ asset('images/kdp_crest.png') }}" alt="K. D. Polytechnic Crest" class="w-full h-full object-contain">
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-white p-1 shadow-md shrink-0 border border-slate-200">
                            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" alt="GTU / CTE Seal" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-500/20 text-orange-300 border border-orange-400/30 mb-2 font-heading">
                        GTU Portal Security Desk
                    </span>

                    <h2 class="font-heading font-black text-xl sm:text-2xl leading-tight mb-2">
                        Credential Recovery &amp;<br>Access Retrieval
                    </h2>
                    
                    <p class="text-blue-200 text-xs leading-relaxed font-sans mb-6">
                        Official credential reset service for diploma alumni, current students, and faculty of K. D. Polytechnic, Patan.
                    </p>

                    <!-- Verification Steps -->
                    <div class="space-y-3 pt-4 border-t border-white/10 text-xs text-blue-100 font-sans">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">✉️</span>
                            <span>Secure reset link sent to your registered email</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">⏱️</span>
                            <span>Link remains valid for 60 minutes</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🔒</span>
                            <span>End-to-end encrypted token verification</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🏛️</span>
                            <span>Institutional identity &amp; record protection</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/10 text-[11px] text-blue-200/80 font-medium">
                    Affiliated to Gujarat Technological University (GTU) &bull; CTE Approved
                </div>
            </div>

            <!-- Right Panel: Password Recovery Form -->
            <div class="md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
                
                <div class="mb-6">
                    <span class="text-[11px] font-black uppercase tracking-widest text-[#f97316] font-heading block mb-1">
                        Account Recovery
                    </span>
                    <h3 class="font-heading font-black text-2xl text-[#0f172a]">
                        Forgot Your Password?
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 font-sans">
                        Enter your registered portal email address below. We will dispatch an official GTU portal password reset link allowing you to choose a new password.
                    </p>
                </div>

                <!-- Session Status Notice -->
                @if (session('status'))
                    <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-3.5 rounded-r-xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
                        <span class="text-sm">✅</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-3.5 rounded-r-xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
                        <span class="text-sm">⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Password Reset Request Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Registered Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                ✉️
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="alumni@kdppatan.ac.in"
                                   class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                        </div>
                        @error('email')
                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                            <span>Email Password Reset Link</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <!-- Navigation & Helper Links -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <a href="{{ route('login') }}" class="text-[#1e3a8a] font-bold hover:text-[#f97316] transition-colors font-heading inline-flex items-center gap-1">
                        <span>&larr;</span>
                        <span>Back to Sign In</span>
                    </a>
                    
                    <a href="{{ route('register') }}" class="text-slate-500 hover:text-slate-800 font-medium">
                        Need an account? <span class="text-[#f97316] font-bold hover:underline font-heading">Apply Here</span>
                    </a>
                </div>

                <!-- Campus Help Desk Note -->
                <div class="mt-6 bg-slate-50 border border-slate-200/80 rounded-xl p-3 text-[11px] text-slate-500 flex items-start gap-2.5">
                    <span class="text-sm">🏛️</span>
                    <div>
                        <strong class="text-slate-700 font-heading">Need institutional support?</strong>
                        <p class="mt-0.5">If you no longer have access to your registered email, contact the Student Section / TPO Cell at K. D. Polytechnic, Patan campus with your GTU Enrollment Number.</p>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection
