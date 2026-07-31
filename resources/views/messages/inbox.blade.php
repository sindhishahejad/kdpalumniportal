@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-xl sm:rounded-sm border-t-4 border-[#8b0000] p-8 space-y-6">
        
        <h1 class="text-2xl font-serif font-bold text-[#1C3661] border-b pb-4">My Inbox</h1>

        @forelse($messages as $msg)
            <div class="border border-gray-200 rounded-sm p-5 space-y-2 hover:shadow-md transition-shadow bg-gray-50/50">
                <div class="flex justify-between items-center text-xs text-gray-500 border-b pb-2">
                    <span>From: <strong class="text-gray-800">{{ $msg->sender->name ?? 'Unknown User' }}</strong> ({{ $msg->sender->email ?? '' }})</span>
                    <span>{{ $msg->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed pt-1">
                    {{ $msg->message }}
                </p>
                <div class="pt-2 flex justify-end">
                    <a href="{{ route('alumni.show', $msg->sender_id) }}" class="text-xs font-semibold text-[#8b0000] hover:underline">
                        View Sender Profile &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500 border border-dashed border-gray-300 rounded-sm">
                <p class="text-sm">Your inbox is currently empty.</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $messages->links() }}
        </div>

    </div>
</div>
@endsection