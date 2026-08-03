@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Sleek Header -->
        <div class="mb-8 border-b border-gray-200 pb-5 px-4 sm:px-0">
            <h1 class="font-serif text-3xl font-extrabold text-[#0f2042] tracking-tight">My Inbox</h1>
            <p class="text-gray-500 mt-2 text-sm">Manage your messages and network with fellow alumni.</p>
        </div>

        <!-- Premium Message Container -->
        <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100 mb-6">
            
            @forelse($messages as $msg)
                <!-- SINGLE MESSAGE ROW (With Alpine.js toggle for reply) -->
                <div x-data="{ replying: false }" class="border-b border-gray-100 hover:bg-blue-50/30 transition-all duration-300 group">
                    
                    <div class="p-6 md:p-8 flex flex-col sm:flex-row gap-5 items-start">
                        <!-- Dynamic Avatar -->
                        <div class="relative flex-shrink-0 mt-1">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->sender->name ?? 'User') }}&background=0D8ABC&color=fff&size=120" class="w-14 h-14 rounded-full shadow-md border-2 border-white">
                        </div>
                        
                        <!-- Message Content -->
                        <div class="flex-1 w-full">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                                <!-- Sender Name -->
                                <h4 class="font-bold text-gray-900 text-lg group-hover:text-[#0f2042] transition-colors">
                                    {{ $msg->sender->name ?? 'Unknown User' }}
                                    <span class="text-xs font-normal text-gray-400 ml-1">({{ $msg->sender->email ?? '' }})</span>
                                </h4>
                                <!-- Timestamp -->
                                <span class="text-xs text-gray-400 font-medium mt-1 sm:mt-0 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $msg->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <!-- Message Body -->
                            <div class="mt-3 bg-gray-50 text-gray-700 text-sm p-4 rounded-2xl rounded-tl-none border border-gray-100 shadow-inner">
                                {{ $msg->message }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="sm:ml-4 mt-4 sm:mt-0 self-start sm:self-center flex flex-col sm:flex-row md:flex-col gap-2 w-full sm:w-auto">
                            <!-- Reply Toggle Button -->
                            <button @click="replying = !replying" class="inline-flex items-center justify-center bg-[#0f2042] text-white hover:bg-blue-800 text-xs font-bold uppercase tracking-wider py-2.5 px-5 rounded-full transition-all shadow-sm w-full sm:w-auto whitespace-nowrap">
                                Reply
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            </button>
                            
                            <!-- View Profile Button -->
                            <a href="{{ route('alumni.show', $msg->sender_id) }}" class="inline-flex items-center justify-center bg-white border border-gray-200 text-kdp-textblue hover:bg-gray-50 hover:border-kdp-textblue text-xs font-bold uppercase tracking-wider py-2.5 px-5 rounded-full transition-all shadow-sm w-full sm:w-auto whitespace-nowrap">
                                Profile
                            </a>
                        </div>
                    </div>

                    <!-- SLIDE DOWN REPLY FORM -->
                    <div x-show="replying" style="display: none;" class="px-6 md:px-8 pb-6 md:pb-8 pt-0">
                        <form method="POST" action="{{ route('alumni.message', $msg->sender_id) }}" class="flex gap-3 items-start bg-blue-50/50 p-4 rounded-2xl border border-blue-100 shadow-inner">
                            @csrf
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Me') }}&background=ea580c&color=fff" class="w-10 h-10 rounded-full hidden sm:block border-2 border-white shadow-sm mt-1">
                            
                            <div class="flex-1">
                                <textarea name="message" rows="2" placeholder="Type your reply to {{ $msg->sender->name ?? 'User' }}..." required class="w-full bg-white border border-gray-200 text-gray-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kdp-textblue focus:border-transparent text-sm resize-none shadow-sm"></textarea>
                                
                                <div class="mt-3 flex justify-end gap-2">
                                    <button type="button" @click="replying = false" class="text-xs font-bold uppercase tracking-wider py-2 px-4 text-gray-500 hover:text-gray-700 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="bg-kdp-textblue hover:bg-blue-800 text-white text-xs font-bold uppercase tracking-wider py-2 px-6 rounded-full shadow-md transition-transform transform hover:scale-105 flex items-center">
                                        Send
                                        <svg class="w-3.5 h-3.5 ml-1.5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-20 px-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-200 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Messages Yet</h3>
                    <p class="text-sm text-gray-500">Your inbox is currently empty. Reach out to fellow alumni to start networking!</p>
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
            <div class="pt-4 px-4 sm:px-0">
                {{ $messages->links() }}
            </div>
        @endif

    </div>
</div>
@endsection