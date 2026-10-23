@extends('layouts.app')

@section('content')
<div x-data="galleryApp({{ $memorySpots->toJson() }})" class="min-h-screen bg-slate-50 pb-20">

    <!-- Official Header Banner -->
    <div class="bg-gradient-to-r from-[#1c3272] via-[#294c9b] to-[#4074e6] text-white py-12 px-4 sm:px-6 lg:px-8 border-b-4 border-[#f97316]">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-widest bg-orange-500/20 text-orange-200 border border-orange-400/30">
                        KDP Visual Archives &amp; Nostalgia Vault
                    </span>
                    <span class="text-xs text-blue-200">• Est. 1961 Patan</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-serif font-black tracking-tight text-white">
                    Campus Memory Time-Machine &amp; Gallery
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed">
                    Compare historical campus landmarks with 2026 modern upgrades, explore the interactive nostalgic blueprint map, and revisit timeless batch albums.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="openStoryModal()" class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider bg-[#f97316] hover:bg-orange-600 text-white shadow-lg transition-all flex items-center gap-2 shrink-0">
                    <span>✍️</span> Pin a Throwback Memory
                </button>
            </div>
        </div>
    </div>

    <!-- Top Tab Navigation Switcher -->
    <div class="sticky top-14 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 sm:gap-4 overflow-x-auto py-3 no-scrollbar">
                
                <button type="button" 
                        @click="activeTab = 'timemachine'"
                        :class="activeTab === 'timemachine' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                    <span>⏳</span>
                    <span>Campus Time-Machine (Then vs. Now)</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'map'"
                        :class="activeTab === 'map' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                    <span>🗺️</span>
                    <span>Interactive Nostalgia Blueprint Map</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'albums'"
                        :class="activeTab === 'albums' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                    <span>📸</span>
                    <span>Photo Albums &amp; Archives ({{ $albums->count() }})</span>
                </button>

            </div>
        </div>
    </div>

    <!-- Flash Status Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('status'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-sm flex items-center justify-between">
                <span class="font-medium flex items-center gap-2">
                    <span>✓</span> {{ session('status') }}
                </span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs rounded-r-xl shadow-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- ============================================================== -->
        <!-- TAB 1: CAMPUS TIME-MACHINE (THEN VS. NOW SPLIT SLIDER)         -->
        <!-- ============================================================== -->
        <div x-show="activeTab === 'timemachine'" x-cloak class="space-y-8">
            
            <!-- Spot Selector Cards Bar -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Select Campus Heritage Spot:
                    </span>
                    <span class="text-xs text-slate-400">
                        {{ $memorySpots->count() }} Landmark Spots Available
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <template x-for="spot in spots" :key="spot.id">
                        <button type="button" 
                                @click="selectSpot(spot)"
                                :class="selectedSpot && selectedSpot.id === spot.id ? 'border-[#1c3272] bg-blue-50/70 ring-2 ring-[#1c3272]/20' : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="p-3 rounded-2xl border text-left transition-all flex flex-col justify-between h-24 shadow-sm group">
                            <div class="flex items-center justify-between">
                                <span class="text-lg" x-text="getSpotEmoji(spot.icon)"></span>
                                <span class="text-[10px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600" x-text="spot.then_year + ' vs ' + spot.now_year"></span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight group-hover:text-[#1c3272]" x-text="spot.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Spotlight Time-Machine Stage -->
            <template x-if="selectedSpot">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    
                    <!-- Title and Meta -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 rounded-full" x-text="selectedSpot.category"></span>
                                <span class="text-xs text-slate-400 font-mono" x-text="'Established ' + selectedSpot.then_year"></span>
                            </div>
                            <h2 class="text-2xl font-serif font-black text-slate-900" x-text="selectedSpot.name"></h2>
                            <p class="text-xs text-slate-500 mt-1 max-w-3xl" x-text="selectedSpot.description"></p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="openStoryModal(selectedSpot.id)" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1c3272] hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1.5">
                                <span>💬</span> Add Your Memory Here
                            </button>
                        </div>
                    </div>

                    <!-- INTERACTIVE SPLIT COMPARISON SLIDER -->
                    <div class="relative w-full aspect-[16/9] sm:aspect-[21/9] rounded-2xl overflow-hidden shadow-xl select-none group bg-slate-900 border border-slate-800"
                         @mousemove="handleDrag($event)" 
                         @touchmove="handleTouchDrag($event)">
                        
                        <!-- NOW IMAGE (Background Right) -->
                        <img :src="selectedSpot.now_photo_path" 
                             class="absolute inset-0 w-full h-full object-cover" 
                             :alt="selectedSpot.name + ' Modern View'">
                        
                        <!-- NOW BADGE -->
                        <div class="absolute top-4 right-4 z-10 bg-slate-900/80 backdrop-blur-md text-white px-3.5 py-1.5 rounded-full text-xs font-bold border border-white/20 shadow-lg flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span x-text="'MODERN ERA — ' + selectedSpot.now_year"></span>
                        </div>

                        <!-- THEN IMAGE (Clipped Left Side) -->
                        <div class="absolute inset-y-0 left-0 overflow-hidden" 
                             :style="'width: ' + sliderPos + '%'">
                            <img :src="selectedSpot.then_photo_path" 
                                 class="absolute inset-0 max-w-none h-full object-cover filter contrast-125 sepia-[0.35]" 
                                 :style="'width: ' + containerWidth + 'px;'"
                                 :alt="selectedSpot.name + ' Archival View'">
                            
                            <!-- THEN BADGE -->
                            <div class="absolute top-4 left-4 z-10 bg-amber-950/80 backdrop-blur-md text-amber-200 px-3.5 py-1.5 rounded-full text-xs font-bold border border-amber-500/30 shadow-lg flex items-center gap-2">
                                <span>🏛️</span>
                                <span x-text="'ARCHIVAL HERITAGE — ' + selectedSpot.then_year"></span>
                            </div>
                        </div>

                        <!-- SLIDER DIVIDER LINE & HANDLE -->
                        <div class="absolute inset-y-0 z-20 pointer-events-none" 
                             :style="'left: ' + sliderPos + '%'">
                            <!-- Line -->
                            <div class="w-1 h-full bg-white shadow-[0_0_15px_rgba(0,0,0,0.8)] -translate-x-1/2 relative">
                                <!-- Center Button Knob -->
                                <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 left-1/2 w-10 h-10 bg-white text-slate-800 rounded-full shadow-2xl flex items-center justify-center font-bold text-xs border-2 border-slate-900 cursor-ew-resize">
                                    <span>◂▸</span>
                                </div>
                            </div>
                        </div>

                        <!-- RANGE INPUT (Accessible Scrubber) -->
                        <input type="range" 
                               min="0" 
                               max="100" 
                               x-model="sliderPos" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-30" 
                               aria-label="Drag to compare archival past and modern present">

                        <!-- Bottom Legend Overlay -->
                        <div class="absolute bottom-3 inset-x-4 z-10 flex justify-between items-center text-[11px] text-white/90 drop-shadow-md pointer-events-none font-medium">
                            <span class="bg-black/60 px-3 py-1 rounded-lg backdrop-blur-sm" x-text="'◂ ' + selectedSpot.then_title"></span>
                            <span class="bg-black/60 px-3 py-1 rounded-lg backdrop-blur-sm" x-text="selectedSpot.now_title + ' ▸'"></span>
                        </div>
                    </div>

                    <!-- FUN FACT & VINTAGE ANECDOTE CARD -->
                    <template x-if="selectedSpot.fun_fact">
                        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 flex items-start gap-3.5 text-xs text-amber-900">
                            <span class="text-xl shrink-0">💡</span>
                            <div>
                                <span class="font-extrabold uppercase tracking-wider text-[10px] text-amber-800 block mb-0.5">KDP Campus Heritage Legend:</span>
                                <p class="leading-relaxed" x-text="selectedSpot.fun_fact"></p>
                            </div>
                        </div>
                    </template>

                    <!-- SPOT STORIES / MEMORY FEED -->
                    <div class="pt-4 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <span>📖</span> Alumni Throwback Stories for this Spot
                                <span class="text-xs text-slate-400 font-normal" x-text="'(' + selectedSpot.stories.length + ')'"></span>
                            </h3>
                            <button type="button" @click="openStoryModal(selectedSpot.id)" class="text-xs font-bold text-[#1c3272] hover:underline">
                                + Add Yours
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="story in selectedSpot.stories" :key="story.id">
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-slate-900 text-xs block" x-text="story.author_name"></span>
                                            <span class="text-[11px] text-slate-500" x-text="story.department + ' • Class of ' + story.batch_year"></span>
                                        </div>
                                        <button type="button" @click="likeStory(story)" class="flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-white text-rose-600 border border-slate-200 hover:bg-rose-50 transition-colors shadow-2xs">
                                            <span>❤️</span>
                                            <span x-text="story.likes_count"></span>
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed italic" x-text="'“' + story.story + '”'"></p>
                                </div>
                            </template>
                            <template x-if="selectedSpot.stories.length === 0">
                                <div class="col-span-2 py-8 text-center text-xs text-slate-400 italic">
                                    No throwback stories posted for this spot yet. Be the first to pin one!
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </template>

        </div>

        <!-- ============================================================== -->
        <!-- TAB 2: INTERACTIVE NOSTALGIA CAMPUS BLUEPRINT MAP              -->
        <!-- ============================================================== -->
        <div x-show="activeTab === 'map'" x-cloak class="space-y-6">
            
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#f97316]">Geographic Campus Blueprint</span>
                        <h2 class="text-2xl font-serif font-black text-slate-900 mt-0.5">Interactive KDP Campus Nostalgia Map</h2>
                        <p class="text-xs text-slate-500">Click on any glowing pin to reveal archival photos, vintage trivia, and stories tagged to that specific building.</p>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Click pins to explore memory nodes</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- SVG Campus Blueprint Canvas (8 Cols) -->
                    <div class="lg:col-span-8 bg-slate-900 rounded-3xl p-4 sm:p-6 border border-slate-800 shadow-inner relative overflow-hidden aspect-[4/3] sm:aspect-[16/10]">
                        
                        <!-- Stylized Campus Layout Drawing (SVG) -->
                        <svg class="w-full h-full" viewBox="0 0 1000 650" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Campus Boundary -->
                            <rect x="20" y="20" width="960" height="610" rx="24" fill="#0f172a" stroke="#1e293b" stroke-width="4"/>
                            
                            <!-- Green Pathways & Grounds -->
                            <path d="M 50 140 Q 500 130 950 140 L 950 170 Q 500 160 50 170 Z" fill="#1e3a8a" opacity="0.3"/>
                            <path d="M 500 150 L 500 600" stroke="#334155" stroke-width="24" stroke-dasharray="10 10"/>
                            
                            <!-- Athletic Oval Ground (Bottom Center) -->
                            <ellipse cx="550" cy="520" rx="160" ry="75" fill="#064e3b" stroke="#059669" stroke-width="3" opacity="0.6"/>
                            <text x="550" y="525" text-anchor="middle" fill="#6ee7b7" font-size="14" font-weight="bold" font-family="sans-serif">SPORTS TURF &amp; CRICKET OVAL</text>

                            <!-- Buildings Layout Mockup -->
                            <!-- Admin Heritage Block (Center Top) -->
                            <rect x="380" y="60" width="240" height="90" rx="12" fill="#1e293b" stroke="#3b82f6" stroke-width="2"/>
                            <text x="500" y="110" text-anchor="middle" fill="#93c5fd" font-size="12" font-weight="bold">ADMINISTRATION &amp; PORCH</text>

                            <!-- Mechanical Workshops (Left) -->
                            <rect x="80" y="220" width="260" height="130" rx="12" fill="#1e293b" stroke="#f97316" stroke-width="2"/>
                            <text x="210" y="285" text-anchor="middle" fill="#fdba74" font-size="12" font-weight="bold">MECHANICAL &amp; FOUNDRY WORKSHOPS</text>

                            <!-- Central Library (Right Top) -->
                            <rect x="660" y="200" width="240" height="110" rx="12" fill="#1e293b" stroke="#10b981" stroke-width="2"/>
                            <text x="780" y="260" text-anchor="middle" fill="#6ee7b7" font-size="12" font-weight="bold">CENTRAL TECHNICAL LIBRARY</text>

                            <!-- Canteen & Neem Tree (Bottom Left) -->
                            <rect x="180" y="440" width="180" height="90" rx="12" fill="#1e293b" stroke="#eab308" stroke-width="2"/>
                            <text x="270" y="490" text-anchor="middle" fill="#fde047" font-size="11" font-weight="bold">CANTEEN &amp; HERITAGE NEEM</text>

                            <!-- Electrical Labs (Bottom Right) -->
                            <rect x="680" y="390" width="220" height="110" rx="12" fill="#1e293b" stroke="#8b5cf6" stroke-width="2"/>
                            <text x="790" y="450" text-anchor="middle" fill="#c4b5fd" font-size="11" font-weight="bold">ELECTRICAL HIGH VOLTAGE LAB</text>

                            <!-- Main Gate (Top Center Indicator) -->
                            <text x="500" y="40" text-anchor="middle" fill="#94a3b8" font-size="11" font-weight="extrabold" letter-spacing="3">MAIN CAMPUS ENTRANCE (PATAN HIGHWAY)</text>
                        </svg>

                        <!-- DYNAMIC INTERACTIVE PINS -->
                        <template x-for="spot in spots" :key="spot.id">
                            <div class="absolute -translate-x-1/2 -translate-y-1/2 cursor-pointer transition-transform duration-300 hover:scale-125 z-20 group"
                                 :style="'left: ' + spot.x_percent + '%; top: ' + spot.y_percent + '%;'"
                                 @click="selectSpot(spot)">
                                
                                <!-- Pulse Effect -->
                                <span class="absolute -inset-2 rounded-full bg-orange-400 opacity-75 animate-ping"></span>
                                
                                <!-- Main Pin Head -->
                                <div :class="selectedSpot && selectedSpot.id === spot.id ? 'bg-[#f97316] ring-4 ring-white text-white scale-125' : 'bg-white text-slate-900 border-2 border-[#1c3272]'"
                                     class="relative w-8 h-8 rounded-full shadow-2xl flex items-center justify-center font-bold text-xs transition-all">
                                    <span x-text="getSpotEmoji(spot.icon)"></span>
                                </div>

                                <!-- Tooltip on Hover -->
                                <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden group-hover:block z-30 pointer-events-none whitespace-nowrap">
                                    <div class="bg-slate-900/95 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold shadow-xl border border-slate-700">
                                        <span x-text="spot.name"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Selected Spot Sidebar Card (4 Cols) -->
                    <div class="lg:col-span-4 bg-slate-50 rounded-3xl p-5 border border-slate-200 space-y-4">
                        <template x-if="selectedSpot">
                            <div class="space-y-4">
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden relative shadow-md">
                                    <img :src="selectedSpot.now_photo_path" class="w-full h-full object-cover" :alt="selectedSpot.name">
                                    <div class="absolute bottom-2 left-2 bg-slate-900/80 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-bold text-white">
                                        <span x-text="'Est. ' + selectedSpot.then_year"></span>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900 text-base" x-text="selectedSpot.name"></h3>
                                    <p class="text-xs text-slate-500 mt-1" x-text="selectedSpot.description"></p>
                                </div>

                                <template x-if="selectedSpot.fun_fact">
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-[11px] text-slate-700 space-y-1">
                                        <span class="font-bold text-orange-700 block text-[10px] uppercase">Campus Lore:</span>
                                        <p x-text="selectedSpot.fun_fact"></p>
                                    </div>
                                </template>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="activeTab = 'timemachine'" class="w-full py-2 rounded-xl bg-[#1c3272] hover:bg-blue-900 text-white font-bold text-xs transition-colors shadow-sm text-center">
                                        Open in Split Time-Machine &rarr;
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="!selectedSpot">
                            <div class="py-16 text-center text-xs text-slate-400">
                                Click any pin on the map to inspect archival photos and stories.
                            </div>
                        </template>
                    </div>

                </div>

            </div>

        </div>

        <!-- ============================================================== -->
        <!-- TAB 3: PHOTO ALBUMS & ARCHIVES (EXISTING LIGHTBOX GALLERY)    -->
        <!-- ============================================================== -->
        <div x-show="activeTab === 'albums'" x-cloak class="space-y-8">
            
            @forelse($albums as $album)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif font-black text-slate-900 flex items-center gap-2">
                                <span class="text-blue-600">📁</span>
                                {{ $album->title }}
                            </h2>
                            <span class="text-xs text-slate-400">Memory Collection</span>
                        </div>
                        <span class="bg-blue-50 text-[#1c3272] border border-blue-200 text-xs font-bold py-1.5 px-4 rounded-full">
                            {{ $album->photos->count() }} Photos
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $imageUrls = $album->photos->map(fn($photo) => asset('storage/' . $photo->image_path))->values()->toArray();
                        @endphp

                        @foreach($album->photos as $photo)
                            <div @click='openLightbox(@json($imageUrls), {{ $loop->index }})' 
                                 class="relative group rounded-2xl overflow-hidden cursor-pointer aspect-[4/3] bg-slate-100 shadow-xs">
                                <img src="{{ asset('storage/' . $photo->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Gallery Photo">
                                
                                <div class="absolute inset-0 bg-[#1c3272]/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#1c3272] shadow-lg transform scale-50 group-hover:scale-100 transition-transform duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($album->photos->isEmpty())
                        <p class="text-slate-400 text-xs italic py-4 text-center">No photos uploaded to this section yet.</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-slate-300 p-8 space-y-2">
                    <p class="text-slate-600 font-bold text-sm">No photo albums uploaded yet.</p>
                    <p class="text-slate-400 text-xs">Administrators can create albums in the admin control panel.</p>
                </div>
            @endforelse

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- PIN A THROWBACK MEMORY MODAL                                   -->
    <!-- ============================================================== -->
    <div x-show="storyModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-xs p-4">
        <div @click.away="storyModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">✍️</span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Pin a Throwback Campus Memory</h3>
                        <p class="text-xs text-slate-500">Your story will appear on the Time-Machine and Campus Map</p>
                    </div>
                </div>
                <button @click="storyModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('gallery.stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Spot Selector -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Campus Spot *</label>
                    <select name="campus_memory_spot_id" x-model="modalSpotId" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                        <template x-for="spot in spots" :key="spot.id">
                            <option :value="spot.id" x-text="spot.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Author Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Your Name *</label>
                        <input type="text" name="author_name" value="{{ auth()->user()->name ?? '' }}" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Batch Year *</label>
                        <input type="number" name="batch_year" min="1970" max="{{ date('Y') + 1 }}" value="{{ auth()->user()->graduation_year ?? 2018 }}" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                    </div>
                </div>

                <!-- Department -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Engineering Department *</label>
                    <input type="text" name="department" value="{{ auth()->user()->department ?? 'Mechanical Engineering' }}" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                </div>

                <!-- Story Text -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Your Memory / Anecdote *</label>
                    <textarea name="story" rows="4" required placeholder="What happened here during your college days? A funny lab incident, canteen chai debate, or farewell moment..." class="w-full rounded-xl border-slate-300 text-xs sm:text-sm"></textarea>
                </div>

                <!-- Optional Throwback Photo -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Attach Throwback Photo (Optional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1c3272]">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="storyModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1c3272] hover:bg-blue-900 text-white shadow-sm">Pin to Campus Memory</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- LIGHTBOX FULLSCREEN MODAL (FOR ALBUMS)                         -->
    <!-- ============================================================== -->
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/95">
        <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <button @click.stop="prevPhoto()" x-show="lightboxImages.length > 1" class="absolute left-4 sm:left-8 text-white bg-black/50 hover:bg-black/80 p-3 rounded-lg transition-all focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <div @click.away="closeLightbox()" class="relative max-w-5xl w-full max-h-screen p-4 flex justify-center items-center h-full">
            <template x-if="lightboxImages.length > 0">
                <img :src="lightboxImages[lightboxIndex]" class="max-w-full max-h-[85vh] object-contain shadow-2xl rounded-sm" alt="Fullscreen Image">
            </template>
        </div>

        <button @click.stop="nextPhoto()" x-show="lightboxImages.length > 1" class="absolute right-4 sm:right-8 text-white bg-black/50 hover:bg-black/80 p-3 rounded-lg transition-all focus:outline-none z-[60]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <div class="absolute bottom-8 bg-black/80 text-white text-xs font-bold tracking-widest px-6 py-2 rounded-full z-[60]" x-show="lightboxImages.length > 0">
            <span x-text="(lightboxIndex + 1) + ' / ' + lightboxImages.length"></span>
        </div>
    </div>

</div>

<!-- Alpine Gallery & Time-Machine Application Logic -->
<script>
    function galleryApp(initialSpots) {
        return {
            activeTab: 'timemachine',
            spots: initialSpots || [],
            selectedSpot: initialSpots.length > 0 ? initialSpots[0] : null,
            sliderPos: 50,
            containerWidth: 1000,
            storyModalOpen: false,
            modalSpotId: initialSpots.length > 0 ? initialSpots[0].id : null,

            // Lightbox state
            lightboxOpen: false,
            lightboxImages: [],
            lightboxIndex: 0,

            init() {
                this.updateContainerWidth();
                window.addEventListener('resize', () => this.updateContainerWidth());
            },

            updateContainerWidth() {
                const el = document.querySelector('.aspect-\\[16\\/9\\]') || document.querySelector('.aspect-\\[21\\/9\\]');
                if (el) {
                    this.containerWidth = el.offsetWidth;
                }
            },

            selectSpot(spot) {
                this.selectedSpot = spot;
                this.modalSpotId = spot.id;
                this.sliderPos = 50;
                this.$nextTick(() => this.updateContainerWidth());
            },

            handleDrag(e) {
                const rect = e.currentTarget.getBoundingClientRect();
                const x = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
                this.sliderPos = Math.round((x / rect.width) * 100);
            },

            handleTouchDrag(e) {
                if (!e.touches || e.touches.length === 0) return;
                const rect = e.currentTarget.getBoundingClientRect();
                const x = Math.max(0, Math.min(e.touches[0].clientX - rect.left, rect.width));
                this.sliderPos = Math.round((x / rect.width) * 100);
            },

            getSpotEmoji(icon) {
                const icons = {
                    'building': '🏛️',
                    'wrench': '🛠️',
                    'book': '📚',
                    'coffee': '☕',
                    'bolt': '⚡',
                    'trophy': '🏆'
                };
                return icons[icon] || '📍';
            },

            openStoryModal(spotId = null) {
                if (spotId) {
                    this.modalSpotId = spotId;
                } else if (this.selectedSpot) {
                    this.modalSpotId = this.selectedSpot.id;
                }
                this.storyModalOpen = true;
            },

            likeStory(story) {
                fetch('/gallery/stories/' + story.id + '/like', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        story.likes_count = data.likes_count;
                    }
                })
                .catch(() => {
                    story.likes_count++;
                });
            },

            openLightbox(images, index) {
                this.lightboxImages = images;
                this.lightboxIndex = index;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = 'auto';
            },

            nextPhoto() {
                this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxImages.length;
            },

            prevPhoto() {
                this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxImages.length) % this.lightboxImages.length;
            }
        };
    }
</script>
@endsection
