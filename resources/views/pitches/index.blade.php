@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Project Incubation Board</h1>
            @if(auth()->user()->role === 'student')
                <a href="{{ route('pitches.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Pitch a Project</a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pitches as $pitch)
                <div class="bg-white border rounded-lg p-5 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-semibold">{{ $pitch->title }}</h2>
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">{{ ucfirst($pitch->status) }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">By {{ $pitch->student->name ?? 'Student' }}</p>
                    <p class="text-gray-700 mb-4 line-clamp-3">{{ $pitch->description }}</p>
                    
                    <div class="mb-2">
                        <span class="text-xs font-bold text-gray-600 uppercase">Tech Stack:</span>
                        <p class="text-sm text-blue-600">{{ $pitch->tech_stack }}</p>
                    </div>
                    
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Needs:</span>
                        <p class="text-sm text-orange-600">{{ $pitch->assistance_needed }}</p>
                    </div>
                    
                    @if(auth()->user()->role === 'alumni')
                        <button class="mt-4 w-full bg-gray-100 text-gray-800 border py-2 rounded hover:bg-gray-200">Offer Guidance</button>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 col-span-3 text-center py-8">No project pitches submitted yet.</p>
            @endforelse
        </div>
    </div>
@endsection