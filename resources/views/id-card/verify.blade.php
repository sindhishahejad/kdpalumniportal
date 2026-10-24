@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center">
    
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
        
        @if($isValid)
            <!-- Verified Header Banner -->
            <div class="bg-gradient-to-r from-emerald-700 via-teal-800 to-[#0f172a] text-white p-6 text-center relative overflow-hidden border-b-4 border-amber-400">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <img src="{{ asset('images/kdp_crest.png') }}" class="w-10 h-10 object-contain filter brightness-110 drop-shadow" alt="KDP Crest">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-9 h-9 object-contain rounded-full bg-white p-0.5 shadow-xs" alt="GTU CTE Seal">
                </div>
                <span class="inline-block px-3 py-1 bg-white/20 text-white text-[11px] font-extrabold uppercase tracking-widest rounded-full mb-1">
                    Official CTE Gujarat Verification
                </span>
                <h1 class="text-xl font-bold tracking-tight">Verified Alumni Credential</h1>
                <p class="text-xs text-emerald-100 mt-1">K. D. Polytechnic, Patan (Alumni Association)</p>
            </div>

            <!-- Verified Details Body -->
            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- Member Summary Card -->
                <div class="flex items-center gap-4 border-b border-gray-100 pb-6">
                    @php
                        $photo = $user->photo_path ?? null;
                    @endphp
                    @if($photo)
                        <img src="{{ filter_var($photo, FILTER_VALIDATE_URL) ? $photo : asset('storage/' . $photo) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-emerald-500 shadow-sm shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-full bg-[#1C3661] text-white flex items-center justify-center font-bold text-xl shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 leading-snug">{{ $user->name }}</h2>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Active Lifetime Member
                        </span>
                    </div>
                </div>

                <!-- Credential Attributes Table -->
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Alumni ID</span>
                        <span class="font-mono font-bold text-[#1C3661]">KDP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    @if($user->entry_no)
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Roll / Entry No</span>
                            <span class="font-semibold text-gray-800">{{ $user->entry_no }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Department</span>
                        <span class="font-semibold text-gray-800">{{ $user->department ?? optional($user->profile)->department ?? 'Engineering' }}</span>
                    </div>

                    @php
                        $gradYear = $user->graduation_year ?? optional($user->profile)->graduation_year;
                    @endphp
                    @if($gradYear)
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Graduation Year</span>
                            <span class="font-semibold text-gray-800">Class of {{ $gradYear }}</span>
                        </div>
                    @endif

                    @php
                        $degree = $user->degree ?? optional($user->profile)->degree;
                    @endphp
                    @if($degree)
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Degree / Course</span>
                            <span class="font-semibold text-gray-800">{{ $degree }}</span>
                        </div>
                    @endif

                    @php
                        $company = $user->company ?? optional($user->profile)->current_company;
                        $desig = $user->designation ?? optional($user->profile)->job_title;
                    @endphp
                    @if($company || $desig)
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Current Role</span>
                            <span class="font-semibold text-gray-800 text-right">{{ $desig ?? 'Professional' }} @if($company) at {{ $company }} @endif</span>
                        </div>
                    @endif

                    @if($user->blood_group)
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Blood Group</span>
                            <span class="font-bold text-red-700">{{ $user->blood_group }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-1.5">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Issued By</span>
                        <span class="font-semibold text-gray-700">K.D. Polytechnic, Patan</span>
                    </div>
                </div>

                <!-- Real-time Verification Stamp -->
                <div class="bg-emerald-50 rounded-xl p-3.5 border border-emerald-200 text-center">
                    <p class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Authentication Verified</p>
                    <p class="text-[11px] text-emerald-700 mt-0.5">
                        Digital record confirmed live on {{ now()->format('d M Y, h:i A') }}
                    </p>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('alumni.show', $user->id) }}" class="text-xs text-[#8b0000] font-bold hover:underline">
                        View Community Public Profile &rarr;
                    </a>
                </div>
            </div>

        @else
            <!-- Invalid / Not Found Banner -->
            <div class="bg-red-700 text-white p-6 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h1 class="text-xl font-bold">Unverified Credential</h1>
                <p class="text-xs text-red-100 mt-1">KDP Alumni Verification Registry</p>
            </div>

            <div class="p-8 text-center space-y-4">
                <p class="text-sm text-gray-700">
                    No active or approved alumni membership was found matching identifier:
                </p>
                <div class="font-mono font-bold text-red-700 bg-red-50 py-2 px-4 rounded-lg border border-red-200 inline-block text-base">
                    {{ $identifier }}
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    This ID card may be expired, pending administrative approval, or invalid. For inquiries, please contact the K.D. Polytechnic administration.
                </p>

                <div class="pt-4">
                    <a href="{{ url('/') }}" class="inline-block bg-[#1C3661] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-900 transition-colors">
                        Return to Portal Homepage
                    </a>
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
