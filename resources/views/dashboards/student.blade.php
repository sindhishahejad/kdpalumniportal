@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <div class="bg-white p-10 rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
            
            <!--- Header Section --->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Student Dashboard</h1>
                    <p class="text-gray-500 mt-2 text-sm">Access academic resources, connect with alumni mentors, and explore internships.</p>
                </div>
                <span class="w-max bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-blue-200">
                    Current Student
                </span>
            </div>
            
            <!-- Quick Actions Grid (Updated to 4 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Action Card 1 -->
                <a href="{{ route('mentorship.index') ?? '#' }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Find a Mentor</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Connect with experienced KDP alumni for guidance.</p>
                </a>
                
                <!-- Action Card 2 -->
                <a href="{{ route('jobs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Job & Internship Board</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Browse opportunities posted exclusively by the alumni network.</p>
                </a>

                <!-- Action Card 3 -->
                <a href="{{ route('resources.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Resource Vault</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Access study materials, past papers, and department guides.</p>
                </a>

                <!-- Action Card 4 (NEW: Document Requests) -->
                <a href="{{ route('documents.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Document Requests</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Request official bonafide certificates and recommendation letters.</p>
                </a>

                <!-- Action Card: Success Stories -->
                <a href="{{ route('stories.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Success Stories</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Get inspired by notable alumni career paths and achievements.</p>
                </a>

                <!-- Action Card: Project Incubation -->
                <a href="{{ route('pitches.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Project Incubation</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Pitch minor/major projects to request hardware components or alumni guidance.</p>
                </a>

                <!-- Action Card: Lab Directory (Student) -->
                <a href="{{ route('labs.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#0f172a] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#0f172a] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Lab Directory</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Explore department labs, hardware inventories, and faculty in-charge.</p>
                </a>

                <!-- Action Card: Regional Chapters & Map -->
                <a href="{{ route('chapters.index') }}" class="group p-6 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#1c3272] hover:shadow-md transition-all flex flex-col">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#1c3272] group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold mb-1">Regional Chapters & Map</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-grow">Find where alumni live across Gujarat's industrial corridors.</p>
                </a>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- GEOGRAPHIC ALUMNI MAP & REGIONAL CHAPTERS      -->
        <!-- ============================================== -->
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

            <div id="studentDashboardMap" class="w-full h-80 sm:h-96 bg-gray-50 z-10"></div>
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

    <!-- Leaflet Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;
            var mapEl = document.getElementById('studentDashboardMap');
            if (!mapEl) return;

            var map = L.map('studentDashboardMap').setView([22.8, 72.5], 7);

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