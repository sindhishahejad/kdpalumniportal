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
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Student Portal</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-0.5">
                    My Tool &amp; Book Requests ({{ $requests->total() }})
                </h1>
                <p class="text-xs text-slate-500 mt-1">Track the status of your equipment requests and coordinate pickup with senior alumni.</p>
            </div>
            <a href="{{ route('tools.index') }}" class="px-5 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-colors">
                Browse Equipment Catalog &rarr;
            </a>
        </div>

        <!-- Requests List -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            @if($requests->isEmpty())
                <div class="p-12 text-center text-slate-500 space-y-3">
                    <span class="text-5xl">✋</span>
                    <h3 class="text-lg font-bold text-slate-800">No Active Claim Requests</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        You have not requested any engineering tools or textbooks yet. Explore our student equipment bank to claim free tools!
                    </p>
                    <a href="{{ route('tools.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider">
                        Explore Tool Bank &rarr;
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($requests as $req)
                        @php $item = $req->donation; @endphp
                        <div class="p-6 space-y-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('tools.show', $item->slug) }}" class="font-bold text-slate-900 text-base sm:text-lg hover:text-indigo-600 transition-colors">
                                                {{ $item->title }}
                                            </a>

                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $req->status === 'accepted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($req->status === 'declined' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-900 border border-amber-200') }}">
                                                {{ $req->status === 'accepted' ? '✓ Accepted by Donor' : ($req->status === 'declined' ? 'Declined' : '⏳ Pending Review') }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-indigo-700 font-semibold">
                                            Donated by <strong>{{ $item->donor->name }}</strong> (Class of {{ $item->donor->graduation_year ?? 'Alumnus' }})
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Your Request Reason: <span class="italic text-slate-700">"{{ $req->reason }}"</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs font-bold text-emerald-700 block">
                                        ₹{{ number_format($item->estimated_value) }} Value Saved
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        Requested {{ $req->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Pickup Venue if Accepted -->
                            @if($req->status === 'accepted')
                                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200 text-xs space-y-2">
                                    <div class="font-bold text-emerald-950 flex items-center gap-2">
                                        <span>📍 Pickup Instructions:</span>
                                    </div>
                                    <p class="text-emerald-800">
                                        {{ $item->pickup_location ?: 'Please coordinate with the donor via KDP Portal Inbox.' }}
                                    </p>
                                    @if($req->donor_response)
                                        <p class="text-emerald-900 italic font-medium">
                                            Donor Message: "{{ $req->donor_response }}"
                                        </p>
                                    @endif
                                    <div class="pt-1">
                                        <a href="{{ route('messages.inbox', $item->donor->id) }}" class="inline-flex items-center gap-1 font-bold text-emerald-900 hover:underline">
                                            💬 Message Donor &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Handover Complete & Gratitude Form -->
                            @if($item->isHandedOver() && $req->status === 'accepted')
                                @if(!$item->gratitude)
                                    <div class="bg-indigo-50/70 p-4 rounded-2xl border border-indigo-200 text-xs space-y-3">
                                        <div class="font-bold text-indigo-950">
                                            🎉 Have you received this equipment? Say Thank You to {{ $item->donor->name }}!
                                        </div>
                                        <form action="{{ route('tools.gratitude', $item->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <textarea name="message" rows="2" required minlength="20" placeholder="Say thank you and describe how this book/tool will help your studies..." class="w-full rounded-xl border-indigo-200 text-xs focus:ring-indigo-600 focus:border-indigo-600"></textarea>
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-sm">
                                                Post Thank-You to Gratitude Wall
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-emerald-50/70 p-3 rounded-2xl border border-emerald-200 text-xs text-emerald-900 space-y-1">
                                        <strong class="font-bold block">✓ You posted a gratitude message:</strong>
                                        <p class="italic">"{{ $item->gratitude->message }}"</p>
                                    </div>
                                @endif
                            @endif

                        </div>
                    @endforeach
                </div>

                @if($requests->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $requests->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
