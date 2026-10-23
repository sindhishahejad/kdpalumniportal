@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ 
    editModalOpen: false, 
    activeReferral: null,
    updateUrl: '',
    currentStatus: 'pending',
    currentFeedback: '',
    openEdit(referral, url) {
        this.activeReferral = referral;
        this.updateUrl = url;
        this.currentStatus = referral.status;
        this.currentFeedback = referral.feedback || '';
        this.editModalOpen = true;
    }
}">

    <!-- Top Sub-Navigation -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
        <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
            &larr; Browse All Opportunities
        </a>
        <a href="{{ route('jobs.my-applications') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
            My Referral Requests
        </a>
        <a href="{{ route('jobs.pipeline') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#1c3272] text-white shadow-sm">
            Referral Review Pipeline
        </a>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Talent Pipeline & Candidate Screening</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a] mt-1">Alumni Referral Desk</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                Review applicant resumes, provide mentorship feedback, and track candidates referred to your organization's internal hiring teams.
            </p>
        </div>

        @if(auth()->user()->role === 'admin')
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-lg border border-purple-200">
                <span>🛡️</span> Admin View (All Jobs)
            </span>
        @endif
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

    <!-- Metric Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Total Applicants</span>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm bg-gradient-to-br from-white to-amber-50/30">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 block mb-1">Under Review</span>
            <div class="text-2xl font-bold text-amber-700">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm bg-gradient-to-br from-white to-emerald-50/30">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block mb-1">Referred to HR</span>
            <div class="text-2xl font-bold text-emerald-700">{{ $stats['referred'] }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-purple-100 shadow-sm bg-gradient-to-br from-white to-purple-50/30">
            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 block mb-1">Shortlisted</span>
            <div class="text-2xl font-bold text-purple-700">{{ $stats['shortlisted'] }}</div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 space-y-4">
        <form method="GET" action="{{ route('jobs.pipeline') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Job Selector -->
            <div class="sm:col-span-5">
                <select name="job_id" class="w-full text-xs font-medium border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                    <option value="">All Posted Positions ({{ $myJobs->count() }})</option>
                    @foreach($myJobs as $myJob)
                        <option value="{{ $myJob->id }}" {{ request('job_id') == $myJob->id ? 'selected' : '' }}>
                            {{ $myJob->title }} ({{ $myJob->company }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Selector -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full text-xs font-medium border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                    <option value="">All Referral Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Under Review</option>
                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Resume Reviewed</option>
                    <option value="referred" {{ request('status') === 'referred' ? 'selected' : '' }}>Referred to HR</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Not Referred</option>
                </select>
            </div>

            <!-- Search Field -->
            <div class="sm:col-span-4 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate name or email..." 
                       class="w-full text-xs border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                <button type="submit" class="bg-[#0f172a] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>
                @if(request('job_id') || request('status') || request('search'))
                    <a href="{{ route('jobs.pipeline') }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Candidate Pipeline Cards -->
    <div class="space-y-4">
        @forelse($referrals as $referral)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pb-4 border-b border-gray-100">
                    <div class="flex items-start gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($referral->candidate_name) }}&background=1c3272&color=fff&bold=true" 
                             class="w-12 h-12 rounded-xl shadow-sm shrink-0">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900">{{ $referral->candidate_name }}</h3>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $referral->status_badge_class }}">
                                    {{ $referral->status_label }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 font-medium mt-0.5">
                                For position: <span class="font-bold text-[#1c3272]">{{ $referral->jobPosting->title ?? 'Opening' }}</span> &bull; 
                                {{ $referral->jobPosting->company ?? '' }}
                                @if($referral->user && $referral->user->profile && $referral->user->profile->department)
                                    &bull; <span class="text-gray-600">{{ $referral->user->profile->department }} (Class of {{ $referral->user->profile->graduation_year ?? 'N/A' }})</span>
                                @endif
                            </p>

                            <!-- Candidate Contact Details -->
                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-2 flex-wrap">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <a href="mailto:{{ $referral->candidate_email }}" class="hover:underline text-gray-700">{{ $referral->candidate_email }}</a>
                                </span>
                                @if($referral->candidate_phone)
                                    @php
                                        $quickWaUrl = \App\Services\WhatsAppAlertService::generateClickToChatUrl($referral->candidate_phone, "Hello {$referral->candidate_name}, regarding your referral application on KDP Alumni Portal for " . ($referral->jobPosting->title ?? 'Position') . '...');
                                    @endphp
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <a href="{{ $quickWaUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 font-semibold hover:underline flex items-center gap-1">
                                            {{ $referral->candidate_phone }} <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1 py-0.2 rounded font-bold">WA</span>
                                        </a>
                                    </span>
                                @endif
                                @if($referral->candidate_linkedin)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                        <a href="{{ $referral->candidate_linkedin }}" target="_blank" class="text-blue-600 hover:underline font-medium">LinkedIn Profile</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
                        <a href="{{ route('jobs.referrals.download-resume', $referral->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-[#1c3272] bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors border border-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Resume
                        </a>

                        @if($referral->candidate_phone)
                            @php
                                $candidateWaMsg = \App\Services\WhatsAppAlertService::jobReferralStatusMessage(
                                    $referral, 
                                    $referral->status, 
                                    $referral->feedback
                                );
                                $candidateWaUrl = \App\Services\WhatsAppAlertService::generateClickToChatUrl($referral->candidate_phone, $candidateWaMsg);
                            @endphp
                            <a href="{{ $candidateWaUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Send instant status update to candidate via WhatsApp"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors border border-emerald-200">
                                <span>📱</span> WhatsApp Candidate
                            </a>
                        @endif

                        <button type="button" 
                                @click="openEdit({{ $referral->toJson() }}, '{{ route('jobs.referrals.update-status', $referral->id) }}')" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-[#0f172a] hover:bg-black rounded-xl transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Review & Update
                        </button>
                    </div>
                </div>

                <!-- Intro Pitch & Feedback Note -->
                <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px] block mb-1">Candidate's Pitch:</span>
                        <p class="text-gray-700 leading-relaxed italic">
                            {{ $referral->cover_note ? '"' . $referral->cover_note . '"' : 'No intro note provided.' }}
                        </p>
                    </div>

                    @if($referral->feedback)
                        <div class="bg-blue-50 rounded-xl p-3 border border-blue-200">
                            <span class="font-bold text-blue-900 uppercase tracking-wider text-[10px] block mb-1">Your Mentor / Referral Guidance:</span>
                            <p class="text-blue-950 font-medium leading-relaxed">"{{ $referral->feedback }}"</p>
                        </div>
                    @else
                        <div class="bg-amber-50/60 rounded-xl p-3 border border-dashed border-amber-200 flex items-center justify-between text-amber-800 text-xs">
                            <span>No mentor feedback or internal notes added yet.</span>
                            <button type="button" @click="openEdit({{ $referral->toJson() }}, '{{ route('jobs.referrals.update-status', $referral->id) }}')" class="font-bold underline text-amber-900">
                                Add Note
                            </button>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                    <span>Applied on {{ $referral->created_at->format('M d, Y \a\t h:i A') }} ({{ $referral->created_at->diffForHumans() }})</span>
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
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Referral Applicants Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">Candidates who request referrals for your posted jobs will appear here for resume review and screening.</p>
            </div>
        @endforelse
    </div>

    @if($referrals->hasPages())
        <div class="mt-8">
            {{ $referrals->links() }}
        </div>
    @endif

    <!-- Review & Update Status Modal -->
    <div x-show="editModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="editModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="editModalOpen = false" 
                 class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="editModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-gray-100">
                
                <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316]">Candidate Decision</span>
                        <h3 class="text-xl font-serif font-bold text-gray-900 mt-0.5">Update Referral Status</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Candidate: <span class="font-bold text-gray-700" x-text="activeReferral ? activeReferral.candidate_name : ''"></span>
                        </p>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="updateUrl" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Referral Status <span class="text-red-500">*</span></label>
                        <select name="status" x-model="currentStatus" required class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                            <option value="pending">🟡 Under Review (Pending Screening)</option>
                            <option value="reviewed">🔵 Resume Reviewed (Feedback Provided)</option>
                            <option value="referred">🟢 Referred to HR / Hiring Desk</option>
                            <option value="shortlisted">🟣 Shortlisted for Interview</option>
                            <option value="declined">⚪ Not Referred at this time</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Mentor Guidance / Referral Notes
                        </label>
                        <textarea name="feedback" x-model="currentFeedback" rows="4" 
                                  placeholder="Leave constructive guidance for the student, or note who in HR you forwarded their CV to (e.g. 'Submitted your CV to our Patan plant manager. Brush up on PLC/SCADA basics.')..." 
                                  class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]"></textarea>
                        <span class="text-[11px] text-gray-400 block mt-1">This feedback will be notified to the student applicant on their portal dashboard.</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-lg shadow-sm transition-colors">
                            Save & Notify Candidate &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
