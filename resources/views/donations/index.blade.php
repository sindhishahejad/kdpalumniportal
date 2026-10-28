@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20" x-data="{ selectedCampaign: 'Student Scholarship Fund', customAmount: 2500 }">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Watermark Backgrounds -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-80 h-80 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
        <div class="absolute -left-12 -top-12 opacity-5 pointer-events-none">
            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-64 h-64 object-contain filter invert" alt="GTU Seal Watermark">
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    KDP Alumni Giving Circle
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>📜</span> 80G Tax Exemption Eligible
                </span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white mb-2">
                        Empower the Next Generation of Engineers
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 leading-relaxed font-sans">
                        Your contributions directly fund student scholarships, cutting-edge laboratory upgrades, and academic resources at K. D. Polytechnic, Patan. All donations qualify for 80G tax deduction under the Income Tax Act.
                    </p>
                </div>

                <!-- Live Fund Counters -->
                <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
                    <div class="bg-white/10 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-white/20 text-center flex-1 sm:flex-initial min-w-[150px]">
                        <span class="block text-[10px] uppercase tracking-wider text-orange-300 font-bold font-heading">Total Funds Raised</span>
                        <span class="text-2xl sm:text-3xl font-black text-white mt-0.5 block font-heading">₹{{ number_format($totalRaised, 2) }}</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-white/20 text-center flex-1 sm:flex-initial min-w-[130px]">
                        <span class="block text-[10px] uppercase tracking-wider text-blue-200 font-bold font-heading">Alumni Donors</span>
                        <span class="text-2xl sm:text-3xl font-black text-white mt-0.5 block font-heading">{{ $totalDonorsCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN CONTENT (CAMPAIGNS + FORM + WALL)      -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        
        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    @if(session('whatsapp_url'))
                        <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold font-heading shadow-xs transition-colors">
                            <span>📲</span>
                            <span>Open 80G Receipt on WhatsApp</span>
                        </a>
                    @endif
                    <button type="button" onclick="this.closest('.mb-8').remove()" class="text-emerald-700 hover:text-emerald-900 font-bold text-lg cursor-pointer leading-none">&times;</button>
                </div>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- ACTIVE CAMPUS CAMPAIGNS                        -->
        <!-- ============================================== -->
        <div class="mb-12">
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#f97316] font-heading block">
                        Campus Advancement
                    </span>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                        Active Campus Campaigns
                    </h2>
                    <p class="text-xs text-slate-500 font-sans mt-0.5">Explore initiatives currently being championed by our alumni network and department heads</p>
                </div>
                <div class="text-xs text-slate-500 font-medium font-sans">
                    4 Priority Development Funds
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($campaigns as $camp)
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <!-- Category Badge & Donors -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $camp['badge_color'] }} font-heading">
                                    {{ $camp['category'] }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-400">
                                    {{ $camp['donor_count'] }} {{ Str::plural('donor', $camp['donor_count']) }}
                                </span>
                            </div>

                            <h3 class="font-heading font-black text-sm text-[#0f172a] mb-1.5 leading-snug group-hover:text-[#1e3a8a] transition-colors">
                                {{ $camp['title'] }}
                            </h3>

                            <p class="text-xs text-slate-500 mb-4 line-clamp-2 leading-relaxed font-sans">
                                {{ $camp['description'] }}
                            </p>
                        </div>

                        <div>
                            <!-- Progress Bar -->
                            <div class="mb-3 pt-2 border-t border-slate-100">
                                <div class="flex justify-between items-center text-xs mb-1.5 font-sans">
                                    <span class="font-bold text-slate-900 font-heading">₹{{ number_format($camp['raised']) }}</span>
                                    <span class="text-slate-400 text-[11px]">Target: ₹{{ number_format($camp['target']) }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-[#1e3a8a] h-2.5 rounded-full transition-all duration-500" style="width: {{ $camp['percent'] }}%"></div>
                                </div>
                                <div class="text-right mt-1.5">
                                    <span class="text-[11px] font-bold text-[#f97316] font-heading">{{ $camp['percent'] }}% Funded</span>
                                </div>
                            </div>

                            <button type="button" 
                                    @click="openRazorpayPayment({ amount: 2500, purpose: 'giving_back', campaign: '{{ $camp['title'] }}' })" 
                                    class="w-full text-center text-xs font-bold uppercase tracking-wider text-white bg-[#1e3a8a] hover:bg-blue-900 py-2.5 rounded-xl shadow-xs transition-colors font-heading cursor-pointer flex items-center justify-center gap-1.5">
                                <span>💳 Support with Razorpay</span>
                                <span>&rarr;</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ============================================== -->
        <!-- CONTRIBUTION FORM & WALL OF CONTRIBUTORS       -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            
            <!-- Donation Form Column (4 cols on lg) -->
            <div id="donate-form" class="lg:col-span-4 bg-white shadow-xl rounded-3xl border border-slate-200/90 overflow-hidden sticky top-6">
                
                <div class="bg-[#1e3a8a] text-white p-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">💳</span>
                        <h3 class="font-heading font-black text-xs uppercase tracking-wider">
                            Make a Contribution
                        </h3>
                    </div>
                    <span class="text-[10px] uppercase font-bold text-orange-300 font-heading bg-white/10 px-2 py-0.5 rounded-full">
                        80G Tax Benefit
                    </span>
                </div>

                <div class="p-6">
                    <form action="{{ route('donations.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Campaign Selection -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Select Campaign <span class="text-red-500">*</span>
                            </label>
                            <select name="campaign" x-model="selectedCampaign" required 
                                    class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans cursor-pointer">
                                @foreach($campaigns as $camp)
                                    <option value="{{ $camp['title'] }}">{{ $camp['title'] }}</option>
                                @endforeach
                            </select>
                            @error('campaign') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Contribution Amount Selector -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5 font-heading">
                                Contribution Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-4 gap-2 mb-2">
                                @foreach([500, 1000, 2500, 5000] as $preset)
                                    <button type="button" @click="customAmount = {{ $preset }}" 
                                            class="py-2 px-1 rounded-xl text-xs font-bold font-heading uppercase transition-colors cursor-pointer border" 
                                            :class="customAmount == {{ $preset }} ? 'bg-[#1e3a8a] text-white border-[#1e3a8a] shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">
                                        ₹{{ $preset }}
                                    </button>
                                @endforeach
                            </div>
                            
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-500 font-bold text-xs font-heading">₹</span>
                                <input type="number" name="amount" min="50" step="50" x-model="customAmount" required placeholder="Enter custom amount" 
                                       class="w-full pl-8 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-heading font-bold">
                            </div>
                            @error('amount') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Payment Channel -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Payment Channel
                            </label>
                            <select name="payment_method" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans cursor-pointer">
                                <option value="UPI / QR Code">UPI (GPay / PhonePe / Paytm)</option>
                                <option value="Net Banking">Net Banking (SBI / HDFC / BOB / Axis)</option>
                                <option value="Credit / Debit Card">Credit / Debit Card (Visa / MasterCard / RuPay)</option>
                                <option value="Direct College Account Transfer">Direct College Account NEFT/RTGS</option>
                            </select>
                        </div>

                        <!-- Dedication / Note -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Dedication / Message (Optional)
                            </label>
                            <input type="text" name="notes" placeholder="e.g. In honor of Class of 2018 or faculty mentors" 
                                   class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans">
                        </div>

                        <!-- Anonymous Toggle -->
                        <div class="pt-1">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_anonymous" value="1" class="rounded border-slate-300 text-[#1e3a8a] focus:ring-[#1e3a8a]">
                                <span class="text-xs text-slate-600 font-sans">Make this donation anonymous on public wall</span>
                            </label>
                        </div>

                        <!-- Submit Button with Razorpay Gateway Trigger -->
                        <div class="pt-2">
                            <button type="button" 
                                    @click="openRazorpayPayment({ 
                                        amount: customAmount, 
                                        purpose: 'giving_back', 
                                        campaign: selectedCampaign, 
                                        is_anonymous: $el.form.querySelector('input[name=is_anonymous]')?.checked, 
                                        notes: $el.form.querySelector('input[name=notes]')?.value 
                                    })"
                                    class="w-full bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                                <span>🔒</span>
                                <span>Pay Securely with Razorpay (₹<span x-text="Number(customAmount).toLocaleString('en-IN')"></span>)</span>
                            </button>
                        </div>

                        <!-- 80G Tax Benefit Callout -->
                        <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-3 text-[11px] text-amber-900 leading-normal space-y-1">
                            <div class="flex items-center gap-1.5 font-bold font-heading uppercase text-[10px]">
                                <span>🔒</span>
                                <span>80G Tax Deduction Certificate</span>
                            </div>
                            <p class="font-sans text-amber-800">
                                An official downloadable PDF receipt with your transaction reference number will be generated immediately for income tax filing.
                            </p>
                        </div>

                    </form>
                </div>

            </div>

            <!-- Wall of Contributors & User Donation History Column (8 cols on lg) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- User's Personal Donation Receipts -->
                @auth
                    @if($userDonations->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden p-6 sm:p-7">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                <div>
                                    <h3 class="font-heading font-black text-sm uppercase tracking-wide text-[#0f172a]">
                                        My Giving History &amp; 80G Receipts
                                    </h3>
                                    <p class="text-xs text-slate-500 font-sans mt-0.5">Download your verified tax deduction certificates</p>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full font-heading">
                                    {{ $userDonations->count() }} {{ Str::plural('Contribution', $userDonations->count()) }}
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach($userDonations as $userDonation)
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold text-slate-900 font-heading">{{ $userDonation->campaign }}</p>
                                            <p class="text-[11px] text-slate-500 font-sans mt-0.5">
                                                Ref: <span class="font-mono text-slate-700">{{ $userDonation->transaction_id }}</span> &bull; {{ $userDonation->created_at->format('d M, Y') }}
                                            </p>
                                            @if($userDonation->notes)
                                                <p class="text-[11px] italic text-slate-600 mt-1 font-sans">"{{ $userDonation->notes }}"</p>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 justify-between sm:justify-end flex-wrap">
                                            <span class="text-sm font-black text-emerald-700 font-heading mr-1">₹{{ number_format($userDonation->amount, 2) }}</span>
                                            <a href="{{ route('donations.whatsapp', $userDonation->id) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-800 px-3 py-1.5 rounded-xl text-xs font-bold font-heading uppercase tracking-wide shadow-xs border border-emerald-200 transition-colors" title="Send official 80G tax receipt to WhatsApp">
                                                <span>📲</span>
                                                <span>WhatsApp 80G</span>
                                            </a>
                                            <a href="{{ route('donations.receipt', $userDonation->id) }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-[#1e3a8a] hover:text-white text-slate-800 px-3 py-1.5 rounded-xl text-xs font-bold font-heading uppercase tracking-wide shadow-xs border border-slate-200 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span>PDF Receipt</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- Wall of Contributors -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden p-6 sm:p-7">
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#f97316] font-heading block">
                                Honor Roll
                            </span>
                            <h3 class="font-heading font-black text-base text-[#0f172a] uppercase tracking-wide">
                                Wall of Contributors
                            </h3>
                            <p class="text-xs text-slate-500 font-sans mt-0.5">Honoring alumni giving back to their alma mater</p>
                        </div>
                        <span class="text-xs text-slate-400 font-heading uppercase tracking-wider font-bold">
                            Live Contributions
                        </span>
                    </div>
                    
                    @if($donations->isEmpty())
                        <div class="text-center py-12">
                            <span class="text-4xl block mb-2">🤝</span>
                            <h4 class="font-heading font-black text-sm text-[#0f172a] uppercase">No Contributions Recorded Yet</h4>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto font-sans leading-relaxed">
                                Be the first alumnus to pave the way! Support student scholarships or laboratory infrastructure today.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($donations as $donation)
                                <div class="p-4 bg-slate-50/70 border border-slate-200/80 rounded-2xl flex flex-col justify-between hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start gap-2 mb-2">
                                        <div>
                                            @if($donation->is_anonymous)
                                                <h4 class="font-heading font-black text-slate-700 text-xs flex items-center gap-1.5 uppercase tracking-wide">
                                                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-[10px]">👤</span>
                                                    <span>Anonymous Donor</span>
                                                </h4>
                                                <p class="text-[10px] text-slate-400 font-sans">Proud Alumni Supporter</p>
                                            @else
                                                <h4 class="font-heading font-black text-[#0f172a] text-xs uppercase tracking-wide">{{ $donation->user->name }}</h4>
                                                <p class="text-[10px] text-slate-500 font-sans">
                                                    {{ $donation->user->department ?? optional($donation->user->profile)->department ?? 'Alumnus' }}
                                                    @if($donation->user->graduation_year) &bull; Class of {{ $donation->user->graduation_year }} @endif
                                                </p>
                                            @endif
                                        </div>
                                        <span class="font-heading font-black text-emerald-700 text-xs bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200 shrink-0">
                                            ₹{{ number_format($donation->amount) }}
                                        </span>
                                    </div>

                                    <div class="mt-2 pt-2 border-t border-slate-200/60 text-[11px] text-slate-500 flex justify-between items-center font-sans">
                                        <span class="truncate max-w-[170px] font-medium text-slate-700" title="{{ $donation->campaign }}">{{ $donation->campaign }}</span>
                                        <span class="text-[10px] text-slate-400 shrink-0">{{ $donation->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($donation->notes && !$donation->is_anonymous)
                                        <p class="text-[11px] italic text-slate-600 mt-1.5 line-clamp-1 font-sans">
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
