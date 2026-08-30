@extends('layouts.app')

@section('content')
<div class="relative w-full h-64 flex items-center justify-center overflow-hidden shadow-md">
    <img src="https://picsum.photos/id/1025/1920/600" class="absolute inset-0 w-full h-full object-cover" alt="Profile Background">
    <div class="absolute inset-0 bg-gradient-to-r from-[#1C3661] via-[#1C3661]/90 to-[#8b0000]/90"></div>
    
    <div class="relative z-10 text-center text-white px-4 mt-8">
        <h1 class="font-serif text-3xl md:text-5xl font-bold mb-2 tracking-wide">{{ $user->name }}</h1>
        <p class="text-sm md:text-lg font-light text-gray-200">
            {{ ucfirst($user->role) }} • {{ $user->department ?? 'N/A' }}
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 pb-16" x-data="{ openMessageModal: false }">
    <div class="bg-white shadow-xl sm:rounded-sm border-t-4 border-[#8b0000] p-8 md:p-10 space-y-8">
        
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-sm flex items-center">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Academic Details -->
        <div>
            <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-4">Academic Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="font-semibold text-gray-600">Degree:</span> {{ $user->degree ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Department:</span> {{ $user->department ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Year of Joining:</span> {{ $user->year_joining ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Graduation Year:</span> {{ $user->graduation_year ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Professional Details -->
        <div>
            <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-4">Professional Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="font-semibold text-gray-600">Company:</span> {{ $user->company ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Designation:</span> {{ $user->designation ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Industry:</span> {{ $user->work_industry ?? 'N/A' }}</div>
                <div><span class="font-semibold text-gray-600">Skills:</span> {{ $user->skills ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Contact Details & Action -->
        <div>
            <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm items-center mb-6">
                <!-- ✨ Privacy Masked Email ✨ -->
                <div>
                    <span class="font-semibold text-gray-600">Email:</span> 
                    @if(optional($user->profile)->is_email_public || auth()->user()->role === 'admin')
                        <a href="mailto:{{ $user->email }}" class="text-[#8b0000] hover:underline">{{ $user->email }}</a>
                    @else
                        <span class="text-gray-400 italic">Hidden by user</span>
                    @endif
                </div>
                
                <!-- ✨ Privacy Masked Phone ✨ -->
                <div>
                    <span class="font-semibold text-gray-600">Phone:</span> 
                    @if(optional($user->profile)->is_phone_public || auth()->user()->role === 'admin')
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="text-[#8b0000] hover:underline">{{ $user->phone }}</a>
                        @else
                            <span class="text-gray-800">Not Provided</span>
                        @endif
                    @else
                        <span class="text-gray-400 italic">Hidden by user</span>
                    @endif
                </div>
            </div>
            
            <button @click="openMessageModal = true" class="inline-flex items-center bg-[#8b0000] hover:bg-[#6b0d0d] text-white font-bold py-2.5 px-6 rounded-sm shadow-md transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Message
            </button>
        </div>

        <div class="pt-4 flex justify-start border-t border-gray-100">
            <a href="{{ route('alumni.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-sm text-sm transition-colors">
                &larr; Back to Directory
            </a>
        </div>
    </div>

    <!-- Message Modal Popup -->
    <div x-show="openMessageModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <div @click.outside="openMessageModal = false" class="bg-white rounded-sm shadow-xl max-w-lg w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-serif font-bold text-[#1C3661]">Send Message to {{ $user->name }}</h3>
                <button @click="openMessageModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            
            <form action="{{ route('alumni.message', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Your Message</label>
                    <textarea name="message" rows="4" required placeholder="Write your message here..." class="w-full border border-gray-300 rounded-sm p-3 text-sm focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000]"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="openMessageModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-sm text-sm font-medium">Cancel</button>
                    <button type="submit" class="bg-[#8b0000] hover:bg-[#6b0d0d] text-white px-6 py-2 rounded-sm text-sm font-bold">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection