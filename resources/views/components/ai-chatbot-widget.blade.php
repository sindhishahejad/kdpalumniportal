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
         class="bg-white rounded-3xl shadow-2xl border border-slate-200 flex flex-col w-[94vw] sm:w-[440px] h-[600px] max-h-[86vh] overflow-hidden">
        
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
                        <span class="px-1.5 py-0.2 text-[9px] font-black uppercase tracking-wider rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">AI Academic Counselor</span>
                    </div>
                    <p class="text-[10px] text-blue-200 font-medium">કિલાચંદ એઆઈ મિત્ર • K. D. Polytechnic, Patan</p>
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
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-amber-50 text-amber-900 text-[11px] font-semibold border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-[#f97316]"></span>
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                    </span>
                    <span class="text-base">🏛️</span>
                </div>
                <div class="font-heading font-bold text-slate-900 text-xs">
                    Welcome to KDP Mitra Academic Counselor!
                </div>
                <p class="text-slate-600 leading-relaxed text-xs">
                    Ask me about **Official Transcripts**, **GTU Old Papers (PYQs)**, **GTU Results**, **TPO Placements**, **D2D Admissions**, or **Book &amp; Tool Bank**.
                </p>
                <div class="text-[10px] text-slate-400 font-medium">Quick academic inquiries:</div>
            </div>

            <!-- Quick Starter Chips (Visible initially or when conversation is short) -->
            <div x-show="messages.length === 0" class="flex flex-wrap gap-1.5 pt-1">
                <button type="button" @click="sendPrompt('How do I apply for Transcripts and Bonafide certificates?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    📜 Apply for Transcripts
                </button>
                <button type="button" @click="sendPrompt('Where can I download GTU diploma old question papers (PYQs)?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    📖 GTU Old Papers (PYQ)
                </button>
                <button type="button" @click="sendPrompt('How can I check GTU diploma exam results and grade card?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    📊 GTU Results Portal
                </button>
                <button type="button" @click="sendPrompt('Which companies recruit diploma students through TPO?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    🏢 TPO Placement Recruiters
                </button>
                <button type="button" @click="sendPrompt('How does ACPC D2D degree engineering admission work?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    🎓 ACPC D2D Admissions
                </button>
                <button type="button" @click="sendPrompt('How can I claim free mini drafter or books from the tool bank?')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
                    🛠️ Free Tools &amp; Drafters
                </button>
                <button type="button" @click="sendPrompt('કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ વિશે ગુજરાતીમાં માહિતી આપો')" class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[#1e3a8a] hover:bg-blue-50 font-semibold text-[11px] transition-colors shadow-2xs cursor-pointer">
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
                        <div class="flex justify-start pr-4 gap-2.5">
                            <div class="w-7 h-7 rounded-2xl bg-gradient-to-br from-[#1e3a8a] to-[#0f2042] text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-2xs">
                                🤖
                            </div>
                            <div class="bg-white border border-slate-200 text-slate-800 p-3.5 rounded-2xl rounded-tl-xs shadow-2xs leading-relaxed max-w-[92%] space-y-2">
                                <!-- Enhanced Clean Markdown Rendering -->
                                <div class="chat-markdown max-w-none" x-html="renderMarkdown(msg.content)"></div>
                                <div class="flex items-center justify-between text-[9px] text-slate-400 pt-1.5 border-t border-slate-100">
                                    <span x-text="msg.model ? msg.model : 'KDP Mitra'"></span>
                                    <button type="button" @click="copyText(msg.content)" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
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
                <div class="w-7 h-7 rounded-2xl bg-[#1e3a8a] text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                    🤖
                </div>
                <div class="bg-white border border-slate-200 px-4 py-3 rounded-2xl rounded-tl-xs shadow-2xs flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce [animation-delay:0.4s]"></span>
                    <span class="text-[10px] text-slate-400 ml-1.5 font-medium">Consulting KDP &amp; GTU database...</span>
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
                       placeholder="Ask about GTU papers, results, transcripts, subjects... (English/ગુજરાતી)" 
                       class="flex-1 px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:border-[#1e3a8a] focus:ring-[#1e3a8a] focus:bg-white text-slate-800 disabled:opacity-60 transition-colors">
                
                <button type="submit" 
                        :disabled="isLoading || !inputMessage.trim()" 
                        class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] disabled:opacity-50 text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center justify-center shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
            
            <div class="flex items-center justify-between text-[10px] text-slate-400 px-1">
                <span>⚡ Powered by <strong>Groq API</strong></span>
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Academic Counselor
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
                        model: data.model || 'Groq LLaMA',
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
            try {
                // 1. Normalize unicode bullets into markdown dashes so lists render as clean <ul><li>
                let normalized = text.replace(/^[\s]*[•·●○▪▫]\s*/gm, '- ');

                // 2. Auto-link bare portal relative paths (e.g. " /alumni " -> " [/alumni](/alumni) ")
                normalized = normalized.replace(/(^|[^(\[])(\/(?:alumni|mentorship|placement|documents|tools|donations|events|pitches|awards|card|faculty|jobs|stories|academic-calendar|contact|dashboard)[a-zA-Z0-9_\-\/]*)/g, function(match, prefix, path) {
                    return prefix + '[' + path + '](' + path + ')';
                });

                if (typeof marked !== 'undefined') {
                    marked.setOptions({
                        gfm: true,
                        breaks: false
                    });
                    let parsed = marked.parse(normalized);
                    // Wrap tables in responsive overflow container
                    parsed = parsed.replace(/<table>/gi, '<div class="table-responsive"><table>');
                    parsed = parsed.replace(/<\/table>/gi, '</table></div>');
                    // Ensure external links open in new tab
                    parsed = parsed.replace(/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/gi, function(match, url) {
                        if (url.startsWith('http://') || url.startsWith('https://')) {
                            return '<a href="' + url + '" target="_blank" rel="noopener noreferrer"';
                        }
                        return match;
                    });
                    return parsed;
                }
            } catch (e) {
                console.warn('marked.js parse error:', e);
            }
            return text.replace(/\n/g, '<br>');
        }
    };
}
</script>
