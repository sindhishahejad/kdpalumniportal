@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden" x-data="{ showPass: false, showDevLogin: false }">

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
                K. D. Polytechnic Alumni &amp; Student Portal
            </p>
        </div>

        <!-- Main Login Card (Dual Split Government Parity) -->
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
                        State Govt. Polytechnic
                    </span>

                    <h2 class="font-heading font-black text-xl sm:text-2xl leading-tight mb-2">
                        K. D. Polytechnic<br>Alumni Association
                    </h2>
                    
                    <p class="text-blue-200 text-xs leading-relaxed font-sans mb-6">
                        Patan, North Gujarat — Empowering six decades of diploma engineers across government, industry, and global engineering frontiers.
                    </p>

                    <!-- Privileges List -->
                    <div class="space-y-3 pt-4 border-t border-white/10 text-xs text-blue-100 font-sans">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🪪</span>
                            <span>Digital Smart ID Card &amp; Gate Verification</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">🤝</span>
                            <span>1-on-1 Alumni Mentorship &amp; D2D Guidance</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">📚</span>
                            <span>GTU Study Notes &amp; Exam Paper Vault</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center text-sm shrink-0">💼</span>
                            <span>Direct Engineering Job &amp; TPO Placements</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-white/10 text-[11px] text-blue-200/80 font-medium">
                    Affiliated to Gujarat Technological University (GTU) &bull; CTE Approved
                </div>
            </div>

            <!-- Right Panel: Sign-In Form -->
            <div class="md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
                
                <div class="mb-6">
                    <span class="text-[11px] font-black uppercase tracking-widest text-[#f97316] font-heading block mb-1">
                        Member Sign In
                    </span>
                    <h3 class="font-heading font-black text-2xl text-[#0f172a]">
                        Welcome Back
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 font-sans">
                        Sign in to access your digital alumni identity, network directory, and campus services.
                    </p>
                </div>

                @if(session('status'))
                    <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-3.5 rounded-r-xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
                        <span>✅</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-3.5 rounded-r-xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                ✉️
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@domain.com"
                                   class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                        </div>
                        @error('email')
                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field with Alpine Toggle -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider font-heading">
                                Password <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1e3a8a] hover:text-[#f97316] transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                🔒
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••"
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

                    <!-- Remember Device -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                            <span class="text-xs text-slate-600 font-medium">Remember this device</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                            <span>Sign In to Portal</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <!-- OR Divider -->
                <div class="flex items-center my-5 relative">
                    <hr class="flex-grow border-slate-200">
                    <div class="absolute left-1/2 transform -translate-x-1/2 bg-white text-slate-400 text-[11px] px-3 font-bold font-heading">
                        OR
                    </div>
                </div>

                <!-- Google OAuth Button -->
                <a href="{{ route('auth.google') }}" class="w-full bg-white border border-slate-300 hover:bg-slate-50 hover:border-slate-400 text-slate-700 font-bold text-xs py-2.5 px-4 rounded-xl shadow-2xs transition-all text-center uppercase tracking-wider flex items-center justify-center gap-2 font-heading">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>Continue with Google</span>
                </a>

                <!-- Register Link -->
                <div class="mt-6 text-center pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-600 font-medium">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-[#f97316] font-bold hover:underline font-heading">
                            Register / Apply for Membership &rarr;
                        </a>
                    </p>
                </div>

                <!-- Local Dev Helper (Preserved for testing environments) -->
                @if(app()->isLocal() && config('app.debug'))
                    <div class="mt-6 pt-3 border-t border-dashed border-slate-200">
                        <button type="button" @click="showDevLogin = !showDevLogin" class="text-[11px] text-slate-400 hover:text-slate-600 flex items-center justify-between w-full font-heading cursor-pointer">
                            <span>🛠 Local Dev Quick Email Login</span>
                            <span x-text="showDevLogin ? '▲' : '▼'"></span>
                        </button>

                        <div x-show="showDevLogin" style="display: none;" class="mt-2 bg-amber-50/80 border border-amber-200 p-3 rounded-xl text-xs space-y-2">
                            <p class="text-[11px] text-amber-800 font-semibold">Quick-fill approved local test accounts:</p>
                            <form action="{{ route('login.email') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="email" name="email" placeholder="admin@example.com" required class="flex-1 text-xs border border-amber-300 rounded-lg px-2.5 py-1 bg-white">
                                <button type="submit" class="bg-amber-700 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-amber-800 font-heading cursor-pointer">Dev Login</button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
            
        </div>
    </div>
</div>
@endsection
