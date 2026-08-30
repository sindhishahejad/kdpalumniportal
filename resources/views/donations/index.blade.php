@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-[#1C3661] to-[#8b0000] text-white p-8 rounded-sm shadow-xl mb-10 flex flex-col md:flex-row justify-between items-center">
        <div>
            <h1 class="text-3xl font-serif font-bold mb-2">Giving Back to K.D. Polytechnic</h1>
            <p class="text-gray-200 text-sm max-w-2xl">Support future generations of engineers by contributing to scholarship funds, lab equipment upgrades, and campus infrastructure.</p>
        </div>
        <div class="mt-6 md:mt-0 bg-white/10 backdrop-blur-md p-4 rounded-sm border border-white/20 text-center">
            <span class="block text-xs uppercase tracking-wider text-gray-300">Total Funds Raised</span>
            <span class="text-3xl font-black text-white">₹{{ number_format($totalRaised, 2) }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-sm flex items-center">
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Donation Form Column -->
        <div class="lg:col-span-1 bg-white shadow-xl sm:rounded-sm border-t-4 border-[#8b0000] p-6">
            <h3 class="text-xl font-serif font-bold text-[#1C3661] mb-4">Make a Contribution</h3>
            
            <form action="{{ route('donations.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Select Campaign *</label>
                    <select name="campaign" required class="w-full border border-gray-300 rounded-sm py-2 px-3 text-sm focus:outline-none focus:border-[#8b0000]">
                        <option value="Student Scholarship Fund">Student Scholarship Fund</option>
                        <option value="Computer Engineering Lab Upgrade">Computer Engineering Lab Upgrade</option>
                        <option value="Library & Resource Center">Library & Resource Center</option>
                        <option value="General Development Fund">General Development Fund</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Donation Amount (₹) *</label>
                    <input type="number" name="amount" min="100" step="50" required placeholder="5000" class="w-full border border-gray-300 rounded-sm py-2 px-3 text-sm focus:outline-none focus:border-[#8b0000]">
                </div>

                <button type="submit" class="w-full bg-[#8b0000] hover:bg-[#6b0d0d] text-white font-bold py-3 px-4 rounded-sm shadow transition-colors text-sm uppercase tracking-wider">
                    Proceed to Donate
                </button>
            </form>
        </div>

        <!-- Recent Donors & History Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Recent Wall of Contributors -->
            <div class="bg-white shadow-xl sm:rounded-sm border-t-4 border-[#1C3661] p-6">
                <h3 class="text-xl font-serif font-bold text-[#1C3661] mb-4">Wall of Contributors</h3>
                
                @if($donations->isEmpty())
                    <p class="text-gray-500 text-sm">No donations recorded yet. Be the first to contribute!</p>
                @else
                    <div class="space-y-3">
                        @foreach($donations as $donation)
                            <div class="flex justify-between items-center p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $donation->user->name }}</h4>
                                    <p class="text-xs text-gray-500">Campaign: {{ $donation->campaign }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-green-700 text-sm">₹{{ number_format($donation->amount, 2) }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $donation->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection