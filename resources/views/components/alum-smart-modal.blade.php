<!-- ⚡ AlumSmart Natural Language Query Concierge Modal -->
<div x-data="alumSmartModal()" 
     @keydown.window.prevent.ctrl.k="openModal()" 
     @keydown.window.prevent.cmd.k="openModal()"
     @open-alum-smart.window="openModal()"
     @keydown.escape.window="closeModal()">

    <!-- Backdrop & Modal Container -->
    <div x-show="isOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-start justify-center pt-12 sm:pt-20 px-4 pb-6 bg-slate-900/75 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="closeModal()" 
             class="bg-white rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl border border-slate-200 flex flex-col max-h-[85vh] animate-in fade-in zoom-in-95 duration-150">
            
            <!-- Top Search Bar Header -->
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <span class="text-2xl text-amber-500 shrink-0">⚡</span>
                
                <div class="relative flex-1">
                    <input type="text" 
                           x-ref="searchInput"
                           x-model.debounce.250ms="query" 
                           @input="fetchResults()"
                           placeholder="Ask anything... e.g. 'Mechanical alumni in Adani', 'Vernier caliper in tool bank', 'Jobs in Ahmedabad'" 
                           class="w-full pl-3 pr-10 py-2.5 bg-transparent border-0 text-slate-900 placeholder:text-slate-400 font-medium text-sm sm:text-base focus:ring-0 focus:outline-none">
                    
                    <button type="button" 
                            x-show="query.length > 0" 
                            @click="clearQuery()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="hidden sm:flex items-center gap-1.5 shrink-0">
                    <kbd class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-slate-200/80 text-slate-600 rounded-lg">ESC to Close</kbd>
                </div>

                <button type="button" @click="closeModal()" class="sm:hidden text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Filter Tabs (When results exist) -->
            <div x-show="totalResults > 0" class="px-5 py-2.5 bg-slate-100/50 border-b border-slate-100 flex items-center gap-2 overflow-x-auto text-xs font-bold no-scrollbar">
                <button type="button" 
                        @click="activeFilter = 'all'" 
                        :class="activeFilter === 'all' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all">
                    All Results (<span x-text="totalResults"></span>)
                </button>
                <button type="button" 
                        x-show="results.alumni.length > 0"
                        @click="activeFilter = 'alumni'" 
                        :class="activeFilter === 'alumni' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all">
                    Alumni (<span x-text="results.alumni.length"></span>)
                </button>
                <button type="button" 
                        x-show="results.jobs.length > 0"
                        @click="activeFilter = 'jobs'" 
                        :class="activeFilter === 'jobs' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all">
                    Jobs (<span x-text="results.jobs.length"></span>)
                </button>
                <button type="button" 
                        x-show="results.tools.length > 0"
                        @click="activeFilter = 'tools'" 
                        :class="activeFilter === 'tools' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all">
                    Tool Bank (<span x-text="results.tools.length"></span>)
                </button>
                <button type="button" 
                        x-show="results.quick_actions.length > 0"
                        @click="activeFilter = 'actions'" 
                        :class="activeFilter === 'actions' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all">
                    Actions (<span x-text="results.quick_actions.length"></span>)
                </button>
            </div>

            <!-- Scrollable Body Content -->
            <div class="overflow-y-auto p-4 sm:p-6 space-y-6 flex-1">
                
                <!-- Loading State -->
                <div x-show="isLoading" class="py-12 text-center text-slate-400 space-y-2">
                    <div class="w-8 h-8 mx-auto border-3 border-slate-200 border-t-[#1c3272] rounded-full animate-spin"></div>
                    <p class="text-xs font-semibold">Searching KDP records &amp; alumni network...</p>
                </div>

                <!-- EMPTY STATE: Suggestions & Quick Actions -->
                <div x-show="!isLoading && query.length < 2" class="space-y-6">
                    
                    <!-- Suggested Query Chips -->
                    <div class="space-y-2.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">
                            💡 Try Searching For:
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="suggestion in suggestions" :key="suggestion">
                                <button type="button" 
                                        @click="setQuery(suggestion)" 
                                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5 border border-slate-200/60">
                                    <span>🔍</span>
                                    <span x-text="suggestion"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Recommended Shortcuts -->
                    <div class="space-y-2.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">
                            ⚡ Instant Portal Shortcuts:
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="action in results.quick_actions" :key="action.title">
                                <a :href="action.url" class="p-3.5 rounded-2xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50/40 transition-all flex items-start gap-3 group">
                                    <span class="text-2xl p-2 rounded-xl bg-slate-100 group-hover:bg-white shrink-0" x-text="action.icon"></span>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm group-hover:text-[#1c3272]" x-text="action.title"></h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1" x-text="action.subtitle"></p>
                                    </div>
                                    <span class="text-xs text-blue-600 font-bold shrink-0 self-center">&rarr;</span>
                                </a>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- RESULTS VIEW -->
                <div x-show="!isLoading && query.length >= 2" class="space-y-6">
                    
                    <!-- 1. ALUMNI RESULTS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'alumni') && results.alumni.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🎓</span> Alumni &amp; Students (<span x-text="results.alumni.length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="person in results.alumni" :key="person.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-3 shadow-2xs">
                                    <div class="flex items-start gap-3">
                                        <img :src="person.avatar" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-100" :alt="person.name">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <h4 class="font-bold text-slate-900 text-xs truncate" x-text="person.name"></h4>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-blue-100 text-blue-800" x-text="person.role"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate" x-text="person.department + ' • Class of ' + person.graduation_year"></p>
                                            <p class="text-[11px] text-[#1c3272] font-semibold truncate mt-0.5" x-text="person.designation ? person.designation + ' at ' + person.company : person.company"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2 border-t border-slate-50">
                                        <a :href="person.profile_url" class="flex-1 py-1.5 text-center text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                            View Profile
                                        </a>

                                        <template x-if="person.whatsapp_url">
                                            <a :href="person.whatsapp_url" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors flex items-center gap-1 shrink-0">
                                                <span>📱</span> WhatsApp
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 2. QUICK ACTIONS & INTENT MATCHES -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'actions') && results.quick_actions.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>⚡</span> Direct Portal Actions
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="action in results.quick_actions" :key="action.title">
                                <div class="p-3.5 rounded-2xl bg-gradient-to-r from-blue-50/50 to-amber-50/40 border border-blue-200/70 flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl" x-text="action.icon"></span>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-xs" x-text="action.title"></h4>
                                            <p class="text-[10px] text-slate-500" x-text="action.subtitle"></p>
                                        </div>
                                    </div>
                                    <a :href="action.url" class="px-3 py-1.5 rounded-xl bg-[#1c3272] hover:bg-blue-900 text-white font-bold text-xs shrink-0 whitespace-nowrap shadow-xs" x-text="action.action_label"></a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. CAREER OPPORTUNITIES -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'jobs') && results.jobs.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>💼</span> Job Openings (<span x-text="results.jobs.length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="job in results.jobs" :key="job.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs" x-text="job.title"></h4>
                                        <p class="text-[11px] text-slate-500" x-text="job.company + ' • ' + job.location"></p>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700" x-text="job.type"></span>
                                        <a :href="job.url" class="text-xs font-bold text-[#1c3272] hover:underline">Apply / Referral &rarr;</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 4. TOOL BANK INVENTORY -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'tools') && results.tools.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🛠️</span> Student Tool &amp; Book Bank (<span x-text="results.tools.length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="tool in results.tools" :key="tool.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs" x-text="tool.title"></h4>
                                        <p class="text-[11px] text-slate-500" x-text="tool.department + ' • ' + tool.condition"></p>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-blue-50 text-blue-700" x-text="tool.status"></span>
                                        <a :href="tool.url" class="text-xs font-bold text-emerald-700 hover:underline">Claim Tool &rarr;</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- NO RESULTS FALLBACK -->
                    <div x-show="totalResults === 0" class="py-12 text-center space-y-3">
                        <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center text-slate-400 text-xl">
                            🔍
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">No matching records found for "<span x-text="query"></span>"</h4>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto">Try typing an engineering branch (Mechanical, Electrical, Civil, Computer), an enterprise (Adani, Tata, L&T), or tools like Vernier Caliper.</p>
                    </div>

                </div>

            </div>

            <!-- Footer Meta Bar -->
            <div class="p-3 px-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <div class="flex items-center gap-1.5">
                    <span class="text-amber-500">⚡</span>
                    <span><strong>AlumSmart Concierge:</strong> Instant Natural Language Search</span>
                </div>
                <span class="hidden sm:inline text-slate-400">Powered by KDP Alumni Core Engine</span>
            </div>

        </div>

    </div>

</div>

<!-- Alpine Script for AlumSmart Concierge Modal -->
<script>
    function alumSmartModal() {
        return {
            isOpen: false,
            query: '',
            isLoading: false,
            totalResults: 0,
            activeFilter: 'all',
            suggestions: [],
            results: {
                alumni: [],
                jobs: [],
                tools: [],
                quick_actions: []
            },

            openModal() {
                this.isOpen = true;
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
                if (this.suggestions.length === 0) {
                    this.fetchResults();
                }
            },

            closeModal() {
                this.isOpen = false;
            },

            setQuery(newQuery) {
                this.query = newQuery;
                this.fetchResults();
            },

            clearQuery() {
                this.query = '';
                this.fetchResults();
                this.$nextTick(() => this.$refs.searchInput.focus());
            },

            fetchResults() {
                this.isLoading = true;
                fetch('/api/alum-smart/search?q=' + encodeURIComponent(this.query), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                    this.totalResults = data.total_results || 0;
                    this.suggestions = data.suggestions || [];
                    this.isLoading = false;
                })
                .catch(err => {
                    console.error('AlumSmart search error:', err);
                    this.isLoading = false;
                });
            }
        };
    }
</script>
