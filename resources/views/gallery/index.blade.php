@extends('layouts.app')

@section('content')
<!-- Alpine Data Scope for Lightbox -->
<div x-data="lightbox()" @keydown.escape.window="close()" @keydown.right.window="next()" @keydown.left.window="prev()">

    <!-- Official KDP Blue Header Banner -->
    <div class="bg-gradient-to-r from-[#294c9b] to-[#4074e6] text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold tracking-wide">Photo Gallery</h1>
        </div>
    </div>

    <!-- Gallery Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @forelse($albums as $album)
            <!-- Dynamic Album Section -->
            <div class="bg-white rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.06)] border border-gray-100 p-6 mb-10">
                
                <!-- Section Header -->
                <div class="flex justify-between items-center border-b-2 border-orange-500 pb-3 mb-6">
                    <h2 class="text-[#1e3a8a] text-2xl font-extrabold flex items-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        {{ $album->title }}
                    </h2>
                    <span class="bg-[#3b82f6] text-white text-xs font-bold py-2 px-5 rounded-full">
                        {{ $album->photos->count() }} Photos
                    </span>
                </div>

                <!-- Photos Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        // Create a clean array of image URLs for this specific album
                        $imageUrls = $album->photos->map(fn($photo) => asset('storage/' . $photo->image_path))->values()->toArray();
                    @endphp

                    @foreach($album->photos as $photo)
                        <!-- Image Card with Hover Effect -->
                        <div @click='open(@json($imageUrls), {{ $loop->index }})' class="relative group rounded-xl overflow-hidden cursor-pointer aspect-[4/3] bg-gray-100">
                            <!-- Image -->
                            <img src="{{ asset('storage/' . $photo->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Gallery Photo">
                            
                            <!-- Blue '+' Hover Overlay -->
                            <div class="absolute inset-0 bg-[#1e3a8a]/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-[#1e3a8a] shadow-lg transform scale-50 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($album->photos->isEmpty())
                    <p class="text-gray-400 text-sm italic">No photos uploaded to this section yet.</p>
                @endif
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                <p class="text-gray-500 font-medium">No memory sections created yet. Admin can add them soon!</p>
            </div>
        @endforelse
        
    </div>

    <!-- ============================================== -->
    <!-- The Upgraded Dark Lightbox Modal -->
    <!-- ============================================== -->
    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/95">
        
        <!-- Close Button -->
        <button @click="close()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Left Arrow -->
        <button @click.stop="prev()" x-show="images.length > 1" class="absolute left-4 sm:left-8 text-white bg-black/50 hover:bg-black/80 p-3 rounded-lg transition-all focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <!-- Image Container -->
        <div @click.away="close()" class="relative max-w-5xl w-full max-h-screen p-4 flex justify-center items-center h-full">
            <template x-if="images.length > 0">
                <img :src="images[currentIndex]" class="max-w-full max-h-[85vh] object-contain shadow-2xl rounded-sm transition-opacity duration-300" alt="Fullscreen Image">
            </template>
        </div>

        <!-- Right Arrow -->
        <button @click.stop="next()" x-show="images.length > 1" class="absolute right-4 sm:right-8 text-white bg-black/50 hover:bg-black/80 p-3 rounded-lg transition-all focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Counter Badge (e.g., 1 / 9) -->
        <div class="absolute bottom-8 bg-black/80 text-white text-sm font-bold tracking-widest px-6 py-2 rounded-full z-[60]" x-show="images.length > 0">
            <span x-text="(currentIndex + 1) + ' / ' + images.length"></span>
        </div>
        
    </div>
</div>

<!-- Alpine Lightbox Logic -->
<script>
    function lightbox() {
        return {
            isOpen: false,
            images: [],
            currentIndex: 0,
            open(imageArray, index) {
                this.images = imageArray;
                this.currentIndex = index;
                this.isOpen = true;
                document.body.style.overflow = 'hidden'; 
            },
            close() {
                this.isOpen = false;
                setTimeout(() => {
                    this.images = [];
                    this.currentIndex = 0;
                }, 300); 
                document.body.style.overflow = 'auto'; 
            },
            next() {
                if (this.currentIndex < this.images.length - 1) {
                    this.currentIndex++;
                } else {
                    this.currentIndex = 0; // Loop back to the first image
                }
            },
            prev() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                } else {
                    this.currentIndex = this.images.length - 1; // Loop to the last image
                }
            }
        }
    }
</script>
@endsection