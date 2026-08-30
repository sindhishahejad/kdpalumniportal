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
            
            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Action Card 1 -->
                <a href="{{ route('mentorship.index') ?? '#' }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Find a Mentor</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Connect with experienced KDP alumni for guidance.</p>
                </a>
                
                <!-- Action Card 2 -->
                <a href="{{ route('jobs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Job & Internship Board</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Browse opportunities posted exclusively by the alumni network.</p>
                </a>

                <!-- Action Card 3 -->
                <!-- Action Card 3 -->
                    <a href="{{ route('resources.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Resource Vault</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Access study materials, past papers, and department guides.</p>
                </a>
            </div>
        </div>
    </div>
@endsection
