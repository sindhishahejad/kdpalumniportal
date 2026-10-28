<!-- 🤖 KDP Mitra AI Chatbot Widget (Powered by Groq API) -->
<div x-data="kdpChatbotWidget()" 
     x-cloak 
     @keydown.escape.window="isOpen = false"
     class="fixed bottom-6 right-6 z-[90] font-sans">
    
    <!-- Floating Trigger Launcher Button -->
    <button type="button" 
            x-show="!isOpen" 
            @click="toggleChat()" 
            class="group relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f2042] text-white shadow-2xl hover:shadow-blue-900/40 border-2 border-amber-400/40 hover:scale-105 transition-all duration-300 cursor-pointer"
            title="Chat with KDP Mitra (AI Campus Counselor)">
        
        <!-- Saffron Pulse Ring -->
        <span class="absolute -top-1 -right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#f97316] opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-[#f97316] border-2 border-white"></span>
        </span>

        <!-- AI Assistant Icon -->
        <div class="flex items-center justify-center">
            <svg class="w-7 h-7 text-white transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </div>
    </button>

    <!-- Floating Chat Window / Drawer -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-95"
         class="bg-white rounded-3xl shadow-2xl border border-slate-200 flex flex-col w-[92vw] sm:w-[410px] h-[580px] max-h-[85vh] overflow-hidden">
        
        <!-- Header with Institutional Branding -->
        <div class="bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white p-4 sm:p-4.5 flex items-center justify-between border-b-2 border-[#f97316] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center p-1 relative shrink-0">
                    <img src="{{ asset('images/kdp_crest.png') }}" class="w-full h-full object-contain filter brightness-110" alt="KDP Crest">
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 rounded-full border-2 border-[#0f2042]"></span>
                </div>
                <div class="leading-tight">
                    <div class="flex items-center gap-1.5">
                        <h3 class="font-heading font-black text-sm text-white tracking-wide">KDP Mitra</h3>
                        <span class="px-1.5 py-0.2 text-[9px] font-black uppercase tracking-wider rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">AI</span>
                    </div>
                    <p class="text-[10px] text-blue-200 font-medium">કિલાચંદ એઆઈ મિત્ર • Groq LLaMA 3.3</p>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-1.5">
                <a href="{{ route('chatbot.index') }}" 
                   title="Open Fullscreen Assistant" 
                   class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </a>
                <button type="button" 
                        @click="clearChat()" 
                        title="Clear Conversation" 
                        class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <button type="button" 
                        @click="isOpen = false" 
                        title="Minimize" 
                        class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Scrollable Conversation Messages Area -->
        <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3.5 bg-slate-50/70 text-xs">
            
            <!-- Welcome Greeting -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-2xs space-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-base">🏛️</span>
                    <span class="font-heading font-bold text-slate-900 text-xs">Welcome to K. D. Polytechnic Patan!</span>
                </div>
                <p class="text-slate-600 leading-relaxed">
                    I am **KDP Mitra**, your AI counselor. I can guide you on **Transcripts &amp; Documents**, **TPO Placements**, **Mentorship**, **Tool Bank claims**, and **GTU Exam circulars** in English, ગુજરાતી, or हिन्दी.
                </p>
                <div class="text-[10px] text-slate-400 font-medium">Click any topic below or ask your own question:</div>
            </div>

            <!-- Quick Starter Chips (Visible initially or when conversation is short) -->
            <div x-show="messages.length === 0" class="flex flex-wrap gap-1.5 pt-1">
                <button type="button" @click="sendPrompt('How do I apply for Transcripts and Bonafide certificates?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs">
                    📜 Apply for Transcripts
                </button>
                <button type="button" @click="sendPrompt('Which companies recruit diploma students through TPO?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs">
                    🏢 TPO Placement Recruiters
                </button>
                <button type="button" @click="sendPrompt('How can I claim free mini drafter or books from the tool bank?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs">
                    🛠️ Free Tools &amp; Drafters
                </button>
                <button type="button" @click="sendPrompt('How can I connect with an alumni mentor?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs">
                    🤝 1-on-1 Mentorship
                </button>
                <button type="button" @click="sendPrompt('કેડીપી પાટણ પોલિટેકનિક વિશે માહિતી આપો')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs">
                    🇮🇳 ગુજરાતી માહિતી
                </button>
            </div>

            <!-- Message Bubbles Loop -->
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    <!-- User Message -->
                    <template x-if="msg.role === 'user'">
                        <div class="flex justify-end pl-8">
                            <div class="bg-[#1e3a8a] text-white px-3.5 py-2.5 rounded-2xl rounded-tr-xs shadow-2xs leading-relaxed max-w-[85%] whitespace-pre-wrap font-medium">
                                <span x-text="msg.content"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Assistant Message -->
                    <template x-if="msg.role === 'assistant'">
                        <div class="flex justify-start pr-6 gap-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#1e3a8a] to-[#0f2042] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5 shadow-2xs">
                                🤖
                            </div>
                            <div class="bg-white border border-slate-200 text-slate-800 p-3.5 rounded-2xl rounded-tl-xs shadow-2xs leading-relaxed max-w-[92%] space-y-1.5">
                                <div class="prose prose-xs max-w-none text-slate-800" x-html="renderMarkdown(msg.content)"></div>
                                <div class="flex items-center justify-between text-[9px] text-slate-400 pt-1 border-t border-slate-100">
                                    <span x-text="msg.model ? msg.model : 'KDP Mitra'"></span>
                                    <button type="button" @click="copyText(msg.content)" class="text-slate-400 hover:text-slate-600 transition-colors">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Loading Typing Indicator -->
            <div x-show="isLoading" class="flex justify-start gap-2">
                <div class="w-6 h-6 rounded-full bg-[#1e3a8a] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">
                    🤖
                </div>
                <div class="bg-white border border-slate-200 px-4 py-3 rounded-2xl rounded-tl-xs shadow-2xs flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce [animation-delay:0.4s]"></span>
                    <span class="text-[10px] text-slate-400 ml-1.5 font-medium">Consulting KDP database...</span>
                </div>
            </div>

        </div>

        <!-- Input Box & Actions Bar -->
        <div class="p-3 bg-white border-t border-slate-200 shrink-0 space-y-2">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input type="text" 
                       x-ref="messageInput"
                       x-model="inputMessage" 
                       :disabled="isLoading"
                       placeholder="Ask KDP Mitra... (English, ગુજરાતી, हिन्दी)" 
                       class="flex-1 px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:border-[#1e3a8a] focus:ring-[#1e3a8a] focus:bg-white text-slate-800 disabled:opacity-60 transition-colors">
                
                <button type="submit" 
                        :disabled="isLoading || !inputMessage.trim()" 
                        class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] disabled:opacity-50 text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center justify-center shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
            
            <div class="flex items-center justify-between text-[10px] text-slate-400 px-1">
                <span>⚡ Powered by <strong>Groq API</strong> (LLaMA 3.3)</span>
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Online
                </span>
            </div>
        </div>

    </div>
