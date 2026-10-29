@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10" x-data="kdpChatbotFullPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <div class="flex items-center justify-between text-xs font-heading font-semibold text-slate-500 uppercase tracking-wider">
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
                <span>&rarr;</span>
                <span class="text-slate-800">KDP Mitra (Academic Counselor)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    AI Active • Universal Assistant Ready
                </span>
            </div>
        </div>

        <!-- Institutional Hero Header with Crest Watermark -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white shadow-2xl p-8 sm:p-10 border border-blue-900/40">
            <!-- Background Glows -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- College Crest Watermark -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none flex items-center justify-end pr-8 overflow-hidden">
                <img src="{{ asset('images/kdp_crest.png') }}" class="h-80 w-auto object-contain filter grayscale contrast-200" alt="KDP Crest Watermark">
            </div>

            <div class="relative z-10 max-w-3xl space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wide border border-amber-500/30">
                        <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-wider border border-white/20">
                        🤖 AI Universal Counselor &amp; Concierge
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading tracking-tight leading-tight text-white">
                    KDP Mitra • AI Universal Guide
                </h1>

                <p class="text-blue-100 text-sm sm:text-base leading-relaxed max-w-2xl font-light">
                    Your universal AI companion for any inquiry, coding assistance, general knowledge, diploma engineering, GTU exams, official transcripts, and campus placements.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2 text-xs font-semibold text-blue-200">
                    <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                        <span>⚡</span> Instant Real-Time AI
                    </span>
                    <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                        <span>🌐</span> Multilingual: English, ગુજરાતી &amp; हिन्दी
                    </span>
                    <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                        <span>📖</span> GTU Portals &amp; Official Links
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Chat Console Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Suggested Topics & Campus Quick Info (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Quick Inquiries Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span>💡</span> Academic Quick Ask
                        </h3>
                        <span class="text-[10px] text-slate-400 font-bold">1-Click</span>
                    </div>

                    <div class="space-y-2">
                        <button type="button" 
                                @click="askPrompt('Where can I download GTU diploma old question papers (PYQs)?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">📖 GTU Old Papers (PYQs)</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('How can I check GTU diploma semester results and grade card?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">📊 GTU Results &amp; Grade Cards</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('How do I apply for Transcripts and Bonafide certificates on this portal?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">📜 Request Transcripts &amp; LOR</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('Which companies recruit diploma students through KDP Patan TPO?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">🏢 TPO Campus Recruiters &amp; Packages</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('How does ACPC D2D degree engineering admission work after diploma?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">🎓 ACPC D2D Degree Admissions</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('How can junior students borrow a mini drafter or books from the tool bank?')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">🛠️ Free Mini Drafters &amp; Books</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>

                        <button type="button" 
                                @click="askPrompt('કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ નો ઇતિહાસ અને એન્જિનિયરિંગ શાખાઓ વિશે જણાવો')" 
                                class="w-full text-left p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-[#1e3a8a]/40 text-xs text-slate-700 transition-all flex items-center justify-between group cursor-pointer">
                            <span class="font-semibold text-slate-800 group-hover:text-[#1e3a8a]">🇮🇳 ગુજરાતી: કૉલેજ ઇતિહાસ અને શાખાઓ</span>
                            <span class="text-slate-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Academic Scope Notice Card -->
                <div class="bg-gradient-to-br from-[#0f2042] via-[#1e3a8a] to-[#0f172a] rounded-3xl p-6 text-white shadow-md border border-blue-900/40 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🛡️</span>
                        <h4 class="font-heading font-bold text-sm text-white uppercase tracking-wider">Academic Counseling Scope</h4>
                    </div>

                    <p class="text-xs text-blue-100 leading-relaxed font-light">
                        KDP Mitra is an AI counselor dedicated to <strong>technical engineering, diploma academics, GTU portals, and KDP campus services</strong>. It does not entertain non-educational time-wasting or jokes.
                    </p>

                    <div class="pt-2 border-t border-white/10 flex items-center justify-between text-[11px] text-amber-300 font-semibold">
                        <span>🏛️ GTU Institute Code: 622</span>
                        <span>Estd. 1961</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Interactive Chat Console (8 cols) -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[740px]">
                
                <!-- Chat Window Top Bar -->
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#0f2042] text-white flex items-center justify-center font-bold text-base shadow-sm">
                            🤖
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-bold text-slate-900 text-sm sm:text-base">KDP Mitra Session</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-[#1e3a8a]">
                                    Active
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400">Ask in English, Gujarati, or Hindi</p>
                        </div>
                    </div>

                    <button type="button" 
                            @click="clearHistory()" 
                            class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 text-xs font-bold text-slate-600 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span>🗑️</span>
                        <span>Clear Chat</span>
                    </button>
                </div>

                <!-- Messages Stream -->
                <div x-ref="chatStream" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50 text-xs sm:text-sm">
                    
                    <!-- Intro Card -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-900 text-xs font-semibold border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-[#f97316]"></span>
                                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                            </span>
                            <span class="text-xl">🙏</span>
                        </div>
                        <div class="font-heading font-bold text-slate-900 text-sm">
                            Namaste! Welcome to KDP Mitra Academic Guide
                        </div>
                        <p class="text-slate-600 leading-relaxed text-xs">
                            Ask me about **GTU Old Papers (PYQs)**, **Results**, **Transcripts &amp; LOR**, **Campus Placements**, **D2D Degree Admissions**, or **Book &amp; Tool Bank**.
                        </p>
                    </div>

                    <!-- Dynamic Messages Loop -->
                    <template x-for="(msg, index) in messages" :key="index">
                        <div>
                            <!-- User Message -->
                            <template x-if="msg.role === 'user'">
                                <div class="flex justify-end pl-12">
                                    <div class="bg-[#1e3a8a] text-white px-4 py-3 rounded-2xl rounded-tr-xs shadow-2xs leading-relaxed max-w-[85%] font-medium">
                                        <p x-text="msg.content" class="whitespace-pre-wrap"></p>
                                        <div class="text-[9px] text-blue-200 text-right mt-1" x-text="msg.time"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Assistant Message -->
                            <template x-if="msg.role === 'assistant'">
                                <div class="flex justify-start pr-8 gap-3">
                                    <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-[#1e3a8a] to-[#0f2042] text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-2xs">
                                        🤖
                                    </div>
                                    <div class="bg-white border border-slate-200 text-slate-800 p-4 sm:p-5 rounded-2xl rounded-tl-xs shadow-2xs leading-relaxed max-w-[92%] space-y-2 overflow-hidden">
                                        <!-- Enhanced Markdown with Table & Link Support -->
                                        <div class="chat-markdown max-w-none text-slate-800" x-html="parseMarkdown(msg.content)"></div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-100">
                                            <span x-text="msg.model || 'Groq AI'"></span>
                                            <span x-text="msg.time"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Loading Indicator -->
                    <div x-show="isLoading" class="flex justify-start gap-3">
                        <div class="w-8 h-8 rounded-2xl bg-[#1e3a8a] text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                            🤖
                        </div>
                        <div class="bg-white border border-slate-200 px-5 py-4 rounded-2xl rounded-tl-xs shadow-2xs flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce"></span>
                            <span class="w-2 h-2 rounded-full bg-[#f97316] animate-bounce [animation-delay:0.2s]"></span>
                            <span class="w-2 h-2 rounded-full bg-[#1e3a8a] animate-bounce [animation-delay:0.4s]"></span>
                            <span class="text-xs text-slate-500 ml-2 font-medium">KDP Mitra is consulting GTU &amp; KDP resources...</span>
                        </div>
                    </div>

                </div>

                <!-- Input Footer -->
                <div class="p-4 sm:p-5 bg-white border-t border-slate-200 space-y-2.5">
                    <form @submit.prevent="submitMessage()" class="flex items-center gap-3">
                        <input type="text" 
                               x-ref="pageInput"
                               x-model="currentInput" 
                               :disabled="isLoading"
                               placeholder="Ask anything... engineering, coding, general questions, or campus queries (English, ગુજરાતી, हिन्दी)" 
                               class="flex-1 px-4 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-xl focus:border-[#1e3a8a] focus:ring-[#1e3a8a] focus:bg-white text-slate-800 transition-colors">
                        
                        <button type="submit" 
                                :disabled="isLoading || !currentInput.trim()" 
                                class="px-6 py-3 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] disabled:opacity-50 text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-2 shrink-0 cursor-pointer">
                            <span>Send</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>

                    <div class="flex items-center justify-end text-[11px] text-slate-400 px-1">
                        <span>K. D. Polytechnic, Patan</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
function kdpChatbotFullPage() {
    return {
        isLoading: false,
        currentInput: '',
        messages: @json($history),

        init() {
            this.$nextTick(() => this.scrollDown());
            if (this.$refs.pageInput) {
                this.$refs.pageInput.focus();
            }
        },

        askPrompt(prompt) {
            this.currentInput = prompt;
            this.submitMessage();
        },

        async submitMessage() {
            const text = this.currentInput.trim();
            if (!text || this.isLoading) return;

            this.messages.push({
                role: 'user',
                content: text,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });

            this.currentInput = '';
            this.isLoading = true;
            this.$nextTick(() => this.scrollDown());

            try {
                const res = await fetch('{{ route("chatbot.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await res.json();
                if (data.success && data.reply) {
                    this.messages.push({
                        role: 'assistant',
                        content: data.reply,
                        model: data.model || 'Groq AI',
                        source: data.source || 'groq',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } else {
                    this.messages.push({
                        role: 'assistant',
                        content: 'Sorry, I encountered an issue. Please try asking again or visit our [Contact Desk](/contact).',
                        model: 'System Notification',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                }
            } catch (err) {
                this.messages.push({
                    role: 'assistant',
                    content: 'An error occurred while communicating with KDP Mitra. Please verify your connection.',
                    model: 'System Notification',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
            } finally {
                this.isLoading = false;
                this.$nextTick(() => this.scrollDown());
            }
        },

        async clearHistory() {
            if (!confirm('Clear all conversation history?')) return;
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
                console.error(e);
            }
        },

        scrollDown() {
            const el = this.$refs.chatStream;
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        },

        parseMarkdown(text) {
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
@endsection
