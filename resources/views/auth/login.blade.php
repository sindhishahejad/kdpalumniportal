@extends('layouts.app')

@section('content')
    <!-- Dark Header Banner -->
    <div class="bg-[#1a202c] w-full pt-12 pb-24 px-4 sm:px-6 lg:px-8 shadow-inner">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-sans text-white font-light tracking-wide">Login</h1>
        </div>
    </div>

    <!-- Login Card (Overlapping the banner) -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-24 relative z-10" x-data="{ showPass: false, showDevLogin: false }">
        <div class="bg-white shadow-xl flex flex-col md:flex-row overflow-hidden border border-gray-200 rounded-sm">
            
            <!-- Left Side: Branding -->
            <div class="md:w-1/2 p-10 md:p-12 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200 text-center bg-gray-50/50">
                <div class="w-16 h-16 rounded-full bg-[#8b0000] text-white flex items-center justify-center font-serif font-black text-2xl mb-6 shadow-sm">
                    KDP
                </div>
                <h2 class="text-2xl font-serif font-bold text-gray-800 mb-2 tracking-wide">K. D. Polytechnic<br>Alumni Association</h2>
                <p class="text-xs uppercase font-bold text-[#8b0000] tracking-widest mb-4">Patan, Gujarat</p>
                <p class="text-sm text-gray-500 px-4 leading-relaxed">
                    Access mentorships, explore alumni directory, document fulfillment, and community opportunities.
                </p>
            </div>

            <!-- Right Side: Authentication -->
            <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
                
                @if(session('status'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-xs font-medium leading-relaxed">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-xs font-medium leading-relaxed">
                        {{ session('error') }}
                    </div>
                @endif

                <p class="text-gray-800 text-xl mb-6 font-semibold">
                    Welcome back
                </p>

                <!-- Standard Secure Email + Password Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@domain.com"
                               class="w-full border-b border-gray-300 py-2 text-gray-900 focus:outline-none focus:border-[#8b0000] transition-colors bg-transparent placeholder-gray-400 text-sm">
                        @error('email')
                            <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field with Toggle -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-[#8b0000] hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••"
                                   class="w-full border-b border-gray-300 py-2 pr-10 text-gray-900 focus:outline-none focus:border-[#8b0000] transition-colors bg-transparent placeholder-gray-400 text-sm">
                            <button type="button" @click="showPass = !showPass" class="absolute right-1 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="showPass" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-[#8b0000] focus:ring-[#8b0000]">
                        <label for="remember_me" class="ml-2 block text-xs text-gray-600">Remember this device</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#8b0000] hover:bg-[#6b0000] text-white font-bold text-xs uppercase tracking-wider py-3 px-4 shadow transition-colors rounded-sm">
                            Sign In to Portal
                        </button>
                    </div>
                </form>

                <!-- OR Divider -->
                <div class="flex items-center my-6 relative">
                    <hr class="flex-grow border-gray-200">
                    <div class="absolute left-1/2 transform -translate-x-1/2 bg-white text-gray-400 text-xs px-2 font-medium">
                        OR
                    </div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google') }}" class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-xs py-2.5 px-4 shadow-sm transition-colors text-center uppercase tracking-wider rounded-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    Continue with Google
                </a>

                <!-- Link to Register -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-[#8b0000] font-bold hover:underline">Register here</a>
                    </p>
                </div>

                <!-- Local Dev Shortcut Helper (Only visible in local development environment) -->
                @if(app()->isLocal() && config('app.debug'))
                    <div class="mt-8 pt-4 border-t border-dashed border-gray-200">
                        <button type="button" @click="showDevLogin = !showDevLogin" class="text-[11px] text-gray-400 hover:text-gray-600 flex items-center justify-between w-full">
                            <span>🛠 Local Dev Quick Email Login</span>
                            <span x-text="showDevLogin ? '▲' : '▼'"></span>
                        </button>

                        <div x-show="showDevLogin" style="display: none;" class="mt-3 bg-amber-50/70 border border-amber-200 p-3 rounded text-xs space-y-2">
                            <p class="text-[11px] text-amber-800">Quick-fill approved local test accounts:</p>
                            <form action="{{ route('login.email') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="email" name="email" placeholder="admin@example.com" required class="flex-1 text-xs border border-amber-300 rounded px-2 py-1 bg-white">
                                <button type="submit" class="bg-amber-700 text-white px-2.5 py-1 rounded text-xs font-semibold hover:bg-amber-800">Dev Login</button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
            
        </div>
    </div>
@endsection