</div>

<script>
function kdpChatbotWidget() {
    return {
        isOpen: false,
        isLoading: false,
        inputMessage: '',
        messages: [],

        init() {
            this.fetchHistory();
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (this.$refs.messageInput) {
                        this.$refs.messageInput.focus();
                    }
                });
            }
        },

        async fetchHistory() {
            try {
                const res = await fetch('{{ route("chatbot.history") }}');
                const data = await res.json();
                if (data.success && Array.isArray(data.history)) {
                    this.messages = data.history;
                }
            } catch (e) {
                console.warn('Failed to load chat history:', e);
            }
        },

        sendPrompt(text) {
            this.inputMessage = text;
            this.sendMessage();
        },

        async sendMessage() {
            const msg = this.inputMessage.trim();
            if (!msg || this.isLoading) return;

            this.messages.push({
                role: 'user',
                content: msg,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });

            this.inputMessage = '';
            this.isLoading = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const response = await fetch('{{ route("chatbot.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await response.json();
                if (data.success && data.reply) {
                    this.messages.push({
                        role: 'assistant',
                        content: data.reply,
                        model: data.model || 'Groq LLaMA 3.3',
                        source: data.source || 'groq',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } else {
                    this.messages.push({
                        role: 'assistant',
                        content: 'Sorry, I encountered a temporary network issue. Please ask again or visit our [Contact Desk](/contact).',
                        model: 'System Notification',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                }
            } catch (err) {
                this.messages.push({
                    role: 'assistant',
                    content: 'An error occurred while connecting to KDP Mitra. Please check your connection and try again.',
                    model: 'System Notification',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
            } finally {
                this.isLoading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async clearChat() {
            if (!confirm('Clear your conversation history with KDP Mitra?')) return;
            try {
                await fetch('{{ route("chatbot.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                this.messages = [];
            } catch (e) {
                console.warn('Failed to clear chat:', e);
            }
        },

        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        copyText(text) {
            navigator.clipboard.writeText(text);
            alert('Copied message to clipboard!');
        },

        renderMarkdown(text) {
            if (!text) return '';
            let html = text
                // Headers
                .replace(/^### (.*$)/gim, '<h4 class="font-bold text-xs text-slate-900 mt-2 mb-1">$1</h4>')
                .replace(/^## (.*$)/gim, '<h3 class="font-bold text-sm text-slate-900 mt-2 mb-1">$1</h3>')
                // Bold
                .replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-slate-900">$1</strong>')
                // Italic
                .replace(/\*(.*?)\*/gim, '<em class="italic">$1</em>')
                // Markdown Links [text](url)
                .replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2" class="text-[#1e3a8a] font-bold underline hover:text-[#f97316] transition-colors">$1</a>')
                // Bullet points
                .replace(/^\s*• (.*$)/gim, '<div class="flex items-start gap-1.5 my-0.5"><span class="text-[#f97316] font-bold">•</span><span>$1</span></div>')
                .replace(/^\s*\- (.*$)/gim, '<div class="flex items-start gap-1.5 my-0.5"><span class="text-[#f97316] font-bold">•</span><span>$1</span></div>')
                // Line breaks
                .replace(/\n/gim, '<br>');
            return html;
        }
    };
}
</script>
