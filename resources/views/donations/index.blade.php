@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen" x-data="{ selectedCampaign: 'Student Scholarship Fund', customAmount: 2500 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Header -->
        <div class="bg-gradient-to-r from-[#1C3661] via-[#31102f] to-[#8b0000] text-white p-8 sm:p-10 rounded-2xl shadow-xl mb-12 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white mb-3">
                    <span>🌟 KDP Alumni Giving Circle</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold tracking-tight text-white mb-2">
                    Empower the Next Generation of Engineers
                </h1>
                <p class="text-gray-200 text-sm leading-relaxed">
                    Your contributions directly fund student scholarships, cutting-edge laboratory upgrades, and academic resources at K. D. Polytechnic, Patan. All donations qualify for 80G tax deduction.
                </p>
            </div>
            
            <div class="flex items-center gap-4 relative z-10 shrink-0 w-full sm:w-auto">
                <div class="bg-white/10 backdrop-blur-md p-5 rounded-xl border border-white/20 text-center flex-1 sm:flex-initial">
                    <span class="block text-xs uppercase tracking-wider text-gray-300 font-semibold">Total Funds Raised</span>
                    <span class="text-2xl sm:text-3xl font-black text-white mt-1">₹{{ number_format($totalRaised, 2) }}</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-5 rounded-xl border border-white/20 text-center flex-1 sm:flex-initial">
                    <span class="block text-xs uppercase tracking-wider text-gray-300 font-semibold">Alumni Donors</span>
                    <span class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalDonorsCount }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-300 p-4 rounded-xl flex items-center justify-between text-emerald-800 text-sm shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold text-lg">&times;</button>
            </div>
        @endif

        <!-- Crowdfunding Active Campaigns Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-serif font-bold text-gray-900">Active Campus Campaigns</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Explore initiatives currently being championed by our alumni network</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($campaigns as $camp)
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $camp['badge_color'] }}">
                                    {{ $camp['category'] }}
                                </span>
                                <span class="text-xs font-semibold text-gray-400">
                                    {{ $camp['donor_count'] }} {{ Str::plural('donor', $camp['donor_count']) }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-gray-900 mb-1 leading-snug">
                                {{ $camp['title'] }}
                            </h3>

                            <p class="text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed">
                                {{ $camp['description'] }}
                            </p>
                        </div>

                        <div>
                            <!-- Progress Bar -->
                            <div class="mb-2">
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="font-bold text-gray-900">₹{{ number_format($camp['raised']) }}</span>
                                    <span class="text-gray-400">Target: ₹{{ number_format($camp['target']) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $camp['bar_color'] }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $camp['percent'] }}%"></div>
                                </div>
                                <div class="text-right mt-1">
                                    <span class="text-[11px] font-bold text-gray-600">{{ $camp['percent'] }}% Funded</span>
                                </div>
                            </div>

                            <button type="button" @click="selectedCampaign = '{{ $camp['title'] }}'; window.location.hash = 'donate-form'" class="w-full mt-2 text-center text-xs font-bold text-[#8b0000] bg-red-50 hover:bg-red-100 border border-red-200 py-2 rounded-xl transition-colors">
                                Support This Fund &rarr;
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            
            <!-- Donation Form Column -->
            <div id="donate-form" class="lg:col-span-1 bg-white shadow-sm rounded-2xl border border-gray-200 p-6 sm:p-7">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-full bg-[#8b0000] text-white flex items-center justify-center font-bold text-sm">₹</span>
                    <h3 class="text-lg font-bold text-gray-900">Make a Contribution</h3>
                </div>
                
                <form action="{{ route('donations.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Campaign Selection -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Select Campaign *</label>
                        <select name="campaign" x-model="selectedCampaign" required class="w-full border border-gray-300 rounded-xl py-2.5 px-3 text-xs sm:text-sm focus:outline-none focus:border-[#8b0000] bg-white">
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp['title'] }}">{{ $camp['title'] }}</option>
                            @endforeach
                        </select>
                        @error('campaign') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Contribution Amount *</label>
                        <div class="grid grid-cols-4 gap-2 mb-2">
                            @foreach([500, 1000, 2500, 5000] as $preset)
                                <button type="button" @click="customAmount = {{ $preset }}" class="py-1.5 px-2 rounded-lg text-xs font-bold border transition-colors" :class="customAmount == {{ $preset }} ? 'bg-[#1C3661] text-white border-[#1C3661]' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100'">
                                    ₹{{ $preset }}
                                </button>
                            @endforeach
                        </div>
                        
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500 font-bold text-sm">₹</span>
                            <input type="number" name="amount" min="50" step="50" x-model="customAmount" required placeholder="Custom Amount" class="w-full border border-gray-300 rounded-xl py-2 pl-7 pr-3 text-sm font-semibold focus:outline-none focus:border-[#8b0000]">
                        </div>
                        @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Payment Channel</label>
                        <select name="payment_method" class="w-full border border-gray-300 rounded-xl py-2 px-3 text-xs sm:text-sm focus:outline-none focus:border-[#8b0000] bg-white">
                            <option value="UPI / QR Code">UPI (GPay / PhonePe / Paytm)</option>
                            <option value="Net Banking">Net Banking (SBI / HDFC / BOB)</option>
                            <option value="Credit / Debit Card">Credit / Debit Card</option>
                            <option value="Direct College Account Transfer">Direct College Account NEFT/RTGS</option>
                        </select>
                    </div>

                    <!-- Optional Dedication / Note -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Dedication / Message (Optional)</label>
                        <input type="text" name="notes" placeholder="e.g. In honor of Class of 2018 or faculty mentors" class="w-full border border-gray-300 rounded-xl py-2 px-3 text-xs sm:text-sm focus:outline-none focus:border-[#8b0000]">
                    </div>

                    <!-- Anonymous Checkbox -->
                    <div class="pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_anonymous" value="1" class="rounded border-gray-300 text-[#8b0000] focus:ring-[#8b0000]">
                            <span class="ml-2 text-xs text-gray-600">Make this donation anonymous on the public contributor wall</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#8b0000] hover:bg-[#6b0000] text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Complete Contribution
                        </button>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-[11px] text-gray-500 leading-normal">
                        🔒 <strong>80G Tax Benefit:</strong> An official downloadable PDF receipt with your transaction reference number will be generated immediately for your income tax deduction.
                    </div>
                </form>
            </div>

            <!-- Wall of Contributors & User Donation History Column -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- User's Personal Donation Receipts -->
                @auth
                    @if($userDonations->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">My Giving History &amp; 80G Receipts</h3>
                                    <p class="text-xs text-gray-500">Download your verified tax deduction certificates</p>
                                </div>
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $userDonations->count() }} {{ Str::plural('Contribution', $userDonations->count()) }}
                                </span>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($userDonations as $userDonation)
                                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">{{ $userDonation->campaign }}</p>
                                            <p class="text-[11px] text-gray-500">
                                                Ref: <span class="font-mono text-gray-700">{{ $userDonation->transaction_id }}</span> • {{ $userDonation->created_at->format('d M, Y') }}
                                            </p>
                                            @if($userDonation->notes)
                                                <p class="text-[11px] italic text-gray-600 mt-0.5">"{{ $userDonation->notes }}"</p>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3 justify-between sm:justify-end">
                                            <span class="text-sm font-black text-emerald-700">₹{{ number_format($userDonation->amount, 2) }}</span>
                                            <a href="{{ route('donations.receipt', $userDonation->id) }}" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-xs border border-gray-200 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Download 80G Receipt
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- Wall of Contributors -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Wall of Contributors</h3>
                            <p class="text-xs text-gray-500">Honoring alumni giving back to their alma mater</p>
                        </div>
                        <span class="text-xs text-gray-400">Live Contributions</span>
                    </div>
                    
                    @if($donations->isEmpty())
                        <div class="text-center py-8 text-gray-400 text-xs">
                            No contributions recorded yet. Be the first alumnus to pave the way!
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($donations as $donation)
                                <div class="p-3.5 bg-gray-50/70 border border-gray-200 rounded-xl flex flex-col justify-between">
                                    <div class="flex justify-between items-start gap-2 mb-1.5">
                                        <div>
                                            @if($donation->is_anonymous)
                                                <h4 class="font-bold text-gray-600 text-xs flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    Anonymous Donor
                                                </h4>
                                                <p class="text-[10px] text-gray-400">Proud Alumni Supporter</p>
                                            @else
                                                <h4 class="font-bold text-gray-900 text-xs">{{ $donation->user->name }}</h4>
                                                <p class="text-[10px] text-gray-500">
                                                    {{ $donation->user->department ?? optional($donation->user->profile)->department ?? 'Alumnus' }}
                                                    @if($donation->user->graduation_year) • Class of {{ $donation->user->graduation_year }} @endif
                                                </p>
                                            @endif
                                        </div>
                                        <span class="font-black text-emerald-700 text-xs bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                            ₹{{ number_format($donation->amount) }}
                                        </span>
                                    </div>

                                    <div class="mt-1 pt-1.5 border-t border-gray-100 text-[11px] text-gray-500 flex justify-between items-center">
                                        <span class="truncate max-w-[170px]" title="{{ $donation->campaign }}">{{ $donation->campaign }}</span>
                                        <span class="text-[10px] text-gray-400 shrink-0">{{ $donation->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($donation->notes && !$donation->is_anonymous)
                                        <p class="text-[10px] italic text-gray-600 mt-1 line-clamp-1">
                                            "{{ $donation->notes }}"
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
