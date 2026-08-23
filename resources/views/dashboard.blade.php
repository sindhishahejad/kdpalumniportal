@extends('layouts.app')

@section('content')
    <!-- ============================================== -->
    <!-- 1. HERO SLIDER & WELCOME RIBBON                -->
    <!-- ============================================== -->
    <div x-data="heroSlider()" x-init="start()" class="relative w-full flex flex-col shadow-2xl">
        
        <!-- Slider Images Container here -->
        <div class="relative w-full h-[400px] md:h-[550px] overflow-hidden group">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" 
                     x-transition:enter="transition ease-out duration-1000 transform"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-1000 absolute inset-0"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 w-full h-full">
                    <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                    <!-- Premium dark gradient overlay for depth -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-black/10"></div>
                </div>
            </template>

            <!-- Elegant Dash Indicators -->
            <div class="absolute bottom-8 left-0 right-0 flex justify-center space-x-3 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goTo(index)" 
                            :class="{'w-10 bg-white shadow-[0_0_10px_rgba(255,255,255,0.8)]': activeSlide === index, 'w-4 bg-white/40 hover:bg-white/80': activeSlide !== index}"
                            class="h-1.5 rounded-full transition-all duration-500 focus:outline-none"
                            :aria-label="'Slide ' + (index + 1)"></button>
                </template>
            </div>

            <!-- Navigation Arrows -->
            <button @click="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-black/20 backdrop-blur-md border border-white/20 hover:bg-black/50 hover:scale-110 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-black/20 backdrop-blur-md border border-white/20 hover:bg-black/50 hover:scale-110 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Premium Welcome Ribbon -->
        <div class="bg-gradient-to-r from-kdp-textblue via-[#1a2f5c] to-kdp-textblue text-white text-center py-8 px-6 shadow-[0_-10px_30px_rgba(0,0,0,0.2)] border-t-2 border-kdp-orange relative z-30">
            <h2 class="text-2xl md:text-3xl font-serif font-bold mb-3 tracking-wider uppercase text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 drop-shadow-sm">Welcome to KDP Alumni Association Network, {{ Auth::user()->name ?? 'Alumnus' }}</h2>
            <p class="text-sm md:text-base max-w-4xl mx-auto font-light leading-relaxed text-gray-200">
                A world that is teeming with talent, brilliance, diversity, and adventure. A world where you can strengthen current connections, make new ones, explore myriad opportunities, and create many more to support the future of K. D. Polytechnic, Patan.
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. PREMIUM ALUMNI SERVICES GRID                -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1: Profile (Professional Working) -->
            <a href="{{ route('profile.edit') }}" class="group relative rounded-2xl shadow-md hover:shadow-2xl overflow-hidden h-72 flex flex-col justify-end transition-all duration-500 hover:-translate-y-2 border border-gray-100 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover z-0 transition-transform duration-700 group-hover:scale-110" alt="Alumni Working" />
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-textblue via-kdp-textblue/80 to-black/10 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                <div class="relative z-20 p-6 flex flex-col justify-end h-full">
                    <h3 class="font-sans font-bold text-xl mb-2 text-white transform group-hover:-translate-y-1 transition-transform duration-300">Your Alumni Profile</h3>
                    <p class="text-sm mb-5 text-blue-100 font-light leading-relaxed opacity-90 transform group-hover:-translate-y-1 transition-transform duration-300">Keep your details updated to stay connected with the KDP community.</p>
                    <div class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-white group-hover:text-orange-300 transition-colors">
                        View Profile <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Card 2: Mentorship (Guiding/Discussion) -->
            <a href="{{ route('mentorship.index') }}" class="group relative rounded-2xl shadow-md hover:shadow-2xl overflow-hidden h-72 flex flex-col justify-end transition-all duration-500 hover:-translate-y-2 border border-gray-100 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover z-0 transition-transform duration-700 group-hover:scale-110" alt="Mentorship Meeting" />
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-orange via-kdp-orange/80 to-black/10 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                <div class="relative z-20 p-6 flex flex-col justify-end h-full">
                    <h3 class="font-sans font-bold text-xl mb-2 text-white transform group-hover:-translate-y-1 transition-transform duration-300">Mentorship</h3>
                    <p class="text-sm mb-5 text-orange-50 font-light leading-relaxed opacity-90 transform group-hover:-translate-y-1 transition-transform duration-300">Guide the next generation of KDP students or seek expert advice.</p>
                    <div class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-white group-hover:text-blue-900 transition-colors">
                        Connect <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Card 3: Directory (Graduates/Networking) -->
            <a href="{{ route('alumni.index') }}" class="group relative rounded-2xl shadow-md hover:shadow-2xl overflow-hidden h-72 flex flex-col justify-end transition-all duration-500 hover:-translate-y-2 border border-gray-100 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover z-0 transition-transform duration-700 group-hover:scale-110" alt="Students Networking" />
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-textblue via-kdp-textblue/80 to-black/10 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                <div class="relative z-20 p-6 flex flex-col justify-end h-full">
                    <h3 class="font-sans font-bold text-xl mb-2 text-white transform group-hover:-translate-y-1 transition-transform duration-300">Alumni Directory</h3>
                    <p class="text-sm mb-5 text-blue-100 font-light leading-relaxed opacity-90 transform group-hover:-translate-y-1 transition-transform duration-300">Explore the directory and network with professionals across industries.</p>
                    <div class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-white group-hover:text-orange-300 transition-colors">
                        Search <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Card 4: Resource Vault (Library/Books) -->
            <a href="{{ route('resources.index') }}" class="group relative rounded-2xl shadow-md hover:shadow-2xl overflow-hidden h-72 flex flex-col justify-end transition-all duration-500 hover:-translate-y-2 border border-gray-100 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover z-0 transition-transform duration-700 group-hover:scale-110" alt="Library Books" />
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-orange via-kdp-orange/80 to-black/10 z-10 transition-opacity duration-500 group-hover:opacity-90"></div>
                <div class="relative z-20 p-6 flex flex-col justify-end h-full">
                    <h3 class="font-sans font-bold text-xl mb-2 text-white transform group-hover:-translate-y-1 transition-transform duration-300">Resource Vault</h3>
                    <p class="text-sm mb-5 text-orange-50 font-light leading-relaxed opacity-90 transform group-hover:-translate-y-1 transition-transform duration-300">Access exclusive academic materials, past papers, and learning resources.</p>
                    <div class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-white group-hover:text-blue-900 transition-colors">
                        Access <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>

        </div>
    </div>

