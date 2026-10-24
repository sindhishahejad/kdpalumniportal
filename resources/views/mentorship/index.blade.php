@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20" 
     x-data="{ 
         activeTab: window.location.hash ? window.location.hash.replace('#', '') : 'directory',
         showRequestModal: false, 
         selectedListingId: null, 
         selectedMentorName: '', 
         selectedMentorTitle: '',
         showAcceptModal: false,
         acceptRequestId: null,
         acceptMenteeName: '',
         showDeclineModal: false,
         declineRequestId: null,
         declineMenteeName: ''
     }">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Watermark Backgrounds -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-80 h-80 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
        <div class="absolute -left-12 -top-12 opacity-5 pointer-events-none">
            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-64 h-64 object-contain filter invert" alt="GTU Seal Watermark">
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Mentorship Program &bull; Career Desk
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>🤝</span> {{ $mentors->total() }} Active Mentors
                </span>
                @if($activeSessionsCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                        <span>⚡</span> {{ $activeSessionsCount }} Sessions Connected
                    </span>
                @endif
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                        1-on-1 Alumni Mentorship Hub
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                        Connect 1-on-1 with distinguished KDP engineering alumni for Diploma-to-Degree (D2D) guidance, GTU exam strategies, technical mock interviews, and career counseling.
                    </p>
                </div>

                <!-- Guidance Cell Info Box & Quick CTA -->
                <div class="flex flex-col sm:flex-row md:flex-col gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-2xl p-4 text-xs max-w-xs text-blue-100 hidden sm:block">
                        <div class="flex items-center gap-2 font-bold text-white uppercase font-heading mb-1 text-[11px]">
                            <span>🏛️</span> Career &amp; Mentorship Cell
                        </div>
                        <p class="text-[11px] leading-relaxed text-blue-200/90">
                            100% Free peer-to-peer volunteer guidance. Sessions are conducted via Google Meet, Zoom, or direct messaging.
                        </p>
                    </div>

                    <button @click="activeTab = 'profile'; window.location.hash = 'profile'" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 font-heading bg-[#f97316] hover:bg-orange-600 text-white shadow-md cursor-pointer uppercase tracking-wider">
                        <span>{{ $userListing ? '✏️ Manage Mentor Profile' : '🤝 Apply to be a Mentor' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Flash Notifications -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-base">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-base">&times;</button>
            </div>
        @endif

        <!-- Main Navigation Tabs -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-2 mb-8 flex items-center gap-2 overflow-x-auto scrollbar-none">
            <!-- Tab 1: Directory -->
            <button type="button" 
                    @click="activeTab = 'directory'; window.location.hash = 'directory'" 
                    :class="activeTab === 'directory' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0">
                <span>🔍 Find a Mentor</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'directory' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $mentors->total() }}
                </span>
            </button>

            <!-- Tab 2: My Sent Requests -->
            <button type="button" 
                    @click="activeTab = 'my_sessions'; window.location.hash = 'my-sessions'" 
                    :class="activeTab === 'my_sessions' || activeTab === 'my-sessions' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0">
                <span>🎓 My Mentorship Requests</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'my_sessions' || activeTab === 'my-sessions' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $mySentRequests->count() }}
                </span>
            </button>

            <!-- Tab 3: Incoming Requests (Mentor Hub) -->
            <button type="button" 
                    @click="activeTab = 'incoming'; window.location.hash = 'mentor-requests'" 
                    :class="activeTab === 'incoming' || activeTab === 'mentor-requests' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0 relative">
                <span>📥 Requests Received (Mentor Hub)</span>
                @if($pendingReceivedCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500 text-white animate-pulse">
                        {{ $pendingReceivedCount }} Action Required
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'incoming' || activeTab === 'mentor-requests' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">
                        {{ $myReceivedRequests->count() }}
                    </span>
                @endif
            </button>

            <!-- Tab 4: Profile Settings -->
            <button type="button" 
                    @click="activeTab = 'profile'; window.location.hash = 'profile'" 
                    :class="activeTab === 'profile' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0">
                <span>⚙️ Mentor Profile</span>
                @if($userListing && $userListing->is_available)
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                @endif
            </button>
        </div>

        <!-- ============================================== -->
        <!-- TAB 1: MENTOR DIRECTORY (FIND A MENTOR)        -->
        <!-- ============================================== -->
        <div x-show="activeTab === 'directory'" x-transition:enter="transition ease-out duration-200">
            
            <!-- Search & Filter Bar -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 mb-8">
                <form method="GET" action="{{ route('mentorship.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Search Mentors</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, or skills (e.g. Python, D2D)..." class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] shadow-xs">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Engineering Branch</label>
                        <select name="department" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] text-slate-700 font-medium shadow-xs">
                            <option value="">All Engineering Departments</option>
                            <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                            <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                            <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                            <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                            <option value="General" {{ request('department') === 'General' ? 'selected' : '' }}>🔬 General Department</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3 flex items-center gap-2">
                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading cursor-pointer">
                            Filter Mentors
                        </button>
                        @if(request()->anyFilled(['search', 'department']))
                            <a href="{{ route('mentorship.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold transition-colors" title="Reset Filters">✕</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Mentors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($mentors as $listing)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header Strip -->
                            <div class="h-20 bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] relative p-3 flex justify-between items-start">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/90 text-white backdrop-blur-xs font-heading shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Available Mentor
                                </span>
                                
                                @if(optional($listing->user->profile)->department)
                                    <span class="text-[10px] font-bold text-orange-300 uppercase tracking-wider font-heading px-2 py-0.5 rounded-md bg-white/10 border border-white/20">
                                        {{ optional($listing->user->profile)->department }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body with Avatar -->
                            <div class="px-6 pb-6 pt-0 relative">
                                <div class="-mt-10 mb-3 flex items-end justify-between">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-white shadow-md bg-white shrink-0">
                                        @if(optional($listing->user->profile)->avatar_url)
                                            <img src="{{ optional($listing->user->profile)->avatar_url }}" alt="{{ $listing->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#1e3a8a] text-white font-bold text-2xl flex items-center justify-center font-heading">
                                                {{ substr($listing->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('alumni.show', $listing->user_id) }}" class="text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] flex items-center gap-1 font-heading transition-colors">
                                        <span>View Profile</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>

                                <h3 class="font-heading font-black text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors line-clamp-1 leading-snug">
                                    {{ $listing->user->name }}
                                </h3>

                                <p class="text-xs font-bold text-[#f97316] mb-1 line-clamp-1 font-heading">
                                    {{ $listing->title }}
                                </p>

                                <p class="text-[11px] text-slate-500 mb-3 flex items-center gap-1.5 flex-wrap">
                                    <span class="font-medium text-slate-600">🎓 Class of {{ optional($listing->user->profile)->batch_year ?? $listing->user->graduation_year ?? 'Alumni' }}</span>
                                    @if(optional($listing->user->profile)->current_company)
                                        <span>&bull;</span>
                                        <span class="truncate font-medium text-slate-600">🏢 {{ optional($listing->user->profile)->current_company }}</span>
                                    @endif
                                </p>

                                <!-- Expertise Tags -->
                                <div class="mb-4">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5 font-heading">Expertise Focus:</span>
                                    <div class="flex flex-wrap gap-1.5 max-h-14 overflow-hidden">
                                        @foreach(explode(',', $listing->expertise_areas) as $tag)
                                            <span class="bg-blue-50 border border-blue-200/80 text-[#1e3a8a] text-[10px] font-bold px-2 py-0.5 rounded-lg truncate">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Bio Description -->
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-2 font-sans bg-slate-50/50 p-2.5 rounded-xl border border-slate-100">
                                    {{ $listing->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-3">
                            @if($listing->user_id === auth()->id())
                                <button type="button" @click="activeTab = 'profile'; window.location.hash = 'profile'" class="w-full text-center bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold py-2.5 rounded-xl hover:bg-amber-100 transition-colors font-heading cursor-pointer">
                                    ✏️ Manage Your Listing
                                </button>
                            @else
                                <button type="button" 
                                        @click="showRequestModal = true; selectedListingId = {{ $listing->id }}; selectedMentorName = '{{ addslashes($listing->user->name) }}'; selectedMentorTitle = '{{ addslashes($listing->title) }}'" 
                                        class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs hover:shadow transition-all font-heading flex items-center justify-center gap-2 cursor-pointer uppercase tracking-wider">
                                    <span>🤝 Request 1-on-1 Guidance</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 px-6 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 shadow-sm">
                        <div class="w-20 h-20 mx-auto mb-4 bg-blue-50 text-[#1e3a8a] rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-blue-100">
                            🤝
                        </div>
                        <h3 class="text-lg font-black text-[#0f172a] mb-1 font-heading uppercase tracking-wide">No Mentors Found</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-5 font-sans leading-relaxed">
                            Try clearing your search filters or be the first alumnus to register as a mentor to guide junior diploma students.
                        </p>
                        <button type="button" @click="activeTab = 'profile'; window.location.hash = 'profile'" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition-colors font-heading cursor-pointer">
                            Apply to be a Mentor &rarr;
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $mentors->links() }}
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: MY MENTORSHIP REQUESTS (STUDENT)        -->
        <!-- ============================================== -->
        <div x-show="activeTab === 'my_sessions' || activeTab === 'my-sessions'" x-transition:enter="transition ease-out duration-200">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black font-heading text-[#0f172a] uppercase tracking-wide">
                            My Guidance Requests &amp; Scheduled Sessions
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5 font-sans">
                            Track the status of mentorship guidance requests you have sent to alumni.
                        </p>
                    </div>
                    <button type="button" @click="activeTab = 'directory'; window.location.hash = 'directory'" class="px-4 py-2 bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 font-bold text-xs rounded-xl transition-colors font-heading flex items-center gap-1 cursor-pointer">
                        <span>+ Find More Mentors</span>
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse($mySentRequests as $request)
                        <div class="p-5 sm:p-6 rounded-2xl border border-slate-200 hover:border-slate-300 transition-all bg-white shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $request->isAccepted() ? 'border-l-4 border-l-emerald-500' : ($request->isPending() ? 'border-l-4 border-l-[#f97316]' : ($request->isCompleted() ? 'border-l-4 border-l-[#1e3a8a]' : 'border-l-4 border-l-slate-400')) }}">
                            <div class="flex-1 min-w-0">
                                <!-- Status & Metadata Badge -->
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($request->isPending())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#f97316] animate-pulse"></span>
                                            Pending Mentor Review
                                        </span>
                                    @elseif($request->isAccepted())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-heading">
                                            <span>✓</span> Session Confirmed &amp; Scheduled
                                        </span>
                                    @elseif($request->isCompleted())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-blue-50 text-[#1e3a8a] border border-blue-200 font-heading">
                                            <span>★</span> Mentorship Completed
                                        </span>
                                    @elseif($request->isDeclined())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 font-heading">
                                            <span>✕</span> Request Declined
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400 font-mono">
                                        Submitted {{ $request->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <h3 class="font-heading font-bold text-base text-[#0f172a] mb-1">
                                    Mentor: <a href="{{ route('alumni.show', $request->mentor_id) }}" class="text-[#1e3a8a] hover:underline">{{ $request->mentor->name }}</a>
                                    <span class="text-xs text-slate-500 font-normal">({{ optional($request->listing)->title ?? 'Alumni Mentor' }})</span>
                                </h3>

                                @if($request->topic)
                                    <p class="text-xs font-bold text-slate-700 mb-1">
                                        Topic: <span class="text-[#f97316]">{{ $request->topic }}</span>
                                    </p>
                                @endif

                                <p class="text-xs text-slate-600 mb-3 bg-slate-50 p-3 rounded-xl border border-slate-100 italic font-sans leading-relaxed">
                                    "{{ $request->message }}"
                                </p>

                                <!-- Accepted Session Info Box -->
                                @if($request->isAccepted())
                                    <div class="p-4 bg-emerald-50/80 rounded-2xl border border-emerald-200 text-xs text-emerald-950 space-y-2 mb-2">
                                        @if($request->session_time)
                                            <p class="font-bold flex items-center gap-1.5">
                                                <span>⏰ Scheduled Time:</span>
                                                <span class="font-normal">{{ $request->session_time }}</span>
                                            </p>
                                        @endif
                                        @if($request->meeting_link)
                                            <p class="font-bold flex items-center gap-1.5">
                                                <span>🔗 Session Link:</span>
                                                <a href="{{ $request->meeting_link }}" target="_blank" class="text-blue-700 underline font-normal break-all">{{ $request->meeting_link }}</a>
                                            </p>
                                        @endif
                                        @if($request->mentor_notes)
                                            <p class="font-bold flex items-center gap-1.5">
                                                <span>📝 Mentor's Note:</span>
                                                <span class="font-normal italic">{{ $request->mentor_notes }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if($request->isDeclined() && $request->mentor_notes)
                                    <p class="text-xs text-slate-500 italic mb-2">
                                        Mentor feedback: "{{ $request->mentor_notes }}"
                                    </p>
                                @endif
                            </div>

                            <!-- Action Controls -->
                            <div class="flex items-center gap-2 shrink-0 self-end md:self-center flex-wrap">
                                @if($request->isAccepted() && $request->meeting_link)
                                    <a href="{{ $request->meeting_link }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-xs flex items-center gap-1.5 font-heading">
                                        <span>📹 Join Meeting</span>
                                    </a>
                                @endif

                                <a href="{{ route('messages.inbox', $request->mentor_id) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1.5 font-heading">
                                    <span>💬 Message</span>
                                </a>

                                @if($request->isAccepted())
                                    <form action="{{ route('mentorship.requests.complete', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors font-heading cursor-pointer" title="Mark session as completed">
                                            ✓ Complete
                                        </button>
                                    </form>
                                @endif

                                @if($request->isPending())
                                    <form action="{{ route('mentorship.requests.cancel', $request->id) }}" method="POST" onsubmit="return confirm('Cancel this mentorship request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors font-heading cursor-pointer">
                                            Cancel Request
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-14 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-6">
                            <div class="text-3xl mb-2">🎓</div>
                            <h4 class="text-sm font-bold text-slate-700 font-heading mb-1">No Active Guidance Requests</h4>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-sans">
                                You have not requested guidance from any alumni mentors yet. Explore the directory to find a mentor!
                            </p>
                            <button type="button" @click="activeTab = 'directory'; window.location.hash = 'directory'" class="px-4 py-2 bg-[#1e3a8a] text-white hover:bg-blue-900 font-bold text-xs rounded-xl transition-colors font-heading">
                                Browse Mentors &rarr;
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 3: INCOMING REQUESTS (MENTOR HUB)          -->
        <!-- ============================================== -->
        <div x-show="activeTab === 'incoming' || activeTab === 'mentor-requests'" x-transition:enter="transition ease-out duration-200">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black font-heading text-[#0f172a] uppercase tracking-wide">
                            Mentees Awaiting Your Guidance
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5 font-sans">
                            Review and schedule sessions for students and junior alumni seeking your mentorship.
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200 font-heading">
                        {{ $myReceivedRequests->count() }} Total Requests
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($myReceivedRequests as $req)
                        <div class="p-5 sm:p-6 rounded-2xl border border-slate-200 hover:border-slate-300 transition-all bg-white shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $req->isPending() ? 'border-l-4 border-l-[#f97316]' : ($req->isAccepted() ? 'border-l-4 border-l-emerald-500' : ($req->isCompleted() ? 'border-l-4 border-l-[#1e3a8a]' : 'border-l-4 border-l-slate-400')) }}">
                            <div class="flex-1 min-w-0">
                                <!-- Status Indicator -->
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($req->isPending())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#f97316] animate-pulse"></span>
                                            Action Required &bull; Pending Review
                                        </span>
                                    @elseif($req->isAccepted())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-heading">
                                            <span>✓</span> Accepted &amp; Scheduled
                                        </span>
                                    @elseif($req->isCompleted())
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-blue-50 text-[#1e3a8a] border border-blue-200 font-heading">
                                            <span>★</span> Completed Session
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 font-heading">
                                            Declined
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400 font-mono">
                                        {{ $req->created_at->format('M d, Y &bull; h:i A') }}
                                    </span>
                                </div>

                                <!-- Student Info -->
                                <h3 class="font-heading font-bold text-base text-[#0f172a] mb-1">
                                    Student: <a href="{{ route('alumni.show', $req->mentee_id) }}" class="text-[#1e3a8a] hover:underline">{{ $req->mentee->name }}</a>
                                    <span class="text-xs text-slate-500 font-normal font-sans">
                                        ({{ optional($req->mentee->profile)->department ?: 'Student' }} &bull; Class of {{ optional($req->mentee->profile)->batch_year ?: ($req->mentee->graduation_year ?: 'Current') }})
                                    </span>
                                </h3>

                                @if($req->topic)
                                    <p class="text-xs font-bold text-slate-700 mb-1">
                                        Guidance Topic: <span class="text-[#f97316]">{{ $req->topic }}</span>
                                    </p>
                                @endif

                                <p class="text-xs text-slate-600 mb-3 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed font-sans">
                                    "{{ $req->message }}"
                                </p>

                                <!-- Scheduled Session Info -->
                                @if($req->isAccepted())
                                    <div class="p-4 bg-emerald-50/80 rounded-2xl border border-emerald-200 text-xs text-emerald-950 space-y-1 mb-2">
                                        @if($req->session_time)
                                            <p class="font-bold">⏰ Scheduled Time: <span class="font-normal">{{ $req->session_time }}</span></p>
                                        @endif
                                        @if($req->meeting_link)
                                            <p class="font-bold">🔗 Meeting Link: <a href="{{ $req->meeting_link }}" target="_blank" class="text-blue-700 underline font-normal break-all">{{ $req->meeting_link }}</a></p>
                                        @endif
                                        @if($req->mentor_notes)
                                            <p class="font-bold">📝 Note Given: <span class="font-normal italic">{{ $req->mentor_notes }}</span></p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Mentor Action Controls -->
                            <div class="flex items-center gap-2 shrink-0 self-end md:self-center flex-wrap justify-end">
                                <a href="{{ route('messages.inbox', $req->mentee_id) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors font-heading">
                                    💬 Direct Chat
                                </a>

                                @if($req->isPending())
                                    <button type="button" 
                                            @click="showAcceptModal = true; acceptRequestId = {{ $req->id }}; acceptMenteeName = '{{ addslashes($req->mentee->name) }}'" 
                                            class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-colors font-heading cursor-pointer">
                                        ✓ Accept &amp; Schedule
                                    </button>

                                    <button type="button" 
                                            @click="showDeclineModal = true; declineRequestId = {{ $req->id }}; declineMenteeName = '{{ addslashes($req->mentee->name) }}'" 
                                            class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer font-heading">
                                        Decline
                                    </button>
                                @endif

                                @if($req->isAccepted())
                                    <form action="{{ route('mentorship.requests.complete', $req->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white transition-colors shadow-xs font-heading cursor-pointer">
                                            ★ Mark Completed
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-14 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-6">
                            <div class="text-3xl mb-2">📥</div>
                            <h4 class="text-sm font-bold text-slate-700 font-heading mb-1">No Incoming Mentorship Requests</h4>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-sans">
                                You have no incoming mentorship requests at the moment. Ensure your profile is set to "Available" in the Mentor Profile tab!
                            </p>
                            <button type="button" @click="activeTab = 'profile'; window.location.hash = 'profile'" class="px-4 py-2 bg-[#1e3a8a] text-white hover:bg-blue-900 font-bold text-xs rounded-xl transition-colors font-heading">
                                Update Availability &rarr;
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 4: MENTOR PROFILE SETTINGS                 -->
        <!-- ============================================== -->
        <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 p-6 sm:p-10 max-w-3xl mx-auto">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <span class="text-xs font-bold text-[#f97316] uppercase tracking-wider font-heading block mb-1">Alumni Volunteering Desk</span>
                    <h2 class="text-xl sm:text-2xl font-black font-heading text-[#0f172a] uppercase tracking-wide">
                        {{ $userListing ? 'Manage Your Mentorship Profile' : 'Apply to be an Alumni Mentor' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 font-sans">
                        Share your practical industry experience, interview tips, and higher education guidance with current K. D. Polytechnic Patan students.
                    </p>
                </div>

                <form method="POST" action="{{ route('mentorship.store') }}" class="space-y-5">
                    @csrf

                    <!-- Professional Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Professional Title / Current Role <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $userListing->title ?? '') }}" placeholder="e.g. Senior Software Engineer at TCS / Graduate Engineer at L&amp;T" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Areas of Expertise -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Areas of Expertise <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="expertise_areas" value="{{ old('expertise_areas', $userListing->expertise_areas ?? '') }}" placeholder="e.g. D2D GTU Transition, Python, AutoCAD, Microcontrollers, Interview Prep" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                        <span class="text-[10px] text-slate-400 mt-1 block">Separate multiple keywords with commas.</span>
                        @error('expertise_areas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description / Bio -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                            Mentorship Scope &amp; Bio <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" required placeholder="Describe how you can help students (e.g. project review, guidance on degree engineering admission, career transition) and preferred timings..." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-sans leading-relaxed">{{ old('description', $userListing->description ?? '') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Availability Toggle -->
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $userListing->is_available ?? true) ? 'checked' : '' }} class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                            <div>
                                <span class="text-xs font-bold text-[#0f172a] block font-heading">I am currently available to accept new mentees</span>
                                <span class="text-[11px] text-slate-500">Uncheck this if you are temporarily busy or on leave to hide your card from the directory.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-3 px-6 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md font-heading cursor-pointer">
                            {{ $userListing ? 'Update Mentor Profile' : 'Publish Mentorship Profile' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- ============================================== -->
    <!-- MODAL 1: REQUEST GUIDANCE                      -->
    <!-- ============================================== -->
    <div x-show="showRequestModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.outside="showRequestModal = false" class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-6 bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316] font-heading">KDP Mentorship Desk</span>
                    <h3 class="text-base font-bold font-heading uppercase tracking-wide">Request 1-on-1 Guidance</h3>
                    <p class="text-[11px] text-blue-200 mt-0.5">
                        Connecting with <strong class="text-white" x-text="selectedMentorName"></strong>
                    </p>
                </div>
                <button type="button" @click="showRequestModal = false" class="text-white/70 hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/' + selectedListingId + '/request'" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">Guidance Topic (Optional)</label>
                    <select name="topic" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 text-slate-700 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                        <option value="D2D Degree Transition Guidance">D2D Degree Transition Guidance</option>
                        <option value="Career &amp; Placement Preparation">Career &amp; Placement Preparation</option>
                        <option value="Engineering Project / CAD / Coding Help">Engineering Project / CAD / Coding Help</option>
                        <option value="GTU Exam Strategy &amp; Notes">GTU Exam Strategy &amp; Notes</option>
                        <option value="General Professional Mentorship">General Professional Mentorship</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">
                        Your Introduction &amp; Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" rows="4" required 
                        placeholder="Introduce yourself, mention your semester &amp; branch at K. D. Polytechnic, and what specific questions you have..." 
                        class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-sans leading-relaxed"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">This request will be sent to the mentor via email and logged into your Mentorship Tracker.</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showRequestModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer font-heading">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading uppercase tracking-wider">
                        Send Guidance Request &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 2: ACCEPT MENTORSHIP SESSION             -->
    <!-- ============================================== -->
    <div x-show="showAcceptModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div @click.outside="showAcceptModal = false" class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-6 bg-gradient-to-r from-emerald-700 to-[#0f172a] text-white flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-300 font-heading">Mentor Action</span>
                    <h3 class="text-base font-bold font-heading uppercase tracking-wide">Accept &amp; Schedule Session</h3>
                    <p class="text-[11px] text-emerald-100 mt-0.5">
                        Mentee: <strong class="text-white" x-text="acceptMenteeName"></strong>
                    </p>
                </div>
                <button type="button" @click="showAcceptModal = false" class="text-white/70 hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/requests/' + acceptRequestId + '/accept'" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">Scheduled Date &amp; Time (Optional)</label>
                    <input type="text" name="session_time" placeholder="e.g. Saturday, Nov 2nd at 5:00 PM" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-emerald-600 focus:border-emerald-600 shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">Meeting Link (Google Meet / Zoom / WhatsApp)</label>
                    <input type="url" name="meeting_link" placeholder="https://meet.google.com/xyz or https://wa.me/..." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-emerald-600 focus:border-emerald-600 shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">Notes / Instructions for Mentee</label>
                    <textarea name="mentor_notes" rows="3" placeholder="e.g. Please bring your resume or diploma marksheet to the session..." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-emerald-600 focus:border-emerald-600 shadow-xs font-sans leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showAcceptModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer font-heading">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-colors cursor-pointer font-heading uppercase tracking-wider">
                        Confirm &amp; Schedule &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 3: DECLINE MENTORSHIP REQUEST            -->
    <!-- ============================================== -->
    <div x-show="showDeclineModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div @click.outside="showDeclineModal = false" class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="p-6 bg-gradient-to-r from-slate-700 to-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold font-heading uppercase tracking-wide">Decline Mentorship Request</h3>
                    <p class="text-[11px] text-slate-300 mt-0.5">
                        Mentee: <strong class="text-white" x-text="declineMenteeName"></strong>
                    </p>
                </div>
                <button type="button" @click="showDeclineModal = false" class="text-white/70 hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/requests/' + declineRequestId + '/decline'" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 font-heading">Reason / Feedback Note (Optional)</label>
                    <textarea name="mentor_notes" rows="3" placeholder="e.g. Currently preparing for exams or project deadlines. Please reapply next semester." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-1 focus:ring-slate-600 focus:border-slate-600 shadow-xs font-sans leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showDeclineModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer font-heading">
                        Back
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-700 text-white shadow-xs transition-colors cursor-pointer font-heading uppercase tracking-wider">
                        Confirm Decline
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
