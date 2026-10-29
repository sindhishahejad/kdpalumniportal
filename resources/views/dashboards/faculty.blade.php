@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

    <!-- Header Section -->
    <div class="relative bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-purple-50/50 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                        <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                    </span>
                    <span class="bg-purple-50 text-purple-800 text-xs font-extrabold uppercase tracking-wider py-1 px-3 rounded-full border border-purple-100 font-heading">
                        Department Faculty Portal
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight">
                    Faculty Dashboard
                </h1>
                <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Welcome, Professor <span class="font-bold text-[#0f2042]">{{ Auth::user()->name }}</span>. Manage department laboratories, upload study resources, oversee diploma project pitches, and engage with our alumni network.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0">
                <a href="{{ route('chatbot.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#0f2042] to-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg hover:scale-[1.02] transition-all font-heading">
                    <span>🤖</span> KDP Mitra AI &rarr;
                </a>
                <span class="bg-purple-50 text-purple-800 text-xs font-extrabold uppercase tracking-widest py-3 px-4 rounded-2xl border border-purple-200 font-heading">
                    Academic Access
                </span>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-8 border-t border-slate-100">
            <a href="{{ route('labs.index') }}" class="group bg-slate-50/80 hover:bg-purple-50/50 p-4 rounded-2xl border border-slate-100 hover:border-purple-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-purple-700">Department Labs</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['total_labs'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Workshops &amp; Facilities</span>
            </a>
            <a href="{{ route('resources.index') }}" class="group bg-slate-50/80 hover:bg-blue-50/50 p-4 rounded-2xl border border-slate-100 hover:border-blue-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-blue-700">Shared Resources</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['total_resources'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Notes &amp; Lab Manuals</span>
            </a>
            <a href="{{ route('pitches.index') }}" class="group bg-slate-50/80 hover:bg-amber-50/50 p-4 rounded-2xl border border-slate-100 hover:border-amber-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-amber-700">Project Pitches</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['open_pitches'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Open Capstone Ideas</span>
            </a>
            <a href="{{ route('events.index') }}" class="group bg-slate-50/80 hover:bg-emerald-50/50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-emerald-700">Upcoming Events</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['upcoming_events'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Seminars &amp; Drives</span>
            </a>
        </div>
    </div>

    <!-- Department & Engagement Modules -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0f2042] font-heading tracking-tight">Faculty Management Modules</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Academic coordination, student mentorship, and alumni partnership tools.</p>
            </div>
            <span class="text-xs font-bold text-[#f97316] uppercase tracking-wider hidden sm:block font-heading">KDP Polytechnic Patan</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Module 1: Academic Resource Vault -->
            <a href="{{ route('resources.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        📚
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-[#1e3a8a] transition-colors">Academic Resource Vault</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Share course notes, GTU exam papers, lab manuals, and MOOC video references with diploma students.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-blue-700 group-hover:translate-x-1 transition-transform font-heading">
                    Browse &amp; Upload &rarr;
                </div>
            </a>

            <!-- Module 2: Alumni Directory -->
            <a href="{{ route('alumni.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        👥
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-emerald-700 transition-colors">Alumni Directory</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Search notable alumni across industries and invite them as guest speakers, project juries, or examiners.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-emerald-700 group-hover:translate-x-1 transition-transform font-heading">
                    Search Alumni &rarr;
                </div>
            </a>

            <!-- Module 3: GTU Academic Calendar -->
            <a href="{{ route('calendar.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        🗓️
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-indigo-700 transition-colors">GTU Academic Calendar</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Keep term timelines, mid-semester exam dates, submission cutoffs, and GTU circulars synced.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-indigo-700 group-hover:translate-x-1 transition-transform font-heading">
                    View Academic Calendar &rarr;
                </div>
            </a>

            <!-- Module 4: Department Labs -->
            <a href="{{ route('labs.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        🔬
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-purple-700 transition-colors">Department Labs</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Update laboratory hardware inventories, room locations, testing benches, and faculty coordinator records.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-purple-700 group-hover:translate-x-1 transition-transform font-heading">
                    Manage Labs &rarr;
                </div>
            </a>

            <!-- Module 5: Project Incubation -->
            <a href="{{ route('pitches.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        💡
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-amber-700 transition-colors">Project Incubation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Review diploma capstone innovation pitches seeking mentorship, alumni grants, or lab test equipment.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-amber-700 group-hover:translate-x-1 transition-transform font-heading">
                    Review Pitches &rarr;
                </div>
            </a>

            <!-- Module 6: Pay-It-Forward Tool Bank -->
            <a href="{{ route('tools.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-orange-50 text-[#f97316] rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        📐
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-[#f97316] transition-colors">Tool &amp; Book Bank Oversight</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Support juniors by encouraging graduating students to donate calculators, drafters, and reference manuals.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-[#f97316] group-hover:translate-x-1 transition-transform font-heading">
                    Access Tool Bank &rarr;
                </div>
            </a>

            <!-- Module 7: Training & Placement Cell -->
            <a href="{{ route('placement.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-cyan-50 text-cyan-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        🏢
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-cyan-700 transition-colors">Training &amp; Placement (TPO)</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Check campus recruitment partners, drive announcements, and student placement package metrics.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-cyan-700 group-hover:translate-x-1 transition-transform font-heading">
                    View Placement Stats &rarr;
                </div>
            </a>

            <!-- Module 8: Campus Events -->
            <a href="{{ route('events.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        📅
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-rose-700 transition-colors">Campus Events</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Coordinate departmental seminars, expert faculty sessions, project expos, and webinars.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-rose-700 group-hover:translate-x-1 transition-transform font-heading">
                    Manage Events &rarr;
                </div>
            </a>

            <!-- Module 9: Knowledge & Challenge Feed -->
            <a href="{{ route('posts.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-teal-50 text-teal-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        💬
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-teal-700 transition-colors">Knowledge Feed &amp; Challenges</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Post technical engineering challenges, algorithmic problems, or participate in student discussions.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-teal-700 group-hover:translate-x-1 transition-transform font-heading">
                    Visit Feed &rarr;
                </div>
            </a>

            <!-- Module 10: Digital E-Library & Capstone Vault -->
            <a href="{{ route('elibrary.index') }}" class="group p-6 bg-white rounded-3xl border border-slate-100 hover:border-[#0f2042] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center shadow-xs mb-4 group-hover:scale-110 transition-transform text-xl">
                        📖
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-lg mb-1 font-heading group-hover:text-indigo-700 transition-colors">Digital E-Library &amp; Vault</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Publish department capstone project synopses, GTU e-books, and lab reference manual sheets.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-indigo-700 group-hover:translate-x-1 transition-transform font-heading">
                    Manage E-Library &rarr;
                </div>
            </a>

        </div>
    </div>

    <!-- Bottom Two Column Section: Recent Pitches & Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Student Project Pitches -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">💡</span>
                        <h3 class="font-extrabold text-[#0f2042] text-base font-heading">Recent Student Project Pitches</h3>
                    </div>
                    <a href="{{ route('pitches.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">View All &rarr;</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentPitches as $pitch)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-sm text-[#0f2042]">{{ $pitch->title }}</h4>
                                    <span class="bg-blue-100 text-[#1e3a8a] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase font-heading">
                                        {{ $pitch->status ?? 'Open' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">{{ $pitch->description }}</p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-400">
                                <span>Student: <strong class="text-[#0f2042]">{{ $pitch->student->name ?? 'Student' }}</strong></span>
                                <span class="font-medium text-slate-600">Tech: {{ $pitch->tech_stack }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            No student project pitches submitted yet.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-6 pt-3 border-t border-slate-100 text-right">
                <a href="{{ route('pitches.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">Explore Incubation Portal &rarr;</a>
            </div>
        </div>

        <!-- Upcoming Campus Events -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📅</span>
                        <h3 class="font-extrabold text-[#0f2042] text-base font-heading">Upcoming Campus Events</h3>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">View All &rarr;</a>
                </div>

                <div class="space-y-4">
                    @forelse($upcomingEvents as $evt)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white rounded-xl p-2 text-center border border-slate-200 min-w-[50px] shadow-xs">
                                    <span class="block text-[10px] font-black uppercase text-[#f97316] font-heading">{{ $evt->event_date->format('M') }}</span>
                                    <span class="block text-base font-black text-[#0f2042] leading-none font-heading">{{ $evt->event_date->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f2042] line-clamp-1">{{ $evt->title }}</h4>
                                    <p class="text-xs text-slate-500">{{ $evt->time_display ?? 'Campus' }} &bull; {{ $evt->category ?? 'General' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('events.show', $evt->id) }}" class="text-xs font-bold text-[#1e3a8a] hover:underline shrink-0 font-heading">
                                View &rarr;
                            </a>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            No upcoming campus events scheduled right now.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-6 pt-3 border-t border-slate-100 text-right">
                <a href="{{ route('events.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">Manage All Events &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Interactive Regional Chapter Map -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="font-extrabold text-[#0f2042] text-lg flex items-center gap-2 font-heading">
                        <span>📍</span> Interactive Regional Chapter Map
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Click on any pin to inspect chapter details, member counts, and industrial venues across Gujarat.</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap font-heading">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#0f2042]"></span> Gujarat Hubs</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> National &amp; Global</span>
                    <a href="{{ route('chapters.index') }}" class="font-bold text-[#1e3a8a] hover:text-[#0f2042] hover:underline flex items-center gap-1 ml-2">
                        Explore All Chapters &rarr;
                    </a>
                </div>
            </div>

            <div id="facultyDashboardMap" class="w-full h-80 sm:h-96 bg-slate-50 z-10"></div>
        </div>

        <!-- Geographic Clusters Breakdown Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Alumni Presence by Industrial Corridor</h3>
                    <p class="text-xs text-slate-500">Distribution of diploma graduates working across Gujarat's industrial areas.</p>
                </div>
                <a href="{{ route('chapters.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">
                    View Regional Directory &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
                @if(isset($clusters))
                    @foreach($clusters as $key => $cluster)
                        <a href="{{ route('alumni.index', ['location' => $cluster['name']]) }}" 
                           class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-xs hover:border-[#1e3a8a] hover:shadow-md transition-all flex flex-col justify-between group">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-[#1e3a8a] transition-colors block truncate font-heading" title="{{ $cluster['name'] }}">
                                    {{ $cluster['name'] }}
                                </span>
                                <div class="text-xl font-black text-[#0f2042] mt-1 font-heading">
                                    {{ $cluster['count'] }}
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 group-hover:text-[#1e3a8a] transition-colors font-bold mt-2 font-heading">View Alumni &rarr;</span>
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

        var map = L.map('facultyDashboardMap', { attributionControl: false }).setView([22.8, 72.5], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: ''
        }).addTo(map);

        var pins = @json($mapPins ?? []);

        pins.forEach(function (pin) {
            if (!pin.lat || !pin.lng) return;

            var marker = L.circleMarker([pin.lat, pin.lng], {
                radius: 8,
                fillColor: pin.city === 'Patan' ? '#ea580c' : '#0f2042',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.85
            }).addTo(map);

            var popupContent = `
                <div style="font-family: inherit; min-width: 170px; padding: 2px;">
                    <div style="font-size: 11px; font-weight: 800; color: #f97316; text-transform: uppercase;">${pin.city}, ${pin.state}</div>
                    <div style="font-size: 13px; font-weight: bold; color: #0f2042; margin-top: 2px;">${pin.name}</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">👥 ${pin.members_count} Alumni Joined</div>
                    ${pin.venue ? `<div style="font-size: 10px; color: #475569; margin-top: 2px;">📍 ${pin.venue}</div>` : ''}
                    <div style="margin-top: 8px;">
                        <a href="${pin.url}" style="display: inline-block; background: #0f2042; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-decoration: none;">Explore Chapter &rarr;</a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
        });
    });
</script>
@endsection
