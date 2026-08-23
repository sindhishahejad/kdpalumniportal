@extends('layouts.app')

@section('content')
<!-- Notice: Removed the x-data openModal state since this is read-only now -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Alumni Job Board</h1>
            <p class="text-gray-500 mt-2 text-sm">Discover career opportunities or hire from the KDP network.</p>
        </div>
        <!-- Notice: The Post Job button has been permanently moved to the Admin Dashboard -->
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
                <p class="text-gray-500 text-sm font-medium">No jobs posted yet. Check back later!</p>
            </div>
        @endforelse
    </div>
    
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @endif

</div>
@endsection