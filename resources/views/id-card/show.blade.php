@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <!-- Added id-card-wrapper for targeted print centering -->
    <div id="id-card-wrapper" class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col items-center justify-center space-y-6">
        
        <!-- Action Button Above Card -->
        <div class="w-full max-w-sm flex justify-between items-center px-2">
            <h2 class="font-serif font-bold text-xl text-[#1C3661]">
                Digital Identity Card
            </h2>
            <button onclick="window.print()" class="px-4 py-2 bg-[#8b0000] hover:bg-[#6b0d0d] text-white rounded-sm text-xs font-bold uppercase tracking-wider shadow transition-colors">
                Print ID Card
            </button>
        </div>

        <!-- ID Card Container (Exact Physical Card Replica) -->
        <div class="bg-white w-80 md:w-96 rounded-xl shadow-xl overflow-hidden border border-gray-300 print:shadow-none print:border-gray-400 relative">
            
            <!-- Card Header -->
            <div class="pt-5 pb-3 px-4 text-center bg-white border-b border-gray-100 relative">
                <!-- College Logo Placeholder -->
                <div class="absolute left-4 top-4 w-12 h-12 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs shadow-inner">
                    KDP
                </div>
                
                <h3 class="font-sans font-extrabold text-lg text-blue-900 tracking-tight leading-none">K. D. POLYTECHNIC</h3>
                <h4 class="font-sans font-bold text-base text-blue-900 tracking-tight leading-snug mt-0.5">PATAN</h4>
                <p class="text-[10px] font-bold text-[#8b0000] italic mt-0.5">(GOVERNMENT OF GUJARAT)</p>
                <p class="text-[10px] text-gray-600 font-medium leading-tight mt-1">Opp. T.B. Hospital, HNGU Road,<br>Patan-384265</p>
            </div>

            <!-- Curved Ribbon Divider with Photo -->
            <div class="relative bg-white pt-2 pb-4 flex flex-col items-center">
                <!-- Decorative Blue/Maroon Ribbon Graphic Area -->
                <div class="w-full h-8 bg-gradient-to-r from-blue-900 via-red-800 to-blue-900 absolute top-0 opacity-90 transform skew-y-1"></div>

                <!-- Profile Photo Box -->
                <div class="relative z-10 w-28 h-32 bg-gray-100 rounded-sm border-2 border-amber-400 shadow-md overflow-hidden mt-2">
                    @if(optional($user->profile)->photo_path)
                        <img src="{{ asset('storage/' . $user->profile->photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-600 font-serif font-bold text-2xl uppercase">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Full Name in Bold Red -->
                <h2 class="text-sm font-bold text-[#8b0000] uppercase tracking-wide text-center mt-3 px-4">
                    {{ $user->name }}
                </h2>
            </div>

            <!-- Details Section -->
            <div class="px-6 pb-4 text-xs space-y-1.5 text-gray-800 font-medium">
                <div class="grid grid-cols-12 gap-1">
                    <span class="col-span-4 text-gray-600 font-semibold">Programme</span>
                    <span class="col-span-8 font-bold text-black uppercase">: {{ optional($user->profile)->department ?? ($user->department ?? 'COMPUTER ENGINEERING') }}</span>
                </div>
                <div class="grid grid-cols-12 gap-1">
                    <span class="col-span-4 text-gray-600 font-semibold">Enrollment No</span>
                    <span class="col-span-8 font-bold text-black uppercase">: {{ $user->enrollment_no ?? '24631030' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="grid grid-cols-12 gap-1">
                    <span class="col-span-4 text-gray-600 font-semibold">Mobile</span>
                    <span class="col-span-8 font-bold text-black">: {{ $user->phone ?? '9316751056' }}</span>
                </div>
                <div class="grid grid-cols-12 gap-1">
                    <span class="col-span-4 text-gray-600 font-semibold">Date of Birth</span>
                    <span class="col-span-8 font-bold text-black">: {{ optional($user->profile)->dob ?? '23-09-2008' }}</span>
                </div>
                <div class="grid grid-cols-12 gap-1">
                    <span class="col-span-4 text-gray-600 font-semibold">Blood Group</span>
                    <span class="col-span-8 font-bold text-red-600">: O+</span>
                </div>
                <div class="grid grid-cols-12 gap-1 items-start">
                    <span class="col-span-4 text-gray-600 font-semibold">Address</span>
                    <span class="col-span-8 font-bold text-black leading-tight">: {{ optional($user->profile)->address ?? '6/A HARSIDDHNAGAR SOCIETY, VISNAGAR ROAD, MAHESANA' }}</span>
                </div>
            </div>

            <!-- Signatures Footer -->
            <div class="px-6 pb-6 pt-4 flex justify-between items-end text-[10px] text-gray-700 font-bold border-t border-gray-100">
                <div>
                    <div class="h-6"></div>
                    <span>Student Sign</span>
                </div>
                <div class="text-center">
                    <!-- Simulated Principal Signature -->
                    <div class="font-serif italic text-blue-900 text-sm tracking-widest transform -rotate-6">Sign</div>
                    <span>Sign of Principal</span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        @page {
            margin: 0mm; 
            size: portrait;
        }
        body {
            margin: 0;
            background-color: white;
        }
        body * {
            visibility: hidden;
        }
        #id-card-wrapper, #id-card-wrapper * {
            visibility: visible;
        }
        #id-card-wrapper {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            margin: 0;
            padding: 0;
        }
        button {
            display: none !important;
        }
    }
</style>
@endsection