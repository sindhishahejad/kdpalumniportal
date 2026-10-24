@extends('layouts.app')

@section('content')
<div class="py-12 bg-slate-100 min-h-[85vh] flex flex-col items-center justify-center px-4 font-sans" x-data="{ copied: false }">
    
    <!-- Action Buttons (Hidden when printing) -->
    <div class="mb-6 flex flex-wrap items-center justify-center gap-3 print:hidden">
        <button onclick="window.print()" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-5 rounded-xl shadow-xs transition-all text-xs flex items-center gap-2 font-heading cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print ID Card</span>
        </button>

        <a href="{{ route('id-card.download') }}" class="bg-[#f97316] hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-xs transition-all text-xs uppercase tracking-wider inline-flex items-center gap-2 font-heading">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Download Official PDF Card</span>
        </a>

        <button @click="navigator.clipboard.writeText('{{ $verificationUrl }}'); copied = true; setTimeout(() => copied = false, 2500)" class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold py-2.5 px-4 rounded-xl shadow-2xs transition-colors text-xs flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span x-text="copied ? 'Verification Link Copied!' : 'Copy Verification URL'"></span>
        </button>

        <a href="{{ route('dashboard') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-4 rounded-xl transition-colors text-xs">
            Back to Dashboard
        </a>
    </div>

    <!-- ID CARD CONTAINER (Standard PVC Smart Card Proportion ~ 640px) -->
    <div id="id-card" class="w-full max-w-[640px] bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-slate-300 relative print:shadow-none print:border-black font-sans">
        
        <!-- INSTITUTIONAL HEADER -->
        <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] text-white pt-4 pb-3 px-5 border-b-2 border-amber-500 relative">
            <div class="flex items-center justify-between gap-3">
                <!-- Left: KDP College Crest -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset('images/kdp_crest.png') }}" class="w-12 h-12 object-contain filter brightness-110 drop-shadow" alt="KDP Crest">
                </div>

                <!-- Center: Official Header Text -->
                <div class="text-center flex-1 min-w-0">
                    <span class="block text-[9px] uppercase tracking-widest text-[#f97316] font-extrabold font-heading">
                        Govt. of Gujarat • Commissionerate of Technical Education
                    </span>
                    <h1 class="text-xs sm:text-sm font-black tracking-wider uppercase font-heading text-white truncate">
                        ALUMNI ASSOCIATION OF K. D. POLYTECHNIC
                    </h1>
                    <p class="text-[10px] sm:text-[11px] font-semibold tracking-wide text-blue-100">
                        GOVT. POLYTECHNIC CAMPUS, PATAN, GUJARAT - 384265
                    </p>
                </div>

                <!-- Right: GTU / CTE Seal -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-11 h-11 object-contain rounded-full bg-white p-0.5 shadow-sm" alt="CTE GTU Logo">
                </div>
            </div>
            
            <!-- Banner Ribbon -->
            <div class="mt-2.5 bg-[#f97316] text-white font-extrabold text-[10px] sm:text-[11px] py-0.5 tracking-widest uppercase rounded shadow-inner text-center font-heading">
                OFFICIAL SMART ALUMNI CARD • આજીવન સભ્યપદ
            </div>
        </div>

        <!-- BODY CONTENT -->
        <div class="p-5 sm:p-6 bg-white flex flex-col sm:flex-row items-center sm:items-stretch gap-5 relative overflow-hidden">
            
            <!-- Watermark Crest Background -->
            <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                <img src="{{ asset('images/kdp_crest.png') }}" class="w-72 h-72 object-contain" alt="Watermark">
            </div>

            @php
                $photoPath = $user->photo_path ?? null;
            @endphp

            <!-- PHOTO & SMART CHIP BOX (Left) -->
            <div class="shrink-0 flex flex-col items-center justify-center relative z-10">
                <div class="w-28 sm:w-32 h-36 sm:h-40 bg-slate-50 rounded-xl overflow-hidden border-2 border-slate-300 shadow-xs flex items-center justify-center relative group">
                    @if($photoPath)
                        <img src="{{ filter_var($photoPath, FILTER_VALIDATE_URL) ? $photoPath : asset('storage/' . $photoPath) }}" alt="Profile Photo" class="w-full h-full object-cover">
                    @else
                        <!-- Fallback Initials -->
                        <div class="w-full h-full bg-blue-50 text-[#1e3a8a] font-bold text-3xl flex items-center justify-center uppercase font-heading">
                            {{ collect(explode(' ', $user->name))->map(fn($seg) => mb_substr($seg, 0, 1))->join('') }}
                        </div>
                    @endif

                    <!-- Change Photo Overlay Button (Hidden in Print) -->
                    <a href="{{ route('profile.edit') }}" title="Update Profile Photo" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white print:hidden">
                        <svg class="w-6 h-6 mb-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span class="text-[9px] font-bold tracking-wider uppercase bg-black/60 px-2 py-0.5 rounded">Change</span>
                    </a>
                </div>

                <!-- Gold EMV Contact Chip Graphic -->
                <div class="mt-2 w-10 h-7 bg-gradient-to-tr from-amber-300 via-yellow-200 to-amber-400 rounded-sm border border-amber-600/60 shadow-xs relative flex items-center justify-center" title="Smart RFID / EMV Chip">
                    <div class="w-6 h-4 border border-amber-600/40 rounded-xs flex items-center justify-center">
                        <div class="w-3 h-2 border-r border-l border-amber-600/40"></div>
                    </div>
                </div>
            </div>

            <!-- DETAILS (Middle) -->
            <div class="flex-1 flex flex-col justify-center min-w-0 text-left relative z-10">
                
                <div class="mb-2.5">
                    <h2 class="text-base sm:text-lg font-black font-heading text-slate-900 uppercase tracking-tight truncate border-b border-slate-200 pb-1">
                        {{ $user->name }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active Lifetime Member • Verified Alumnus
                    </span>
                </div>

                <!-- Attributes Grid -->
                <div class="space-y-1.5 text-xs font-medium text-slate-800">
                    <div class="flex">
                        <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Alumni ID</span>
                        <span class="font-bold text-[#1e3a8a] font-mono">: KDP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    @if($user->entry_no)
                        <div class="flex">
                            <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Roll / Entry</span>
                            <span class="font-semibold text-slate-900">: {{ $user->entry_no }}</span>
                        </div>
                    @endif

                    <div class="flex">
                        <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Civil Engineering</span>
                        <span class="hidden">: Civil Engineering</span>
                        <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider hidden">Dept</span>
                        <span class="font-semibold text-slate-900 truncate">: {{ $user->department ?? optional($user->profile)->department ?? 'Civil Engineering' }}</span>
                    </div>

                    @php
                        $gradYear = $user->graduation_year ?? optional($user->profile)->graduation_year;
                    @endphp
                    @if($gradYear)
                        <div class="flex">
                            <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Class of</span>
                            <span class="font-semibold text-slate-900">: {{ $gradYear }}</span>
                        </div>
                    @endif

                    @if($user->blood_group)
                        <div class="flex">
                            <span class="w-24 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Blood Grp</span>
                            <span class="font-bold text-red-600">: {{ $user->blood_group }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- SCANNABLE QR CODE (Right) -->
            <div class="shrink-0 flex flex-col items-center justify-center p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-center relative z-10">
                <img src="{{ $qrCodeUrl }}" alt="Scan to Verify Alumni" class="w-20 h-20 sm:w-24 sm:h-24 object-contain shadow-2xs">
                <span class="text-[9px] font-black uppercase tracking-wider text-emerald-800 mt-1.5 flex items-center gap-1 font-heading">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Scan to Verify
                </span>
                <span class="hidden text-[8px]">{{ $verificationUrl }}</span>
            </div>

        </div>

        <!-- CARD FOOTER -->
        <div class="bg-slate-50 px-5 py-2.5 border-t border-slate-200 flex justify-between items-center text-[10px] sm:text-[11px] font-bold text-slate-700">
            <div>
                <span>Registered: {{ $user->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="text-center hidden sm:block">
                <span class="text-[9px] font-extrabold text-[#f97316] uppercase tracking-widest font-heading">
                    • OFFICIAL CTE GUJARAT CREDENTIAL •
                </span>
            </div>
            <div class="text-right">
                <span class="font-serif italic text-[#1e3a8a] font-bold tracking-wider mr-2">Principal / President</span>
                <span class="text-[9px] uppercase tracking-wider text-slate-500 font-bold border-l pl-2 border-slate-300 font-heading">KDP PATAN</span>
            </div>
        </div>

    </div>

    <!-- Verification Info Note -->
    <div class="mt-6 text-center text-xs text-slate-500 max-w-md print:hidden">
        <p>This Smart Card is equipped with a digital verification credential. Scanning the QR code opens the live K. D. Polytechnic verification registry at <a href="{{ $verificationUrl }}" target="_blank" class="text-[#1e3a8a] font-semibold underline">{{ $verificationUrl }}</a>.</p>
    </div>

</div>

<!-- Print Stylesheet -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #id-card, #id-card * {
        visibility: visible;
    }
    #id-card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100% !important;
        border: 2px solid black !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
