@extends('layouts.app')

@section('content')
    <!-- Hero Section here -->
    <header class="relative w-full h-[600px] flex items-center justify-center bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/1920x600/?university,campus');">
        <div class="absolute inset-0 bg-brand-navy bg-opacity-60 mix-blend-multiply"></div>
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h2 class="font-serif text-5xl md:text-6xl text-white font-bold mb-6 leading-tight drop-shadow-xl">Relive, Reconnect, Reunite</h2>
            <p class="text-lg md:text-xl text-gray-200 mb-10 font-light drop-shadow-md">Empowering the KDP global community. Building bridges between the past, present, and future.</p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                @auth
                    <a href="{{ route('profile.edit') }}" class="bg-brand-gold text-brand-navy px-8 py-3.5 text-base font-bold rounded-sm shadow-[0_4px_14px_0_rgba(212,175,55,0.39)] hover:bg-yellow-500 transition-all">Update Profile</a>
                @else
                    <a href="{{ route('register') }}" class="bg-brand-gold text-brand-navy px-8 py-3.5 text-base font-bold rounded-sm shadow-[0_4px_14px_0_rgba(212,175,55,0.39)] hover:bg-yellow-500 transition-all">Join the Network</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- News Ticker -->
    <div class="w-full bg-brand-maroon text-white overflow-hidden py-2.5 relative flex items-center shadow-inner">
        <div class="bg-brand-maroon absolute left-0 z-10 px-4 font-bold text-sm h-full flex items-center shadow-[4px_0_10px_rgba(128,0,0,1)] uppercase tracking-wider">Alerts</div>
        <div class="whitespace-nowrap animate-marquee flex text-sm font-medium ml-24">
            <span class="mx-6">Registration for the Annual Global Meet 2026 is now officially open!</span> •
            <span class="mx-6">Contribute to the KDP Alumni Endowment Fund to support incoming scholars.</span> •
        </div>
    </div>

    <!-- Alumni Services Grid -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="font-serif text-4xl text-brand-navy font-bold mb-4">Alumni Services</h3>
                <div class="h-1 w-20 bg-brand-gold mx-auto rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Directory Card -->
                <a href="{{ route('alumni.index') }}" class="group bg-white rounded-md p-8 shadow-sm hover:shadow-xl transition-all duration-300 border-t-4 border-brand-navy">
                    <h4 class="font-serif text-xl font-bold text-gray-900 mb-2">Find Alumni</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Search and connect with the global network of KDP graduates across industries.</p>
                </a>

                <!-- Job Board Card -->
                <a href="{{ route('jobs.index') }}" class="group bg-white rounded-md p-8 shadow-sm hover:shadow-xl transition-all duration-300 border-t-4 border-brand-navy">
                    <h4 class="font-serif text-xl font-bold text-gray-900 mb-2">Careers & Opportunities</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Explore exclusive job postings, refer peers, and hire top-tier talent from the community.</p>
                </a>

                <!-- Resource Vault -->
                <a href="{{ route('resources.index') }}" class="group bg-white rounded-md p-8 shadow-sm hover:shadow-xl transition-all duration-300 border-t-4 border-brand-navy">
                    <h4 class="font-serif text-xl font-bold text-gray-900 mb-2">MOOCs & Exam Papers</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Access historical examination archives and curated continuing education courses.</p>
                </a>

                <!-- Mentorship -->
                <a href="{{ route('mentorship.index') }}" class="group bg-white rounded-md p-8 shadow-sm hover:shadow-xl transition-all duration-300 border-t-4 border-brand-navy">
                    <h4 class="font-serif text-xl font-bold text-gray-900 mb-2">Diploma-to-Degree</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Guide younger alumni transitioning from diploma studies to advanced university degrees.</p>
                </a>
            </div>
        </div>
    </section>
@endsection
