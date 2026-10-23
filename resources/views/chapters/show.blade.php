@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showMeetupModal: false }">

    <!-- Top Sub-Navigation / Breadcrumb -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
        <a href="{{ route('chapters.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all flex items-center gap-1.5">
            &larr; All Regional Chapters & Map
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Chapter Banner Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-10 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-blue-50/60 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full {{ $chapter->region_type === 'gujarat' ? 'bg-blue-50 text-[#1c3272] border border-blue-200' : ($chapter->region_type === 'global' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-orange-50 text-[#ea580c] border border-orange-200') }}">
                        {{ $chapter->region_type === 'gujarat' ? 'Gujarat Industrial Corridor' : ($chapter->region_type === 'global' ? 'Global Chapter' : 'National Tech Hub') }}
                    </span>
                    <span class="text-xs text-gray-500 font-bold flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $chapter->city }}, {{ $chapter->state }}, {{ $chapter->country }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a]">{{ $chapter->name }}</h1>
                <p class="text-gray-600 mt-3 text-sm leading-relaxed">
                    {{ $chapter->description }}
                </p>

                <!-- Chapter Details Row -->
                <div class="mt-6 flex flex-wrap items-center gap-4 text-xs text-gray-600">
                    <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <span class="font-bold text-[#1c3272]">👥 Members:</span>
                        <span>{{ $chapter->members->count() }} Alumni Connected</span>
                    </div>

                    @if($chapter->meeting_venue)
                        <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                            <span class="font-bold text-[#1c3272]">📍 Meeting Hub:</span>
                            <span>{{ $chapter->meeting_venue }}</span>
                        </div>
                    @endif

                    @if($chapter->contact_email)
                        <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                            <span class="font-bold text-[#1c3272]">✉️ Chapter Contact:</span>
                            <a href="mailto:{{ $chapter->contact_email }}" class="text-blue-600 hover:underline">{{ $chapter->contact_email }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0 w-full sm:w-auto">
                <form action="{{ route('chapters.join', $chapter->slug) }}" method="POST" class="w-full">
                    @csrf
                    @if($isMember)
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-red-50 hover:text-red-700 rounded-xl border border-emerald-200 transition-all shadow-sm" title="Click to leave chapter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Joined Member ✓
                        </button>
                    @else
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-xl shadow-sm transition-all">
                            <span>+</span> Join This Chapter
                        </button>
                    @endif
                </form>

                @if($chapter->whatsapp_group_link)
                    <a href="{{ $chapter->whatsapp_group_link }}" target="_blank" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl border border-emerald-200 transition-colors shadow-sm">
                        <span>💬</span> Join WhatsApp Hub
                    </a>
                @endif

                @auth
                    @if($isMember || auth()->user()->role === 'admin')
                        <button type="button" @click="showMeetupModal = true" 
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            <span>📅</span> Schedule a Meetup
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Chapter Activities & Meetups Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-serif font-bold text-gray-900">Upcoming Regional Meetups & Events</h3>
                <p class="text-xs text-gray-500">Local catchups, technical seminars, and informal dinners organized by this chapter.</p>
            </div>

            @auth
                @if($isMember || auth()->user()->role === 'admin')
                    <button type="button" @click="showMeetupModal = true" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-xl shadow-sm transition-all">
                        <span>+</span> Post Meetup
                    </button>
                @endif
            @endauth
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activities as $activity)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-[#f97316] uppercase tracking-wider mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $activity->event_date->format('l, M d, Y \a\t h:i A') }}
                        </div>

                        <h4 class="text-base font-bold text-gray-900 leading-snug">{{ $activity->title }}</h4>
                        
                        <div class="mt-2 text-xs text-gray-500 flex items-start gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $activity->venue }}</span>
                        </div>

                        @if($activity->description)
                            <p class="text-xs text-gray-600 mt-3 leading-relaxed">
                                {{ $activity->description }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-50 text-[11px] text-gray-400">
                        <span>Organized by: <strong class="text-gray-700">{{ $activity->user->name ?? 'Chapter Member' }}</strong></span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                    <div class="w-12 h-12 mx-auto mb-3 bg-blue-50 text-[#1c3272] rounded-full flex items-center justify-center font-bold">
                        📅
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-1">No Upcoming Meetups Scheduled</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">Be the first to schedule an informal coffee catchup or industrial networking session for this chapter.</p>
                    @auth
                        @if($isMember || auth()->user()->role === 'admin')
                            <button type="button" @click="showMeetupModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1c3272] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-[#152554] transition-colors">
                                Schedule Meetup
                            </button>
                        @endif
                    @endauth
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chapter Members Directory Section -->
    <div>
        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-serif font-bold text-gray-900">Chapter Members ({{ $chapter->members->count() }})</h3>
                <p class="text-xs text-gray-500">Alumni who are active members of this regional chapter.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($chapter->members as $member)
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all flex items-start gap-3.5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=1c3272&color=fff&bold=true" 
                         class="w-12 h-12 rounded-xl shadow-sm shrink-0">
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-bold text-gray-900 truncate">
                            <a href="{{ route('alumni.show', $member->id) }}" class="hover:text-[#1c3272] transition-colors">
                                {{ $member->name }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500 font-semibold truncate mt-0.5">
                            {{ $member->profile->department ?? $member->department ?? 'Diploma Engineering' }}
                        </p>
                        @if($member->profile && $member->profile->current_company)
                            <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                {{ $member->profile->current_company }}
                            </p>
                        @endif
                        <span class="inline-block mt-1 text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                            Class of {{ $member->profile->graduation_year ?? $member->graduation_year ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-xs text-gray-500">No members have joined this chapter yet. Be the first to join!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Schedule Meetup Modal -->
    @auth
        <div x-show="showMeetupModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showMeetupModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showMeetupModal = false" 
                     class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showMeetupModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-gray-100">
                    
                    <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316]">Chapter Meetup Planner</span>
                            <h3 class="text-xl font-serif font-bold text-gray-900 mt-0.5">Schedule Chapter Meetup</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Organize an in-person or virtual gathering for {{ $chapter->name }}.</p>
                        </div>
                        <button type="button" @click="showMeetupModal = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('chapters.activities.store', $chapter->slug) }}" method="POST" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Meetup Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required placeholder="e.g. Weekend Coffee & Industrial Networking" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Date & Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="event_date" required class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Venue / Location <span class="text-red-500">*</span></label>
                                <input type="text" name="venue" required placeholder="e.g. Vastrapur Lake / GIDC Hall" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Agenda & Notes</label>
                            <textarea name="description" rows="3" placeholder="Describe the purpose, discussion topics, or parking/meeting instructions..." class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-[#1c3272] focus:ring-[#1c3272]"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                            <button type="button" @click="showMeetupModal = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[#1c3272] hover:bg-[#152554] rounded-lg shadow-sm transition-colors">
                                Schedule & Announce &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

</div>
@endsection
