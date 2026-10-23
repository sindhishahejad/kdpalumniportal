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

        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('tools.index') }}" class="hover:text-indigo-600 transition-colors">Tool Bank</a>
            <span>&rarr;</span>
            <span class="text-slate-800 truncate max-w-sm">{{ $donation->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Item Photos & Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Item Card -->
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm space-y-6">
                    <!-- Photo Header -->
                    <div class="relative h-72 sm:h-96 w-full bg-slate-900">
                        <img src="{{ $donation->photo_url }}" alt="{{ $donation->title }}" class="w-full h-full object-cover">
                        
                        <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-950/80 text-white backdrop-blur shadow-sm">
                            {{ $donation->item_type_label }}
                        </div>

                        <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-500 text-slate-950 shadow-md">
                            Save ₹{{ number_format($donation->estimated_value) }}
                        </div>

                        <!-- Status Bar Bottom -->
                        <div class="absolute bottom-4 left-4">
                            @if($donation->isAvailable())
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-md">
                                    ✓ Available for Junior Students
                                </span>
                            @elseif($donation->isClaimed())
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500 text-slate-950 shadow-md">
                                    ⏳ Reserved for {{ $donation->claimedBy->name ?? 'Student' }}
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-md">
                                    ✓ Handed Over to {{ $donation->claimedBy->name ?? 'Student' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Body -->
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-700">
                                <span>{{ $donation->department }}</span>
                                <span>•</span>
                                <span>{{ $donation->semester_suitability }}</span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                                {{ $donation->title }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-3 pt-1 text-xs">
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-semibold">
                                    Condition: {{ $donation->condition_label }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-800 font-semibold">
                                    📍 {{ $donation->pickup_method_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2 pt-4 border-t border-slate-100 text-sm">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Description &amp; Included Components</h3>
                            <p class="text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $donation->description }}
                            </p>
                        </div>

                        <!-- Pickup Location Details -->
                        @if($donation->pickup_location)
                            <div class="bg-blue-50/70 rounded-2xl p-4 border border-blue-100 space-y-1 text-xs">
                                <strong class="text-blue-950 font-bold block">📍 Pickup / Handover Venue:</strong>
                                <p class="text-blue-800">{{ $donation->pickup_location }}</p>
                            </div>
                        @endif

                        <!-- Donor Encouragement Note -->
                        @if($donation->donor_note)
                            <div class="bg-amber-50/70 rounded-2xl p-4 border border-amber-200/80 space-y-1 text-xs">
                                <strong class="text-amber-950 font-bold block flex items-center gap-1.5">
                                    <span>💬</span> Note from Donor to Junior:
                                </strong>
                                <p class="text-amber-900 italic">"{{ $donation->donor_note }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Gratitude Wall Story (If Handed Over) -->
                @if($donation->gratitude)
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-6 sm:p-8 border border-emerald-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-800">
                            <span>❤️</span> Pay-It-Forward Gratitude Story
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold">
                                {{ substr($donation->gratitude->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $donation->gratitude->user->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $donation->gratitude->user->department ?? 'Diploma Student' }}</p>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed bg-white/80 p-4 rounded-2xl border border-emerald-100">
                            "{{ $donation->gratitude->message }}"
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Donor Profile & Claim Action Box -->
            <div class="space-y-6">
                
                <!-- Action / Claim Box -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
                    <h3 class="text-base font-bold text-slate-900">
                        Equipment Status &amp; Claiming
                    </h3>

                    @if($donation->isAvailable())
                        @if(Auth::id() === $donation->user_id)
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-900 space-y-2">
                                <span class="font-bold block">👤 You listed this item.</span>
                                <p>You will receive claim requests from junior students here when submitted.</p>
                                <a href="{{ route('tools.my-donations') }}" class="inline-block mt-1 font-bold text-blue-800 underline">
                                    Manage in My Donated Items &rarr;
                                </a>
                            </div>
                        @elseif($userRequest)
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-xs text-emerald-900 space-y-2">
                                <span class="font-bold block flex items-center gap-1.5">
                                    <span>✓</span> Request Submitted
                                </span>
                                <p>Status: <strong class="uppercase text-emerald-950">{{ $userRequest->status }}</strong></p>
                                <p class="text-emerald-700">The donor has received your request and will review your note.</p>
                                <a href="{{ route('tools.my-requests') }}" class="inline-block font-bold text-emerald-800 underline">
                                    View All My Requests &rarr;
                                </a>
                            </div>
                        @else
                            <form action="{{ route('tools.request', $donation->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-1">
                                    <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                        Why do you need this equipment? <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea name="reason" id="reason" rows="3" required minlength="15" placeholder="Mention your semester, enrollment branch, and how this will support your diploma studies..." class="w-full rounded-xl border-slate-300 text-xs focus:ring-indigo-600 focus:border-indigo-600"></textarea>
                                </div>

                                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-700 to-blue-800 hover:from-indigo-800 hover:to-blue-900 text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-indigo-600/30 transition-all">
                                    Request to Claim This Item (Free)
                                </button>
                                <p class="text-[11px] text-slate-400 text-center">
                                    100% Free • Honor system to pass forward upon graduation.
                                </p>
                            </form>
                        @endif

                    @elseif($donation->isClaimed())
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-950 space-y-2">
                            <span class="font-bold block">⏳ Item Claimed</span>
                            <p>This item has been assigned to <strong>{{ $donation->claimedBy->name ?? 'Student' }}</strong> and is awaiting campus pickup.</p>
                            
                            @if(Auth::id() === $donation->user_id)
                                <form action="{{ route('tools.handover', $donation->id) }}" method="POST" class="pt-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider">
                                        Confirm Handed Over to Student
                                    </button>
                                </form>
                            @endif
                        </div>

                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-700 space-y-2">
                            <span class="font-bold block text-slate-900">✓ Successfully Handed Over</span>
                            <p>Handed over to <strong>{{ $donation->claimedBy->name ?? 'Student' }}</strong> on {{ $donation->handed_over_at ? $donation->handed_over_at->format('M d, Y') : 'Recently' }}.</p>
                            
                            @if(Auth::id() === $donation->claimed_by_user_id && !$donation->gratitude)
                                <div class="pt-2">
                                    <h4 class="font-bold text-xs text-slate-900">Post a Gratitude Thank-You Note:</h4>
                                    <form action="{{ route('tools.gratitude', $donation->id) }}" method="POST" class="mt-2 space-y-2">
                                        @csrf
                                        <textarea name="message" rows="3" required minlength="20" placeholder="Say thank you to the donor and how this helped your studies..." class="w-full rounded-xl border-slate-300 text-xs"></textarea>
                                        <button type="submit" class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs">
                                            Post Thank-You Note
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Donor Profile Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Donated By Alumnus
                    </h3>

                    <div class="flex items-center gap-3">
                        <img src="{{ $donation->donor->photo_path ? asset('storage/' . $donation->donor->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($donation->donor->name) . '&background=1e3a8a&color=fff&size=100' }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-sm">
                        <div class="space-y-0.5">
                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $donation->donor->name }}</h4>
                            <p class="text-xs text-indigo-700 font-semibold">{{ $donation->donor->department }}</p>
                            <p class="text-[11px] text-slate-500">Class of {{ $donation->donor->graduation_year ?? 'Alumnus' }}</p>
                        </div>
                    </div>

                    @if($donation->donor->company || $donation->donor->designation)
                        <div class="text-xs text-slate-600 pt-2 border-t border-slate-100">
                            🏢 {{ $donation->donor->designation ? $donation->donor->designation . ', ' : '' }}{{ $donation->donor->company }}
                        </div>
                    @endif

                    @if(Auth::id() !== $donation->user_id)
                        <div class="pt-2">
                            <a href="{{ route('messages.inbox', $donation->donor->id) }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs uppercase tracking-wider transition-colors">
                                <span>💬</span> Send Direct Message
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Incoming Claim Requests (Visible only to Donor) -->
                @if(Auth::id() === $donation->user_id && $donation->requests->count() > 0)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center justify-between">
                            <span>Incoming Claim Requests</span>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-black">
                                {{ $donation->requests->count() }}
                            </span>
                        </h3>

                        <div class="space-y-3">
                            @foreach($donation->requests as $req)
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="font-bold text-slate-900">{{ $req->user->name }}</div>
                                        <span class="text-[10px] uppercase font-bold {{ $req->status === 'accepted' ? 'text-emerald-700' : ($req->status === 'declined' ? 'text-rose-600' : 'text-amber-700') }}">
                                            {{ $req->status }}
                                        </span>
                                    </div>

                                    <p class="text-slate-600 italic">"{{ $req->reason }}"</p>

                                    @if($donation->isAvailable() && $req->isPending())
                                        <form action="{{ route('tools.requests.accept', $req->id) }}" method="POST" class="pt-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-full py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider">
                                                Accept &amp; Assign to {{ Str::before($req->user->name, ' ') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
