@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden" x-data="{ showPass: false }">

    <!-- Subtle Background Top Gradient Strip -->
    <div class="absolute top-0 inset-x-0 h-72 bg-gradient-to-b from-[#0f2042] via-[#1e3a8a] to-transparent opacity-95 pointer-events-none"></div>

    <div class="relative z-10 max-w-xl mx-auto w-full">

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
                Security Checkpoint &bull; Confirm Password
            </p>
        </div>

        <!-- Confirm Password Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden p-8 sm:p-10">
            
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="w-14 h-14 rounded-2xl bg-white p-1 shadow-md shrink-0 border border-amber-300">
                    <img src="{{ asset('images/kdp_crest.png') }}" alt="K. D. Polytechnic Crest" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-[#f97316] font-heading block mb-0.5">
                        Protected Campus Area
                    </span>
                    <h2 class="font-heading font-black text-xl text-[#0f172a]">
                        Confirm Your Identity
                    </h2>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed font-sans mb-6">
                This is a secure area of the K. D. Polytechnic alumni application. Please confirm your account password before proceeding.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                        Account Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            🔒
                        </span>
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password" autofocus placeholder="••••••••"
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

                <div class="pt-2 flex items-center justify-between gap-3">
                    <a href="{{ url()->previous() }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">
                        &larr; Cancel
                    </a>
                    <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md transition-all font-heading cursor-pointer">
                        Confirm Identity &rarr;
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
