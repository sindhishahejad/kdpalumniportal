@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-10 rounded-[20px] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Admin Dashboard</h1>
                    <p class="text-gray-500 mt-2 text-sm">Manage users, approve job postings, and oversee the KDP Alumni network.</p>
                </div>
                <span class="bg-red-50 text-red-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-red-200">
                    Administrator Access
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <!-- Placeholder Stat Card 1 -->
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Alumni</h3>
                    <p class="text-2xl font-bold text-[#0f172a]">1,248</p>
                </div>
                
                <!-- Placeholder Stat Card 2 -->
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Pending Jobs</h3>
                    <p class="text-2xl font-bold text-[#0f172a]">12</p>
                </div>

                <!-- Placeholder Stat Card 3 -->
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Active Students</h3>
                    <p class="text-2xl font-bold text-[#0f172a]">432</p>
                </div>
            </div>
        </div>
    </div>
@endsection