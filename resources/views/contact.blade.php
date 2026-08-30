@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h1 class="text-3xl font-serif font-bold text-[#1C3661] mb-2">Get in Touch</h1>
        <p class="text-gray-500 mb-8">Have a question or want to reach out to the KDP Alumni administration? Send us a message below.</p>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Your Name</label>
                <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Subject</label>
                <input type="text" name="subject" required placeholder="e.g. Inquiry regarding transcript verification" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                <textarea name="message" rows="5" required placeholder="Write your message here..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>

            <button type="submit" class="w-full bg-[#1C3661] hover:bg-blue-900 text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm uppercase tracking-wider shadow-sm">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection