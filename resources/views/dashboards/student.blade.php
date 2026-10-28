@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    
    <!-- Hero Header Section -->
    <div class="relative bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-blue-50/50 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                        <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                    </span>
                    <span class="bg-blue-50 text-blue-800 text-xs font-extrabold uppercase tracking-wider py-1 px-3 rounded-full border border-blue-100 font-heading">
                        GTU Code: 622
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight">
                    Student Academic &amp; Career Hub
                </h1>
                <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Welcome back, <span class="font-bold text-[#0f2042]">{{ Auth::user()->name }}</span>! Access academic resources, borrow equipment from the Pay-It-Forward tool bank, seek mentorship from senior alumni engineers, and prepare for GTU exams.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0">
                <a href="{{ route('chatbot.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#0f2042] to-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg hover:scale-[1.02] transition-all font-heading">
                    <span>🤖</span> Ask KDP Mitra AI &rarr;
                </a>
                <span class="bg-emerald-50 text-emerald-800 text-xs font-extrabold uppercase tracking-widest py-3 px-4 rounded-2xl border border-emerald-200 font-heading">
                    Diploma Engineer
                </span>
            </div>
        </div>

        <!-- Quick Stats Ribbon -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-8 border-t border-slate-100">
            <a href="{{ route('tools.index') }}" class="group bg-slate-50/80 hover:bg-orange-50/50 p-4 rounded-2xl border border-slate-100 hover:border-[#f97316]/30 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-[#f97316]">Tools in Bank</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['available_tools'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Free Drafters &amp; Calcs</span>
            </a>
            <a href="{{ route('jobs.index') }}" class="group bg-slate-50/80 hover:bg-blue-50/50 p-4 rounded-2xl border border-slate-100 hover:border-blue-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-blue-700">Active Jobs</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['active_jobs'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Fresher &amp; Intern Openings</span>
            </a>
            <a href="{{ route('events.index') }}" class="group bg-slate-50/80 hover:bg-amber-50/50 p-4 rounded-2xl border border-slate-100 hover:border-amber-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-amber-700">Upcoming Events</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['upcoming_events'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">Workshops &amp; Seminars</span>
            </a>
            <a href="{{ route('resources.index') }}" class="group bg-slate-50/80 hover:bg-purple-50/50 p-4 rounded-2xl border border-slate-100 hover:border-purple-200 transition-all">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading group-hover:text-purple-700">Academic Vault</span>
                <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['total_resources'] ?? 0 }}</span>
                <span class="text-[10px] text-slate-400 font-medium">GTU Notes &amp; Papers</span>
            </a>
        </div>
    </div>

    <!-- Quick Action Portals (12 Curated Student Cards) -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0f2042] font-heading tracking-tight">Student Action Centers</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Explore essential tools, academic services, and career advancement features.</p>
            </div>
            <span class="text-xs font-bold text-[#f97316] uppercase tracking-wider hidden sm:block font-heading">KDP Polytechnic Patan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Tool & Book Bank -->
            <a href="{{ route('tools.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-[#f97316] hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-orange-50 text-[#f97316] rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        📐
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-orange-100 text-[#c2410c] text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Pay-It-Forward
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-[#f97316] transition-colors">Tool &amp; Book Bank</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Borrow mini-drafters, scientific calculators, and GTU textbooks for free.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#f97316] font-heading">
                    <span>Borrow Item</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 2: KDP Mitra AI Chatbot -->
            <a href="{{ route('chatbot.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-[#1e3a8a] hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-[#1e3a8a] rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🤖
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Groq AI Powered
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-[#1e3a8a] transition-colors">KDP Mitra AI Counselor</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Get instant explanations on GTU diploma syllabus, exam updates, and engineering queries.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#1e3a8a] font-heading">
                    <span>Chat Now</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 3: Academic Calendar -->
            <a href="{{ route('calendar.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-[#1e3a8a] hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🗓️
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        GTU Schedules
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-[#1e3a8a] transition-colors">Academic Calendar</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">View term commencement, mid-sem exam dates, submission windows, and holidays.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-700 font-heading">
                    <span>View Calendar</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 4: Find an Alumni Mentor -->
            <a href="{{ route('mentorship.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🤝
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        1-on-1 Guidance
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-emerald-700 transition-colors">Find a Mentor</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Connect with senior alumni engineers for resume reviews, advice, and interview prep.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-700 font-heading">
                    <span>Connect Mentor</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 5: Job & Internship Board -->
            <a href="{{ route('jobs.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-blue-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        💼
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Alumni Referrals
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-blue-700 transition-colors">Job &amp; Internship Board</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Apply for diploma apprentice roles, internships, and entry-level engineering vacancies.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-700 font-heading">
                    <span>Browse Openings</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 6: Resource Vault -->
            <a href="{{ route('resources.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-purple-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        📚
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Study Hub
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-purple-700 transition-colors">Resource Vault &amp; PYQs</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Download GTU syllabus, previous year question papers, and faculty lecture notes.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-purple-700 font-heading">
                    <span>Access Materials</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 7: Document Requests -->
            <a href="{{ route('documents.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-teal-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-teal-50 text-teal-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        📜
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        College Secretariat
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-teal-700 transition-colors">Document Desk</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Request bonafide certificates, recommendation letters, and transcript verifications online.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-700 font-heading">
                    <span>Submit Request</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 8: Project Incubation -->
            <a href="{{ route('pitches.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-amber-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        💡
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Incubation &amp; Grants
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-amber-700 transition-colors">Project Incubation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Pitch diploma capstone projects to request hardware components or alumni funding.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-700 font-heading">
                    <span>Pitch Project</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 9: Placement Wall -->
            <a href="{{ route('placement.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-cyan-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-cyan-50 text-cyan-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🏢
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        TPO Cell
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-cyan-700 transition-colors">Placement Wall</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Check campus recruitment partners, average packages, and top engineering recruiters.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-cyan-700 font-heading">
                    <span>View Stats</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 10: Success Stories -->
            <a href="{{ route('stories.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-rose-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🌟
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Inspiration
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-rose-700 transition-colors">Success Stories</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Read inspiring career milestones of KDP diploma alumni in industry and entrepreneurship.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-rose-700 font-heading">
                    <span>Read Stories</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 11: Department Labs -->
            <a href="{{ route('labs.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-violet-600 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-violet-50 text-violet-700 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        🔬
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-violet-100 text-violet-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Workshops
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-violet-700 transition-colors">Lab Directory</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Explore department laboratories, testing machinery, and faculty in-charge contacts.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-violet-700 font-heading">
                    <span>Explore Labs</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Card 12: Regional Chapters -->
            <a href="{{ route('chapters.index') }}" class="group p-5 bg-white rounded-2xl border border-slate-100 hover:border-slate-800 hover:shadow-lg transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-slate-100 rounded-bl-full -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 bg-slate-100 text-slate-800 rounded-xl flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform text-xl">
                        📍
                    </div>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-200 text-slate-800 text-[10px] font-extrabold uppercase tracking-wider mb-2 font-heading">
                        Industrial Corridors
                    </div>
                    <h3 class="text-[#0f2042] font-bold text-base mb-1 font-heading group-hover:text-slate-900 transition-colors">Regional Chapters</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Find where KDP graduates work across Ahmedabad, Vadodara, Surat, Rajkot, and Mehsana.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-800 font-heading">
                    <span>View Map &amp; Hubs</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

        </div>
    </div>

    <!-- Dual Live Feeds: Campus Notices & Pay-It-Forward Highlights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- GTU Circulars & Campus Notices -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📋</span>
                        <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">GTU &amp; Campus Circulars</h3>
                    </div>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">Academic Dates &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($notices ?? [] as $notice)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 hover:border-blue-200 transition-all">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-[10px] font-black uppercase text-[#f97316] font-heading">
                                    {{ $notice->created_at ? $notice->created_at->format('M d, Y') : 'Official Notice' }}
                                </span>
                                @if($notice->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-extrabold uppercase tracking-wide">
                                        Live
                                    </span>
                                @endif
                            </div>
                            <h4 class="font-bold text-sm text-[#0f2042] line-clamp-1">{{ $notice->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $notice->body }}</p>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            No circulars published at the moment.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Regularly updated by KDP Patan Administration</span>
                <a href="{{ route('contact') }}" class="font-bold text-[#1e3a8a] hover:underline font-heading">Contact Student Section &rarr;</a>
            </div>
        </div>

        <!-- Available Tools in Bank & Upcoming Campus Events -->
        <div class="space-y-6">
            
            <!-- Pay-It-Forward Available Tools -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📐</span>
                        <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">Available in Tool Bank</h3>
                    </div>
                    <a href="{{ route('tools.index') }}" class="text-xs font-bold text-[#f97316] hover:underline font-heading">Browse All &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($availableTools ?? [] as $tool)
                        <div class="p-3.5 bg-orange-50/40 rounded-2xl border border-orange-100/70 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-orange-200 flex items-center justify-center text-lg shrink-0 shadow-xs">
                                    {{ $tool->item_type === 'book' ? '📖' : ($tool->item_type === 'drafter' ? '📐' : '🧮') }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-[#0f2042] line-clamp-1">{{ $tool->title }}</h4>
                                    <span class="text-[10px] text-slate-500">{{ $tool->department ?? 'General' }} &bull; Sem {{ $tool->semester_suitability ?? 'All' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('tools.show', $tool->slug) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-[11px] font-bold font-heading whitespace-nowrap shadow-xs transition-colors">
                                Claim &rarr;
                            </a>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">
                            No shared tools pending in bank. <a href="{{ route('tools.create') }}" class="text-[#f97316] font-bold hover:underline">Donate an item</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Campus Events -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📅</span>
                        <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">Upcoming Campus Events</h3>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:underline font-heading">All Events &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingEvents ?? [] as $evt)
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="bg-white rounded-xl p-2 text-center border border-slate-200 min-w-[46px]">
                                    <span class="block text-[9px] font-black uppercase text-[#f97316] font-heading">{{ $evt->event_date->format('M') }}</span>
                                    <span class="block text-sm font-black text-[#0f2042] leading-none font-heading">{{ $evt->event_date->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm text-[#0f2042] line-clamp-1">{{ $evt->title }}</h4>
                                    <p class="text-[11px] text-slate-500">{{ $evt->time_display ?? 'Campus' }} &bull; {{ $evt->category ?? 'General' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('events.show', $evt->id) }}" class="text-xs font-bold text-[#1e3a8a] hover:underline shrink-0 font-heading">
                                Details &rarr;
                            </a>
                        </div>
                    @empty
                        <div class="py-4 text-center text-slate-400 text-xs">
                            No upcoming events scheduled right now.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Interactive Regional Chapter Map -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h3 class="font-extrabold text-[#0f2042] text-lg flex items-center gap-2 font-heading">
                    <span>📍</span> Interactive Regional Chapter Map
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Click on any pin to inspect chapter details, member counts, and meetup venues across Gujarat.</p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap font-heading">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#0f2042]"></span> Gujarat Hubs</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> National &amp; Global</span>
                <a href="{{ route('chapters.index') }}" class="font-bold text-[#1e3a8a] hover:text-[#0f2042] hover:underline flex items-center gap-1 ml-2">
                    Explore All Chapters &rarr;
                </a>
            </div>
        </div>

        <div id="studentDashboardMap" class="w-full h-80 sm:h-96 bg-slate-50 z-10"></div>
    </div>

    <!-- Geographic Clusters Breakdown Section -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-[#0f2042] font-heading">Alumni Presence by Industrial Corridor</h3>
                <p class="text-xs text-slate-500">Distribution of diploma graduates working across Gujarat's manufacturing &amp; IT belts.</p>
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
