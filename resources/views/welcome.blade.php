@extends('layouts.app')

@section('content')
    <!-- ============================================== -->
    <!-- 1. HERO SECTION WITH OFFICIAL BRANDING         -->
    <!-- ============================================== -->
    <div class="relative min-h-[550px] lg:min-h-[620px] flex items-center justify-center overflow-hidden bg-[#0f2042]">
        
        <!-- Background Campus Image & Deep Blue Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/one.jpg') }}" alt="K.D. Polytechnic Campus" class="w-full h-full object-cover opacity-50 mix-blend-luminosity" />
            <div class="absolute inset-0 bg-gradient-to-b from-[#1e3a8a]/90 via-[#0f2042]/85 to-[#0b132b]"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto py-16">
            
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2 mb-6 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-[#f97316] animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-orange-200 font-heading">
                    Official Alumni &amp; Heritage Portal
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-xl font-heading leading-tight uppercase">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-yellow-200">
                    K. D. Polytechnic, Patan
                </span>
            </h1>
            
            <p class="mt-4 text-base sm:text-xl text-blue-100 max-w-3xl mx-auto font-normal leading-relaxed drop-shadow-md">
                Empowering diploma engineers since 1961. Connecting six decades of alumni excellence across government, industry, and global engineering frontiers.
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="group inline-flex justify-center items-center bg-[#f97316] hover:bg-orange-600 text-white font-bold text-sm sm:text-base py-3.5 px-8 rounded-full shadow-[0_0_20px_rgba(249,115,22,0.4)] hover:shadow-[0_0_30px_rgba(249,115,22,0.6)] transition-all transform hover:-translate-y-0.5 tracking-wider uppercase font-heading">
                        Go to My Dashboard
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('alumni.index') }}" class="group inline-flex justify-center items-center bg-white/10 backdrop-blur-md border border-white/30 hover:bg-white hover:text-[#1e3a8a] text-white font-bold text-sm sm:text-base py-3.5 px-8 rounded-full shadow-lg transition-all transform hover:-translate-y-0.5 tracking-wider uppercase font-heading">
                        🎓 Alumni Directory &rarr;
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group inline-flex justify-center items-center bg-[#f97316] hover:bg-orange-600 text-white font-bold text-sm sm:text-base py-3.5 px-8 rounded-full shadow-[0_0_20px_rgba(249,115,22,0.4)] hover:shadow-[0_0_30px_rgba(249,115,22,0.6)] transition-all transform hover:-translate-y-0.5 tracking-wider uppercase font-heading">
                        Join Alumni Network
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    
                    <a href="{{ route('login') }}" class="group inline-flex justify-center items-center bg-white/10 backdrop-blur-md border border-white/30 hover:bg-white hover:text-[#1e3a8a] text-white font-bold text-sm sm:text-base py-3.5 px-8 rounded-full shadow-lg transition-all transform hover:-translate-y-0.5 tracking-wider uppercase font-heading">
                        Member Login &rarr;
                    </a>
                @endauth
            </div>
            
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. OFFICIAL INSTITUTIONAL ABOUT OVERVIEW       -->
    <!-- ============================================== -->
    <section class="py-14 bg-white font-sans border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <span class="inline-block px-3 py-1 bg-blue-50 text-[#1e3a8a] text-xs font-extrabold uppercase tracking-widest rounded-full mb-3 font-heading">
                    Government Polytechnic • Established 1961
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1e3a8a] font-heading tracking-tight uppercase leading-snug">
                    Welcome to K. D. Polytechnic, Patan
                </h2>
                <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full my-3"></div>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed text-justify sm:text-center mt-3">
                    Kilachand Devchand Polytechnic was established in the year 1961 at Patan, Gujarat, India, with the objective of providing quality technical education to students of North Gujarat. The institute is a state government polytechnic and has played a significant role in producing skilled diploma engineers to meet the needs of industry and society. Affiliated with Gujarat Technological University (GTU), Ahmedabad, ensuring standardized academic practices and quality technical education.
                </p>
                <div class="mt-6 flex flex-wrap justify-center items-center gap-6 text-xs font-semibold text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> AICTE Approved</span>
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> Affiliated to GTU</span>
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> CTE Gujarat Supervised</span>
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> 60+ Years of Academic Excellence</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!-- 3. DUAL LIVE FEED (Notice Board & Activities)  -->
    <!-- ============================================== -->
    <x-kdp-notice-events :notices="$notices ?? collect()" :events="$events ?? collect()" />

    <!-- GTU Academic Calendar & Circulars Widget Strip -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <x-kdp-gtu-calendar-widget :schedules="$schedules ?? collect()" />
    </div>

    <!-- ============================================== -->
    <!-- 4. ENGINEERING DEPARTMENTS SHOWCASE            -->
    <!-- ============================================== -->
    <x-kdp-departments />

    <!-- ============================================== -->
    <!-- 5. CORPORATE RECRUITERS & TPO WALL             -->
    <!-- ============================================== -->
    <x-kdp-recruiters :recruiters="$recruiters ?? collect()" />

    <!-- ============================================== -->
    <!-- 6. ALUMNI SERVICES HIGHLIGHT                   -->
    <!-- ============================================== -->
    <section class="py-16 bg-slate-50 font-sans border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block px-3 py-1 bg-orange-100 text-[#f97316] text-[11px] font-extrabold uppercase tracking-widest rounded-full mb-2 font-heading">
                    Network Privileges
                </span>
                <h3 class="font-heading text-2xl sm:text-3xl text-[#1e3a8a] font-extrabold uppercase tracking-tight">
                    Alumni Portal Services
                </h3>
                <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full mt-2"></div>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                    Exclusive digital tools, career advancement pathways, and heritage resources designed specifically for KDP engineers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Directory Card -->
                <a href="{{ route('alumni.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🎓
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Find Alumni</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Search and connect with the global network of KDP graduates filtered by batch, city, and branch.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Search Directory &rarr;
                    </span>
                </a>

                <!-- Job Board Card -->
                <a href="{{ route('jobs.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            💼
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Career &amp; Jobs</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Explore engineering job postings, submit resumes directly to alumni recruiters, and refer peers.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        View Jobs &rarr;
                    </span>
                </a>

                <!-- Smart ID Card -->
                <a href="{{ route('id-card.show') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🪪
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Digital Smart ID</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Instant verified alumni card with live QR code for campus entry, library access, and reunions.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Get I-Card &rarr;
                    </span>
                </a>

                <!-- Mentorship -->
                <a href="{{ route('mentorship.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#f97316] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🤝
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">1-on-1 Mentorship</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Guide younger diploma juniors in D2D degree admissions, competitive exams, and interview prep.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Connect Now &rarr;
                    </span>
                </a>

                <!-- AI Academic Counselor -->
                <a href="{{ route('chatbot.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🤖
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">KDP Mitra (AI Counselor)</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">24/7 AI guide for GTU old papers (PYQs), exam results, ACPC D2D admissions, and campus services.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Ask KDP Mitra &rarr;
                    </span>
                </a>

                <!-- Tool Bank -->
                <a href="{{ route('tools.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🛠️
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Book &amp; Tool Bank</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Claim free mini-drafters, Casio scientific calculators, textbooks, and Arduino kits passed on by alumni.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Explore Tool Bank &rarr;
                    </span>
                </a>

                <!-- Student Pitch Deck -->
                <a href="{{ route('pitches.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            💡
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Student Pitch Deck</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Support student engineering prototypes, Robocon rigs, and startup projects with alumni seed grants.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        View Innovation Pitches &rarr;
                    </span>
                </a>

                <!-- Giving Back Hub -->
                <a href="{{ route('donations.index') }}" class="group bg-white rounded-2xl p-6 shadow-xs hover:shadow-xl transition-all duration-300 border border-slate-200/80 hover:border-[#1e3a8a] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            🎁
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 group-hover:text-[#1e3a8a] mb-2">Giving Back Hub</h4>
                        <p class="text-slate-600 text-xs leading-relaxed">Contribute to student scholarship funds and computer lab upgrades with instant 80G tax exemption receipts.</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] group-hover:text-[#f97316] mt-4 flex items-center gap-1 font-heading">
                        Support Alma Mater &rarr;
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!-- 6. STUDENT & ALUMNI TESTIMONIALS (From Official Site) -->
    <!-- ============================================== -->
    <section class="py-16 bg-white font-sans border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-3 py-1 bg-blue-50 text-[#1e3a8a] text-[11px] font-extrabold uppercase tracking-widest rounded-full mb-2 font-heading">
                    Voices of Excellence
                </span>
                <h3 class="font-heading text-2xl sm:text-3xl text-[#1e3a8a] font-extrabold uppercase tracking-tight">
                    Student &amp; Alumni Testimonials
                </h3>
                <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full mt-2"></div>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                    What our students and graduates say about learning, culture, and faculty mentorship at K. D. Polytechnic, Patan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed mb-6">
                        "Well-structured lectures and in-depth explanations of core concepts. Practical demonstrations and problem-solving sessions helped in building strong technical understanding."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/60">
                        <div class="w-10 h-10 rounded-full bg-[#1e3a8a] text-white flex items-center justify-center font-bold text-sm font-heading">
                            S
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900 font-heading">Suraj</div>
                            <div class="text-xs text-[#f97316] font-semibold">Mechanical Engineering • Batch 2025</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed mb-6">
                        "Excellent teaching methods with strong focus on practical knowledge. Concepts were explained clearly with real-world examples, making subjects easy to understand and apply confidently."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/60">
                        <div class="w-10 h-10 rounded-full bg-[#f97316] text-white flex items-center justify-center font-bold text-sm font-heading">
                            N
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900 font-heading">Namra</div>
                            <div class="text-xs text-[#f97316] font-semibold">Computer Engineering • Batch 2024</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed mb-6">
                        "Great learning environment with clear, well-structured explanations. Every topic was taught in a way that built confidence and made concepts easy to understand and apply effectively."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/60">
                        <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm font-heading">
                            D
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900 font-heading">Daksh</div>
                            <div class="text-xs text-[#f97316] font-semibold">Civil Engineering • Batch 2025</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('stories.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-50 text-[#1e3a8a] hover:bg-[#1e3a8a] hover:text-white font-bold text-xs uppercase tracking-wider transition-all font-heading border border-blue-200 shadow-2xs">
                    <span>🌟</span> Read More Alumni Success Stories &amp; Spotlights &rarr;
                </a>
            </div>
        </div>
    </section>
@endsection
