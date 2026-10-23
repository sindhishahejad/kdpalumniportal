@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

    <!-- Header Section -->
    <div class="bg-white p-8 sm:p-10 rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Academic Portal</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a] mt-1">Faculty Dashboard</h1>
                <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                    Manage department laboratories, share academic resources with students, oversee innovation pitches, and connect with alumni for guest lectures.
                </p>
            </div>
            <span class="w-max bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-purple-200">
                Faculty Access
            </span>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-100">
            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Department Labs</span>
                <span class="text-2xl font-black text-[#0f172a] mt-1 block">{{ $stats['total_labs'] ?? 0 }}</span>
            </div>
            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Shared Resources</span>
                <span class="text-2xl font-black text-[#0f172a] mt-1 block">{{ $stats['total_resources'] ?? 0 }}</span>
            </div>
            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Open Project Pitches</span>
                <span class="text-2xl font-black text-[#0f172a] mt-1 block">{{ $stats['open_pitches'] ?? 0 }}</span>
            </div>
            <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Upcoming Events</span>
                <span class="text-2xl font-black text-[#0f172a] mt-1 block">{{ $stats['upcoming_events'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid (6 Clean Cards) -->
    <div>
        <h2 class="text-xl font-serif font-bold text-[#0f172a] mb-6">Department & Engagement Modules</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Action Card 1: Upload Resources -->
            <a href="{{ route('resources.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Academic Resource Vault</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Share course notes, GTU exam papers, lab manuals, and MOOC references with students.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-blue-600 group-hover:translate-x-1 transition-transform">
                    Browse & Upload &rarr;
                </div>
            </a>

            <!-- Action Card 2: Alumni Directory -->
            <a href="{{ route('alumni.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Alumni Directory</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Search notable alumni across industries and invite them as guest speakers or jury members.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-emerald-600 group-hover:translate-x-1 transition-transform">
                    Search Alumni &rarr;
                </div>
            </a>

            <!-- Action Card 3: Manage Events -->
            <a href="{{ route('events.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Campus Events</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Coordinate departmental seminars, expert sessions, reunions, and webinars.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-amber-600 group-hover:translate-x-1 transition-transform">
                    View Events &rarr;
                </div>
            </a>

            <!-- Action Card 4: Department Labs -->
            <a href="{{ route('labs.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Department Labs</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Keep lab hardware inventories, locations, and faculty coordinator details updated.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-purple-600 group-hover:translate-x-1 transition-transform">
                    Manage Labs &rarr;
                </div>
            </a>

            <!-- Action Card 5: Project Incubation -->
            <a href="{{ route('pitches.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Project Incubation</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Review diploma major/minor innovation projects seeking mentorship, hardware, or guidance.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-indigo-600 group-hover:translate-x-1 transition-transform">
                    Review Pitches &rarr;
                </div>
            </a>

            <!-- Action Card 6: Knowledge Feed -->
            <a href="{{ route('posts.index') }}" class="group p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#0f172a] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg mb-1">Knowledge & Challenge Feed</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Post department challenges, algorithmic problems, or answer student queries.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-rose-600 group-hover:translate-x-1 transition-transform">
                    Visit Feed &rarr;
                </div>
            </a>

        </div>
    </div>

    <!-- Bottom Two Column Section: Recent Pitches & Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Student Project Pitches -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-base">Recent Student Project Pitches</h3>
                <a href="{{ route('pitches.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentPitches as $pitch)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-sm text-gray-900">{{ $pitch->title }}</h4>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                    {{ $pitch->status ?? 'Open' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $pitch->description }}</p>
                        </div>
                        <div class="mt-3 pt-2 border-t border-gray-200/60 flex items-center justify-between text-[11px] text-gray-400">
                            <span>By: {{ $pitch->student->name ?? 'Student' }}</span>
                            <span class="font-medium text-gray-600">Tech: {{ $pitch->tech_stack }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        No student project pitches submitted yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Campus Events -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-base">Upcoming Campus Events</h3>
                <a href="{{ route('events.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($upcomingEvents as $evt)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white rounded-lg p-2 text-center border border-gray-200 min-w-[50px]">
                                <span class="block text-[10px] font-black uppercase text-[#f97316]">{{ $evt->event_date->format('M') }}</span>
                                <span class="block text-base font-black text-[#0f172a] leading-none">{{ $evt->event_date->format('d') }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 line-clamp-1">{{ $evt->title }}</h4>
                                <p class="text-xs text-gray-500">{{ $evt->time_display }} &bull; {{ $evt->category }}</p>
                            </div>
                        </div>
                        <a href="{{ route('events.show', $evt->id) }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">
                            Pass &rarr;
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        No upcoming campus events scheduled right now.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- GEOGRAPHIC ALUMNI MAP & REGIONAL CHAPTERS      -->
    <!-- ============================================== -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        <span>📍</span> Interactive Regional Chapter Map
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Click on any pin to inspect chapter details, member counts, and meetup venues.</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#1c3272]"></span> Gujarat Hubs</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> National & Global</span>
                    <a href="{{ route('chapters.index') }}" class="font-bold text-[#1c3272] hover:text-[#0f172a] hover:underline flex items-center gap-1 ml-2">
                        Explore All Chapters &rarr;
                    </a>
                </div>
            </div>

            <div id="facultyDashboardMap" class="w-full h-80 sm:h-96 bg-gray-50 z-10"></div>
        </div>

        <!-- Geographic Clusters Breakdown Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 font-serif">Alumni Presence by Industrial Corridor</h3>
                    <p class="text-xs text-gray-500">Estimated distribution of diploma graduates based on profile location.</p>
                </div>
                <a href="{{ route('chapters.index') }}" class="text-xs font-bold text-[#1c3272] hover:underline">
                    View Regional Directory &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
                @if(isset($clusters))
                    @foreach($clusters as $key => $cluster)
                        <a href="{{ route('alumni.index', ['location' => $cluster['name']]) }}" 
                           class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm hover:border-[#1c3272] hover:shadow-md transition-all flex flex-col justify-between group">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 group-hover:text-[#1c3272] transition-colors block truncate" title="{{ $cluster['name'] }}">
                                    {{ $cluster['name'] }}
                                </span>
                                <div class="text-xl font-black text-gray-900 mt-1">
                                    {{ $cluster['count'] }}
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400 group-hover:text-blue-600 transition-colors font-medium mt-2">View Alumni &rarr;</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Leaflet Map Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L === 'undefined') return;
        var mapEl = document.getElementById('facultyDashboardMap');
        if (!mapEl) return;

        var map = L.map('facultyDashboardMap').setView([22.8, 72.5], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var pins = @json($mapPins ?? []);

        pins.forEach(function (pin) {
            if (!pin.lat || !pin.lng) return;

            var marker = L.circleMarker([pin.lat, pin.lng], {
                radius: 8,
                fillColor: pin.city === 'Patan' ? '#ea580c' : '#1c3272',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.85
            }).addTo(map);

            var popupContent = `
                <div style="font-family: inherit; min-width: 170px; padding: 2px;">
                    <div style="font-size: 11px; font-weight: 800; color: #f97316; text-transform: uppercase;">${pin.city}, ${pin.state}</div>
                    <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 2px;">${pin.name}</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">👥 ${pin.members_count} Alumni Joined</div>
                    ${pin.venue ? `<div style="font-size: 10px; color: #475569; margin-top: 2px;">📍 ${pin.venue}</div>` : ''}
                    <div style="margin-top: 8px;">
                        <a href="${pin.url}" style="display: inline-block; background: #1c3272; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-decoration: none;">Explore Chapter &rarr;</a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
        });
    });
</script>
@endsection
