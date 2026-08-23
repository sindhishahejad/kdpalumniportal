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
            </div> <!-- ✨ THIS IS THE DIV YOU WERE MISSING! ✨ -->

            <!-- Gallery Manager -->
            <div class="mt-10 p-8 bg-gray-50 rounded-[20px] border border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Gallery</h2>
                    <p class="text-gray-500 text-sm mt-1">Create new sections or edit existing memories.</p>
                </div>
                
                @if(session('status'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left: Create Form -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                        <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Create New Section</h3>
                        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-2">Section Name</label>
                                <input type="text" name="title" required placeholder="e.g. Sports Week 2026" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#3b82f6] focus:ring-[#3b82f6]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-2">Upload Photos</label>
                                <input type="file" name="images[]" multiple required accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 focus:outline-none focus:border-[#3b82f6] cursor-pointer">
                            </div>
                            <button type="submit" class="w-full bg-[#3b82f6] hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors">
                                Create Section & Upload
                            </button>
                        </form>
                    </div>

                    <!-- Right: Existing Sections List -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Edit Existing Sections</h3>
                        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                            @forelse($albums as $album)
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                    <div>
                                        <h4 class="font-bold text-sm text-[#0f172a]">{{ $album->title }}</h4>
                                        <p class="text-xs text-gray-500">{{ $album->photos_count }} Photos</p>
                                    </div>
                                    <a href="{{ route('gallery.edit', $album->id) }}" class="text-xs font-bold text-[#3b82f6] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                        Edit / Preview
                                    </a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 italic">No sections created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection