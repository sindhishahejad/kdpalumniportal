@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-8 bg-slate-100 min-h-[calc(100vh-4rem)]" x-data="kdpMessengerHub({{ Auth::id() }}, {{ $activeUser?->id ?? 'null' }})">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4">

        <!-- Top Breadcrumb & Status Bar -->
        <div class="flex items-center justify-between text-xs font-heading font-semibold text-slate-500 uppercase tracking-wider px-1">
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
                <span>&rarr;</span>
                <span class="text-slate-800">Messages &amp; Campus Inbox</span>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f97316]"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
            </div>
        </div>

        <!-- Messenger Container -->
        <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-200 grid grid-cols-1 md:grid-cols-12 h-[82vh] min-h-[600px] relative">
            
            <!-- ============================================== -->
            <!-- LEFT SIDEBAR: Conversations & Contact Threads  -->
            <!-- ============================================== -->
            <div class="md:col-span-4 lg:col-span-4 border-r border-slate-200 flex flex-col bg-white h-full overflow-hidden {{ $activeUser ? 'hidden md:flex' : 'flex' }}">
                
                <!-- Sidebar Top Bar -->
                <div class="p-3.5 sm:p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#0f2042] to-[#1e3a8a] text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="leading-tight">
                            <div class="flex items-center gap-1.5">
                                <h3 class="font-heading font-bold text-slate-900 text-sm">Chats</h3>
                                <span class="px-1.5 py-0.5 text-[9px] font-black uppercase rounded bg-blue-100 text-[#1e3a8a]">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium truncate max-w-[150px]">{{ Auth::user()->name }}</p>
                        </div>
                    </div>

                    <!-- New Chat Button -->
                    <button type="button" 
                            @click="isNewChatModalOpen = true" 
                            class="px-3 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-heading font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all cursor-pointer"
                            title="Start new conversation">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>New Chat</span>
                    </button>
                </div>

                <!-- Instant Search Filter -->
                <div class="p-3 border-b border-slate-100 bg-white shrink-0">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchQuery"
                               placeholder="Search chats by name or role..." 
                               class="w-full bg-slate-100/80 text-xs rounded-xl pl-9 pr-8 py-2.5 border-none focus:ring-2 focus:ring-[#1e3a8a] focus:bg-white text-slate-800 placeholder-slate-400 transition-all">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs cursor-pointer">✕</button>
                    </div>
                </div>

                <!-- Conversations Scroll List -->
                <div class="overflow-y-auto flex-1 divide-y divide-slate-100">
                    @forelse($conversations as $conv)
                        <a href="{{ route('messages.inbox', $conv->id) }}" 
                           x-show="matchesSearch('{{ strtolower($conv->name) }}', '{{ strtolower($conv->role) }}', '{{ strtolower($conv->department ?? '') }}')"
                           class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ optional($activeUser)->id === $conv->id ? 'bg-blue-50/70 border-l-4 border-[#1e3a8a]' : '' }}">
                            
                            <!-- Avatar with Role Emblem -->
                            <div class="relative shrink-0">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                                    {{ strtoupper(substr($conv->name, 0, 2)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 text-xs">
                                    @if($conv->role === 'faculty') 🏛️
                                    @elseif($conv->role === 'student') 🎒
                                    @elseif($conv->isAdmin()) 👑
                                    @else 🎓
                                    @endif
                                </span>
                            </div>

                            <!-- Thread Details -->
                            <div class="ml-3 flex-1 overflow-hidden">
                                <div class="flex justify-between items-baseline gap-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate font-heading">{{ $conv->name }}</h4>
                                    @if($conv->latest_message_time_human)
                                        <span class="text-[10px] text-slate-400 font-medium shrink-0">{{ $conv->latest_message_time_human }}</span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between mt-0.5">
                                    <p class="text-xs text-slate-500 truncate max-w-[170px] sm:max-w-[190px]">
                                        @if($conv->latest_message)
                                            {{ $conv->latest_message }}
                                        @else
                                            <span class="text-slate-400 italic">No messages yet • Say hello</span>
                                        @endif
                                    </p>
                                    @if(!empty($conv->unread_count) && $conv->unread_count > 0)
                                        <span class="bg-[#f97316] text-white text-[10px] font-black rounded-full px-1.5 py-0.2 shrink-0 shadow-2xs animate-pulse">
                                            {{ $conv->unread_count }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[10px] text-slate-400 truncate mt-0.5 capitalize">
                                    {{ $conv->role }} @if($conv->department) • {{ $conv->department }} @endif
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs space-y-3 mt-6">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1e3a8a] mx-auto flex items-center justify-center text-xl">
                                💬
                            </div>
                            <div class="font-heading font-bold text-slate-700 text-sm">No Active Conversations</div>
                            <p class="text-slate-500 leading-relaxed text-[11px] max-w-[220px] mx-auto">
                                You haven't exchanged messages with anyone yet. Start a discussion with fellow alumni, students, or faculty.
                            </p>
                            <button type="button" 
                                    @click="isNewChatModalOpen = true" 
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#1e3a8a] text-white font-heading font-bold text-xs hover:bg-[#0f2042] transition-colors cursor-pointer">
                                <span>➕ Start First Chat</span>
                            </button>
                        </div>
                    @endforelse
                </div>

                <!-- Suggested Contacts Footer Section -->
                @if($conversations->count() > 0 && $availableContacts->count() > 0)
                    <div class="p-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-xs shrink-0">
                        <span class="text-[11px] text-slate-500 font-semibold font-heading">Need to reach someone else?</span>
                        <button type="button" @click="isNewChatModalOpen = true" class="text-xs font-bold text-[#1e3a8a] hover:underline cursor-pointer">Browse All &rarr;</button>
                    </div>
                @endif
            </div>

            <!-- ============================================== -->
            <!-- RIGHT MAIN CONTENT: Active Chat Console / Hub  -->
            <!-- ============================================== -->
            <div class="md:col-span-8 lg:col-span-8 flex flex-col bg-slate-50/50 h-full overflow-hidden {{ $activeUser ? 'flex' : 'hidden md:flex' }}">
                
                @if($activeUser)
                    <!-- Active Chat Header -->
                    <div class="px-4 sm:px-6 py-3 bg-white border-b border-slate-200 flex items-center justify-between shadow-2xs shrink-0 z-10">
                        <div class="flex items-center space-x-3">
                            <!-- Mobile Back Button -->
                            <a href="{{ route('messages.inbox') }}" 
                               class="md:hidden p-1.5 text-slate-500 hover:text-[#1e3a8a] hover:bg-slate-100 rounded-xl transition-colors"
                               title="Back to all conversations">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </a>

                            <!-- Partner Avatar -->
                            <div class="relative shrink-0">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1e3a8a] to-[#0f2042] text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                                    {{ strtoupper(substr($activeUser->name, 0, 2)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 text-xs">
                                    @if($activeUser->role === 'faculty') 🏛️
                                    @elseif($activeUser->role === 'student') 🎒
                                    @elseif($activeUser->isAdmin()) 👑
                                    @else 🎓
                                    @endif
                                </span>
                            </div>

                            <div class="leading-tight">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-heading font-bold text-slate-900 text-sm sm:text-base">{{ $activeUser->name }}</h3>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full bg-blue-100 text-[#1e3a8a]">
                                        {{ $activeUser->role }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 capitalize">
                                    {{ $activeUser->department ?? 'K. D. Polytechnic, Patan' }}
                                    @if($activeUser->company) • {{ $activeUser->company }} @endif
                                </p>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-2">
                            @if($activeUser->role === 'alumni')
                                <a href="{{ route('alumni.show', $activeUser->id) }}" 
                                   class="text-xs bg-slate-100 hover:bg-blue-50 hover:text-[#1e3a8a] text-slate-700 font-heading font-semibold py-1.5 px-3 rounded-xl transition-colors flex items-center gap-1.5 border border-slate-200">
                                    <span>👤</span>
                                    <span class="hidden sm:inline">Profile</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Messages Stream Container -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3.5 flex flex-col min-h-0 bg-slate-100/40" x-ref="chatContainer">
                        @php $lastDate = null; @endphp
                        
                        @forelse($messages as $msg)
                            @php 
                                $isMe = $msg->sender_id === Auth::id(); 
                                $msgDate = $msg->created_at->isToday() 
                                    ? 'Today' 
                                    : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('d/m/Y'));
                            @endphp

                            <!-- Date Separator Pill -->
                            @if($lastDate !== $msgDate)
                                <div class="flex justify-center my-2">
                                    <span class="bg-white/90 text-slate-500 border border-slate-200 text-[10px] px-3 py-1 rounded-full shadow-2xs font-heading font-bold uppercase tracking-wider">
                                        {{ $msgDate }}
                                    </span>
                                </div>
                                @php $lastDate = $msgDate; @endphp
                            @endif

                            <!-- Message Bubble -->
                            <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] sm:max-w-[70%] rounded-2xl px-4 py-2.5 text-xs sm:text-sm shadow-2xs relative leading-relaxed {{ $isMe ? 'bg-[#1e3a8a] text-white rounded-tr-xs' : 'bg-white text-slate-800 border border-slate-200 rounded-tl-xs' }}">
                                    <p class="whitespace-pre-wrap break-words font-medium">{{ $msg->message }}</p>
                                    <div class="text-[9px] mt-1 flex items-center justify-end space-x-1 {{ $isMe ? 'text-blue-200' : 'text-slate-400' }}">
                                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                                        @if($isMe)
                                            @if($msg->is_read)
                                                <!-- Double Blue Tick (Read) -->
                                                <span class="text-amber-300 font-bold tracking-tighter" title="Read by {{ $activeUser->name }}">✓✓</span>
                                            @else
                                                <!-- Double Gray/Light Tick (Delivered) -->
                                                <span class="text-blue-200 font-bold tracking-tighter" title="Delivered">✓✓</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="my-auto text-center space-y-2 p-6">
                                <div class="w-14 h-14 rounded-3xl bg-blue-50 text-[#1e3a8a] mx-auto flex items-center justify-center text-2xl shadow-2xs">
                                    👋
                                </div>
                                <h4 class="font-heading font-bold text-slate-800 text-sm">Say Hello to {{ $activeUser->name }}!</h4>
                                <p class="text-slate-500 text-xs max-w-sm mx-auto leading-relaxed">
                                    Start an academic, project, or mentorship conversation. Messages are safely delivered to their campus inbox and notified via email.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Sticky Bottom Input Form with AJAX & Shift+Enter -->
                    <div class="p-3 sm:p-4 bg-white border-t border-slate-200 shrink-0">
                        <form @submit.prevent="submitMessage()" class="flex items-center gap-2">
                            <input type="text" 
                                   x-ref="messageInput"
                                   x-model="newMessage" 
                                   :disabled="isSending"
                                   placeholder="Type your message to {{ $activeUser->name }}... (Press Enter to send)" 
                                   required 
                                   class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 sm:py-3 text-xs sm:text-sm focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] focus:bg-white text-slate-800 transition-colors">
                            
                            <button type="submit" 
                                    :disabled="isSending || !newMessage.trim()" 
                                    class="bg-[#1e3a8a] hover:bg-[#0f2042] disabled:opacity-50 text-white p-2.5 sm:p-3 rounded-xl transition-all flex items-center justify-center shadow-sm shrink-0 cursor-pointer"
                                    title="Send Message">
                                <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                            </button>
                        </form>
                    </div>

                @else
                    <!-- No Active Chat Selected State (Desktop Right) -->
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8 text-center space-y-6">
                        <div class="w-20 h-20 bg-blue-50 rounded-3xl border border-blue-100 flex items-center justify-center shadow-sm">
                            <img src="{{ asset('images/kdp_crest.png') }}" class="h-12 w-auto object-contain opacity-80" alt="KDP Crest">
                        </div>
                        
                        <div class="max-w-md space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                <span>🏛️</span> KDP Campus Messenger
                            </span>
                            <h3 class="text-lg font-heading font-bold text-slate-800">Direct Academic &amp; Alumni Communications</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Select an existing conversation from the left sidebar or start a new chat with fellow engineers, batchmates, and department faculty.
                            </p>
                        </div>

                        <!-- Quick Recommended Contacts Carousel/Grid -->
                        @if($availableContacts->count() > 0)
                            <div class="w-full max-w-lg pt-4 border-t border-slate-200 text-left space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-heading font-bold uppercase tracking-wider text-slate-600">Quick Start with Campus Members:</h4>
                                    <button type="button" @click="isNewChatModalOpen = true" class="text-xs font-bold text-[#1e3a8a] hover:underline cursor-pointer">View All &rarr;</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($availableContacts->take(4) as $contact)
                                        <a href="{{ route('messages.inbox', $contact->id) }}" 
                                           class="p-2.5 rounded-xl border border-slate-200 bg-white hover:border-[#1e3a8a] hover:bg-blue-50/50 transition-all flex items-center justify-between group">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <div class="w-8 h-8 rounded-lg bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ strtoupper(substr($contact->name, 0, 2)) }}
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-xs font-bold text-slate-900 truncate group-hover:text-[#1e3a8a]">{{ $contact->name }}</p>
                                                    <p class="text-[10px] text-slate-400 capitalize truncate">{{ $contact->role }}</p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-[#1e3a8a] font-bold shrink-0">Chat &rarr;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- ============================================== -->
    <!-- MODAL: "Start New Chat" Contact Directory      -->
    <!-- ============================================== -->
    <div x-show="isNewChatModalOpen" 
         x-cloak 
         @keydown.escape.window="isNewChatModalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden flex flex-col max-h-[85vh]"
             @click.away="isNewChatModalOpen = false">
            
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#1e3a8a] text-white flex items-center justify-center font-bold text-base shadow-xs">
                        ✍️
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-slate-900 text-sm sm:text-base">Start a New Conversation</h3>
                        <p class="text-xs text-slate-400">Choose a student, alumnus, or faculty member to message</p>
                    </div>
                </div>
                <button type="button" @click="isNewChatModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg cursor-pointer p-1">✕</button>
            </div>

            <!-- Modal Search Input -->
            <div class="p-4 border-b border-slate-100 bg-white">
                <div class="relative">
                    <input type="text" 
                           x-model="modalContactSearch" 
                           placeholder="Type to search contacts by name, role, or department..." 
                           class="w-full bg-slate-50 border border-slate-200 text-xs sm:text-sm rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Contacts List -->
            <div class="overflow-y-auto flex-1 divide-y divide-slate-100 p-2">
                @forelse($availableContacts as $contact)
                    <a href="{{ route('messages.inbox', $contact->id) }}" 
                       x-show="modalMatchesSearch('{{ strtolower($contact->name) }}', '{{ strtolower($contact->role) }}', '{{ strtolower($contact->department ?? '') }}')"
                       class="flex items-center justify-between p-3 rounded-2xl hover:bg-blue-50/70 transition-colors group">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                {{ strtoupper(substr($contact->name, 0, 2)) }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate group-hover:text-[#1e3a8a]">{{ $contact->name }}</h4>
                                    <span class="px-2 py-0.2 text-[9px] font-black uppercase rounded bg-slate-100 text-slate-700">
                                        {{ $contact->role }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                    {{ $contact->department ?? 'Engineering Student/Alumnus' }} • {{ $contact->email }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[#1e3a8a] group-hover:bg-[#1e3a8a] group-hover:text-white font-heading font-bold text-xs shadow-2xs transition-all shrink-0">
                            Chat &rarr;
                        </span>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        No additional registered members available.
                    </div>
                @endforelse
            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>⚡ Messages are confidential &amp; sent in real-time</span>
                <a href="{{ route('alumni.index') }}" class="text-[#1e3a8a] font-bold hover:underline">Full Alumni Directory &rarr;</a>
            </div>

        </div>
    </div>

</div>

<!-- Alpine.js Live Messenger Controller -->
<script>
function kdpMessengerHub(currentUserId, activeUserId) {
    return {
        searchQuery: '',
        modalContactSearch: '',
        isNewChatModalOpen: false,
        newMessage: '',
        isSending: false,

        init() {
            this.$nextTick(() => {
                this.scrollToBottom();
                if (this.$refs.messageInput) {
                    this.$refs.messageInput.focus();
                }
            });
            this.initEcho();
        },

        matchesSearch(name, role, department) {
            if (!this.searchQuery) return true;
            const q = this.searchQuery.toLowerCase().trim();
            return name.includes(q) || role.includes(q) || department.includes(q);
        },

        modalMatchesSearch(name, role, department) {
            if (!this.modalContactSearch) return true;
            const q = this.modalContactSearch.toLowerCase().trim();
            return name.includes(q) || role.includes(q) || department.includes(q);
        },

        async submitMessage() {
            const text = this.newMessage.trim();
            if (!text || !activeUserId || this.isSending) return;

            this.isSending = true;

            // Optimistically append message to stream
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const tempBubble = document.createElement('div');
            tempBubble.className = 'flex w-full justify-end';
            tempBubble.innerHTML = `
                <div class="max-w-[85%] sm:max-w-[70%] rounded-2xl px-4 py-2.5 text-xs sm:text-sm shadow-2xs relative leading-relaxed bg-[#1e3a8a] text-white rounded-tr-xs">
                    <p class="whitespace-pre-wrap break-words font-medium">${this.escapeHtml(text)}</p>
                    <div class="text-[9px] mt-1 flex items-center justify-end space-x-1 text-blue-200">
                        <span>${timeStr}</span>
                        <span class="text-blue-200 font-bold tracking-tighter" title="Sending...">✓</span>
                    </div>
                </div>
            `;
            
            if (this.$refs.chatContainer) {
                this.$refs.chatContainer.appendChild(tempBubble);
                this.scrollToBottom();
            }

            this.newMessage = '';

            try {
                const response = await fetch(`{{ url('/alumni') }}/${activeUserId}/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: text })
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update checkmark to double delivered ticks
                    const tickEl = tempBubble.querySelector('span[title="Sending..."]');
                    if (tickEl) {
                        tickEl.textContent = '✓✓';
                        tickEl.title = 'Delivered';
                    }
                }
            } catch (err) {
                console.warn('AJAX submit fallback error:', err);
            } finally {
                this.isSending = false;
                this.scrollToBottom();
                if (this.$refs.messageInput) {
                    this.$refs.messageInput.focus();
                }
            }
        },

        escapeHtml(str) {
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },

        scrollToBottom() {
            const container = this.$refs.chatContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        initEcho() {
            if (!activeUserId) return;
            
            let attempts = 0;
            let checkEcho = setInterval(() => {
                attempts++;
                if (window.Echo) {
                    clearInterval(checkEcho);
                    window.Echo.private(`inbox.${currentUserId}`)
                        .listen('.message.sent', (e) => {
                            if (Number(e.sender_id) === Number(activeUserId)) {
                                const container = this.$refs.chatContainer;
                                if (container) {
                                    const incoming = document.createElement('div');
                                    incoming.className = 'flex w-full justify-start';
                                    incoming.innerHTML = `
                                        <div class="max-w-[85%] sm:max-w-[70%] rounded-2xl px-4 py-2.5 text-xs sm:text-sm shadow-2xs relative leading-relaxed bg-white text-slate-800 border border-slate-200 rounded-tl-xs">
                                            <p class="whitespace-pre-wrap break-words font-medium">${this.escapeHtml(e.message)}</p>
                                            <div class="text-[9px] text-slate-400 text-right mt-1">${e.created_at || 'Just now'}</div>
                                        </div>
                                    `;
                                    container.appendChild(incoming);
                                    container.scrollTop = container.scrollHeight;
                                }
                            }
                        });
                } else if (attempts > 20) {
                    clearInterval(checkEcho);
                }
            }, 100);
        }
    };
}
</script>
@endsection
