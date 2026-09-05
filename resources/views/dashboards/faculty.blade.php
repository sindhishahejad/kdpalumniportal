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

                <!-- Action Card: Lab Management (Faculty) -->
<a href="{{ route('labs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
    </div>
    <h3 class="text-[#0f172a] font-bold mb-1">Department Labs</h3>
    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Manage laboratory equipment lists, locations, and faculty coordinator records.</p>
</a>
            </div>
        </div>
    </div>
@endsection
