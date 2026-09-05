@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4">
        <a href="{{ route('labs.index') }}" class="text-sm font-semibold text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Lab Directory</a>
        
        <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 px-3 py-1 rounded-full">{{ $lab->department }}</span>
                    <h1 class="text-3xl font-serif font-bold text-[#0f172a] mt-2">{{ $lab->name }}</h1>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 font-semibold uppercase">Location</p>
                    <p class="text-sm font-bold text-[#0f172a]">{{ $lab->location }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-bold text-[#0f172a]">Overview</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $lab->description }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                    <h3 class="text-sm font-bold text-[#0f172a] mb-2 uppercase tracking-wide">Coordinator</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $lab->faculty_in_charge }}</p>
                    <p class="text-xs text-gray-500 mt-1">Responsible for lab safety, scheduling, and practical allocations.</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-[#0f172a] mb-3">Hardware & Equipment Inventory</h3>
            <div class="bg-blue-50/50 border border-blue-100 p-5 rounded-2xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(explode(',', $lab->equipment_list) as $item)
                        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border border-blue-100 shadow-xs">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm font-medium text-gray-700">{{ trim($item) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection