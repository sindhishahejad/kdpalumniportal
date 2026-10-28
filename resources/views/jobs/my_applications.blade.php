@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Top Sub-Navigation -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200 font-heading">
        <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-[#0f2042] hover:bg-slate-100 transition-all">
            &larr; Browse All Opportunities
        </a>
        <a href="{{ route('jobs.my-applications') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0f2042] text-white shadow-xs">
            My Referral Requests
        </a>
        @auth
            @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
                <a href="{{ route('jobs.pipeline') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-[#0f2042] hover:bg-slate-100 transition-all">
                    Referral Review Pipeline
                </a>
            @endif
        @endauth
    </div>

    <!-- Header Section -->
    <div class="mb-8 pb-6 border-b border-slate-200">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
            </span>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316] font-heading">Career Mentorship &amp; Placement</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight mt-1">My Referral &amp; Resume Applications</h1>
        <p class="text-slate-600 mt-2 text-sm max-w-2xl leading-relaxed">
            Track your alumni job referral requests, monitor employer screening status, and view guidance notes left by alumni mentors across Gujarat industrial corridors.
        </p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Applications List -->
    <div class="space-y-4">
        @forelse($referrals as $referral)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-4 border-b border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1c3272] flex items-center justify-center font-bold text-lg shrink-0">
                            {{ substr($referral->jobPosting->company ?? 'KD', 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900">{{ $referral->jobPosting->title ?? 'Opportunity' }}</h3>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $referral->status_badge_class }}">
                                    {{ $referral->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">
                                {{ $referral->jobPosting->company ?? 'Company' }} &bull; 
                                {{ $referral->jobPosting->location ?? 'Gujarat' }} &bull;
                                Posted by: <span class="font-semibold text-gray-700">{{ $referral->jobPosting->user->name ?? 'KDP Alumnus' }}</span>
                            </p>

                            <!-- Academic Credentials Submitted -->
                            <div class="flex items-center gap-2 mt-2 flex-wrap text-[11px]">
                                @if($referral->department)
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-[#1e3a8a] border border-blue-200 px-2.5 py-0.5 rounded-full font-bold">
                                        <span>🎓</span> {{ $referral->department }}
                                    </span>
                                @endif
                                @if($referral->enrollment_no)
                                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-full font-mono">
                                        GTU: {{ $referral->enrollment_no }}
                                    </span>
                                @endif
                                @if($referral->cpi)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded-full font-bold">
                                        ⭐ CPI: {{ $referral->cpi }}
                                    </span>
                                @endif
                                @if($referral->portfolio_url)
                                    <a href="{{ $referral->portfolio_url }}" target="_blank" class="inline-flex items-center gap-1 text-purple-700 hover:underline font-semibold">
                                        <span>🔗</span> Portfolio
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 flex-wrap">
                        <a href="{{ route('jobs.referrals.download-resume', $referral->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-[#1c3272] bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Submitted Resume
                        </a>

                        @php
                            $mentorPhone = $referral->jobPosting->user->phone ?? null;
                            $followUpWaUrl = $mentorPhone ? \App\Services\WhatsAppAlertService::generateClickToChatUrl($mentorPhone, "Namaste Er. " . ($referral->jobPosting->user->name ?? 'Mentor') . ", I submitted a referral request for " . ($referral->jobPosting->title ?? 'Opening') . " on the KDP Alumni Portal...") : null;
                        @endphp
                        @if($followUpWaUrl)
                            <a href="{{ $followUpWaUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-200">
                                <span>📱</span> WhatsApp Poster
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Note & Feedback Row -->
                <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Applicant Note -->
                    @if($referral->cover_note)
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px] block mb-1">Your Intro Pitch:</span>
                            <p class="text-gray-700 leading-relaxed italic">"{{ $referral->cover_note }}"</p>
                        </div>
                    @endif

                    <!-- Alumni Mentor Feedback -->
                    @if($referral->feedback)
                        <div class="bg-blue-50 rounded-xl p-3 border border-blue-200">
                            <div class="flex items-center gap-1.5 text-blue-900 font-bold text-[10px] uppercase tracking-wider mb-1">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                Alumni Mentor Guidance:
                            </div>
                            <p class="text-blue-950 font-medium leading-relaxed">"{{ $referral->feedback }}"</p>
                        </div>
                    @else
                        <div class="bg-gray-50/60 rounded-xl p-3 border border-dashed border-gray-200 flex items-center justify-center text-gray-400">
                            <span>Awaiting alumni review & notes</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                    <span>Submitted {{ $referral->created_at->format('M d, Y \a\t h:i A') }} ({{ $referral->created_at->diffForHumans() }})</span>
                    @if($referral->status === 'referred')
                        <span class="text-emerald-600 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Referred to internal hiring desk
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 text-[#1c3272] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Referral Requests Yet</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-6">Browse the job board and click "Ask for Alumni Referral" to submit your CV directly to working alumni.</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#1c3272] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-[#152554] transition-colors">
                    Explore Opportunities &rarr;
                </a>
            </div>
        @endforelse
    </div>

    @if($referrals->hasPages())
        <div class="mt-8">
            {{ $referrals->links() }}
        </div>
    @endif

</div>
@endsection
