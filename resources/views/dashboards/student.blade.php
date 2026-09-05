@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-10 rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            
            <!--- Header Section --->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Student Dashboard</h1>
                    <p class="text-gray-500 mt-2 text-sm">Access academic resources, connect with alumni mentors, and explore internships.</p>
                </div>
                <span class="w-max bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-blue-200">
                    Current Student
                </span>
            </div>
            
            <!-- Quick Actions Grid (Updated to 4 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Action Card 1 -->
                <a href="{{ route('mentorship.index') ?? '#' }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Find a Mentor</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Connect with experienced KDP alumni for guidance.</p>
                </a>
                
                <!-- Action Card 2 -->
                <a href="{{ route('jobs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Job & Internship Board</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Browse opportunities posted exclusively by the alumni network.</p>
                </a>

                <!-- Action Card 3 -->
                <a href="{{ route('resources.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Resource Vault</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Access study materials, past papers, and department guides.</p>
                </a>

                <!-- Action Card 4 (NEW: Document Requests) -->
                <a href="{{ route('documents.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Document Requests</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Request official bonafide certificates and recommendation letters.</p>
                </a>

                <!-- Action Card: Success Stories -->
<a href="{{ route('stories.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
    </div>
    <h3 class="text-[#0f172a] font-bold mb-1">Success Stories</h3>
    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Get inspired by notable alumni career paths and achievements.</p>
</a>

<!-- Action Card: Project Incubation -->
<a href="{{ route('pitches.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
    </div>
    <h3 class="text-[#0f172a] font-bold mb-1">Project Incubation</h3>
    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Pitch minor/major projects to request hardware components or alumni guidance.</p>
</a>

<!-- Action Card: Lab Directory (Student) -->
<a href="{{ route('labs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
    </div>
    <h3 class="text-[#0f172a] font-bold mb-1">Lab Directory</h3>
    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Explore department labs, hardware inventories, and faculty in-charge.</p>
</a>
            </div>
        </div>
    </div>
@endsection