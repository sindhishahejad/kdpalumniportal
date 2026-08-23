@extends('layouts.app')

@section('content')
<!-- Alpine Data Scope for Lightbox -->
<div x-data="lightbox()">

    <!-- Blue Header Banner (Matches image_5771eb) -->
    <div class="bg-[#3b82f6] text-white py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-2 tracking-wide">Photo Gallery</h1>
            <p class="text-blue-100 text-sm font-medium">Home / Campus / Gallery</p>
        </div>
    </div>

    <!-- Gallery Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @forelse($albums as $album)
            <!-- Dynamic Album Section (Matches image_577282) -->
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
                    @foreach($album->photos as $photo)
                        <!-- Image Card with Hover Effect (Matches image_577a0a) -->
                        <div @click="open('{{ asset('storage/' . $photo->image_path) }}')" class="relative group rounded-xl overflow-hidden cursor-pointer aspect-[4/3] bg-gray-100">
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
                
                <!-- Fallback if album has no photos yet -->
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
    <!-- The Dark Lightbox Modal (Matches image_577a2c) -->
    <!-- ============================================== -->
    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/95">
        
        <!-- Close Button -->
        <button @click="close()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Image Container -->
        <div @click.away="close()" class="relative max-w-5xl w-full max-h-screen p-4 flex justify-center items-center">
            <img :src="imageUrl" x-show="isOpen" x-transition.opacity.duration.300ms class="max-w-full max-h-[85vh] object-contain shadow-2xl rounded-sm" alt="Fullscreen Image">
        </div>
        
    </div>

</div>

<!-- Alpine Lightbox Logic -->
<script>
    function lightbox() {
        return {
            isOpen: false,
            imageUrl: '',
            open(url) {
                this.imageUrl = url;
                this.isOpen = true;
                document.body.style.overflow = 'hidden'; // Prevents background scrolling
            },
            close() {
                this.isOpen = false;
                setTimeout(() => this.imageUrl = '', 300); // Wait for transition to clear image
                document.body.style.overflow = 'auto'; // Restores scrolling
            }
        }
    }
</script>
@endsection