@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showModal: false }">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-200 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Careers & Industrial Training</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a] mt-1">Alumni & GIDC Job Board</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                Discover full-time positions, internships, and mandatory 6-month industrial apprenticeships in local GIDC zones and leading companies.
            </p>
        </div>

        @auth
            @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
                <button type="button" 
                        @click="showModal = true" 
                        class="inline-flex items-center gap-2 bg-[#1c3272] hover:bg-[#152554] text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-sm transition-all whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Post an Opportunity
                </button>
            @endif
        @endauth
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

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 space-y-4">
        <form method="GET" action="{{ route('jobs.index') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by role title, company, or location (e.g. GIDC Patan, Software, L&T)..." 
                       class="w-full pl-10 pr-4 py-2 text-sm border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
            </div>
            <button type="submit" class="bg-[#0f172a] hover:bg-black text-white px-6 py-2 rounded-xl text-xs font-bold transition-colors">
                Search
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Clear
                </a>
            @endif
        </form>

        <!-- Category Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">Filter:</span>
            
            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'))) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ !request('type') ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                All Openings
            </a>

            <!-- GIDC Connect / Apprenticeship Special Highlight -->
            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => 'apprenticeship'])) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'apprenticeship' ? 'bg-[#f97316] text-white shadow-sm' : 'bg-orange-50 text-[#ea580c] hover:bg-orange-100 border border-orange-200' }}">
                <span>★</span>
                <span>Apprenticeship (GIDC Connect)</span>
            </a>

            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => 'internship'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'internship' ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                Internships
            </a>

            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => 'full-time'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'full-time' ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                Full-Time
            </a>

            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => 'contract'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'contract' ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                Contract / Part-Time
            </a>
        </div>
    </div>

    <!-- Job Listings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jobs as $job)
            <div class="bg-white border {{ $job->employment_type === 'apprenticeship' ? 'border-orange-200 shadow-orange-50/50' : 'border-gray-100' }} rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Top Row: Avatar & Title -->
                    <div class="flex items-start gap-3.5 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->company) }}&background=f3f4f6&color=0f172a&bold=true" class="w-12 h-12 rounded-xl border border-gray-100 shadow-sm shrink-0">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900 text-base leading-snug truncate" title="{{ $job->title }}">{{ $job->title }}</h3>
                            <p class="text-xs text-gray-500 font-semibold truncate mt-0.5">{{ $job->company }}</p>
                        </div>
                    </div>

                    <!-- Pills: Location & Employment Type -->
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @if($job->location)
                            <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded-md border border-gray-100">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $job->location }}
                            </span>
                        @endif

                        @if($job->employment_type === 'apprenticeship')
                            <span class="inline-flex items-center gap-1 bg-orange-50 text-[#ea580c] text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded-md border border-orange-200">
                                <span>★</span> GIDC Apprenticeship
                            </span>
                        @elseif($job->employment_type === 'internship')
                            <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded-md border border-indigo-100">
                                Internship
                            </span>
                        @else
                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded-md border border-blue-100">
                                {{ ucfirst($job->employment_type) }}
                            </span>
                        @endif
                    </div>

                    <!-- Job Description -->
                    <p class="text-xs text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                        {{ $job->description }}
                    </p>
                </div>

                <!-- Footer: Posted info & Actions -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="text-[11px] text-gray-400 block font-medium">Posted {{ $job->created_at->diffForHumans() }}</span>
                        @if($job->user)
                            <span class="text-[11px] text-gray-500 block truncate max-w-[130px]">By: {{ $job->user->name }}</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->id() === $job->user_id)
                                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Remove this job listing?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold py-1 px-2 rounded hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        @endauth

                        @if($job->application_link_or_email)
                            <a href="{{ filter_var($job->application_link_or_email, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$job->application_link_or_email : $job->application_link_or_email }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-1 bg-[#0f172a] hover:bg-black text-white text-xs font-bold py-1.5 px-3.5 rounded-lg shadow-sm transition-colors">
                                Apply &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Opportunities Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">There are currently no job or apprenticeship postings matching your filters. Check back soon or post an opening to assist your fellow alumni.</p>
            </div>
        @endforelse
    </div>
    
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @endif

    <!-- Post Opportunity Modal (Alumni, Faculty & Admin) -->
    @auth
        @if(in_array(auth()->user()->role, ['admin', 'alumni', 'faculty']))
            <div x-show="showModal" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showModal = false" 
                         class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block w-full max-w-xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-gray-100">
                        
                        <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316]">KDP Career Hub</span>
                                <h3 class="text-xl font-serif font-bold text-gray-900 mt-0.5">Post a Job or Apprenticeship</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Share openings to recruit fellow alumni or guide diploma students into mandatory training.</p>
                            </div>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('jobs.store') }}" method="POST" class="mt-4 space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Job / Role Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" required placeholder="e.g. Junior Web Developer" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Company / Organization <span class="text-red-500">*</span></label>
                                    <input type="text" name="company" required placeholder="e.g. Patan Infotech, L&T" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Location</label>
                                    <input type="text" name="location" placeholder="e.g. GIDC Patan, Ahmedabad, Remote" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Employment Type <span class="text-red-500">*</span></label>
                                    <select name="employment_type" required class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                                        <option value="apprenticeship">Apprenticeship (GIDC Connect / Mandatory Training)</option>
                                        <option value="internship">Internship</option>
                                        <option value="full-time" selected>Full-Time</option>
                                        <option value="part-time">Part-Time</option>
                                        <option value="contract">Contract</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Application Link or Contact Email</label>
                                <input type="text" name="application_link_or_email" placeholder="e.g. https://company.com/apply OR hr@company.com" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Description & Requirements <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="4" required placeholder="Describe key responsibilities, required skills, eligibility, or stipend/salary details..." class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]"></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-lg shadow-sm transition-colors">
                                    Publish Opportunity &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

</div>
@endsection
