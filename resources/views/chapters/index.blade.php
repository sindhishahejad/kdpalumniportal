@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ 
    activeFilter: '{{ request('type', 'all') }}',
    mapPins: {{ json_encode($mapPins) }}
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-200 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Geographic Network & Local Chapters</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a] mt-1">Alumni Distribution & Regional Chapters</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                Discover where fellow KDP diploma engineers work and live across Gujarat's industrial corridors, India's tech hubs, and international diaspora.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('alumni.index') }}" class="inline-flex items-center gap-2 bg-[#0f172a] hover:bg-black text-white text-xs sm:text-sm font-bold py-2.5 px-4 rounded-xl shadow-sm transition-all whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Search Alumni by City
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- High-Level Metric Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Active Chapters</span>
            <div class="text-2xl font-bold text-gray-900">{{ $chapters->count() }} Chapters</div>
            <span class="text-[11px] text-gray-500">Gujarat, National & Global</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm bg-gradient-to-br from-white to-blue-50/40">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#1c3272] block mb-1">Chapter Members</span>
            <div class="text-2xl font-bold text-[#1c3272]">{{ $totalMembersJoined }} Alumni</div>
            <span class="text-[11px] text-blue-600 font-medium">Joined Regional Hubs</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm bg-gradient-to-br from-white to-orange-50/40">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#f97316] block mb-1">Total Network</span>
            <div class="text-2xl font-bold text-[#0f172a]">{{ $totalAlumni }} Alumni</div>
            <span class="text-[11px] text-gray-500">Registered on Portal</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm bg-gradient-to-br from-white to-emerald-50/40">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block mb-1">My Chapter Status</span>
            <div class="text-2xl font-bold text-emerald-700">{{ count($myChapterIds) }} Joined</div>
            <span class="text-[11px] text-emerald-600 font-medium">Your Local Community</span>
        </div>
    </div>

    <!-- Interactive Leaflet Map Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span>📍</span> Interactive Regional Chapter Map
                </h3>
                <p class="text-xs text-gray-500">Click on any pin to inspect chapter details, member counts, and meetup venues.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#1c3272]"></span> Gujarat Hubs
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#f97316] ml-2"></span> National & Global
            </div>
        </div>

        <div id="alumniMap" class="w-full h-80 sm:h-96 bg-gray-50 z-10"></div>
    </div>

    <!-- Geographic Clusters Breakdown Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 font-serif">Alumni Presence by Industrial Corridor</h3>
                <p class="text-xs text-gray-500">Estimated distribution of diploma graduates based on profile location.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
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
        </div>
    </div>

    <!-- Chapter Cards List Header & Controls -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">Filter Region:</span>
                
                <a href="{{ route('chapters.index') }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ !request('type') ? 'bg-[#0f172a] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                    All Chapters ({{ $chapters->count() }})
                </a>

                <a href="{{ route('chapters.index', ['type' => 'gujarat']) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'gujarat' ? 'bg-[#1c3272] text-white shadow-sm' : 'bg-blue-50 text-[#1c3272] hover:bg-blue-100 border border-blue-100' }}">
                    Gujarat Corridors
                </a>

                <a href="{{ route('chapters.index', ['type' => 'national']) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'national' ? 'bg-[#1c3272] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                    National Hubs
                </a>

                <a href="{{ route('chapters.index', ['type' => 'global']) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('type') === 'global' ? 'bg-[#1c3272] text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100' }}">
                    Overseas & Global
                </a>
            </div>

            <!-- Search Field -->
            <form method="GET" action="{{ route('chapters.index') }}" class="flex gap-2 w-full sm:w-auto">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search chapter name or city..." 
                       class="text-xs border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272] w-full sm:w-64">
                <button type="submit" class="bg-[#0f172a] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Regional Chapters Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($chapters as $chapter)
            @php
                $isJoined = in_array($chapter->id, $myChapterIds);
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Header with Region Badge -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $chapter->region_type === 'gujarat' ? 'bg-blue-50 text-[#1c3272] border border-blue-200' : ($chapter->region_type === 'global' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-orange-50 text-[#ea580c] border border-orange-200') }}">
                            {{ $chapter->region_type === 'gujarat' ? 'Gujarat Corridor' : ($chapter->region_type === 'global' ? 'Global Chapter' : 'National Hub') }}
                        </span>

                        <span class="text-xs font-bold text-gray-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                            {{ $chapter->members_count }} {{ Str::plural('Member', $chapter->members_count) }}
                        </span>
                    </div>

                    <!-- Title & City -->
                    <h3 class="text-lg font-bold text-gray-900 leading-snug">
                        <a href="{{ route('chapters.show', $chapter->slug) }}" class="hover:text-[#1c3272] transition-colors">
                            {{ $chapter->name }}
                        </a>
                    </h3>
                    <p class="text-xs text-gray-500 font-semibold mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $chapter->city }}, {{ $chapter->state }}
                    </p>

                    <!-- Description -->
                    <p class="text-xs text-gray-600 line-clamp-2 mt-3 leading-relaxed">
                        {{ $chapter->description }}
                    </p>

                    <!-- Venue / Hub note -->
                    @if($chapter->meeting_venue)
                        <div class="mt-4 p-2.5 bg-gray-50 rounded-xl border border-gray-100 text-[11px] text-gray-600 flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-[#1c3272] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="truncate"><strong>Meetup Hub:</strong> {{ $chapter->meeting_venue }}</span>
                        </div>
                    @endif
                </div>

                <!-- Footer Action Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                    <form action="{{ route('chapters.join', $chapter->slug) }}" method="POST">
                        @csrf
                        @if($isJoined)
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-red-50 hover:text-red-700 rounded-lg border border-emerald-200 transition-colors" title="Click to leave chapter">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Joined ✓
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-[#1c3272] bg-blue-50 hover:bg-[#1c3272] hover:text-white rounded-lg border border-blue-200 transition-all shadow-sm">
                                <span>+</span> Join Chapter
                            </button>
                        @endif
                    </form>

                    <a href="{{ route('chapters.show', $chapter->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-gray-700 hover:text-[#1c3272] transition-colors">
                        View Community &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 text-[#1c3272] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Chapters Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">There are currently no regional chapters matching your filter. Clear filters to explore all hubs.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Leaflet Map Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') return;

    // Center on Gujarat: lat: 22.8, lng: 72.5, zoom: 7
    var map = L.map('alumniMap', { attributionControl: false }).setView([22.8, 72.5], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: ''
    }).addTo(map);

    var pins = @json($mapPins);

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
