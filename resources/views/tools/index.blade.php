@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

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

        <!-- Institutional Hero Header with Official Crest Watermark -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white shadow-2xl p-8 sm:p-12 border border-blue-900/40">
            <!-- Background Decorative Glows -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- College Crest Watermark -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none flex items-center justify-end pr-8 overflow-hidden">
                <img src="{{ asset('images/kdp_crest.png') }}" alt="" class="h-80 w-auto object-contain filter grayscale contrast-200">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4 max-w-3xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wide border border-amber-500/30">
                            <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                            કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-wider border border-white/20">
                            📚 Pay-It-Forward Student Equipment Bank
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white font-heading">
                        Alumni Book &amp; Engineering Tool Bank
                    </h1>

                    <p class="text-blue-100 text-sm sm:text-base lg:text-lg leading-relaxed max-w-2xl font-light">
                        Pass on your mini drafters, GTU reference textbooks, Casio scientific calculators, and Arduino kits to incoming junior diploma technicians at K.D. Polytechnic Patan.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2 text-xs font-semibold text-blue-200">
                        <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                            <span>🏫</span> Campus Locker &amp; Workshop Handover
                        </span>
                        <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                            <span>💸</span> 100% Free for Junior Students
                        </span>
                        <span class="flex items-center gap-1.5 bg-white/10 px-3.5 py-1.5 rounded-full border border-white/10">
                            <span>🔄</span> Honor Pledge to Pass Forward
                        </span>
                    </div>
                </div>

                <!-- CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[260px] flex-shrink-0">
                    <a href="{{ route('tools.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#f97316] to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-heading font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
                        <span>✨</span> Donate a Tool or Book
                    </a>
                    <a href="{{ route('tools.my-donations') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-heading font-bold text-xs uppercase tracking-wider backdrop-blur transition-all">
                        <span>📦</span> My Donated Items
                    </a>
                    <a href="{{ route('tools.my-requests') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-heading font-bold text-xs uppercase tracking-wider backdrop-blur transition-all">
                        <span>✋</span> My Claim Requests
                    </a>
                </div>
            </div>
        </div>

        <!-- Impact Statistics Counter -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center relative overflow-hidden group hover:border-[#1e3a8a] transition-all">
                <div class="text-3xl sm:text-4xl font-heading font-black text-[#0f2042]">{{ $stats['total_items'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1.5 font-heading">Total Items Donated</div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-[#1e3a8a] transform scale-x-0 group-hover:scale-x-100 transition-transform"></div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center relative overflow-hidden group hover:border-emerald-600 transition-all">
                <div class="text-3xl sm:text-4xl font-heading font-black text-emerald-600">₹{{ number_format($stats['total_value_saved']) }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1.5 font-heading">₹ Saved for Students</div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center relative overflow-hidden group hover:border-[#f97316] transition-all">
                <div class="text-3xl sm:text-4xl font-heading font-black text-[#f97316]">{{ $stats['available_count'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1.5 font-heading">Available to Claim Now</div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-[#f97316] transform scale-x-0 group-hover:scale-x-100 transition-transform"></div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center relative overflow-hidden group hover:border-indigo-600 transition-all">
                <div class="text-3xl sm:text-4xl font-heading font-black text-indigo-700">{{ $stats['beneficiaries_count'] }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1.5 font-heading">Junior Beneficiaries</div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 💳 ALUMNI EQUIPMENT CASH SPONSORSHIP BANNER     -->
        <!-- ============================================== -->
        <div class="bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-blue-900/40 relative overflow-hidden" 
             x-data="{ sponsorKit: 'drafter', sponsorAmt: 350, customAmt: '' }">
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30 font-heading">
                            🎁 Cash Tool Sponsorship
                        </span>
                        <span class="text-[10px] text-blue-200 font-bold font-heading flex items-center gap-1">
                            <span>🔒</span> Razorpay Secured &bull; 80G Tax Exemption
                        </span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-heading font-black text-white">
                        Don't have used tools at home? Sponsor fresh kits for juniors!
                    </h3>
                    <p class="text-xs sm:text-sm text-blue-100 font-sans leading-relaxed">
                        Your direct financial contribution allows the college tool bank coordinator to purchase brand-new mini drafters, Casio scientific calculators, and Arduino kits for incoming polytechnic freshers.
                    </p>

                    <!-- Kit Selector Options -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2">
                        <button type="button" 
                                @click="sponsorKit = 'drafter'; sponsorAmt = 350; customAmt = ''"
                                class="p-2.5 rounded-xl border text-left transition-all cursor-pointer"
                                :class="sponsorKit === 'drafter' ? 'bg-[#f97316] text-white border-[#f97316] shadow-md' : 'bg-white/10 text-blue-100 border-white/15 hover:bg-white/20'">
                            <span class="block text-base">📐</span>
                            <span class="block text-xs font-heading font-bold mt-1">Mini Drafter Kit</span>
                            <span class="block text-[11px] font-mono text-amber-200">₹350</span>
                        </button>

                        <button type="button" 
                                @click="sponsorKit = 'calculator'; sponsorAmt = 850; customAmt = ''"
                                class="p-2.5 rounded-xl border text-left transition-all cursor-pointer"
                                :class="sponsorKit === 'calculator' ? 'bg-[#f97316] text-white border-[#f97316] shadow-md' : 'bg-white/10 text-blue-100 border-white/15 hover:bg-white/20'">
                            <span class="block text-base">🖩</span>
                            <span class="block text-xs font-heading font-bold mt-1">Casio FX-991CW</span>
                            <span class="block text-[11px] font-mono text-amber-200">₹850</span>
                        </button>

                        <button type="button" 
                                @click="sponsorKit = 'arduino'; sponsorAmt = 1500; customAmt = ''"
                                class="p-2.5 rounded-xl border text-left transition-all cursor-pointer"
                                :class="sponsorKit === 'arduino' ? 'bg-[#f97316] text-white border-[#f97316] shadow-md' : 'bg-white/10 text-blue-100 border-white/15 hover:bg-white/20'">
                            <span class="block text-base">⚡</span>
                            <span class="block text-xs font-heading font-bold mt-1">Arduino IoT Kit</span>
                            <span class="block text-[11px] font-mono text-amber-200">₹1,500</span>
                        </button>

                        <button type="button" 
                                @click="sponsorKit = 'full_pack'; sponsorAmt = 3000; customAmt = ''"
                                class="p-2.5 rounded-xl border text-left transition-all cursor-pointer"
                                :class="sponsorKit === 'full_pack' ? 'bg-[#f97316] text-white border-[#f97316] shadow-md' : 'bg-white/10 text-blue-100 border-white/15 hover:bg-white/20'">
                            <span class="block text-base">🎒</span>
                            <span class="block text-xs font-heading font-bold mt-1">Full Fresher Pack</span>
                            <span class="block text-[11px] font-mono text-amber-200">₹3,000</span>
                        </button>
                    </div>
                </div>

                <!-- Pay CTA Column -->
                <div class="bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/15 space-y-3 shrink-0 sm:min-w-[280px]">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-blue-200 font-heading font-bold uppercase tracking-wider text-[10px]">Sponsorship Value</span>
                        <span class="text-2xl font-heading font-black text-amber-400" x-text="'₹' + Number(customAmt || sponsorAmt).toLocaleString('en-IN')"></span>
                    </div>

                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-blue-300 font-bold text-xs font-heading">₹</span>
                        <input type="number" min="50" step="50" x-model="customAmt" placeholder="Custom Amount"
                               class="w-full pl-7 pr-3 py-2 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-300/60 font-heading font-bold focus:bg-white/20 focus:border-amber-400 focus:ring-0">
                    </div>

                    <button type="button" 
                            @click="openRazorpayPayment({
                                amount: customAmt ? parseFloat(customAmt) : sponsorAmt,
                                purpose: 'tool_bank_sponsor',
                                campaign: 'Pay-It-Forward Tool Bank: ' + (sponsorKit === 'drafter' ? 'Mini Drafter Kit' : sponsorKit === 'calculator' ? 'Casio FX-991 Calculator' : sponsorKit === 'arduino' ? 'Arduino IoT Kit' : 'Full Fresher Pack')
                            })"
                            class="w-full bg-[#f97316] hover:bg-orange-600 text-white font-heading font-black text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-lg hover:shadow-orange-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>💳</span>
                        <span>Sponsor via Razorpay</span>
                    </button>

                    <p class="text-[10px] text-blue-200/80 text-center font-sans">
                        Instant 80G Tax Deduction PDF receipt &bull; 100% used for tool procurement
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔍</span>
                    <h3 class="font-heading font-bold text-base text-slate-900 uppercase tracking-wider">Search &amp; Filter Bank</h3>
                </div>
                @if(request()->anyFilled(['search', 'item_type', 'department', 'status']))
                    <a href="{{ route('tools.index') }}" class="text-xs font-heading font-bold text-rose-600 hover:text-rose-800 uppercase tracking-wider">
                        Clear All Filters &times;
                    </a>
                @endif
            </div>

            <form action="{{ route('tools.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2 space-y-1">
                    <label for="search" class="block text-xs font-heading font-bold uppercase tracking-wider text-slate-700">Search Keywords</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search calculator, drafter, sem books, donor..." class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                </div>

                <!-- Category Filter -->
                <div class="space-y-1">
                    <label for="item_type" class="block text-xs font-heading font-bold uppercase tracking-wider text-slate-700">Equipment Category</label>
                    <select name="item_type" id="item_type" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        <option value="">All Categories</option>
                        <option value="drawing_tools" {{ request('item_type') === 'drawing_tools' ? 'selected' : '' }}>📐 Mini Drafters &amp; Graphics</option>
                        <option value="calculators_electronics" {{ request('item_type') === 'calculators_electronics' ? 'selected' : '' }}>🖩 Scientific Calculators</option>
                        <option value="textbook" {{ request('item_type') === 'textbook' ? 'selected' : '' }}>📚 Engineering Textbooks</option>
                        <option value="lab_hardware" {{ request('item_type') === 'lab_hardware' ? 'selected' : '' }}>⚡ Arduino &amp; Lab Hardware</option>
                        <option value="workshop_safety" {{ request('item_type') === 'workshop_safety' ? 'selected' : '' }}>🦺 Workshop Safety Gear</option>
                        <option value="other" {{ request('item_type') === 'other' ? 'selected' : '' }}>📦 Other Tools</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="space-y-1">
                    <label for="department" class="block text-xs font-heading font-bold uppercase tracking-wider text-slate-700">Department</label>
                    <select name="department" id="department" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        <option value="">All Branches</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="space-y-1">
                    <label for="status" class="block text-xs font-heading font-bold uppercase tracking-wider text-slate-700">Availability</label>
                    <select name="status" id="status" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a]">
                        <option value="" {{ request('status') === '' ? 'selected' : '' }}>Available First</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available Only</option>
                        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Reserved / Claimed</option>
                        <option value="handed_over" {{ request('status') === 'handed_over' ? 'selected' : '' }}>Completed Handover</option>
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Show All</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="sm:col-span-2 lg:col-span-5 flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="px-7 py-3 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-heading font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                        Apply Filter Catalog
                    </button>
                </div>
            </form>
        </div>

        <!-- Catalog Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">
                        Equipment &amp; Book Catalog
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Showing {{ $items->total() }} items donated by generous alumni for KDP students
                    </p>
                </div>
                <a href="{{ route('tools.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-900 font-heading font-bold text-xs uppercase tracking-wider hover:bg-amber-500/20 transition-colors">
                    <span>+</span> Donate an Item
                </a>
            </div>

            @if($items->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-slate-200 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-3xl">📦</div>
                    <h3 class="text-lg font-heading font-bold text-slate-800">No Equipment Found</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                        No equipment matches your search criteria. Try clearing filters or be the pioneer alumnus to donate an item for junior polytechnic students!
                    </p>
                    <a href="{{ route('tools.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#1e3a8a] hover:bg-[#0f2042] text-white text-xs font-heading font-bold uppercase tracking-wider shadow-sm transition-all">
                        Donate Now &rarr;
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($items as $item)
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group hover:border-[#1e3a8a]/40">
                            <!-- Image & Badges Container -->
                            <div class="relative h-52 w-full overflow-hidden bg-slate-900">
                                <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                
                                <!-- Category Pill Top Left -->
                                <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-heading font-bold bg-[#0f2042]/90 text-white backdrop-blur shadow-sm border border-white/20">
                                    {{ $item->item_type_label }}
                                </div>

                                <!-- Value Saved Badge Top Right -->
                                <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-[11px] font-heading font-black bg-emerald-500 text-slate-950 shadow-md">
                                    Save ₹{{ number_format($item->estimated_value) }}
                                </div>

                                <!-- Status Badge Bottom Left -->
                                <div class="absolute bottom-3 left-3">
                                    @if($item->isAvailable())
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-heading font-bold bg-emerald-500 text-slate-950 shadow-sm">
                                            ✓ Available to Claim
                                        </span>
                                    @elseif($item->isClaimed())
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-heading font-bold bg-amber-400 text-slate-950 shadow-sm">
                                            ⏳ Reserved / Claimed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-heading font-bold bg-blue-500 text-white shadow-sm">
                                            ✓ Handed Over
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content Body -->
                            <div class="p-6 space-y-4 flex-grow flex flex-col justify-between">
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-2 text-[11px] font-heading font-bold text-[#1e3a8a]">
                                        <span>{{ $item->department }}</span>
                                        <span>•</span>
                                        <span>{{ $item->semester_suitability }}</span>
                                    </div>

                                    <h3 class="text-lg font-heading font-bold text-slate-900 group-hover:text-[#1e3a8a] transition-colors line-clamp-2 leading-snug">
                                        {{ $item->title }}
                                    </h3>

                                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $item->description }}
                                    </p>

                                    <div class="pt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-600">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-medium">
                                            Condition: {{ $item->condition_label }}
                                        </span>
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-900 font-medium">
                                            📍 {{ $item->pickup_method_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Donor Info & Action -->
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $item->donor->photo_path ? asset('storage/' . $item->donor->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->donor->name) . '&background=1e3a8a&color=fff&size=80' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                        <div class="text-[11px] leading-tight">
                                            <div class="font-heading font-bold text-slate-900">{{ $item->donor->name }}</div>
                                            <div class="text-slate-500 text-[10px]">Class of {{ $item->donor->graduation_year ?? 'Alumnus' }}</div>
                                        </div>
                                    </div>

                                    <a href="{{ route('tools.show', $item->slug) }}" class="inline-flex items-center gap-1 text-xs font-heading font-bold text-[#1e3a8a] hover:text-[#f97316] uppercase tracking-wider group-hover:translate-x-0.5 transition-transform">
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
            <div class="rounded-3xl bg-gradient-to-r from-emerald-50 via-teal-50 to-blue-50 border border-emerald-200/80 p-8 sm:p-10 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-emerald-200/60 pb-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 text-xs font-heading font-bold text-emerald-800 uppercase tracking-widest">
                            <span>❤️</span> Gratitude &amp; Impact Wall
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">
                            Junior Students Saying "Thank You"
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600">
                            Real messages from KDP diploma juniors who received tools and textbooks from senior alumni.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($gratitudes as $grat)
                        <div class="bg-white/95 backdrop-blur rounded-2xl p-5 border border-emerald-200 shadow-sm flex flex-col justify-between hover:-translate-y-1 transition-all space-y-3">
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-heading font-bold text-xs shadow-sm">
                                        {{ substr($grat->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-xs leading-tight">
                                        <div class="font-heading font-bold text-slate-900">{{ $grat->user->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $grat->user->department ?? 'Diploma Student' }}</div>
                                    </div>
                                </div>

                                <div class="text-[11px] font-heading font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                                    Received: {{ $grat->donation->title }}
                                </div>

                                <p class="text-xs text-slate-600 italic line-clamp-4 leading-relaxed">
                                    "{{ $grat->message }}"
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 text-[10px] text-slate-400 flex items-center justify-between">
                                <span>Donated by <strong class="text-slate-700">{{ $grat->donation->donor->name }}</strong></span>
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
