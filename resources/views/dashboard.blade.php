@extends('layouts.app')

@section('content')
    <!-- 1. Hero Image & Welcome Ribbon (IITDAA Style) -->
    <div class="relative w-full">
        <img src="https://picsum.photos/id/1073/1920/500" class="w-full h-[350px] md:h-[500px] object-cover" alt="Alumni Event">
        <div class="bg-brand-maroon text-white text-center py-6 px-4 shadow-md">
            <h2 class="text-xl md:text-2xl font-bold mb-2 tracking-wide">Welcome to KDP Alumni Association Network, {{ Auth::user()->name ?? 'Alumnus' }}</h2>
            <p class="text-sm max-w-4xl mx-auto font-light leading-relaxed">
                A world that is teeming with talent, brilliance, diversity, and adventure. A world where you can strengthen current connections, make new ones, explore myriad opportunities, and create many more.
            </p>
        </div>
    </div>

    <!-- 2. The 4-Card Action Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1: Profile -->
            <div class="bg-brand-navy text-white rounded-sm shadow-md overflow-hidden relative h-56 flex flex-col justify-end group transition-transform duration-300 hover:-translate-y-1">
                <img src="https://picsum.photos/id/1011/400/300" class="opacity-30 group-hover:opacity-40 transition-opacity w-full h-full object-cover absolute inset-0 z-0" alt="Profile Background" />
                <div class="relative z-10 p-5 bg-gradient-to-t from-brand-navy via-brand-navy/80 to-transparent h-full flex flex-col justify-end">
                    <h3 class="font-serif font-bold text-lg mb-1">Your Alumni Profile</h3>
                    <p class="text-xs mb-4 text-gray-200">Stay Connected with Your Alumni Community by Keeping Your Profile Updated.</p>
                    <a href="{{ route('profile.edit') }}" class="bg-white text-brand-navy text-xs font-bold py-2 px-5 inline-block w-max rounded-sm shadow-sm hover:bg-gray-100">VIEW</a>
                </div>
            </div>

            <!-- Card 2: Batchmates / Mentorship -->
            <div class="bg-[#8b0000] text-white rounded-sm shadow-md overflow-hidden relative h-56 flex flex-col justify-end group transition-transform duration-300 hover:-translate-y-1">
                <img src="https://picsum.photos/id/1012/400/300" class="opacity-30 group-hover:opacity-40 transition-opacity w-full h-full object-cover absolute inset-0 z-0" alt="Batchmates Background" />
                <div class="relative z-10 p-5 bg-gradient-to-t from-[#8b0000] via-[#8b0000]/80 to-transparent h-full flex flex-col justify-end">
                    <h3 class="font-serif font-bold text-lg mb-1">Mentorship</h3>
                    <p class="text-xs mb-4 text-gray-200">Reconnect with your batchmates and guide the next generation of students.</p>
                    <a href="{{ route('mentorship.index') }}" class="bg-white text-[#8b0000] text-xs font-bold py-2 px-5 inline-block w-max rounded-sm shadow-sm hover:bg-gray-100">VIEW</a>
                </div>
            </div>

            <!-- Card 3: Directory -->
            <div class="bg-brand-navy text-white rounded-sm shadow-md overflow-hidden relative h-56 flex flex-col justify-end group transition-transform duration-300 hover:-translate-y-1">
                <img src="https://picsum.photos/id/1013/400/300" class="opacity-30 group-hover:opacity-40 transition-opacity w-full h-full object-cover absolute inset-0 z-0" alt="Directory Background" />
                <div class="relative z-10 p-5 bg-gradient-to-t from-brand-navy via-brand-navy/80 to-transparent h-full flex flex-col justify-end">
                    <h3 class="font-serif font-bold text-lg mb-1">Alumni Directory</h3>
                    <p class="text-xs mb-4 text-gray-200">Explore the alumni directory and connect with professionals across industries.</p>
                    <a href="{{ route('alumni.index') }}" class="bg-white text-brand-navy text-xs font-bold py-2 px-5 inline-block w-max rounded-sm shadow-sm hover:bg-gray-100">VIEW</a>
                </div>
            </div>

            <!-- Card 4: Resources / Network -->
            <div class="bg-[#8b0000] text-white rounded-sm shadow-md overflow-hidden relative h-56 flex flex-col justify-end group transition-transform duration-300 hover:-translate-y-1">
                <img src="https://picsum.photos/id/1014/400/300" class="opacity-30 group-hover:opacity-40 transition-opacity w-full h-full object-cover absolute inset-0 z-0" alt="Resources Background" />
                <div class="relative z-10 p-5 bg-gradient-to-t from-[#8b0000] via-[#8b0000]/80 to-transparent h-full flex flex-col justify-end">
                    <h3 class="font-serif font-bold text-lg mb-1">Resource Vault</h3>
                    <p class="text-xs mb-4 text-gray-200">Access exclusive academic resources, past papers, and continuous learning.</p>
                    <a href="{{ route('resources.index') }}" class="bg-white text-[#8b0000] text-xs font-bold py-2 px-5 inline-block w-max rounded-sm shadow-sm hover:bg-gray-100">VIEW</a>
            </div>

        </div>
    </div>

    <!-- 3. Showcase Your Business / Jobs Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="flex flex-col md:flex-row shadow-lg rounded-sm overflow-hidden bg-white">
            <div class="bg-brand-maroon text-white p-8 md:w-1/3 flex flex-col justify-center border-r-4 border-brand-gold">
                <h3 class="font-serif text-2xl font-bold mb-4 leading-tight">Showcase Your Business.<br>Inspire Your Network.</h3>
                <p class="text-sm mb-8 text-gray-200">Let your alumni community discover opportunities, support your ventures, and hire top talent from the KDP network.</p>
                <a href="{{ route('jobs.index') }}" class="bg-white text-brand-maroon text-sm font-bold py-3 px-6 rounded-sm w-max shadow-md hover:bg-gray-100 transition-colors">List Your Business / Job</a>
            </div>
            <div class="md:w-2/3 h-64 md:h-auto grid grid-cols-2 sm:grid-cols-4 gap-1 p-1 bg-gray-200">
                <img src="https://picsum.photos/id/1015/200/200" class="w-full h-full object-cover">
                <img src="https://picsum.photos/id/1016/200/200" class="w-full h-full object-cover">
                <img src="https://picsum.photos/id/1018/200/200" class="w-full h-full object-cover hidden sm:block">
                <img src="https://picsum.photos/id/1019/200/200" class="w-full h-full object-cover hidden sm:block">
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- EVENTS SECTION                                 -->
    <!-- ============================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-transparent">
        
        <!-- Section Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-serif text-3xl font-bold text-gray-900">Events</h2>
            <a href="#" class="text-sm font-semibold text-brand-navy border border-gray-300 bg-white px-4 py-2 rounded-sm shadow-sm hover:bg-gray-50 transition-colors">
                View All
            </a>
        </div>

        <!-- Event Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Assuming your controller passes an $events variable --}}
            @forelse($events ?? [1, 2, 3] as $event)
                <!-- Individual Event Card -->
                <div class="bg-white border border-gray-200 rounded-sm overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                    
                    <!-- Event Image (Placeholder for now) -->
                    <div class="relative h-48 bg-gray-100 border-b border-gray-200">
                        <!-- Replace with $event->image_url in production -->
                        <img src="https://picsum.photos/id/{{ 1040 + $loop->index }}/400/300" alt="Event Image" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Event Content -->
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Title -->
                            <h3 class="font-sans font-bold text-base text-brand-navy mb-3 line-clamp-2 leading-snug hover:text-brand-maroon cursor-pointer transition-colors">
                                {{ is_object($event) ? $event->title : 'Invitation: IIT Delhi Alumni Greater New York Summer Alumni Mixer | 2026' }}
                            </h3>
                            
                            <!-- Upcoming Event Badge -->
                            <span class="inline-block bg-[#f8e6e6] text-brand-maroon text-[11px] font-bold uppercase tracking-wider py-1 px-2.5 rounded-sm mb-4 border border-[#e6b3b3]">
                                Upcoming Event
                            </span>
                            
                            <!-- Date & Time -->
                            <div class="flex items-start text-sm text-gray-600 mb-2 font-medium">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>
                                    {{ is_object($event) ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y') : 'Jul 30, 2026' }} - 
                                    {{ is_object($event) ? $event->time : '05:30 PM' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="mt-6">
                            <a href="#" class="inline-block bg-brand-maroon text-white text-sm font-bold py-2.5 px-6 rounded-sm shadow-sm hover:bg-red-900 transition-colors">
                                View Event
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback state if no events are listed -->
                <div class="col-span-3 text-center py-12 bg-white border border-gray-200 rounded-sm">
                    <p class="text-gray-500 font-medium">No upcoming events at the moment. Please check back later!</p>
                </div>
            @endforelse

        </div>
    </section>

    <!-- 4. The Smart I-Card Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex flex-col md:flex-row shadow-lg rounded-sm overflow-hidden bg-white">
            <div class="bg-brand-maroon text-white p-8 md:w-1/3 flex flex-col justify-center border-r-4 border-brand-gold">
                <h3 class="font-serif text-xl font-bold mb-3 uppercase tracking-wider">Connecting Generations of Excellence</h3>
                <p class="text-sm mb-8 text-gray-200">Unlock a World of Benefits for You and Your Family with the Official Alumni Smart Card.</p>
                <a href="{{ route('id-card.show') }}" class="bg-white text-brand-maroon text-sm font-bold py-3 px-6 rounded-sm w-max shadow-md hover:bg-gray-100 transition-colors">Get Your Smart Card Now</a>
            </div>
            <div class="md:w-2/3 h-48 md:h-auto">
                <img src="https://picsum.photos/id/1020/800/300" class="w-full h-full object-cover" alt="Graduation">
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- GALLERY SECTION                                -->
    <!-- ============================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-transparent">
        
        <!-- Section Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-sans font-medium text-gray-900">Gallery</h2>
            <a href="#" class="text-sm font-medium text-brand-maroon border border-brand-maroon px-6 py-1.5 rounded-sm hover:bg-brand-maroon hover:text-white transition-colors">
                View All
            </a>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- In a real scenario, use a loop like @foreach($galleries as $gallery) --}}
            
            <!-- Gallery Album 1 -->
            <a href="#" class="group flex flex-col cursor-pointer">
                <!-- Image Container -->
                <div class="relative w-full h-56 rounded-md overflow-hidden mb-3 shadow-sm border border-gray-100">
                    <img src="https://picsum.photos/id/1015/600/400" alt="Executive Meeting" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    <!-- Optional: Overlay to replicate the slightly darkened edge effect in your screenshot -->
                    <div class="absolute inset-0 shadow-[inset_0_0_20px_rgba(0,0,0,0.2)] pointer-events-none"></div>
                </div>
                <!-- Text Container -->
                <div class="flex justify-between items-start mt-1">
                    <h3 class="text-[15px] font-medium text-gray-900 leading-snug pr-4 group-hover:text-brand-maroon transition-colors">
                        IITDAA 1st Executive Meeting held on 23rd May, 2026
                    </h3>
                    <span class="text-[13px] text-gray-600 whitespace-nowrap pt-0.5">
                        61 Items
                    </span>
                </div>
            </a>

            <!-- Gallery Album 2 -->
            <a href="#" class="group flex flex-col cursor-pointer">
                <!-- Image Container -->
                <div class="relative w-full h-56 rounded-md overflow-hidden mb-3 shadow-sm border border-gray-100">
                    <img src="https://picsum.photos/id/1018/600/400" alt="Farewell Batch" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    <div class="absolute inset-0 shadow-[inset_0_0_20px_rgba(0,0,0,0.2)] pointer-events-none"></div>
                </div>
                <!-- Text Container -->
                <div class="flex justify-between items-start mt-1">
                    <h3 class="text-[15px] font-medium text-gray-900 leading-snug pr-4 group-hover:text-brand-maroon transition-colors">
                        Farewell to IIT Delhi Alumni Graduating Batch 2026
                    </h3>
                    <span class="text-[13px] text-gray-600 whitespace-nowrap pt-0.5">
                        429 Items
                    </span>
                </div>
            </a>

            <!-- Gallery Album 3 -->
            <a href="#" class="group flex flex-col cursor-pointer">
                <!-- Image Container -->
                <div class="relative w-full h-56 rounded-md overflow-hidden mb-3 shadow-sm border border-gray-100">
                    <img src="https://picsum.photos/id/1020/600/400" alt="Annual General Meeting" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    <div class="absolute inset-0 shadow-[inset_0_0_20px_rgba(0,0,0,0.2)] pointer-events-none"></div>
                </div>
                <!-- Text Container -->
                <div class="flex justify-between items-start mt-1">
                    <h3 class="text-[15px] font-medium text-gray-900 leading-snug pr-4 group-hover:text-brand-maroon transition-colors">
                        Annual General Meeting 2026
                    </h3>
                    <span class="text-[13px] text-gray-600 whitespace-nowrap pt-0.5">
                        341 Items
                    </span>
                </div>
            </a>

        </div>
    </section>
    
    <!-- ============================================== -->
    <!-- SOCIAL MEDIA SECTION                           -->
    <!-- ============================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-transparent">
        
        <!-- Section Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-sans font-medium text-gray-900 tracking-wide">Social Media</h2>
        </div>

        <!-- Social Media Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- ========================================== -->
            <!-- 1. FACEBOOK WIDGET CONTAINER               -->
            <!-- ========================================== -->
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden flex flex-col h-[600px]">
                <!-- Widget Header -->
                <div class="px-4 py-3 border-b border-gray-100 flex items-center bg-gray-50/50">
                    <svg class="w-5 h-5 text-[#1877F2] mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                    <span class="text-sm font-semibold text-gray-800">KDP on Facebook</span>
                </div>
                
                <!-- Widget Body / Embed Area -->
                <div class="flex-grow overflow-y-auto relative bg-white">
                    <!-- TODO: Replace this block with your actual Facebook Page Plugin <iframe> -->
                    <div class="p-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="https://picsum.photos/id/147/50/50" class="w-10 h-10 rounded-full border border-gray-200" alt="Page Logo">
                            <div>
                                <h4 class="font-bold text-sm text-[#1877F2]">KDP Alumni Association</h4>
                                <p class="text-xs text-gray-500">25K followers</p>
                            </div>
                        </div>
                        <img src="https://picsum.photos/id/1050/400/400" class="w-full object-cover rounded-sm mb-3" alt="FB Post Placeholder">
                        <p class="text-xs text-gray-700">Join us for the upcoming Board Game Meet-Up! Play, connect, unwind. #KDPAlumni</p>
                    </div>
                    <!-- End Placeholder -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. INSTAGRAM WIDGET CONTAINER              -->
            <!-- ========================================== -->
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden flex flex-col h-[600px]">
                <!-- Widget Header -->
                <div class="px-4 py-3 border-b border-gray-100 flex items-center bg-gray-50/50">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="url(#ig-grad)"><defs><linearGradient id="ig-grad" x1="0%" y1="100%" x2="100%" y2="0%"><stop offset="0%" stop-color="#f09433" /><stop offset="25%" stop-color="#e6683c" /><stop offset="50%" stop-color="#dc2743" /><stop offset="75%" stop-color="#cc2366" /><stop offset="100%" stop-color="#bc1888" /></linearGradient></defs><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path></svg>
                    <span class="text-sm font-semibold text-gray-800">KDP on Instagram</span>
                </div>
                
                <!-- Widget Body / Embed Area -->
                <div class="flex-grow overflow-y-auto relative bg-white">
                    <!-- TODO: Replace this block with your actual Instagram Embed script/iframe -->
                    <div class="p-4">
                        <div class="flex items-center space-x-4 mb-6">
                            <img src="https://picsum.photos/id/147/60/60" class="w-14 h-14 rounded-full border p-0.5 border-gray-300" alt="IG Avatar">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">kdp_alumni</h4>
                                <p class="text-xs text-gray-500">KDP Alumni Association</p>
                                <p class="text-xs text-gray-800 mt-1"><span class="font-bold">607</span> posts &nbsp;•&nbsp; <span class="font-bold">3,277</span> followers</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-1">
                            <img src="https://picsum.photos/id/1051/200/200" class="w-full aspect-square object-cover" alt="IG 1">
                            <img src="https://picsum.photos/id/1052/200/200" class="w-full aspect-square object-cover" alt="IG 2">
                            <img src="https://picsum.photos/id/1053/200/200" class="w-full aspect-square object-cover" alt="IG 3">
                            <img src="https://picsum.photos/id/1054/200/200" class="w-full aspect-square object-cover" alt="IG 4">
                        </div>
                    </div>
                    <!-- End Placeholder -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 3. TWITTER / X WIDGET CONTAINER            -->
            <!-- ========================================== -->
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden flex flex-col h-[600px]">
                <!-- Widget Header -->
                <div class="px-4 py-3 border-b border-gray-100 flex items-center bg-gray-50/50">
                    <svg class="w-5 h-5 text-black mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                    <span class="text-sm font-semibold text-gray-800">KDP on Twitter</span>
                </div>
                
                <!-- Widget Body / Embed Area -->
                <div class="flex-grow overflow-y-auto relative bg-white p-4">
                    <!-- TODO: Drop your official Twitter Timeline Widget embed code here -->
                    
                    <!-- Simulating the broken/loading state from your screenshot -->
                    <a href="#" class="text-brand-maroon text-sm hover:underline">Tweets By @KDP_Alumni</a>
                </div>
            </div>

        </div>
    </section>
@endsection