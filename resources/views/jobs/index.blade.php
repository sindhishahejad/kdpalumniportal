@extends('layouts.app')

@section('content')
<div x-data="{ openModal: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Alumni Job Board</h1>
            <p class="text-gray-500 mt-2 text-sm">Discover career opportunities or hire from the KDP network.</p>
        </div>
        <button @click="openModal = true" class="bg-[#0f172a] text-white hover:bg-gray-800 text-xs font-bold uppercase tracking-widest py-3 px-6 rounded-full shadow-md transition-all flex items-center gap-2">
            Post a Job
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Job Listings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jobs as $job)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="flex items-start gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($job->company) }}&background=f3f4f6&color=0f172a" class="w-12 h-12 rounded-xl">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $job->company }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($job->location)
                        <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded border border-gray-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $job->location }}
                        </span>
                    @endif
                    @if($job->employment_type)
                        <span class="inline-flex items-center bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider py-1 px-2.5 rounded border border-blue-100">
                            {{ $job->employment_type }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 line-clamp-3 mb-6 flex-grow">
                    {{ $job->description }}
                </p>
                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <span class="text-xs text-gray-400 font-medium">Posted {{ $job->created_at->diffForHumans() }}</span>
                    @if($job->application_link_or_email)
                        <a href="{{ filter_var($job->application_link_or_email, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$job->application_link_or_email : $job->application_link_or_email }}" target="_blank" class="text-[#0f172a] hover:text-blue-600 text-xs font-bold uppercase tracking-widest transition-colors">
                            Apply &rarr;
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-gray-500 text-sm font-medium">No jobs posted yet. Be the first to share an opportunity!</p>
            </div>
        @endforelse
    </div>
    
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @endif

    <!-- ============================================== -->
    <!-- The Submission Form Modal (Matches Design Spec)-->
    <!-- ============================================== -->
    <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            
            <!-- Dark Overlay -->
            <div x-show="openModal" class="fixed inset-0 bg-gray-900 bg-opacity-40 transition-opacity backdrop-blur-sm" @click="openModal = false"></div>
            
            <!-- Modal Content -->
            <div x-show="openModal" class="relative bg-white rounded-[24px] text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-[600px] w-full p-8 md:p-10">
                <form method="POST" action="{{ route('jobs.store') }}">
                    @csrf
                    
                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-[28px] font-bold text-gray-900 tracking-tight leading-none">Post a Job Opening</h3>
                        <p class="text-sm text-gray-500 mt-2">Fill out the details to share this opportunity with the alumni network.</p>
                    </div>
                    
                    <!-- Form Fields -->
                    <div class="space-y-6">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Job Title</label>
                                <input type="text" name="title" required placeholder="e.g. Software Engineer" class="block w-full rounded-lg border-gray-200 text-gray-900 placeholder-gray-400 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Company</label>
                                <input type="text" name="company" required placeholder="e.g. Google" class="block w-full rounded-lg border-gray-200 text-gray-900 placeholder-gray-400 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Location</label>
                                <input type="text" name="location" placeholder="e.g. Remote, Mumbai" class="block w-full rounded-lg border-gray-200 text-gray-900 placeholder-gray-400 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Emp. Type</label>
                                <select name="employment_type" class="block w-full rounded-lg border-gray-200 text-gray-900 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors">
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Contract">Contract</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Application Link or Email</label>
                            <input type="text" name="application_link_or_email" placeholder="Where should they apply?" class="block w-full rounded-lg border-gray-200 text-gray-900 placeholder-gray-400 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-widest mb-2">Short Description</label>
                            <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-200 text-gray-900 focus:border-[#0f172a] focus:ring-0 text-sm px-4 py-3 shadow-sm transition-colors resize-none"></textarea>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-10 flex justify-end items-center gap-6">
                        <button type="button" @click="openModal = false" class="text-gray-500 hover:text-gray-900 text-xs font-bold uppercase tracking-widest transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-[#0f172a] text-white hover:bg-gray-800 text-xs font-bold uppercase tracking-widest py-3.5 px-8 rounded-full shadow-md transition-colors">
                            Post Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection