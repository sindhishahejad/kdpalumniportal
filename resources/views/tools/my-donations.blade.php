@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

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
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Pay-It-Forward Hub</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-0.5">
                    My Donated Tools &amp; Textbooks ({{ $donations->total() }})
                </h1>
                <p class="text-xs text-slate-500 mt-1">Manage your active donations, review requests from junior students, and confirm handovers.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tools.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-colors">
                    + Donate Another Item
                </a>
                <a href="{{ route('tools.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xs uppercase tracking-wider transition-colors">
                    Catalog &rarr;
                </a>
            </div>
        </div>

        <!-- Donations List -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            @if($donations->isEmpty())
                <div class="p-12 text-center text-slate-500 space-y-3">
                    <span class="text-5xl">📦</span>
                    <h3 class="text-lg font-bold text-slate-800">No Donated Equipment Yet</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Do you have unused mini drafters, reference textbooks, scientific calculators, or Arduino kits? Donate to juniors today!
                    </p>
                    <a href="{{ route('tools.create') }}" class="inline-block px-5 py-2.5 rounded-xl bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider">
                        Donate Equipment Now &rarr;
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($donations as $item)
                        <div class="p-6 space-y-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('tools.show', $item->slug) }}" class="font-bold text-slate-900 text-base sm:text-lg hover:text-indigo-600 transition-colors">
                                                {{ $item->title }}
                                            </a>
                                            
                                            @if($item->isAvailable())
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ✓ Available
                                                </span>
                                            @elseif($item->isClaimed())
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                                    ⏳ Reserved for {{ $item->claimedBy->name ?? 'Student' }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                    ✓ Handed Over
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-indigo-700 font-semibold">
                                            {{ $item->department }} • {{ $item->semester_suitability }} • Condition: {{ $item->condition_label }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Value Saved: <strong class="text-emerald-700">₹{{ number_format($item->estimated_value) }}</strong> • Listed {{ $item->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    @if($item->isClaimed())
                                        <form action="{{ route('tools.handover', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm">
                                                Confirm Handed Over
                                            </button>
                                        </form>
                                    @endif

                                    @if($item->isAvailable())
                                        <form action="{{ route('tools.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this donation listing?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 font-bold text-xs transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Requests Accordion -->
                            @if($item->requests->count() > 0)
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-3">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center justify-between">
                                        <span>Junior Claim Requests ({{ $item->requests->count() }})</span>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($item->requests as $req)
                                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                                <div class="space-y-1">
                                                    <div class="font-bold text-slate-900 flex items-center gap-2">
                                                        <span>{{ $req->user->name }}</span>
                                                        <span class="text-[10px] text-slate-500">({{ $req->user->department ?? 'Student' }})</span>
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : ($req->status === 'declined' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                                            {{ $req->status }}
                                                        </span>
                                                    </div>
                                                    <p class="text-slate-600 italic">"{{ $req->reason }}"</p>
                                                </div>

                                                @if($item->isAvailable() && $req->isPending())
                                                    <form action="{{ route('tools.requests.accept', $req->id) }}" method="POST" class="flex-shrink-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider">
                                                            Accept Request
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Gratitude Note If Handed Over -->
                            @if($item->gratitude)
                                <div class="bg-emerald-50/70 p-3 rounded-2xl border border-emerald-200 text-xs text-emerald-950 space-y-1">
                                    <strong class="font-bold block">❤️ Student Gratitude Message from {{ $item->gratitude->user->name }}:</strong>
                                    <p class="italic">"{{ $item->gratitude->message }}"</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($donations->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $donations->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
