@extends('layouts.app')

@section('content')
<div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Large Cover Image -->
        @if($event->image_path)
            <div class="w-full h-64 sm:h-96 bg-gray-200 relative">
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8 sm:p-12">
            <!-- Back Button & Category Badge -->
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-[#3b82f6] hover:text-blue-800 transition-colors">
                    &larr; Back to Dashboard
                </a>
                <span class="bg-blue-50 text-blue-600 text-[10px] font-extrabold uppercase tracking-widest py-1.5 px-3 rounded-full">
                    {{ $event->category }}
                </span>
            </div>

            <!-- Event Title -->
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-[#0f172a] mb-6 leading-tight">
                {{ $event->title }}
            </h1>

            <!-- Date & Time Row -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 mb-10 pb-8 border-b border-gray-100">
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-bold tracking-wide">{{ $event->event_date->format('l, F j, Y') }}</span>
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold tracking-wide">{{ $event->time_display }}</span>
                </div>
            </div>

            <!-- Event Description -->
            <div class="prose max-w-none text-gray-600 leading-relaxed text-lg">
                @if($event->description)
                    {!! nl2br(e($event->description)) !!}
                @else
                    <p class="italic text-gray-400 text-sm">No additional details provided for this event.</p>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection