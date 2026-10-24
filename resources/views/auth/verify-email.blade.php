@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden">

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
                Alumni Registration &bull; Email Verification
            </p>
        </div>

        <!-- Verify Email Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden p-8 sm:p-10">
            
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="w-14 h-14 rounded-2xl bg-white p-1 shadow-md shrink-0 border border-amber-300">
                    <img src="{{ asset('images/kdp_crest.png') }}" alt="K. D. Polytechnic Crest" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-[#f97316] font-heading block mb-0.5">
                        GTU Enrollment Verification
                    </span>
                    <h2 class="font-heading font-black text-xl text-[#0f172a]">
                        Verify Your Email Address
                    </h2>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed font-sans mb-4">
                Thank you for applying to the K. D. Polytechnic Alumni Portal! Before getting started, please verify your email address by clicking the link we just sent to your inbox.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-3.5 rounded-r-xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
                    <span class="text-sm">✅</span>
                    <span>A new verification link has been sent to the email address provided during registration.</span>
                </div>
            @endif

            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md transition-all font-heading cursor-pointer inline-flex items-center gap-1.5">
                        <span>Resend Verification Email</span>
                        <span>&rarr;</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 font-semibold transition-colors underline cursor-pointer">
                        Sign Out / Exit
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
