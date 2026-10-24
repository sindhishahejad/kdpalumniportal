<!-- ⚡ AlumSmart Universal Natural Language Search & Concierge Modal -->
<div x-data="alumSmartModal()" 
     @keydown.window.prevent.ctrl.k="openModal()" 
     @keydown.window.prevent.cmd.k="openModal()"
     @open-alum-smart.window="openModal()"
     @keydown.escape.window="closeModal()">

    <!-- Backdrop & Modal Container -->
    <div x-show="isOpen" 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-start justify-center pt-8 sm:pt-16 px-4 pb-6 bg-slate-900/80 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="closeModal()" 
             class="bg-white rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl border border-slate-200 flex flex-col max-h-[88vh] animate-in fade-in zoom-in-95 duration-150">
            
            <!-- Top Search Bar Header -->
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/70">
                <span class="text-2xl text-amber-500 shrink-0">⚡</span>
                
                <div class="relative flex-1">
                    <input type="text" 
                           x-ref="searchInput"
                           x-model="query" 
                           @input.debounce.150ms="fetchResults()"
                           placeholder="Ask or search anything... e.g. 'ID card', 'Mechanical alumni in Adani', 'Vernier caliper', 'Apply transcript', 'Jobs'" 
                           class="w-full pl-3 pr-10 py-2.5 bg-transparent border-0 text-slate-900 placeholder:text-slate-400 font-medium text-sm sm:text-base focus:ring-0 focus:outline-none">
                    
                    <button type="button" 
                            x-show="query.length > 0" 
                            @click="clearQuery()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
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

            <!-- Dynamic Filter Tabs (When searching) -->
            <div x-show="totalResults > 0 && query.trim().length > 0" class="px-4 sm:px-6 py-2.5 bg-slate-100/60 border-b border-slate-100 flex items-center gap-1.5 overflow-x-auto text-xs font-bold no-scrollbar">
                <button type="button" 
                        @click="activeFilter = 'all'" 
                        :class="activeFilter === 'all' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    All Results (<span x-text="totalResults"></span>)
                </button>

                <button type="button" 
                        x-show="(results.services || []).length > 0"
                        @click="activeFilter = 'services'" 
                        :class="activeFilter === 'services' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    🏛️ Services (<span x-text="(results.services || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.alumni || []).length > 0"
                        @click="activeFilter = 'alumni'" 
                        :class="activeFilter === 'alumni' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    🎓 Alumni (<span x-text="(results.alumni || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.jobs || []).length > 0"
                        @click="activeFilter = 'jobs'" 
                        :class="activeFilter === 'jobs' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    💼 Jobs (<span x-text="(results.jobs || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.tools || []).length > 0"
                        @click="activeFilter = 'tools'" 
                        :class="activeFilter === 'tools' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    🛠️ Tool Bank (<span x-text="(results.tools || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.events || []).length > 0"
                        @click="activeFilter = 'events'" 
                        :class="activeFilter === 'events' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    📅 Events (<span x-text="(results.events || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.labs || []).length > 0"
                        @click="activeFilter = 'labs'" 
                        :class="activeFilter === 'labs' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    🔬 Labs (<span x-text="(results.labs || []).length"></span>)
                </button>

                <button type="button" 
                        x-show="(results.mentorships || []).length > 0"
                        @click="activeFilter = 'mentors'" 
                        :class="activeFilter === 'mentors' ? 'bg-[#1c3272] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="px-3 py-1 rounded-full transition-all shrink-0 cursor-pointer">
                    🤝 Mentorship (<span x-text="(results.mentorships || []).length"></span>)
                </button>
            </div>

            <!-- Scrollable Body Content -->
            <div class="overflow-y-auto p-4 sm:p-6 space-y-6 flex-1">
                
                <!-- Loading State -->
                <div x-show="isLoading" class="py-12 text-center text-slate-400 space-y-2">
                    <div class="w-8 h-8 mx-auto border-3 border-slate-200 border-t-[#1c3272] rounded-full animate-spin"></div>
                    <p class="text-xs font-semibold">Searching complete portal services, records &amp; alumni network...</p>
                </div>

                <!-- ============================================== -->
                <!-- INITIAL VIEW: Master Directory & Suggestions   -->
                <!-- ============================================== -->
                <div x-show="!isLoading && query.trim().length === 0" class="space-y-6">
                    
                    <!-- Suggested Query Chips -->
                    <div class="space-y-2.5">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">
                            💡 Try Searching Natural Language Queries:
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="suggestion in suggestions" :key="suggestion">
                                <button type="button" 
                                        @click="setQuery(suggestion)" 
                                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-[#1c3272] text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5 border border-slate-200/60 cursor-pointer">
                                    <span>🔍</span>
                                    <span x-text="suggestion"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Master Portal Services Directory -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                <span>🏛️</span> Full Portal Services &amp; Facilities Directory
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono" x-text="(results.services || []).length + ' Services Available'"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <template x-for="service in results.services" :key="service.id">
                                <a :href="service.url" class="p-3.5 rounded-2xl border border-slate-200/90 bg-white hover:border-[#1c3272] hover:bg-blue-50/30 transition-all flex flex-col justify-between space-y-2.5 shadow-2xs group">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl p-1.5 rounded-xl bg-slate-50 group-hover:bg-white border border-slate-100 shrink-0" x-text="service.icon"></span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-slate-100 text-slate-600" x-text="service.badge || service.category"></span>
                                        </div>
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm group-hover:text-[#1c3272] leading-snug" x-text="service.title"></h4>
                                        <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed" x-text="service.description"></p>
                                    </div>
                                    <div class="pt-1 flex items-center justify-between text-xs font-bold text-[#1c3272] group-hover:translate-x-0.5 transition-transform">
                                        <span x-text="service.action_label"></span>
                                        <span>&rarr;</span>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- ============================================== -->
                <!-- ACTIVE SEARCH RESULTS VIEW                     -->
                <!-- ============================================== -->
                <div x-show="!isLoading && query.trim().length > 0" class="space-y-6">
                    
                    <!-- 1. DIRECT SPOTLIGHT SERVICE ANSWER (When top intent is matched) -->
                    <template x-if="results.spotlight">
                        <div class="p-4 sm:p-5 rounded-3xl bg-gradient-to-r from-blue-50 via-indigo-50/40 to-amber-50/50 border-2 border-blue-300/80 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <span class="text-3xl p-2.5 rounded-2xl bg-white shadow-xs border border-blue-100 shrink-0" x-text="results.spotlight.icon"></span>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider bg-blue-600 text-white rounded-full">
                                            Direct Portal Service
                                        </span>
                                        <span class="text-xs font-bold text-slate-400" x-text="results.spotlight.category"></span>
                                    </div>
                                    <h3 class="font-serif font-black text-slate-900 text-base sm:text-lg" x-text="results.spotlight.title"></h3>
                                    <p class="text-xs text-slate-600 max-w-xl leading-relaxed" x-text="results.spotlight.description"></p>
                                    <template x-if="results.spotlight.spotlight_tip">
                                        <p class="text-[11px] text-[#1c3272] font-semibold flex items-center gap-1 pt-0.5">
                                            <span>💡</span> <span x-text="results.spotlight.spotlight_tip"></span>
                                        </p>
                                    </template>
                                </div>
                            </div>
                            <a :href="results.spotlight.url" class="px-5 py-2.5 rounded-xl bg-[#1c3272] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm shrink-0 whitespace-nowrap shadow-sm transition-all flex items-center gap-2">
                                <span x-text="results.spotlight.action_label"></span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </template>

                    <!-- 2. MATCHED SERVICES & FEATURES -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'services') && (results.services || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🏛️</span> Matching Portal Features &amp; Services (<span x-text="(results.services || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="service in results.services" :key="service.id">
                                <div class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-blue-400 transition-all flex items-start justify-between gap-3 shadow-2xs">
                                    <div class="flex items-start gap-3">
                                        <span class="text-2xl p-1.5 rounded-xl bg-slate-50 shrink-0" x-text="service.icon"></span>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm" x-text="service.title"></h4>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-slate-100 text-slate-600" x-text="service.badge"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2" x-text="service.description"></p>
                                        </div>
                                    </div>
                                    <a :href="service.url" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#1c3272] font-bold text-xs shrink-0 whitespace-nowrap transition-colors" x-text="service.action_label"></a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. ALUMNI & STUDENTS RESULTS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'alumni') && (results.alumni || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🎓</span> Alumni &amp; Students (<span x-text="(results.alumni || []).length"></span>)
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

                    <!-- 4. CAREER OPPORTUNITIES & JOBS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'jobs') && (results.jobs || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>💼</span> Career Openings &amp; Opportunities (<span x-text="(results.jobs || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="job in results.jobs" :key="job.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm" x-text="job.title"></h4>
                                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700" x-text="job.type"></span>
                                        </div>
                                        <p class="text-[11px] text-[#1c3272] font-semibold mt-0.5" x-text="job.company + ' • ' + job.location"></p>
                                    </div>
                                    <div class="pt-1 flex justify-end">
                                        <a :href="job.url" class="text-xs font-bold text-blue-600 hover:underline">Apply / Referral &rarr;</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 5. TOOL & BOOK BANK ITEMS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'tools') && (results.tools || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🛠️</span> Student Tool &amp; Book Bank Items (<span x-text="(results.tools || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="tool in results.tools" :key="tool.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm" x-text="tool.title"></h4>
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

                    <!-- 6. CAMPUS EVENTS & BATCH REUNIONS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'events') && (results.events || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>📅</span> Campus Events &amp; Batch Reunions (<span x-text="(results.events || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="event in results.events" :key="event.title">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl" x-text="event.icon"></span>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <h4 class="font-bold text-slate-900 text-xs" x-text="event.title"></h4>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-amber-100 text-amber-800" x-text="event.category"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5" x-text="event.subtitle"></p>
                                        </div>
                                    </div>
                                    <a :href="event.url" class="text-xs font-bold text-blue-600 hover:underline shrink-0">View &rarr;</a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 7. DEPARTMENT LABS & WORKSHOPS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'labs') && (results.labs || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🔬</span> Department Labs &amp; Workshops (<span x-text="(results.labs || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="lab in results.labs" :key="lab.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm" x-text="lab.title"></h4>
                                        <p class="text-[11px] text-slate-500" x-text="lab.department + ' • Room: ' + lab.location"></p>
                                    </div>
                                    <div class="pt-1 flex items-center justify-between text-xs">
                                        <span class="text-slate-400 text-[10px]" x-text="'Faculty: ' + (lab.faculty || 'HOD')"></span>
                                        <a :href="lab.url" class="font-bold text-blue-600 hover:underline">Inspect Lab &rarr;</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 8. MENTORSHIP GUIDANCE LISTINGS -->
                    <div x-show="(activeFilter === 'all' || activeFilter === 'mentors') && (results.mentorships || []).length > 0" class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span>🤝</span> Available Mentorship Guidance (<span x-text="(results.mentorships || []).length"></span>)
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="m in results.mentorships" :key="m.id">
                                <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 transition-all flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm" x-text="m.title"></h4>
                                        <p class="text-[11px] text-[#1c3272] font-semibold" x-text="'Mentor: ' + m.mentor_name"></p>
                                        <p class="text-[10px] text-slate-500 mt-0.5 line-clamp-1" x-text="'Expertise: ' + m.expertise"></p>
                                    </div>
                                    <div class="pt-1 flex justify-end">
                                        <a :href="m.url" class="text-xs font-bold text-blue-600 hover:underline">Book Guidance &rarr;</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 9. NO RESULTS FALLBACK -->
                    <div x-show="totalResults === 0" class="py-12 text-center space-y-3">
                        <div class="w-12 h-12 mx-auto bg-slate-100 rounded-full flex items-center justify-center text-slate-400 text-xl">
                            🔍
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">No matching records found for "<span x-text="query"></span>"</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Try typing any college feature like <strong>"ID Card"</strong>, <strong>"Transcript"</strong>, <strong>"Mentorship"</strong>, <strong>"Vernier Caliper"</strong>, or an engineering branch like <strong>"Mechanical"</strong>.
                        </p>
                        <div class="pt-2">
                            <button type="button" @click="clearQuery()" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1c3272] hover:bg-blue-100 transition-colors">
                                Browse All Portal Services
                            </button>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer Meta Bar -->
            <div class="p-3 px-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <div class="flex items-center gap-1.5">
                    <span class="text-amber-500 font-bold">⚡</span>
                    <span><strong>AlumSmart Universal Concierge:</strong> Instant Search &amp; Service Navigation</span>
                </div>
                <span class="hidden sm:inline text-slate-400">Search 20+ KDP Portal Services &amp; Live Records</span>
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
            _abortController: null,
            results: {
                spotlight: null,
                services: [],
                alumni: [],
                jobs: [],
                tools: [],
                events: [],
                labs: [],
                mentorships: [],
                pitches: [],
                landmarks: [],
                notices: [],
                quick_actions: []
            },

            openModal() {
                this.isOpen = true;
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
                if (!this.results.services || this.results.services.length === 0) {
                    this.fetchResults();
                }
            },

            closeModal() {
                this.isOpen = false;
            },

            setQuery(newQuery) {
                this.query = newQuery;
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.value = newQuery;
                }
                this.activeFilter = 'all';
                this.fetchResults();
                this.$nextTick(() => this.$refs.searchInput.focus());
            },

            clearQuery() {
                this.query = '';
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.value = '';
                }
                this.activeFilter = 'all';
                this.fetchResults();
                this.$nextTick(() => this.$refs.searchInput.focus());
            },

            fetchResults() {
                const rawVal = this.$refs.searchInput ? this.$refs.searchInput.value : this.query;
                this.query = rawVal;
                const q = rawVal.trim();

                if (this._abortController) {
                    this._abortController.abort();
                }
                this._abortController = new AbortController();

                this.isLoading = true;
                fetch('/api/alum-smart/search?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' },
                    signal: this._abortController.signal
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    this.results = data || {};
                    this.totalResults = data.total_results || 0;
                    this.suggestions = data.suggestions || [];
                    this.isLoading = false;
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        console.error('AlumSmart search error:', err);
                        this.isLoading = false;
                    }
                });
            }
        };
    }
</script>
