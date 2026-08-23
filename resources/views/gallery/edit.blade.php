@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-[20px] shadow-sm border border-gray-100">
        
        <!-- Header & Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
            <div>
                <h1 class="text-2xl font-serif font-bold text-[#0f172a]">Edit Section: {{ $album->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">Rename this memory section or manage its photos.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-[#3b82f6] hover:text-blue-800 transition-colors">
                &larr; Back to Dashboard
            </a>
        </div>

        @if(session('status'))
            <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Management Forms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            
            <!-- Rename Section Form -->
            <form action="{{ route('gallery.update', $album->id) }}" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                @csrf
                @method('PUT')
                <label class="block text-sm font-bold text-[#0f172a] mb-2">Rename Section</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="title" value="{{ $album->title }}" required class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#3b82f6] focus:ring-[#3b82f6]">
                    <button type="submit" class="bg-[#3b82f6] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors whitespace-nowrap">
                        Update Name
                    </button>
                </div>
            </form>

            <!-- Add More Photos Form -->
            <form action="{{ route('gallery.photos.store', $album->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                @csrf
                <label class="block text-sm font-bold text-[#0f172a] mb-2">Add More Photos</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="file" name="images[]" multiple required accept="image/*" class="flex-1 bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 focus:outline-none focus:border-[#3b82f6] cursor-pointer">
                    <button type="submit" class="bg-[#16a34a] hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors whitespace-nowrap flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        Upload
                    </button>
                </div>
            </form>

        </div>

        <!-- Photos Grid -->
        <h3 class="text-lg font-bold text-[#0f172a] mb-4">Manage Photos ({{ $album->photos->count() }})</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($album->photos as $photo)
                <!-- Individual Photo Card -->
                <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-[4/3] bg-gray-100 shadow-sm">
                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="w-full h-full object-cover" alt="Gallery Photo">
                    
                    <!-- Delete Button Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <form action="{{ route('gallery.photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this photo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg text-sm transition-transform transform scale-75 group-hover:scale-100">
                                Delete Photo
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-sm text-gray-500 font-medium">No photos left in this section.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection