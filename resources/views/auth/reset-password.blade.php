@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden" x-data="{ showPass: false, showConfirmPass: false }">

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
                K. D. Polytechnic Alumni &amp; Student Portal &bull; Set New Password
            </p>
        </div>

        <!-- Main Reset Card (Dual Split Government Parity) -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden grid grid-cols-1 md:grid-cols-12">
            
            <!-- Left Panel: Institutional Security Guidance -->
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
                        Portal Security Standard
                    </span>

                    <h2 class="font-heading font-black text-xl sm:text-2xl leading-tight mb-2">
                        Set New Portal<br>Access Password
                    </h2>
                    
                    <p class="text-blue-200 text-xs leading-relaxed font-sans mb-6">
                        Create a strong, unique password to secure your institutional credentials, GTU transcripts, and verified alumni profile.
                    </p>

                    <!-- Security Requirements -->
                    <div class="space-y-3 pt-4 border-t border-white/10 text-xs text-blue-100 font-sans">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🔑</span>
                            <span>At least 8 characters long</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🔣</span>
                            <span>Include letters, numbers &amp; special characters</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">⚡</span>
                            <span>Instant session update upon successful reset</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/10 text-[11px] text-blue-200/80 font-medium">
                    Affiliated to Gujarat Technological University (GTU) &bull; CTE Approved
                </div>
            </div>

            <!-- Right Panel: Reset Password Form -->
            <div class="md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
                
                <div class="mb-6">
                    <span class="text-[11px] font-black uppercase tracking-widest text-[#f97316] font-heading block mb-1">
                        Secure Authentication
                    </span>
                    <h3 class="font-heading font-black text-2xl text-[#0f172a]">
                        Reset Your Password
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 font-sans">
                        Please enter your registered email address and define your new password below.
                    </p>
                </div>

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                ✉️
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                                   class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                        </div>
                        @error('email')
                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- New Password Field with Alpine Toggle -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                🔒
                            </span>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="At least 8 characters"
                                   class="w-full pl-9 pr-10 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                            <button type="button" @click="showPass = !showPass" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <span x-show="!showPass" class="text-xs">👁️</span>
                                <span x-show="showPass" style="display: none;" class="text-xs">🙈</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm New Password Field with Alpine Toggle -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                🛡️
                            </span>
                            <input id="password_confirmation" :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Re-type password"
                                   class="w-full pl-9 pr-10 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                            <button type="button" @click="showConfirmPass = !showConfirmPass" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <span x-show="!showConfirmPass" class="text-xs">👁️</span>
                                <span x-show="showConfirmPass" style="display: none;" class="text-xs">🙈</span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                            <span>Update Password &amp; Sign In</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <!-- Navigation link -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs">
                    <a href="{{ route('login') }}" class="text-[#1e3a8a] font-bold hover:text-[#f97316] transition-colors font-heading inline-flex items-center gap-1">
                        <span>&larr;</span>
                        <span>Return to Member Sign In</span>
                    </a>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection
