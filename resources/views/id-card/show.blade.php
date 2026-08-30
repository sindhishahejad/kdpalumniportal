@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 min-h-[80vh] flex flex-col items-center justify-center">
    
    <!-- Action Buttons (Hidden when printing) -->
    <div class="mb-6 flex space-x-4 print:hidden">
        <button onclick="window.print()" class="bg-[#2e53a3] hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print / Save ID Card</span>
        </button>

        <a href="{{ route('id-card.download') }}" class="bg-[#8b0000] hover:bg-[#6b0d0d] text-white font-bold py-2 px-6 rounded-sm shadow transition-colors text-sm uppercase tracking-wider inline-block">
            Download Official PDF ID Card
        </a>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors">
            Back to Dashboard
        </a>
    </div>

    <!-- ID CARD CONTAINER (JMI Exact Structural Style) -->
    <div id="id-card" class="w-full max-w-[560px] bg-white rounded-none shadow-2xl overflow-hidden border-2 border-gray-400 relative print:shadow-none print:border-black font-sans">
        
        <!-- HEADER -->
        <div class="bg-[#2e53a3] text-white pt-3 pb-2 px-4 text-center border-b-2 border-orange-500 relative">
            <!-- Optional Logo in Header Corner -->
            <div class="absolute left-4 top-2.5 w-10 h-10 bg-white rounded-full flex items-center justify-center overflow-hidden shadow">
                <img src="{{ asset('images/five.png') }}" alt="Logo" class="w-8 h-8 object-contain" onerror="this.src='https://picsum.photos/id/147/40/40'">
            </div>
            
            <h1 class="text-sm sm:text-base font-black tracking-wider uppercase">ALUMNI ASSOCIATION OF K. D. POLYTECHNIC</h1>
            <p class="text-xs sm:text-sm font-bold tracking-wide text-gray-200 mt-0.5">PATAN, GUJARAT - 384265</p>
            
            <div class="mt-2 bg-white text-[#2e53a3] font-extrabold text-xs py-1 tracking-widest uppercase border-t border-b border-gray-300 shadow-inner">
                ALUMNI MEMBERSHIP CARD
            </div>
        </div>

        <!-- BODY CONTENT -->
        <div class="p-6 bg-white flex items-stretch gap-6 relative">
            
            @php
    $photoPath = $user->photo_path ?? null;
@endphp

<!-- PHOTO BOX (Left) with Pencil Edit Overlay -->
<div class="shrink-0 flex items-center relative">
    <div class="w-36 h-44 bg-gray-100 rounded-none overflow-hidden border-2 border-gray-400 shadow-sm flex items-center justify-center relative group">
        @if($photoPath)
            <img src="{{ filter_var($photoPath, FILTER_VALIDATE_URL) ? $photoPath : asset('storage/' . $photoPath) }}" alt="Profile Photo" class="w-full h-full object-cover">
        @else
            <!-- Fallback Initials -->
            <div class="w-full h-full bg-blue-50 text-[#2e53a3] font-bold text-4xl flex items-center justify-center uppercase">
                {{ collect(explode(' ', $user->name))->map(fn($seg) => mb_substr($seg, 0, 1))->join('') }}
            </div>
        @endif

        <!-- ✨ Pencil Edit Overlay Button ✨ -->
        <a href="{{ route('profile.edit') }}" title="Update Profile Photo" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white print:hidden">
            <svg class="w-8 h-8 mb-1 bg-[#2e53a3] p-1.5 rounded-full shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span class="text-[11px] font-bold tracking-wider uppercase bg-black/60 px-2 py-0.5 rounded">Change Photo</span>
        </a>
    </div>
</div>

            <!-- DETAILS (Right) -->
            <div class="flex-1 flex flex-col justify-center">
                
                <!-- Name & Membership Status -->
                <div class="mb-3">
                    <h2 class="text-xl font-extrabold text-black uppercase tracking-tight leading-none pb-1 border-b border-black">
                        {{ $user->name }}
                    </h2>
                    <p class="text-sm font-bold text-gray-700 mt-1 tracking-wide">Lifetime Member</p>
                </div>

                <!-- Field Grid with Colons -->
                <div class="space-y-1.5 text-xs sm:text-sm font-medium text-gray-900">
                    <div class="flex">
                        <span class="w-32 font-bold text-black">Alumni ID</span>
                        <span class="font-bold text-[#2e53a3]">: KDP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-black">Department</span>
                        <span class="font-semibold">: {{ $user->profile->department ?? $user->department ?? 'Computer Engineering' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-black">Contact No.</span>
                        <span class="font-semibold">: {{ $user->profile->phone ?? $user->phone ?? '9265105831' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-black">Blood Grp.</span>
                        <span class="font-semibold">: {{ $user->blood_group ?? 'N/A' }}</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- CARD FOOTER -->
        <div class="bg-white px-6 py-3 border-t-2 border-gray-300 flex justify-between items-end text-xs font-bold text-black">
            <div>
                <span>Member Since : {{ $user->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="text-right">
                <!-- Handwritten Style Signature Mockup -->
                <div class="font-serif italic text-blue-900 text-base font-bold tracking-widest -mb-1 select-none">
                    A. S. Patel
                </div>
                <div class="border-t border-black pt-0.5 tracking-widest text-[11px] font-black uppercase">
                    PRESIDENT
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Print Stylesheet Customizations -->
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