<!-- ============================================== -->
    <!-- DYNAMIC JOB SHOWCASE SECTION                   -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mb-8">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-serif font-extrabold text-[#0f172a]">Job Showcase</h2>
                <p class="text-gray-500 mt-2">Explore the latest career opportunities shared by the KDP network.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="w-max bg-white border border-gray-200 text-[#0f172a] text-sm font-bold uppercase tracking-wider py-2.5 px-6 rounded-full hover:border-[#294c9b] hover:text-[#294c9b] transition-colors shadow-sm">
                View Job Board
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Clean White Outer Container (Matches image_bd7a90.png) -->
        <div class="bg-white p-2.5 rounded-[24px] shadow-[0_2px_15px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col lg:flex-row gap-2.5">
            
            <!-- Left Side: Solid Dark Blue Banner -->
            <div class="w-full lg:w-[40%] bg-[#0B132B] rounded-[18px] p-8 md:p-10 flex flex-col justify-center">
                <h2 class="font-serif text-3xl md:text-[32px] font-bold text-white mb-4 leading-tight">
                    Share Opportunities.<br>Hire Top Talent.
                </h2>
                <p class="text-blue-50/80 text-sm mb-10 leading-relaxed pr-4">
                    Post job openings and internships directly to the alumni network. Find qualified candidates from the prestigious KDP community.
                </p>
                
                <!-- Redirects directly to the jobs page -->
                <a href="{{ route('jobs.index') }}" class="w-max bg-white text-[#0B132B] hover:bg-gray-100 text-[11px] font-bold uppercase tracking-widest py-3.5 px-6 rounded-full transition-colors flex items-center gap-2">
                    APPLY FOR A JOB 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Right Side: 4 Slots Grid -->
            <div class="w-full lg:w-[60%] grid grid-cols-2 md:grid-cols-4 gap-2.5">
                @if(isset($showcases) && $showcases->count() > 0)
                    @foreach($showcases as $showcase)
                        <!-- Occupied Job Slot -->
                        <div class="rounded-[18px] overflow-hidden bg-[#0B132B] relative group border border-gray-100 flex flex-col justify-end min-h-[220px]">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($showcase->company) }}&background=random&color=fff&size=400" class="absolute inset-0 w-full h-full object-cover opacity-40">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0B132B] via-[#0B132B]/60 to-transparent"></div>
                            
                            <div class="relative z-10 p-5">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-green-400 mb-1 block">{{ $showcase->employment_type ?? 'Role' }}</span>
                                <h4 class="text-white text-sm font-bold truncate leading-tight">{{ $showcase->title }}</h4>
                                <p class="text-gray-300 text-xs truncate mt-1">{{ $showcase->company }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pad remaining with empty slots -->
                    @for($i = $showcases->count(); $i < 4; $i++)
                        <div class="rounded-[18px] border-[1.5px] border-dashed border-gray-200 bg-[#F9FAFB] flex items-center justify-center min-h-[220px]">
                            <span class="text-[#9CA3AF] text-[13px] font-medium">Spot Available</span>
                        </div>
                    @endfor
                @else
                    <!-- All 4 Empty Slots -->
                    @for ($i = 0; $i < 4; $i++)
                        <div class="rounded-[18px] border-[1.5px] border-dashed border-gray-200 bg-[#F9FAFB] flex items-center justify-center min-h-[220px]">
                            <span class="text-[#9CA3AF] text-[13px] font-medium">Spot Available</span>
                        </div>
                    @endfor
                @endif
            </div>
            
        </div>
    </div>

    <!-- ============================================== -->
<!-- EVENTS & GATHERINGS SECTION -->
<!-- ============================================== -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-serif font-extrabold text-[#0f172a]">Events & Gatherings</h2>
                <p class="text-gray-500 mt-2">Stay updated with the latest campus activities and reunions.</p>
            </div>
            <!-- Reverted this link back to # -->
            <a href="#" class="w-max bg-white border border-gray-200 text-[#0f172a] text-sm font-bold uppercase tracking-wider py-2.5 px-6 rounded-full hover:border-[#294c9b] hover:text-[#294c9b] transition-colors shadow-sm">
                View All Events
            </a>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col relative transition-transform hover:-translate-y-1 duration-300">
                    
                    <!-- Image Wrapper -->
                    <div class="relative h-52 w-full rounded-t-2xl overflow-hidden bg-gray-200">
                        @if($event->image_path)
                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <!-- Placeholder if no image -->
                            <div class="w-full h-full bg-gradient-to-br from-blue-900 to-blue-700"></div>
                        @endif

                        <!-- Overlapping Date Badge -->
                        <div class="absolute -bottom-6 right-6 bg-white rounded-xl shadow-lg border border-gray-100 px-4 py-2 flex flex-col items-center justify-center min-w-[70px]">
                            <span class="text-[#f97316] text-[10px] font-extrabold uppercase tracking-widest">{{ $event->event_date->format('M') }}</span>
                            <span class="text-2xl font-black text-[#0f172a] leading-none mt-0.5">{{ $event->event_date->format('d') }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-8 pt-10 flex-1 flex flex-col">
                        
                        <!-- Dynamic Category Tag -->
                        @php
                            $isWorkshop = strtolower($event->category) === 'workshop';
                            $tagBg = $isWorkshop ? 'bg-blue-50' : 'bg-orange-50';
                            $tagText = $isWorkshop ? 'text-blue-600' : 'text-[#f97316]';
                        @endphp
                        <span class="w-max {{ $tagBg }} {{ $tagText }} text-[10px] font-extrabold uppercase tracking-widest py-1.5 px-3 rounded-full mb-4">
                            {{ $event->category }}
                        </span>

                        <h3 class="text-xl font-serif font-bold text-[#0f172a] mb-4 leading-snug">
                            {{ $event->title }}
                        </h3>

                        <div class="flex items-center text-gray-500 text-sm mb-8 font-medium">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $event->time_display }}
                        </div>

                        <!-- Footer Link -->
                        <div class="mt-auto">
                            <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center text-[#294c9b] font-bold text-sm tracking-wide uppercase hover:text-blue-800 transition-colors group">
                                View Details 
                                <span class="ml-2 transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">No upcoming events scheduled right now.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

    <!-- ============================================== -->
    <!-- 5. SMART I-CARD BANNER                         -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-serif font-extrabold text-[#0f172a]">Alumni Smart Card</h2>
                <p class="text-gray-500 mt-2">Access your official digital identity and unlock exclusive network privileges.</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row shadow-2xl rounded-3xl overflow-hidden bg-white transform transition-transform hover:-translate-y-1 duration-500 border border-gray-100">     
            <!-- Left Side: Content & CTA -->
            <div class="bg-gradient-to-br from-kdp-textblue via-[#1a2f5c] to-kdp-textblue text-white p-10 md:p-14 md:w-1/2 flex flex-col justify-center border-b-4 md:border-b-0 md:border-r-4 border-kdp-orange relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] mix-blend-overlay"></div>
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 mb-6 shadow-sm">
                        <svg class="w-4 h-4 text-kdp-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        <span class="text-xs font-bold uppercase tracking-widest text-orange-50">Digital Identity</span>
                    </div>
                    
                    <h3 class="font-serif text-3xl md:text-4xl font-extrabold mb-5 uppercase tracking-wide drop-shadow-md leading-tight">
                        Connecting Generations of Excellence
                    </h3>
                    
                    <p class="text-base mb-10 text-blue-100 font-light leading-relaxed max-w-md">
                        Unlock a world of benefits with your official Alumni Smart Card. Gain seamless campus access, library privileges, and exclusive invitations to global KDP events.
                    </p>
                    
                    <a href="{{ route('id-card.show') }}" class="group inline-flex justify-center items-center bg-kdp-orange text-white text-sm font-bold py-4 px-8 rounded-full shadow-[0_0_20px_rgba(234,88,12,0.4)] hover:shadow-[0_0_30px_rgba(234,88,12,0.6)] hover:bg-orange-600 transition-all uppercase tracking-wider relative z-10 w-max">
                        Get Your Smart Card Now
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
            
            <!-- Right Side: Floating Glassmorphic ID Card Mockup -->
            <div class="md:w-1/2 min-h-[350px] relative overflow-hidden bg-gray-900 flex items-center justify-center p-8 group">
                <img src="{{ asset('images/three.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:scale-105 group-hover:opacity-40 transition-all duration-1000 blur-sm" alt="Campus Background">
                
                <div class="relative z-10 w-full max-w-sm bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden transform rotate-3 md:rotate-6 hover:rotate-0 hover:scale-105 transition-all duration-500 flex flex-col">
                    
                    <div class="bg-gradient-to-r from-[#1a2f5c] to-kdp-textblue p-5 flex items-center justify-between border-b border-white/10">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center font-bold text-kdp-textblue text-sm shadow-inner">
                            KDP
                        </div>
                        <div class="text-right text-white">
                            <div class="text-[10px] uppercase tracking-widest opacity-80 mb-0.5">Alumni Association</div>
                            <div class="font-bold text-base tracking-wide">SMART I-CARD</div>
                        </div>
                    </div>
                    
                    <div class="p-6 flex items-center gap-5 relative bg-gradient-to-br from-white/5 to-transparent">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Alumnus') }}&background=ea580c&color=fff&size=120" class="w-20 h-20 rounded-xl shadow-lg border-2 border-white/30">
                            <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 border-2 border-[#203154] rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        
                        <div class="text-white flex-1">
                            <h4 class="font-bold text-xl leading-tight mb-1">{{ Auth::user()->name ?? 'Alumnus Name' }}</h4>
                            <p class="text-xs text-blue-200 font-mono tracking-wider mb-2">ID: KDP-{{ str_pad(Auth::user()->id ?? '128', 4, '0', STR_PAD_LEFT) }}</p>
                            <span class="inline-block px-2 py-1 bg-white/20 rounded text-[10px] font-bold uppercase tracking-widest border border-white/10">
                                Verified Member
                            </span>
                        </div>
                    </div>
                    
                    <div class="bg-black/20 p-3 px-6 flex justify-between items-center">
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest">Tap or Scan for Access</p>
                        <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 6. MEMORIES & GALLERY SECTION                  -->
    <!-- ============================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <div>
                <h2 class="font-serif text-4xl font-extrabold text-gray-900 tracking-tight">Memories & Gallery</h2>
                <p class="text-gray-500 mt-2 font-medium">Relive the golden days at K. D. Polytechnic.</p>
            </div>
            <a href="{{ route('gallery.index') }}" class="mt-4 md:mt-0 text-sm font-bold text-kdp-textblue border-2 border-gray-200 bg-white px-6 py-2.5 rounded-full shadow-sm hover:border-kdp-textblue hover:bg-kdp-textblue hover:text-white transition-all tracking-wide uppercase">
                Explore Full Gallery
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 auto-rows-[250px]">
            
            <div class="md:col-span-2 md:row-span-2 relative group rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="{{ asset('images/four.png') }}" alt="Graduation Day" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-textblue/90 via-kdp-textblue/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-8">
                    <h3 class="text-white font-bold text-2xl drop-shadow-md transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">Annual Convocation Ceremony</h3>
                    <p class="text-blue-100 text-sm mt-1 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">Celebrating the Batch of 2025</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="{{ asset('images/one.jpg') }}" alt="Main Gate" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-orange/90 via-kdp-orange/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <h3 class="text-white font-bold text-lg drop-shadow-md transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">KDP Main Campus</h3>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=400&q=80" alt="Campus Life" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-textblue/90 via-kdp-textblue/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <h3 class="text-white font-bold text-lg drop-shadow-md transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">Tech Fest Hackathon</h3>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=400&q=80" alt="Alumni Meet" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-orange/90 via-kdp-orange/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <h3 class="text-white font-bold text-lg drop-shadow-md transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">Alumni Reunion</h3>
                </div>
            </div>

            <div class="md:col-span-2 relative group rounded-2xl overflow-hidden cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" alt="Students Studying" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-kdp-textblue/90 via-kdp-textblue/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <h3 class="text-white font-bold text-xl drop-shadow-md transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">Engineering Workshop</h3>
                    <p class="text-blue-100 text-sm mt-1 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">Department of Computer Engineering</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================== -->
    <!-- 7. STAY CONNECTED / SOCIAL MEDIA WIDGET        -->
    <!-- ============================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 mb-8">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-[#0f2042] via-kdp-textblue to-[#1a2f5c] shadow-2xl border border-blue-900/50">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-kdp-orange/10 rounded-full blur-3xl transform -translate-x-1/3 translate-y-1/3"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12 p-10 md:p-16">
                
                <div class="w-full lg:w-1/2 text-center lg:text-left flex flex-col justify-center">
                    <div class="mb-4">
                        <span class="inline-block bg-white/10 backdrop-blur-sm text-orange-400 text-[10px] font-extrabold uppercase tracking-widest py-1.5 px-4 rounded-full border border-white/20">
                            Join The Inner Circle
                        </span>
                    </div>
                    <h2 class="font-serif text-3xl md:text-4xl font-extrabold text-white mb-6 drop-shadow-sm tracking-wide">
                        Stay Connected
                    </h2>
                    <p class="text-blue-100 text-sm md:text-base font-light leading-relaxed max-w-md mx-auto lg:mx-0">
                        Follow K. D. Polytechnic across our official social channels to get the latest campus news, job opportunities, and upcoming reunion event details straight to your feed.
                    </p>
                </div>

                <div class="w-full lg:w-1/2 grid grid-cols-2 gap-4">
                    
                    <a href="#" class="group relative bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl overflow-hidden hover:border-transparent transition-all duration-300 text-center flex flex-col items-center justify-center transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-[#0A66C2] opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <svg class="w-8 h-8 text-white mb-3 transform group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            <span class="text-white font-bold text-sm tracking-wide">LinkedIn</span>
                            <span class="text-white/60 group-hover:text-white/90 text-xs mt-1 transition-colors">Professional Network</span>
                        </div>
                    </a>

                    <a href="#" class="group relative bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl overflow-hidden hover:border-transparent transition-all duration-300 text-center flex flex-col items-center justify-center transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <svg class="w-8 h-8 text-white mb-3 transform group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span class="text-white font-bold text-sm tracking-wide">Instagram</span>
                            <span class="text-white/60 group-hover:text-white/90 text-xs mt-1 transition-colors">Campus Memories</span>
                        </div>
                    </a>

                    <a href="#" class="group relative bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl overflow-hidden hover:border-transparent transition-all duration-300 text-center flex flex-col items-center justify-center transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-[#1877F2] opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <svg class="w-8 h-8 text-white mb-3 transform group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            <span class="text-white font-bold text-sm tracking-wide">Facebook</span>
                            <span class="text-white/60 group-hover:text-white/90 text-xs mt-1 transition-colors">Alumni Groups</span>
                        </div>
                    </a>

                    <a href="#" class="group relative bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl overflow-hidden hover:border-transparent transition-all duration-300 text-center flex flex-col items-center justify-center transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <svg class="w-8 h-8 text-white mb-3 transform group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span class="text-white font-bold text-sm tracking-wide">X (Twitter)</span>
                            <span class="text-white/60 group-hover:text-white/90 text-xs mt-1 transition-colors">Daily Updates</span>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </section>
    
    <!-- Alpine Logic for Hero Slider -->
    <script>
        function heroSlider() {
            return {
                activeSlide: 0,
                interval: null,
                slides: [
                    { image: '{{ asset("images/one.jpg") }}', title: 'KDP Campus Main Gate' },
                    { image: '{{ asset("images/two.jpg") }}', title: 'Academic Block' },
                    { image: '{{ asset("images/three.jpg") }}', title: 'Campus Greenery' },
                    { image: '{{ asset("images/four.png") }}', title: 'Computer Engineering Department' }
                    ],
                start() {
                    this.interval = setInterval(() => {
                        this.next();
                    }, 5000);
                },
                next() {
                    this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
                    this.resetInterval();
                },
                prev() {
                    this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1;
                    this.resetInterval();
                },
                goTo(index) {
                    this.activeSlide = index;
                    this.resetInterval();
                },
                resetInterval() {
                    clearInterval(this.interval);
                    this.start();
                }
            }
        }
    </script>
@endsection