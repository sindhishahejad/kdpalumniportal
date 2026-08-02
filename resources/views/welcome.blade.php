@extends('layouts.app')

@section('content')
    <!-- ============================================== -->
    <!-- HERO / LANDING SECTION                         -->
    <!-- ============================================== -->
    <div class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden -mt-[2px]">
        
        <!-- Background Image & Overlays -->
        <div class="absolute inset-0 z-0 bg-[#0f2042]">
            <!-- Using your local campus image (one.jpg). If you prefer, swap to two.jpg or three.jpg -->
            <img src="{{ asset('images/one.jpg') }}" alt="K.D. Polytechnic Campus" class="w-full h-full object-cover opacity-60 mix-blend-overlay" />
            
            <!-- Gradient Overlays for perfect text readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#0f2042]/80 via-transparent to-[#0f2042]/90"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0f2042]/70 to-transparent"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto mt-12 md:mt-20">
            
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2 mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-orange-50">Welcome to K. D. Polytechnic</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 tracking-tight drop-shadow-2xl font-serif leading-tight">
                Relive, Reconnect, <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-300">Reunite.</span>
            </h1>
            
            <p class="mt-6 text-lg md:text-2xl text-blue-100 max-w-3xl mx-auto font-light leading-relaxed drop-shadow-md">
                Empowering the KDP global community. Building bridges between the past, present, and future.
            </p>
            
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('register') }}" class="group inline-flex justify-center items-center bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm md:text-base py-4 px-10 rounded-full shadow-[0_0_20px_rgba(249,115,22,0.4)] hover:shadow-[0_0_30px_rgba(249,115,22,0.6)] transition-all transform hover:-translate-y-1 tracking-wider uppercase w-full sm:w-auto">
                    Join the Network
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                
                <a href="#about" class="group inline-flex justify-center items-center bg-white/10 backdrop-blur-md border border-white/30 hover:bg-white hover:text-[#0f2042] text-white font-bold text-sm md:text-base py-4 px-10 rounded-full shadow-lg transition-all transform hover:-translate-y-1 tracking-wider uppercase w-full sm:w-auto">
                    Learn More
                </a>
            </div>
            
        </div>
        
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-10 animate-bounce hidden md:block">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
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
