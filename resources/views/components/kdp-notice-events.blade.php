@props(['notices' => collect(), 'events' => collect()])

<section class="py-12 bg-slate-50/80 border-y border-slate-200/80 font-sans" x-data="{ selectedNotice: null }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-100/80 text-[#1e3a8a] text-xs font-bold uppercase tracking-widest mb-2 font-heading">
                <span>🏛️ Official Campus Circulars</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1e3a8a] tracking-tight uppercase font-heading">
                Notice Board &amp; Campus Activities
            </h2>
            <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full mt-2"></div>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-2xl mx-auto">
                Real-time official announcements, GTU circulars, examination schedules, and campus events synchronized directly from K. D. Polytechnic, Patan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- ============================================== -->
            <!-- 1. OFFICIAL NOTICE BOARD (kdppatan.ac.in style) -->
            <!-- ============================================== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-[460px]">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center font-bold shadow-xs">
                            📋
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-[#1e3a8a] font-heading leading-tight">Notice Board</h3>
                            <span class="text-[11px] text-slate-400 font-medium">Academic &amp; GTU Circulars</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}#notice-center" class="text-[11px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 px-2.5 py-1 rounded-lg flex items-center gap-1 transition-colors shadow-2xs font-heading" title="Manage Notices in Admin Control Center">
                                    <span>⚙️</span> Manage
                                </a>
                            @endif
                        @endauth
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active
                        </span>
                    </div>
                </div>

                <!-- Scrollable Notices Feed -->
                <div class="overflow-y-auto space-y-3 pr-2 flex-1 scrollbar-thin scrollbar-thumb-slate-200">
                    @forelse($notices as $notice)
                        <div @click="selectedNotice = {{ json_encode($notice) }}"
                             class="group bg-slate-50/70 hover:bg-white p-3.5 rounded-xl border-l-4 border-[#f97316] border-t border-r border-b border-slate-200/60 transition-all duration-200 hover:shadow-md hover:translate-x-1 cursor-pointer">
                            <div class="text-[13px] sm:text-sm font-bold text-[#1e3a8a] group-hover:text-blue-700 leading-snug line-clamp-2">
                                {{ $notice->title }}
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-semibold text-[#f97316] mt-2 flex-wrap">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $notice->created_at ? $notice->created_at->format('F d, Y') : 'Official Circular' }}</span>
                                </div>
                                @if($notice->hasAttachment())
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold {{ $notice->is_pdf ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-blue-50 text-blue-600 border border-blue-200' }}">
                                        <span>{{ $notice->is_pdf ? '📄 PDF' : '🖼️ Photo' }}</span>
                                    </span>
                                @endif
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-500 font-medium group-hover:underline">Click for details &rarr;</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-sm">
                            No current notices published.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 2. EVENTS & ACTIVITIES (kdppatan.ac.in style)   -->
            <!-- ============================================== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col h-[460px]">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#f97316] flex items-center justify-center font-bold shadow-xs">
                            📅
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-[#1e3a8a] font-heading leading-tight">Events &amp; Activities</h3>
                            <span class="text-[11px] text-slate-400 font-medium">Workshops, Drives &amp; Seminars</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-[#1e3a8a] bg-blue-50 px-2.5 py-1 rounded-full font-heading">
                        Campus Life
                    </span>
                </div>

                <!-- Scrollable Events Feed -->
                <div class="overflow-y-auto space-y-3.5 pr-2 flex-1 scrollbar-thin scrollbar-thumb-slate-200">
                    @forelse($events as $event)
                        <div class="group bg-slate-50/70 hover:bg-white p-3 rounded-xl border border-slate-200/60 transition-all duration-200 hover:shadow-md flex items-center gap-3.5">
                            <!-- Event Thumbnail Image -->
                            <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-slate-200 border border-slate-100 shadow-xs relative">
                                @if($event->image_path)
                                    <img src="{{ asset('storage/' . $event->image_path) }}" 
                                         alt="{{ $event->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.src='{{ asset('images/kdp_crest.png') }}'; this.className='w-full h-full object-contain p-2 bg-slate-100';">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#1e3a8a] to-[#3b82f6] flex items-center justify-center text-white text-xs font-bold font-heading">
                                        KDP
                                    </div>
                                @endif
                            </div>

                            <!-- Event Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-orange-100 text-[#f97316]">
                                        {{ $event->category ?? 'CAMPUS' }}
                                    </span>
                                </div>
                                <h4 class="text-xs sm:text-[13px] font-bold text-slate-900 group-hover:text-[#1e3a8a] line-clamp-2 leading-snug">
                                    {{ $event->title }}
                                </h4>
                                <div class="flex items-center gap-1.5 text-[11px] text-[#f97316] font-semibold mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $event->event_date ? $event->event_date->format('M d, Y') : 'Campus Date' }}</span>
                                    @if($event->time_display)
                                        <span class="text-slate-400 font-normal hidden sm:inline">• {{ $event->time_display }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-sm">
                            No upcoming activities scheduled.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Notice Quick View Modal (Alpine) -->
    <div x-show="selectedNotice" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div @click.away="selectedNotice = null"
             class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-[#1e3a8a] to-[#2563eb] text-white p-5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📋</span>
                    <h4 class="font-bold font-heading text-sm sm:text-base">Official College Circular</h4>
                </div>
                <button @click="selectedNotice = null" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 text-xl font-bold cursor-pointer">
                    &times;
                </button>
            </div>
            <div class="p-6">
                <div class="text-[11px] font-bold uppercase tracking-wider text-[#f97316] mb-1">
                    Published: <span x-text="selectedNotice ? new Date(selectedNotice.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : ''"></span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-heading mb-3" x-text="selectedNotice?.title"></h3>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 text-xs sm:text-sm leading-relaxed mb-4 whitespace-pre-line" x-text="selectedNotice?.body"></div>

                <!-- Attached File Display (Photo or PDF) -->
                <template x-if="selectedNotice && selectedNotice.attachment_path">
                    <div class="mb-4">
                        <!-- Attached Image Photo -->
                        <template x-if="selectedNotice.is_image">
                            <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 p-2">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-600 mb-1.5 px-1">
                                    <span>🖼️ Attached Image Circular</span>
                                    <a :href="selectedNotice.attachment_url" target="_blank" class="text-[#1e3a8a] hover:underline flex items-center gap-1 font-semibold">View Full Image ↗</a>
                                </div>
                                <a :href="selectedNotice.attachment_url" target="_blank">
                                    <img :src="selectedNotice.attachment_url" :alt="selectedNotice.title" class="w-full max-h-80 object-contain rounded-lg shadow-2xs hover:opacity-95 transition-opacity">
                                </a>
                            </div>
                        </template>

                        <!-- Attached PDF Document -->
                        <template x-if="selectedNotice.is_pdf">
                            <div class="p-3.5 rounded-xl bg-red-50/80 border border-red-200 flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-xl shrink-0 font-bold">
                                        📄
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-900 truncate" x-text="selectedNotice.attachment_name || 'Official Circular Document (PDF)'"></div>
                                        <div class="text-[10px] text-red-600 font-semibold">Official PDF Document Attachment</div>
                                    </div>
                                </div>
                                <a :href="selectedNotice.attachment_url" 
                                   target="_blank" 
                                   class="px-3.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs shrink-0 flex items-center gap-1.5 transition-colors font-heading">
                                    <span>📥 View PDF</span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5">
                    <span>🏛️ Issued by Office of the Principal, K. D. Polytechnic, Patan</span>
                </div>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" @click="selectedNotice = null" class="px-4 py-1.5 rounded-lg bg-[#1e3a8a] text-white text-xs font-bold font-heading hover:bg-blue-900 cursor-pointer">
                    Close Notice
                </button>
            </div>
        </div>
    </div>
</section>
