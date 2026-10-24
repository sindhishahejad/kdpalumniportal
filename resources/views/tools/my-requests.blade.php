@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center gap-3 text-emerald-800">
                <span class="text-2xl">🎉</span>
                <div class="font-medium text-sm sm:text-base">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-center gap-3 text-rose-800">
                <span class="text-2xl">⚠️</span>
                <div class="font-medium text-sm sm:text-base">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-heading font-bold uppercase tracking-widest text-[#f97316]">
                    કિલાચંદ દેવચંદ પોલિટેકનિક • Student Portal
                </span>
                <h1 class="text-2xl sm:text-3xl font-heading font-black text-slate-900 mt-0.5">
                    My Tool &amp; Book Requests ({{ $requests->total() }})
                </h1>
                <p class="text-xs text-slate-500 mt-1">Track the status of your equipment requests and coordinate pickup with senior alumni donors.</p>
            </div>
            <a href="{{ route('tools.index') }}" class="px-5 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-colors">
                Browse Equipment Catalog &rarr;
            </a>
        </div>

        <!-- Requests List -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            @if($requests->isEmpty())
                <div class="p-12 text-center text-slate-500 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-3xl">✋</div>
                    <h3 class="text-lg font-heading font-bold text-slate-800">No Active Claim Requests</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                        You have not requested any engineering tools or textbooks yet. Explore our student equipment bank to claim free tools!
                    </p>
                    <a href="{{ route('tools.index') }}" class="inline-block px-6 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-all">
                        Explore Tool Bank &rarr;
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($requests as $req)
                        @php $item = $req->donation; @endphp
                        <div class="p-6 sm:p-8 space-y-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('tools.show', $item->slug) }}" class="font-heading font-bold text-slate-900 text-base sm:text-lg hover:text-[#1e3a8a] transition-colors">
                                                {{ $item->title }}
                                            </a>

                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-heading font-bold uppercase {{ $req->status === 'accepted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($req->status === 'declined' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-900 border border-amber-200') }}">
                                                {{ $req->status === 'accepted' ? '✓ Accepted by Donor' : ($req->status === 'declined' ? 'Declined' : '⏳ Pending Review') }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-[#1e3a8a] font-semibold">
                                            Donated by <strong>{{ $item->donor->name }}</strong> (Class of {{ $item->donor->graduation_year ?? 'Alumnus' }})
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Your Request Reason: <span class="italic text-slate-700">"{{ $req->reason }}"</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs font-heading font-bold text-emerald-700 block">
                                        ₹{{ number_format($item->estimated_value) }} Value Saved
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        Requested {{ $req->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Pickup Venue if Accepted -->
                            @if($req->status === 'accepted')
                                <div class="bg-emerald-50 rounded-2xl p-4 sm:p-5 border border-emerald-200 text-xs space-y-2">
                                    <div class="font-heading font-bold text-emerald-950 flex items-center gap-2">
                                        <span>📍 Pickup Instructions:</span>
                                    </div>
                                    <p class="text-emerald-800 font-medium">
                                        {{ $item->pickup_location ?: 'Please coordinate with the donor via KDP Portal Inbox.' }}
                                    </p>
                                    @if($req->donor_response)
                                        <p class="text-emerald-900 italic font-medium">
                                            Donor Message: "{{ $req->donor_response }}"
                                        </p>
                                    @endif
                                    <div class="pt-1">
                                        <a href="{{ route('messages.inbox', $item->donor->id) }}" class="inline-flex items-center gap-1 font-heading font-bold text-emerald-900 hover:underline">
                                            💬 Message Donor &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Handover Complete & Gratitude Form -->
                            @if($item->isHandedOver() && $req->status === 'accepted')
                                @if(!$item->gratitude)
                                    <div class="bg-blue-50/70 p-4 sm:p-5 rounded-2xl border border-blue-200 text-xs space-y-3">
                                        <div class="font-heading font-bold text-[#0f2042]">
                                            🎉 Have you received this equipment? Say Thank You to {{ $item->donor->name }}!
                                        </div>
                                        <form action="{{ route('tools.gratitude', $item->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <textarea name="message" rows="2" required minlength="20" placeholder="Say thank you and describe how this book/tool will help your studies..." class="w-full rounded-xl border-blue-200 text-xs focus:ring-[#1e3a8a] focus:border-[#1e3a8a]"></textarea>
                                            <button type="submit" class="px-5 py-2 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-heading font-bold text-xs uppercase tracking-wider shadow-sm transition-all">
                                                Post Thank-You to Gratitude Wall
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($requests->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $requests->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
