@extends('layouts.app')

@section('content')
<div class="py-6 bg-gray-100 min-h-[calc(100vh-4rem)]">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- WhatsApp Web Container Wrapper -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200 grid grid-cols-1 md:grid-cols-12 h-[80vh]">
            
            <!-- LEFT SIDEBAR: Contact Threads List -->
            <div class="md:col-span-4 lg:col-span-3 border-r border-gray-200 flex flex-col bg-white h-full overflow-hidden">
                
                <!-- Sidebar Header -->
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=075e54&color=fff" class="w-10 h-10 rounded-full shadow-sm">
                        <span class="font-bold text-gray-800 text-sm">Chats</span>
                    </div>
                </div>

                <!-- Search Filter -->
                <div class="p-3 border-b border-gray-100 bg-white flex-shrink-0">
                    <div class="relative">
                        <input type="text" placeholder="Search chats" class="w-full bg-gray-100 text-xs rounded-lg pl-9 pr-4 py-2 border-none focus:ring-1 focus:ring-emerald-600">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Conversations Scroll List -->
                <div class="overflow-y-auto flex-1 divide-y divide-gray-100">
                    @forelse($conversations as $conv)
                        <a href="{{ route('messages.inbox', $conv->id) }}" 
                           class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors {{ optional($activeUser)->id === $conv->id ? 'bg-gray-100' : '' }}">
                            <div class="relative flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($conv->name) }}&background=128c7e&color=fff" class="w-12 h-12 rounded-full">
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="ml-3 flex-1 overflow-hidden">
                                <div class="flex justify-between items-baseline">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $conv->name }}</h4>
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-0.5 capitalize">{{ $conv->role }} • {{ $conv->department ?? 'N/A' }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-gray-400 text-xs mt-10">
                            No conversations yet.<br>Visit the <a href="{{ route('alumni.index') }}" class="text-emerald-600 underline">Alumni Directory</a> to message someone!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- RIGHT MAIN CONTENT: Active Chat Window -->
            <div class="md:col-span-8 lg:col-span-9 flex flex-col bg-[#efeae2] h-full overflow-hidden" 
                 x-data="chatComponent({{ Auth::id() }}, {{ $activeUser?->id ?? 'null' }})"
                 x-init="initEcho()">
                
                @if($activeUser)
                    <!-- Chat Header -->
                    <div class="px-6 py-3 bg-white border-b border-gray-200 flex items-center justify-between shadow-sm flex-shrink-0 z-10">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($activeUser->name) }}&background=128c7e&color=fff" class="w-10 h-10 rounded-full">
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">{{ $activeUser->name }}</h3>
                                <p class="text-[11px] text-gray-500 capitalize">{{ $activeUser->role }} @if($activeUser->department) • {{ $activeUser->department }} @endif</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('alumni.show', $activeUser->id) }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-1.5 px-3 rounded-md transition-colors">View Profile</a>
                        </div>
                    </div>

                    <!-- Messages Scroll Area with Date Dividers & WhatsApp Ticks -->
<div class="flex-1 overflow-y-auto p-6 space-y-3 flex flex-col min-h-0" x-ref="chatContainer">
    @php $lastDate = null; @endphp
    
    @foreach($messages as $msg)
        @php 
            $isMe = $msg->sender_id === Auth::id(); 
            
            // Format WhatsApp-style Date Header
            $msgDate = $msg->created_at->isToday() 
                ? 'Today' 
                : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('d/m/Y'));
        @endphp

        <!-- Date Separator Pill -->
        @if($lastDate !== $msgDate)
            <div class="flex justify-center my-3">
                <span class="bg-[#e1f3fb] text-gray-600 text-[11px] px-3 py-1 rounded-lg shadow-sm font-medium uppercase tracking-wider">
                    {{ $msgDate }}
                </span>
            </div>
            @php $lastDate = $msgDate; @endphp
        @endif

        <!-- Message Bubble -->
        <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[70%] rounded-lg px-4 py-2 text-sm shadow-sm relative group {{ $isMe ? 'bg-[#dcf8c6] text-gray-900 rounded-tr-none' : 'bg-white text-gray-900 rounded-tl-none' }}">
                <p class="text-xs sm:text-sm leading-relaxed break-words">{{ $msg->message }}</p>
                <div class="text-[10px] text-gray-400 text-right mt-1 flex items-center justify-end space-x-1">
                    <span>{{ $msg->created_at->format('h:i A') }}</span>
                    @if($isMe)
                        @if($msg->is_read)
                            <!-- Double Blue Tick (Seen) -->
                            <span class="text-blue-500 font-bold tracking-tighter" title="Read">✓✓</span>
                        @else
                            <!-- Double Gray Tick (Delivered / Unread) -->
                            <span class="text-gray-400 font-bold tracking-tighter" title="Delivered">✓✓</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

                    <!-- Sticky Bottom Input Form (Flex-shrink-0 ensures it never gets cut off) -->
                    <div class="p-3 bg-white border-t border-gray-200 flex-shrink-0">
                        <form method="POST" action="{{ route('alumni.message', $activeUser->id) }}" @submit="scrollToBottom" class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="message" x-model="newMessage" placeholder="Type a message..." required 
                                   class="flex-1 bg-gray-100 border-none rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-600">
                            
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white p-3 rounded-lg transition-colors flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- No Active Chat Selected State -->
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-6 text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-700">KD Polytechnic WhatsApp Chat</h3>
                        <p class="text-xs text-gray-500 mt-1">Select a conversation from the left sidebar to start messaging in real time.</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

<!-- Alpine.js Live Real-time Echo Listener Logic with Debug Logs -->
<script>
function chatComponent(currentUserId, activeUserId) {
    return {
        newMessage: '',
        initEcho() {
            if (!activeUserId) return;
            
            // Auto scroll to bottom on load
            this.$nextTick(() => {
                let container = this.$refs.chatContainer;
                if(container) container.scrollTop = container.scrollHeight;
            });

            // Poll/Wait for window.Echo if app.js is still loading
            let attempts = 0;
            let checkEcho = setInterval(() => {
                attempts++;
                if (window.Echo) {
                    clearInterval(checkEcho);
                    console.log(`🔗 Subscribing to private channel: inbox.${currentUserId}`);
                    
                    window.Echo.private(`inbox.${currentUserId}`)
                        .listen('.message.sent', (e) => {
                            console.log('✨ Live message event successfully captured:', e);
                            
                            if (Number(e.sender_id) === Number(activeUserId)) {
                                let container = this.$refs.chatContainer;
                                if(container) {
                                    let html = `
                                        <div class="flex w-full justify-start">
                                            <div class="max-w-[70%] rounded-lg px-4 py-2 text-sm shadow-sm relative group bg-white text-gray-900 rounded-tl-none">
                                                <p class="text-xs sm:text-sm leading-relaxed break-words">${e.message}</p>
                                                <div class="text-[10px] text-gray-400 text-right mt-1">${e.created_at}</div>
                                            </div>
                                        </div>
                                    `;
                                    container.insertAdjacentHTML('beforeend', html);
                                    container.scrollTop = container.scrollHeight;
                                }
                            }
                        });
                } else if (attempts > 20) {
                    clearInterval(checkEcho);
                    console.warn('❌ Laravel Echo failed to load.');
                }
            }, 100);
        },
        scrollToBottom() {
            let container = this.$refs.chatContainer;
            if(container) container.scrollTop = container.scrollHeight;
        }
    }
}
</script>
@endsection