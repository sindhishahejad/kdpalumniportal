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
                <!-- Gallery Upload Manager -->
            <div class="mt-10 p-8 bg-gray-50 rounded-xl border border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Gallery</h2>
                    <p class="text-gray-500 text-sm mt-1">Create a new section and upload multiple photos at once.</p>
                </div>
                
                @if(session('status'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-2">Section Name (e.g. "Sports Week 2026")</label>
                        <input type="text" name="title" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#3b82f6] focus:ring-[#3b82f6]">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-[#0f172a] mb-2">Upload Photos</label>
                        <input type="file" name="images[]" multiple required accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 focus:outline-none focus:border-[#3b82f6] cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Hold down Ctrl (Windows) or Cmd (Mac) to select multiple images. Max 5MB per image.</p>
                    </div>

                    <button type="submit" class="bg-[#3b82f6] hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-colors">
                        Create Section & Upload
                    </button>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection