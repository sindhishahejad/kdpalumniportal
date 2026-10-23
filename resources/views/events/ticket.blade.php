@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="bg-white border border-gray-200 rounded-3xl shadow-lg overflow-hidden">
            <div class="bg-[#0f172a] text-white p-6 text-center">
                <span class="text-xs uppercase tracking-widest font-bold bg-blue-600 px-3 py-1 rounded-full">Verified Entry Pass</span>
                <h1 class="text-2xl font-serif font-bold mt-3">{{ $rsvp->event->title ?? 'KDP Campus Event' }}</h1>
                <p class="text-gray-400 text-xs mt-1">K. D. Polytechnic Alumni Association</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-6">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">Attendee Name</p>
                        <p class="text-sm font-semibold text-[#0f172a] mt-1">{{ $rsvp->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">Role / Batch</p>
                        <p class="text-sm font-semibold text-[#0f172a] mt-1">{{ ucfirst($rsvp->user->role) }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 border border-dashed border-gray-300 p-6 rounded-2xl text-center space-y-3">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Unique Ticket Code</p>
                    <div class="text-xl font-mono font-bold text-blue-600 bg-white py-2 px-4 rounded-xl border border-gray-200 inline-block shadow-xs">
                        {{ $rsvp->ticket_code }}
                    </div>
                    <p class="text-xs text-gray-400">Present this code or screenshot at the venue gate for verification.</p>
                </div>

                <div class="flex gap-4">
                    <button onclick="window.print()" class="w-full bg-[#0f172a] text-white py-3 rounded-xl font-semibold text-sm hover:bg-black transition-colors">
                        Print / Save Pass
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection