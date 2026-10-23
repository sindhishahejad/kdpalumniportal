@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center gap-3 text-emerald-800">
                <span class="text-2xl">🎉</span>
                <div class="font-medium text-sm sm:text-base">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-center gap-3 text-rose-800">
                <span class="text-2xl">⚠️</span>
                <div class="font-medium text-sm sm:text-base">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 text-white shadow-2xl p-8 sm:p-12 border border-blue-500/20">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-semibold tracking-wide uppercase">
                        <span>📚</span> Pay-It-Forward Student Equipment Bank
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-white">
                        Alumni Book &amp; Engineering Tool Bank
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                        Pass on your mini drafters, GTU reference textbooks, scientific calculators, and Arduino kits to incoming junior technicians at K.D. Polytechnic Patan.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-300 pt-2">
                        <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full">
                            <span>🏫</span> Campus Locker Handover
                        </span>
                        <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full">
                            <span>💸</span> 100% Free for Junior Students
                        </span>
                    </div>
                </div>

                <!-- CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[240px]">
                    <a href="{{ route('tools.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-amber-500/30 transition-all">
                        <span>✨</span> Donate a Tool or Book
                    </a>
                    <a href="{{ route('tools.my-donations') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm uppercase tracking-wider backdrop-blur transition-all">
                        <span>📦</span> My Donated Items
                    </a>
                    <a href="{{ route('tools.my-requests') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm uppercase tracking-wider backdrop-blur transition-all">
                        <span>✋</span> My Claim Requests
                    </a>
                </div>
            </div>
        </div>

        <!-- Impact Statistics Counter -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-indigo-900">{{ $stats['total_items'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Total Items Donated</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-emerald-600">₹{{ number_format($stats['total_value_saved']) }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">₹ Saved for Students</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-amber-600">{{ $stats['available_count'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Available to Claim Now</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center">
                <div class="text-3xl font-black text-purple-700">{{ $stats['beneficiaries_count'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Junior Beneficiaries</div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <form action="{{ route('tools.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Search Keywords</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search calculator, drafter, sem books, donor..." class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-indigo-600 focus:ring-indigo-600">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="item_type" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Equipment Category</label>
                    <select name="item_type" id="item_type" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-indigo-600 focus:ring-indigo-600">
                        <option value="">All Categories</option>
                        <option value="drawing_tools" {{ request('item_type') === 'drawing_tools' ? 'selected' : '' }}>Mini Drafters &amp; Graphics</option>
                        <option value="calculators_electronics" {{ request('item_type') === 'calculators_electronics' ? 'selected' : '' }}>Scientific Calculators</option>
                        <option value="textbook" {{ request('item_type') === 'textbook' ? 'selected' : '' }}>Engineering Textbooks</option>
                        <option value="lab_hardware" {{ request('item_type') === 'lab_hardware' ? 'selected' : '' }}>Arduino &amp; Lab Hardware</option>
                        <option value="workshop_safety" {{ request('item_type') === 'workshop_safety' ? 'selected' : '' }}>Workshop Safety Gear</option>
                        <option value="other" {{ request('item_type') === 'other' ? 'selected' : '' }}>Other Tools</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div>
                    <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Department</label>
                    <select name="department" id="department" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-indigo-600 focus:ring-indigo-600">
                        <option value="">All Branches</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Availability</label>
                    <select name="status" id="status" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-indigo-600 focus:ring-indigo-600">
                        <option value="" {{ request('status') === '' ? 'selected' : '' }}>Available First</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available Only</option>
                        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Reserved / Claimed</option>
                        <option value="handed_over" {{ request('status') === 'handed_over' ? 'selected' : '' }}>Completed Handover</option>
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Show All</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="sm:col-span-2 lg:col-span-5 flex items-center justify-end gap-3 pt-2">
                    @if(request()->anyFilled(['search', 'item_type', 'department', 'status', 'condition', 'semester']))
                        <a href="{{ route('tools.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                        Filter Catalog
                    </button>
                </div>
            </form>
        </div>

        <!-- Catalog Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    Equipment &amp; Book Catalog ({{ $items->total() }})
                </h2>
                <a href="{{ route('tools.create') }}" class="text-xs font-bold text-indigo-700 hover:text-indigo-900 uppercase tracking-wider">
                    + Donate an Item
                </a>
            </div>

            @if($items->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-300 space-y-3">
                    <span class="text-5xl">📦</span>
                    <h3 class="text-lg font-bold text-slate-800">No Equipment Found</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        No equipment matches your filters. Try clearing filters or be the first alumnus to donate an item for junior students!
                    </p>
                    <a href="{{ route('tools.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider">
                        Donate Now &rarr;
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                            <!-- Image & Badges Container -->
                            <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                                <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                <!-- Category Pill Top Left -->
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-900/80 text-white backdrop-blur shadow-sm">
                                    {{ $item->item_type_label }}
                                </div>

                                <!-- Value Saved Badge Top Right -->
                                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-500 text-slate-950 shadow-sm">
                                    Save ₹{{ number_format($item->estimated_value) }}
                                </div>

                                <!-- Status Badge Bottom Left -->
                                <div class="absolute bottom-3 left-3">
                                    @if($item->isAvailable())
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/90 text-white backdrop-blur shadow-sm">
                                            ✓ Available to Claim
                                        </span>
                                    @elseif($item->isClaimed())
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/90 text-slate-950 backdrop-blur shadow-sm">
                                            ⏳ Reserved / Claimed
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-500/90 text-white backdrop-blur shadow-sm">
                                            ✓ Handed Over
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content Body -->
                            <div class="p-6 space-y-4 flex-grow flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-indigo-700">
                                        <span>{{ $item->department }}</span>
                                        <span>•</span>
                                        <span>{{ $item->semester_suitability }}</span>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-900 transition-colors line-clamp-2">
                                        {{ $item->title }}
                                    </h3>

                                    <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                        {{ $item->description }}
                                    </p>

                                    <div class="pt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">
                                            Condition: {{ $item->condition_label }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-medium">
                                            📍 {{ $item->pickup_method_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Donor Info & Action -->
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $item->donor->photo_path ? asset('storage/' . $item->donor->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->donor->name) . '&background=1e3a8a&color=fff&size=80' }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                        <div class="text-[11px] leading-tight">
                                            <div class="font-bold text-slate-900">{{ $item->donor->name }}</div>
                                            <div class="text-slate-500">Class of {{ $item->donor->graduation_year ?? 'Alumnus' }}</div>
                                        </div>
                                    </div>

                                    <a href="{{ route('tools.show', $item->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-700 hover:text-indigo-900 uppercase tracking-wider group-hover:translate-x-0.5 transition-transform">
                                        {{ $item->isAvailable() ? 'Claim Item' : 'View Details' }} &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($items->hasPages())
                    <div class="pt-6">
                        {{ $items->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Pay-It-Forward Gratitude Wall -->
        @if($gratitudes->count() > 0)
            <div class="rounded-3xl bg-gradient-to-r from-emerald-50 via-teal-50 to-blue-50 border border-emerald-200/80 p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 uppercase tracking-widest">
                            <span>❤️</span> Gratitude &amp; Impact Wall
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                            Junior Students Saying "Thank You"
                        </h2>
                        <p class="text-sm text-slate-600">
                            Real messages from KDP diploma juniors who received tools and textbooks from senior alumni.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($gratitudes as $grat)
                        <div class="bg-white/90 backdrop-blur rounded-2xl p-5 border border-emerald-200 shadow-sm flex flex-col justify-between hover:-translate-y-1 transition-all space-y-3">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">
                                        {{ substr($grat->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-xs leading-tight">
                                        <div class="font-bold text-slate-900">{{ $grat->user->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $grat->user->department ?? 'Diploma Student' }}</div>
                                    </div>
                                </div>

                                <div class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                    Received: {{ $grat->donation->title }}
                                </div>

                                <p class="text-xs text-slate-600 italic line-clamp-4 leading-relaxed">
                                    "{{ $grat->message }}"
                                </p>
                            </div>

                            <div class="pt-2 border-t border-slate-100 text-[10px] text-slate-400 flex items-center justify-between">
                                <span>Donated by <strong>{{ $grat->donation->donor->name }}</strong></span>
                                <span>{{ $grat->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
