@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ 
    activeTab: 'roster',
    rsvpStatus: '{{ $myRsvp->status ?? 'attending' }}',
    showRsvpModal: false
}">

    <!-- Top Sub-Navigation / Breadcrumb -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
        <a href="{{ route('reunions.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all flex items-center gap-1.5">
            &larr; All Batch Reunions
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

    <!-- Reunion Hero Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-10 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-orange-50/50 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full bg-orange-50 text-[#ea580c] border border-orange-200">
                        {{ $reunion->milestone_tag ?: 'Batch Reunion' }}
                    </span>
                    <span class="text-xs text-gray-500 font-bold">
                        🎓 Class of {{ $reunion->batch_year }} &bull; {{ $reunion->department }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#0f172a]">{{ $reunion->title }}</h1>
                <p class="text-gray-600 mt-3 text-sm leading-relaxed">
                    {{ $reunion->description }}
                </p>

                <!-- Logistics Row -->
                <div class="mt-6 flex flex-wrap items-center gap-4 text-xs text-gray-700">
                    <div class="flex items-center gap-1.5 bg-blue-50/70 px-3 py-1.5 rounded-xl border border-blue-100 font-bold text-[#1c3272]">
                        <svg class="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $reunion->event_date->format('l, M d, Y \a\t h:i A') }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $reunion->venue }}, {{ $reunion->city }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <span class="font-bold text-gray-700">Contribution:</span>
                        <span class="font-bold text-emerald-700">{{ $reunion->fee_per_person > 0 ? '₹' . number_format($reunion->fee_per_person) . ' / person' : 'Free Entry' }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <span class="font-bold text-gray-700">Lead:</span>
                        <span>{{ $reunion->organizer->name ?? 'Class Coordinator' }}</span>
                    </div>
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0 w-full sm:w-auto">
                @if($myRsvp)
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Your Current Status</span>
                        <span class="text-xs font-black text-emerald-900 mt-0.5 block">{{ $myRsvp->status_label }}</span>
                        @if($myRsvp->guests_count > 0)
                            <span class="text-[11px] text-emerald-700 block mt-0.5">(+{{ $myRsvp->guests_count }} Guests)</span>
                        @endif
                        @if($reunion->fee_per_person > 0)
                            <div class="mt-2 pt-2 border-t border-emerald-200">
                                @if($myRsvp->isPaid())
                                    <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full inline-block">
                                        ✓ Fee Paid: ₹{{ number_format($myRsvp->amount_paid) }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full inline-block">
                                        ⚠️ Fee Pending: ₹{{ number_format($myRsvp->totalFee()) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                @if($reunion->whatsapp_group_link)
                    <a href="{{ $reunion->whatsapp_group_link }}" target="_blank" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl border border-emerald-200 transition-colors shadow-sm">
                        <span>💬</span> Join Class WhatsApp Group
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Interactive RSVP Banner / Form -->
    @auth
        <div class="bg-gradient-to-r from-[#1c3272] to-[#294c9b] rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-12">
            <div class="max-w-3xl">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#f97316] block mb-1">Attendee Confirmation</span>
                <h3 class="text-2xl font-serif font-bold text-white mb-2">
                    {{ $myRsvp ? 'Update Your Reunion RSVP' : 'Are you joining your batchmates at this reunion?' }}
                </h3>
                <p class="text-xs text-blue-100 leading-relaxed mb-6">
                    Let the organizing committee know if you are coming so we can arrange seating, dinner banquets, and customized batch momentos.
                </p>

                <form action="{{ route('reunions.rsvp', $reunion->slug) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Status Selector Chips -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="attending" x-model="rsvpStatus" class="sr-only">
                            <div :class="rsvpStatus === 'attending' ? 'bg-white text-[#1c3272] shadow-lg ring-2 ring-white' : 'bg-white/10 text-white hover:bg-white/20'" 
                                 class="p-3.5 rounded-xl border border-white/20 text-center transition-all">
                                <span class="block text-sm font-black">🎉 Attending</span>
                                <span class="text-[11px] opacity-80 block mt-0.5">Count me in for sure!</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="maybe" x-model="rsvpStatus" class="sr-only">
                            <div :class="rsvpStatus === 'maybe' ? 'bg-white text-[#1c3272] shadow-lg ring-2 ring-white' : 'bg-white/10 text-white hover:bg-white/20'" 
                                 class="p-3.5 rounded-xl border border-white/20 text-center transition-all">
                                <span class="block text-sm font-black">🤔 Interested</span>
                                <span class="text-[11px] opacity-80 block mt-0.5">Tentative / Depending on schedule</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="declined" x-model="rsvpStatus" class="sr-only">
                            <div :class="rsvpStatus === 'declined' ? 'bg-white text-[#1c3272] shadow-lg ring-2 ring-white' : 'bg-white/10 text-white hover:bg-white/20'" 
                                 class="p-3.5 rounded-xl border border-white/20 text-center transition-all">
                                <span class="block text-sm font-black">😢 Cannot Make It</span>
                                <span class="text-[11px] opacity-80 block mt-0.5">Wishing everyone a great time</span>
                            </div>
                        </label>
                    </div>

                    <!-- Additional Details for Attending/Maybe -->
                    <div x-show="rsvpStatus !== 'declined'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-100 mb-1">Bringing Family / Guests (+1, +2)</label>
                            <select name="guests_count" class="w-full text-xs font-medium text-gray-900 border-0 rounded-xl focus:ring-2 focus:ring-[#f97316]">
                                <option value="0" {{ ($myRsvp->guests_count ?? 0) == 0 ? 'selected' : '' }}>Just me (0 extra guests)</option>
                                <option value="1" {{ ($myRsvp->guests_count ?? 0) == 1 ? 'selected' : '' }}>+1 Guest (Spouse / Friend)</option>
                                <option value="2" {{ ($myRsvp->guests_count ?? 0) == 2 ? 'selected' : '' }}>+2 Guests (Family)</option>
                                <option value="3" {{ ($myRsvp->guests_count ?? 0) == 3 ? 'selected' : '' }}>+3 Guests</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-100 mb-1">Carpool / Travel Note (Optional)</label>
                            <input type="text" name="carpool_note" value="{{ $myRsvp->carpool_note ?? '' }}" placeholder="e.g. Driving from Ahmedabad via SG Highway, 3 seats open" 
                                   class="w-full text-xs text-gray-900 border-0 rounded-xl focus:ring-2 focus:ring-[#f97316]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-blue-100 mb-1">Nostalgic Message / Shoutout to Classmates</label>
                        <input type="text" name="nostalgic_quote" value="{{ $myRsvp->nostalgic_quote ?? '' }}" placeholder="e.g. Can't wait to catch up after all these years and see the old workshop!" 
                               class="w-full text-xs text-gray-900 border-0 rounded-xl focus:ring-2 focus:ring-[#f97316]">
                    </div>

                    @if($reunion->fee_per_person > 0)
                        <div x-show="rsvpStatus === 'attending'" 
                             class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-amber-300 block font-heading">Reunion Registration Fee</span>
                                    <span class="text-xs text-blue-100 font-sans">
                                        ₹{{ number_format($reunion->fee_per_person) }} per person
                                    </span>
                                </div>
                                <span class="text-xs text-blue-200 font-mono">
                                    Secure Razorpay Checkout
                                </span>
                            </div>

                            @if($myRsvp && $myRsvp->isPaid())
                                <div class="bg-emerald-500/20 border border-emerald-400/30 rounded-xl p-2.5 text-center text-xs font-bold text-emerald-200">
                                    ✓ Registration fee already paid (₹{{ number_format($myRsvp->amount_paid) }})
                                </div>
                            @else
                                <button type="button" 
                                        @click="openRazorpayPayment({
                                            amount: {{ $reunion->fee_per_person }} * (1 + Number($el.closest('form').querySelector('select[name=guests_count]')?.value || 0)),
                                            purpose: 'reunion_fee',
                                            campaign: 'Batch Reunion Fee: {{ addslashes($reunion->title) }}',
                                            payable_type: 'App\\Models\\BatchReunion',
                                            payable_id: {{ $reunion->id }},
                                            guests_count: Number($el.closest('form').querySelector('select[name=guests_count]')?.value || 0)
                                        })"
                                        class="w-full bg-[#f97316] hover:bg-[#ea580c] text-white text-xs font-heading font-black uppercase tracking-wider py-3 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <span>💳</span>
                                    <span>Pay Reunion Fee with Razorpay</span>
                                </button>
                            @endif
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold uppercase tracking-wider py-2.5 px-6 rounded-xl border border-white/30 transition-colors">
                            {{ $myRsvp ? 'Save RSVP Details &rarr;' : 'Save RSVP (Pay on Arrival) &rarr;' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <!-- Tabbed Content: Attendee Roster vs. Memory Wall -->
    <div>
        <div class="flex items-center gap-3 border-b border-gray-200 mb-8">
            <button type="button" 
                    @click="activeTab = 'roster'"
                    :class="activeTab === 'roster' ? 'border-[#1c3272] text-[#1c3272] font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="py-3 px-4 border-b-2 text-sm transition-colors flex items-center gap-2">
                <span>👥</span>
                <span>Attendee Roster ({{ $confirmedAttendeesCount }} Confirmed, {{ $totalHeadcount }} Total Headcount)</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'memories'"
                    :class="activeTab === 'memories' ? 'border-[#1c3272] text-[#1c3272] font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="py-3 px-4 border-b-2 text-sm transition-colors flex items-center gap-2">
                <span>📸</span>
                <span>Nostalgic Memory Wall ({{ $reunion->memories->count() }})</span>
            </button>
        </div>

        <!-- TAB 1: Attendee Roster -->
        <div x-show="activeTab === 'roster'" class="space-y-6">
            
            <!-- Confirmed Attending Grid -->
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-4 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Confirmed Attending Classmates ({{ $attendingList->count() }})
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($attendingList as $rsvp)
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start gap-3.5 mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rsvp->user->name) }}&background=1c3272&color=fff&bold=true" 
                                         class="w-11 h-11 rounded-xl shadow-sm shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-sm font-bold text-gray-900 truncate">
                                            <a href="{{ route('alumni.show', $rsvp->user->id) }}" class="hover:text-[#1c3272] transition-colors">
                                                {{ $rsvp->user->name }}
                                            </a>
                                        </h5>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">
                                            {{ $rsvp->user->profile->department ?? $rsvp->user->department ?? 'Diploma Engineering' }}
                                        </p>
                                        @if($rsvp->user->profile && $rsvp->user->profile->current_company)
                                            <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                                {{ $rsvp->user->profile->current_company }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($rsvp->guests_count > 0)
                                        <span class="text-[10px] font-bold bg-blue-50 text-[#1c3272] px-2 py-0.5 rounded-full border border-blue-100 shrink-0">
                                            +{{ $rsvp->guests_count }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Carpool / Travel Note -->
                                @if($rsvp->carpool_note)
                                    <div class="p-2.5 bg-blue-50/60 rounded-xl border border-blue-100 text-[11px] text-blue-900 flex items-start gap-2 mb-2">
                                        <span class="shrink-0">🚗</span>
                                        <span class="leading-relaxed font-medium">{{ $rsvp->carpool_note }}</span>
                                    </div>
                                @endif

                                <!-- Nostalgic Message -->
                                @if($rsvp->nostalgic_quote)
                                    <div class="p-2.5 bg-gray-50 rounded-xl border border-gray-100 text-[11px] text-gray-600 italic">
                                        "{{ $rsvp->nostalgic_quote }}"
                                    </div>
                                @endif
                            </div>

                            <span class="text-[10px] text-gray-400 block mt-3 pt-2 border-t border-gray-50">
                                RSVP confirmed {{ $rsvp->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                            <p class="text-xs text-gray-500">No attendees have confirmed yet. Be the first to RSVP!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tentative / Maybe List -->
            @if($maybeList->count() > 0)
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-amber-700 mb-4 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Interested / Tentative ({{ $maybeList->count() }})
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($maybeList as $rsvp)
                            <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($rsvp->user->name) }}&background=f3f4f6&color=1c3272&bold=true" 
                                     class="w-8 h-8 rounded-lg shrink-0">
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-gray-800 block truncate">{{ $rsvp->user->name }}</span>
                                    <span class="text-[10px] text-gray-400 block truncate">{{ $rsvp->user->profile->department ?? 'Diploma' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- TAB 2: Nostalgic Memory Wall -->
        <div x-show="activeTab === 'memories'" class="space-y-8" style="display: none;">
            
            <!-- Post Throwback Memory Card -->
            @auth
                <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
                    <div class="mb-4">
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316]">Nostalgia Feed</span>
                        <h4 class="text-xl font-serif font-bold text-gray-900 mt-0.5">Share a Campus Throwback Memory</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Post old campus photos, workshop memories, hostel stories, or canteen moments with your classmates.</p>
                    </div>

                    <form action="{{ route('reunions.memories.store', $reunion->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <textarea name="caption" rows="3" required placeholder="Write a nostalgic caption or memory... e.g. 'Remember our semester 4 surveying camp at Sidhpur? Who still has the group photo?'" 
                                      class="w-full text-sm border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Upload Throwback Photo (Optional)</label>
                                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-[#ea580c] hover:file:bg-orange-100 border border-gray-200 rounded-lg cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Year Photo was Taken (e.g. 2017)</label>
                                <input type="text" name="year_taken" placeholder="e.g. 2017" class="w-full text-sm border-gray-200 rounded-xl focus:border-[#1c3272] focus:ring-[#1c3272]">
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#1c3272] hover:bg-[#152554] text-white text-xs font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors">
                                Post to Memory Wall &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            @endauth

            <!-- Memories Gallery Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($reunion->memories as $memory)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            @if($memory->photo_path)
                                <div class="relative h-56 bg-gray-100 overflow-hidden">
                                    <img src="{{ asset('storage/' . $memory->photo_path) }}" class="w-full h-full object-cover">
                                    @if($memory->year_taken)
                                        <span class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                            Circa {{ $memory->year_taken }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($memory->user->name) }}&background=f3f4f6&color=1c3272&bold=true" 
                                         class="w-9 h-9 rounded-xl shadow-sm shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-xs font-bold text-gray-900 truncate">{{ $memory->user->name }}</h5>
                                        <span class="text-[10px] text-gray-400 block">{{ $memory->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(!$memory->photo_path && $memory->year_taken)
                                        <span class="text-[10px] font-bold bg-orange-50 text-[#ea580c] px-2 py-0.5 rounded">
                                            Circa {{ $memory->year_taken }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-gray-700 leading-relaxed italic">
                                    "{{ $memory->caption }}"
                                </p>
                            </div>
                        </div>

                        <!-- Like Reaction Footer -->
                        <div class="p-4 bg-gray-50/70 border-t border-gray-100 flex items-center justify-between text-xs">
                            <form action="{{ route('reunions.memories.like', [$reunion->slug, $memory->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 text-gray-600 hover:text-red-500 font-bold transition-colors">
                                    <span class="text-red-500">❤️</span>
                                    <span>{{ $memory->likes_count }} {{ Str::plural('Like', $memory->likes_count) }}</span>
                                </button>
                            </form>
                            <span class="text-[10px] text-gray-400">Batch Memory</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 mx-auto mb-4 bg-orange-50 text-[#f97316] rounded-full flex items-center justify-center font-bold text-2xl">
                            📸
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">No Throwback Memories Posted Yet</h4>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto">Be the first to post a throwback photo from college days on this reunion wall!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>
@endsection
