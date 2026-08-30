@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif font-extrabold text-[#1C3661]">Alumni Success Stories</h1>
        <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Discover how K. D. Polytechnic graduates are breaking boundaries, driving innovation, and leading industries across the globe.</p>
    </div>

    @if($stories->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-gray-500 italic">No success stories available at the moment. Check back soon!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stories as $story)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                    @if($story->image_path)
                        <div class="h-56 w-full bg-gray-200">
                            <img src="{{ asset('storage/' . $story->image_path) }}" alt="{{ $story->alumni_name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-56 w-full bg-gradient-to-br from-[#1C3661] to-blue-800 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($story->alumni_name, 0, 1) }}
                        </div>
                    @endif
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">{{ $story->department }}</span>
                            <span class="text-xs text-gray-500 font-medium">Batch {{ $story->batch_year }}</span>
                        </div>
                        <h3 class="font-serif font-bold text-xl text-gray-900 mb-2 leading-snug">{{ $story->title }}</h3>
                        <p class="text-xs font-bold text-gray-700 mb-4">— {{ $story->alumni_name }}</p>
                        <p class="text-sm text-gray-600 line-clamp-4 leading-relaxed mt-auto">{{ $story->story }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection