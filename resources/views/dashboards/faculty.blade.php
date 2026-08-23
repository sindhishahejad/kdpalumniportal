@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-10 rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            
            <!--- Header Section --->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Faculty Dashboard</h1>
                    <p class="text-gray-500 mt-2 text-sm">Manage academic resources, connect with students, and oversee alumni engagement.</p>
                </div>
                <span class="w-max bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-purple-200">
                    Faculty Access
                </span>
            </div>
            
            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Action Card 1 -->
                <a href="#" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Upload Resources</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Share academic materials and notes with your department students.</p>
                </a>
                
                <!-- Action Card 2 -->
                <a href="#" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Alumni Directory</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Search and invite notable alumni as guest lecturers for your classes.</p>
                </a>

                <!-- Action Card 3 -->
                <a href="#" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Manage Events</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Coordinate departmental seminars and upcoming technical symposiums.</p>
                </a>
            </div>
        </div>
    </div>
@endsection
