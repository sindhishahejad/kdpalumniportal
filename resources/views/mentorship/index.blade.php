@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" 
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

    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">1-on-1 Alumni Mentorship Desk</span>
        </nav>

        <div class="flex items-center gap-2">
            <button @click="activeTab = 'profile'; window.location.hash = 'profile'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 font-heading border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-2xs">
                <span>{{ $userListing ? '✏️ Manage Mentor Profile' : '🤝 Become a Mentor' }}</span>
            </button>
        </div>
    </div>

    <!-- Institutional Hero Banner -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] rounded-2xl text-white p-6 sm:p-10 shadow-lg relative overflow-hidden mb-8 border-b-4 border-[#f97316]">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 mb-3 font-heading">
                KD Polytechnic Patan • Alumni Mentorship Program &amp; Career Desk
            </span>
            <h1 class="text-2xl sm:text-4xl font-black font-heading tracking-tight leading-tight">
                1-on-1 Alumni Mentorship &amp; Career Guidance
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Get paired directly with alumni working in top engineering firms, research institutes, and public sector undertakings for D2D guidance, technical mock interviews, and career counseling.
            </p>
        </div>

        <!-- Crest Watermark -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs sm:text-sm rounded-r-xl shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>⚠️</span>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Main Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-4 mb-8 overflow-x-auto scrollbar-none">
        <!-- Tab 1: Directory -->
        <button type="button" 
                @click="activeTab = 'directory'; window.location.hash = 'directory'" 
                :class="activeTab === 'directory' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0">
            <span>🔍 Find a Mentor</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'directory' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">
                {{ $mentors->total() }}
            </span>
        </button>

        <!-- Tab 2: My Sent Requests (Student / Mentee) -->
        <button type="button" 
                @click="activeTab = 'my_sessions'; window.location.hash = 'my-sessions'" 
                :class="activeTab === 'my_sessions' || activeTab === 'my-sessions' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0">
            <span>🎓 My Mentorship Requests</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'my_sessions' || activeTab === 'my-sessions' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">
                {{ $mySentRequests->count() }}
            </span>
        </button>

        <!-- Tab 3: Incoming Requests (Mentor Hub) -->
        <button type="button" 
                @click="activeTab = 'incoming'; window.location.hash = 'mentor-requests'" 
                :class="activeTab === 'incoming' || activeTab === 'mentor-requests' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer font-heading shrink-0 relative">
            <span>📥 Requests Received (Mentor Hub)</span>
            @if($pendingReceivedCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500 text-white animate-pulse">
                    {{ $pendingReceivedCount }} New
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
                :class="activeTab === 'profile' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
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
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-8">
            <form method="GET" action="{{ route('mentorship.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <div class="sm:col-span-5">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Search Mentors</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, or expertise (e.g. Python, D2D)..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Engineering Branch</label>
                    <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                        <option value="">All Engineering Departments</option>
                        <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                        <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                        <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                        <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                        <option value="General" {{ request('department') === 'General' ? 'selected' : '' }}>🔬 General Department</option>
                    </select>
                </div>

                <div class="sm:col-span-3 flex items-center gap-2">
                    <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                        Filter Mentors
                    </button>
                    @if(request()->anyFilled(['search', 'department']))
                        <a href="{{ route('mentorship.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold" title="Reset Filters">✕</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Mentors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($mentors as $listing)
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Header Banner -->
                        <div class="h-20 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] relative p-3 flex justify-between items-start">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-500/90 text-white backdrop-blur-xs font-heading shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Available Mentor
                            </span>
                            
                            @if(optional($listing->user->profile)->department)
                                <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider font-heading">
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

                                <a href="{{ route('alumni.show', $listing->user_id) }}" class="text-[11px] font-bold text-[#1e3a8a] hover:underline flex items-center gap-1 font-heading">
                                    <span>View Profile</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>

                            <h3 class="font-heading font-bold text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors line-clamp-1 leading-snug">
                                {{ $listing->user->name }}
                            </h3>

                            <p class="text-xs font-bold text-[#f97316] mb-1 line-clamp-1 font-heading">
                                {{ $listing->title }}
                            </p>

                            <p class="text-[11px] text-slate-500 mb-3 flex items-center gap-1">
                                <span>🎓 Class of {{ optional($listing->user->profile)->batch_year ?? $listing->user->graduation_year ?? 'Alumni' }}</span>
                                @if(optional($listing->user->profile)->current_company)
                                    <span>•</span>
                                    <span class="truncate">🏢 {{ optional($listing->user->profile)->current_company }}</span>
                                @endif
                            </p>

                            <!-- Expertise Tags -->
                            <div class="mb-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Expertise Focus:</span>
                                <div class="flex flex-wrap gap-1.5 max-h-14 overflow-hidden">
                                    @foreach(explode(',', $listing->expertise_areas) as $tag)
                                        <span class="bg-blue-50/70 border border-blue-200/80 text-[#1e3a8a] text-[10px] font-bold px-2 py-0.5 rounded-lg truncate">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bio Description -->
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-2 font-sans">
                                {{ $listing->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
                        @if($listing->user_id === auth()->id())
                            <button type="button" @click="activeTab = 'profile'; window.location.hash = 'profile'" class="w-full text-center bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold py-2 rounded-xl hover:bg-amber-100 transition-colors">
                                Manage Your Listing
                            </button>
                        @else
                            <button type="button" 
                                    @click="showRequestModal = true; selectedListingId = {{ $listing->id }}; selectedMentorName = '{{ addslashes($listing->user->name) }}'; selectedMentorTitle = '{{ addslashes($listing->title) }}'" 
                                    class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-2xs hover:shadow transition-all font-heading flex items-center justify-center gap-2 cursor-pointer">
                                <span>🤝 Request 1-on-1 Guidance</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                    <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                        🤝
                    </div>
                    <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Mentors Found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-sans">
                        Try clearing your search filters or be the first alumnus to register as a mentor.
                    </p>
                    <button type="button" @click="activeTab = 'profile'; window.location.hash = 'profile'" class="text-xs font-bold text-[#1e3a8a] hover:underline">
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
    <!-- TAB 2: MY MENTORSHIP REQUESTS (STUDENT / MENTEE)-->
    <!-- ============================================== -->
    <div x-show="activeTab === 'my_sessions' || activeTab === 'my-sessions'" x-transition:enter="transition ease-out duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 mb-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold font-heading text-[#0f172a]">My Guidance Requests &amp; Scheduled Sessions</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Track the status of mentorship guidance requests you have sent to alumni.</p>
                </div>
                <button type="button" @click="activeTab = 'directory'; window.location.hash = 'directory'" class="px-4 py-2 bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 font-bold text-xs rounded-xl transition-colors font-heading">
                    + Find More Mentors
                </button>
            </div>

            <div class="space-y-4">
                @forelse($mySentRequests as $request)
                    <div class="p-5 rounded-2xl border border-slate-200 hover:border-slate-300 transition-all bg-white shadow-2xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $request->isAccepted() ? 'border-l-4 border-l-emerald-500' : ($request->isPending() ? 'border-l-4 border-l-[#f97316]' : 'border-l-4 border-l-slate-300') }}">
                        <div class="flex-1 min-w-0">
                            <!-- Status & Metadata Badge -->
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                @if($request->isPending())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                        Pending Mentor Review
                                    </span>
                                @elseif($request->isAccepted())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span>✓</span> Session Confirmed &amp; Scheduled
                                    </span>
                                @elseif($request->isCompleted())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                        <span>★</span> Mentorship Completed
                                    </span>
                                @elseif($request->isDeclined())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
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

                            <p class="text-xs text-slate-600 mb-3 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">
                                "{{ $request->message }}"
                            </p>

                            <!-- Accepted Session Info Box -->
                            @if($request->isAccepted())
                                <div class="p-3 bg-emerald-50/70 rounded-xl border border-emerald-200 text-xs text-emerald-950 space-y-1.5 mb-2">
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
                        <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                            @if($request->isAccepted() && $request->meeting_link)
                                <a href="{{ $request->meeting_link }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-2xs flex items-center gap-1.5 font-heading">
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
                                    <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Mark session as completed">
                                        ✓ Complete
                                    </button>
                                </form>
                            @endif

                            @if($request->isPending())
                                <form action="{{ route('mentorship.requests.cancel', $request->id) }}" method="POST" onsubmit="return confirm('Cancel this mentorship request?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 italic text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        You have not requested guidance from any alumni mentors yet. Explore the directory above to connect!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB 3: INCOMING REQUESTS (MENTOR HUB)          -->
    <!-- ============================================== -->
    <div x-show="activeTab === 'incoming' || activeTab === 'mentor-requests'" x-transition:enter="transition ease-out duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 mb-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold font-heading text-[#0f172a]">Mentees Awaiting Your Guidance</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Manage students and junior alumni seeking your mentorship.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                    {{ $myReceivedRequests->count() }} Total Requests
                </span>
            </div>

            <div class="space-y-4">
                @forelse($myReceivedRequests as $req)
                    <div class="p-5 rounded-2xl border border-slate-200 hover:border-slate-300 transition-all bg-white shadow-2xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $req->isPending() ? 'border-l-4 border-l-[#f97316]' : ($req->isAccepted() ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-slate-300') }}">
                        <div class="flex-1 min-w-0">
                            <!-- Status Indicator -->
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                @if($req->isPending())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                        Action Required • Pending Review
                                    </span>
                                @elseif($req->isAccepted())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-heading">
                                        <span>✓</span> Accepted &amp; Scheduled
                                    </span>
                                @elseif($req->isCompleted())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-blue-50 text-[#1e3a8a] border border-blue-200 font-heading">
                                        <span>★</span> Completed Session
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 font-heading">
                                        Declined
                                    </span>
                                @endif

                                <span class="text-xs text-slate-400 font-mono">
                                    {{ $req->created_at->format('M d, Y • h:i A') }}
                                </span>
                            </div>

                            <!-- Student Info -->
                            <h3 class="font-heading font-bold text-base text-[#0f172a] mb-1">
                                Student: <a href="{{ route('alumni.show', $req->mentee_id) }}" class="text-[#1e3a8a] hover:underline">{{ $req->mentee->name }}</a>
                                <span class="text-xs text-slate-500 font-normal font-sans">
                                    ({{ optional($req->mentee->profile)->department ?: 'Student' }} • Class of {{ optional($req->mentee->profile)->batch_year ?: ($req->mentee->graduation_year ?: 'Current') }})
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
                                <div class="p-3 bg-emerald-50/70 rounded-xl border border-emerald-200 text-xs text-emerald-950 space-y-1 mb-2">
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
                            <a href="{{ route('messages.inbox', $req->mentee_id) }}" class="px-3 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors font-heading">
                                💬 Direct Chat
                            </a>

                            @if($req->isPending())
                                <button type="button" 
                                        @click="showAcceptModal = true; acceptRequestId = {{ $req->id }}; acceptMenteeName = '{{ addslashes($req->mentee->name) }}'" 
                                        class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-2xs transition-colors font-heading cursor-pointer">
                                    ✓ Accept &amp; Schedule
                                </button>

                                <button type="button" 
                                        @click="showDeclineModal = true; declineRequestId = {{ $req->id }}; declineMenteeName = '{{ addslashes($req->mentee->name) }}'" 
                                        class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                                    Decline
                                </button>
                            @endif

                            @if($req->isAccepted())
                                <form action="{{ route('mentorship.requests.complete', $req->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white transition-colors shadow-2xs font-heading">
                                        ★ Mark Completed
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 italic text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        You have no incoming mentorship requests at the moment. Ensure your profile is set to "Available" in the Mentor Profile tab!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TAB 4: MENTOR PROFILE SETTINGS                 -->
    <!-- ============================================== -->
    <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 sm:p-10 max-w-3xl mx-auto">
            <div class="mb-6 pb-4 border-b border-slate-100">
                <span class="text-xs font-bold text-[#f97316] uppercase tracking-wider font-heading block mb-1">Alumni Volunteering</span>
                <h2 class="text-xl font-bold font-heading text-[#0f172a]">
                    {{ $userListing ? 'Manage Your Mentorship Profile' : 'Apply to be an Alumni Mentor' }}
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-sans">
                    Share your practical industry experience, interview tips, and higher education guidance with current KD Polytechnic Patan students.
                </p>
            </div>

            <form method="POST" action="{{ route('mentorship.store') }}" class="space-y-5">
                @csrf

                <!-- Professional Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Professional Title / Current Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $userListing->title ?? '') }}" placeholder="e.g. Senior Software Engineer at TCS / Graduate Engineer at L&amp;T" required class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Areas of Expertise -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Areas of Expertise <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="expertise_areas" value="{{ old('expertise_areas', $userListing->expertise_areas ?? '') }}" placeholder="e.g. D2D GTU Transition, Python, AutoCAD, Microcontrollers, Interview Prep" required class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    <span class="text-[10px] text-slate-400 mt-1 block">Separate multiple keywords with commas.</span>
                    @error('expertise_areas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Description / Bio -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Mentorship Scope &amp; Bio <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="4" required placeholder="Describe how you can help students (e.g. project review, guidance on degree engineering admission, career transition) and preferred timings..." class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans">{{ old('description', $userListing->description ?? '') }}</textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Availability Toggle -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $userListing->is_available ?? true) ? 'checked' : '' }} class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                        <div>
                            <span class="text-xs font-bold text-[#0f172a] block">I am currently available to accept new mentees</span>
                            <span class="text-[11px] text-slate-500">Uncheck this if you are temporarily busy or on leave to hide your card from the directory.</span>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                        {{ $userListing ? 'Update Mentor Profile' : 'Publish Mentorship Profile' }}
                    </button>
                </div>
            </form>
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
        
        <div @click.outside="showRequestModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316] font-heading">KDP Mentorship Desk</span>
                    <h3 class="text-base font-bold font-heading">Request 1-on-1 Guidance</h3>
                    <p class="text-[11px] text-blue-200 mt-0.5">
                        Connecting with <strong class="text-white" x-text="selectedMentorName"></strong>
                    </p>
                </div>
                <button type="button" @click="showRequestModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/' + selectedListingId + '/request'" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Guidance Topic (Optional)</label>
                    <select name="topic" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        <option value="D2D Degree Transition Guidance">D2D Degree Transition Guidance</option>
                        <option value="Career &amp; Placement Preparation">Career &amp; Placement Preparation</option>
                        <option value="Engineering Project / CAD / Coding Help">Engineering Project / CAD / Coding Help</option>
                        <option value="GTU Exam Strategy &amp; Notes">GTU Exam Strategy &amp; Notes</option>
                        <option value="General Professional Mentorship">General Professional Mentorship</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Your Introduction &amp; Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" rows="4" required 
                        placeholder="Introduce yourself, mention your semester &amp; branch at K. D. Polytechnic, and what specific questions you have..." 
                        class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">This request will be sent to the mentor via email and logged into your Mentorship Tracker.</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showRequestModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
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
        
        <div @click.outside="showAcceptModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-emerald-700 to-[#0f172a] text-white flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-300 font-heading">Mentor Action</span>
                    <h3 class="text-base font-bold font-heading">Accept &amp; Schedule Session</h3>
                    <p class="text-[11px] text-emerald-100 mt-0.5">
                        Mentees: <strong class="text-white" x-text="acceptMenteeName"></strong>
                    </p>
                </div>
                <button type="button" @click="showAcceptModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/requests/' + acceptRequestId + '/accept'" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Scheduled Date &amp; Time (Optional)</label>
                    <input type="text" name="session_time" placeholder="e.g. Saturday, Nov 2nd at 5:00 PM" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-emerald-600 focus:border-emerald-600 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Link (Google Meet / Zoom / WhatsApp)</label>
                    <input type="url" name="meeting_link" placeholder="https://meet.google.com/xyz or https://wa.me/..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-emerald-600 focus:border-emerald-600 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Notes / Instructions for Mentee</label>
                    <textarea name="mentor_notes" rows="3" placeholder="e.g. Please bring your resume or diploma marksheet to the session..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-emerald-600 focus:border-emerald-600 shadow-2xs"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showAcceptModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-colors cursor-pointer font-heading">
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
        
        <div @click.outside="showDeclineModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-slate-700 to-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold font-heading">Decline Mentorship Request</h3>
                    <p class="text-[11px] text-slate-300 mt-0.5">
                        Mentee: <strong class="text-white" x-text="declineMenteeName"></strong>
                    </p>
                </div>
                <button type="button" @click="showDeclineModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/mentorship/requests/' + declineRequestId + '/decline'" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reason / Feedback Note (Optional)</label>
                    <textarea name="mentor_notes" rows="3" placeholder="e.g. Currently preparing for exams or project deadlines. Please reapply next semester." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-slate-600 focus:border-slate-600 shadow-2xs"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showDeclineModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Back
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-700 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Confirm Decline
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
