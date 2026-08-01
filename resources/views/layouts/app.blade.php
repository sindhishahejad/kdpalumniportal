<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K. D. Polytechnic, Patan - Alumni Association</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kdp: {
                            topbar: '#2e53a3',    /* Official Top Bar Blue */
                            textblue: '#1c3272',  /* Official Deep Blue for Main Title */
                            orange: '#f97316',    /* Official Orange Divider */
                            footer: '#1e293b',    /* Official Dark Footer */
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen relative" x-data="{ mobileMenuOpen: false, showScrollTop: false }" @scroll.window="showScrollTop = (window.pageYOffset > 300)">

    <!-- ============================================== -->
    <!-- TIER 1: Official Blue Top Contact Bar          -->
    <!-- ============================================== -->
    <div class="bg-gradient-to-r from-[#294c9b] to-[#4074e6] text-white w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row justify-between items-center text-[13px] font-medium tracking-wide gap-2">
            
            <div class="flex items-center space-x-6">
                <a href="tel:02766220419" class="flex items-center hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"></path></svg>
                    9265105831
                </a>
                <a href="mailto:kdp-patan-dte@gujarat.gov.in" class="flex items-center hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path></svg>
                    sindhishahejad@gmail.com
                </a>
            </div>

            <div class="flex items-center hidden sm:flex">
                <a href="https://www.facebook.com/profile.php?id=100054398415159" target="_blank" class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition-colors">
                    <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TIER 2: Responsive Logo Banner                 -->
    <!-- ============================================== -->
    <div class="bg-white w-full border-b-[3px] border-kdp-orange relative z-50">
        <div class="container mx-auto px-4 py-4">
            
            <!-- Desktop Header -->
            <div class="hidden md:flex justify-between items-center w-full max-w-7xl mx-auto">
                <div class="w-1/4 flex justify-start">
                    <img class="h-24 w-auto" src="{{ asset('images/five.png') }}" alt="College Logo" onerror="this.src='https://picsum.photos/id/147/100/100'">
                </div>
                
                <div class="w-2/4 text-center flex flex-col justify-center">
                    <h1 class="font-sans text-kdp-textblue text-2xl lg:text-[40px] font-extrabold tracking-wide uppercase leading-none">
                        K. D. POLYTECHNIC, PATAN
                    </h1>
                    <h2 class="font-sans text-gray-500 text-sm font-bold tracking-[0.25em] uppercase mt-2">
                        Alumni Association
                    </h2>
                </div>
                
                <div class="w-1/4 flex justify-end items-center space-x-6">
                    <img class="h-24 w-auto hidden lg:block" src="{{ asset('images/six.jpg') }}" alt="Affiliation Logo" onerror="this.src='https://picsum.photos/id/148/100/100'">
                </div>
            </div>

            <!-- Mobile Header -->
            <div class="md:hidden flex flex-col items-center text-center space-y-3 py-2">
                <img class="h-[70px] w-auto" src="{{ asset('images/logo1_1766135259.png') }}" alt="College Logo" onerror="this.src='https://picsum.photos/id/147/100/100'">
                <div>
                    <h1 class="font-sans text-kdp-textblue text-[26px] font-extrabold tracking-wide uppercase leading-none">
                        K. D. P. PATAN
                    </h1>
                    <p class="font-sans text-kdp-textblue text-[15px] mt-1.5">
                        Excellence in Technical Education
                    </p>
                    <p class="font-sans text-kdp-orange font-bold text-[10px] uppercase tracking-widest mt-0.5">
                        Alumni Association
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TIER 3: Official Navigation Bar                -->
    <!-- ============================================== -->
    <nav class="bg-white w-full shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            <!-- Mobile Menu Toggle & Auth -->
            <div class="md:hidden flex items-center justify-between py-3">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-kdp-textblue focus:outline-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-kdp-textblue uppercase tracking-wider">Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-kdp-topbar text-white text-xs font-bold py-2 px-4 rounded-sm uppercase tracking-wider">Sign Up / Login</a>
                    @endauth
                </div>
            </div>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex flex-wrap justify-center items-center h-14 space-x-6 lg:space-x-10">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-gray-900 hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider transition-colors h-full flex items-center border-b-2 border-transparent hover:border-kdp-topbar">HOME</a>
                <a href="#" class="text-gray-900 hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider transition-colors h-full flex items-center border-b-2 border-transparent hover:border-kdp-topbar">GIVING BACK</a>
                <a href="{{ route('id-card.show') }}" class="text-gray-900 hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider transition-colors h-full flex items-center border-b-2 border-transparent hover:border-kdp-topbar">SMART I-CARD</a>
                
                <!-- Services Dropdown -->
                <div class="group relative flex items-center cursor-pointer h-full border-b-2 border-transparent hover:border-kdp-topbar">
                    <a href="#" class="text-gray-900 group-hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider flex items-center transition-colors">
                        SERVICES <svg class="w-3.5 h-3.5 ml-1 stroke-2 text-gray-500 group-hover:text-kdp-topbar" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                    <div class="absolute top-14 left-0 w-56 bg-white shadow-lg border-t-2 border-kdp-textblue opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 rounded-b-sm">
                        <ul class="py-2">
                            <li><a href="{{ route('alumni.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-kdp-textblue font-medium transition-colors uppercase tracking-wide">Directory</a></li>
                            <li><a href="{{ route('jobs.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-kdp-textblue font-medium transition-colors uppercase tracking-wide">Jobs</a></li>
                            <li><a href="{{ route('resources.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-kdp-textblue font-medium transition-colors uppercase tracking-wide">Resources</a></li>
                            <li><a href="{{ route('mentorship.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-kdp-textblue font-medium transition-colors uppercase tracking-wide">Mentorship</a></li>
                        </ul>
                    </div>
                </div>

                <a href="#" class="text-gray-900 hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider transition-colors h-full flex items-center border-b-2 border-transparent hover:border-kdp-topbar">GALLERY</a>
                <a href="#" class="text-gray-900 hover:text-kdp-topbar text-[14px] font-semibold uppercase tracking-wider transition-colors h-full flex items-center border-b-2 border-transparent hover:border-kdp-topbar">CONTACT</a>
            </div>

            <!-- Profile Dropdown (Desktop Right) -->
            <div class="hidden md:flex absolute right-4 top-0 h-14 items-center">
                @auth
                    <div x-data="{ open: false }" class="relative inline-block text-left z-50">
                        <button @click="open = !open" @click.away="open = false" class="bg-kdp-topbar hover:bg-blue-800 text-white font-bold py-1.5 px-4 rounded-sm shadow-sm transition-colors text-xs uppercase tracking-wide flex items-center gap-1.5">
                            {{ Auth::user()->name }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-sm shadow-xl border border-gray-100 py-1">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800">Your Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-kdp-topbar hover:bg-blue-800 text-white font-bold py-1.5 px-5 rounded-sm shadow-sm transition-colors text-xs uppercase tracking-widest">
                        SIGN UP / LOGIN
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu (Alpine) -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-t border-gray-100 shadow-md absolute w-full z-40">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-bold text-gray-900 hover:bg-blue-50 hover:text-kdp-topbar uppercase tracking-wide">Home</a>
                <a href="{{ route('id-card.show') }}" class="block px-3 py-2 text-sm font-bold text-gray-900 hover:bg-blue-50 hover:text-kdp-topbar uppercase tracking-wide">Smart I-Card</a>
                <a href="{{ route('alumni.index') }}" class="block px-3 py-2 text-sm font-bold text-gray-900 hover:bg-blue-50 hover:text-kdp-topbar uppercase tracking-wide">Alumni Directory</a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-2 text-sm font-bold text-gray-900 hover:bg-blue-50 hover:text-kdp-topbar uppercase tracking-wide">Jobs</a>
                <a href="{{ route('resources.index') }}" class="block px-3 py-2 text-sm font-bold text-gray-900 hover:bg-blue-50 hover:text-kdp-topbar uppercase tracking-wide">Resources</a>
            </div>
        </div>
    </nav>

    <!-- ============================================== -->
    <!-- MAIN CONTENT YIELD                             -->
    <!-- ============================================== -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ============================================== -->
    <!-- OFFICIAL FOOTER                                -->
    <!-- ============================================== -->
    <footer class="bg-kdp-footer text-white py-12 border-t-4 border-kdp-orange relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
            
            <!-- About Section -->
            <div class="lg:col-span-4">
                <h5 class="text-xl font-bold uppercase tracking-wider mb-4 text-white">K. D. POLYTECHNIC, PATAN</h5>
                <div class="mt-4 flex space-x-3">
                    <a href="https://www.facebook.com/profile.php?id=100054398415159" target="_blank" aria-label="Facebook" class="w-10 h-10 rounded-full bg-white/10 hover:bg-kdp-topbar flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-2">
                <h5 class="text-lg font-bold mb-4 text-white">Quick Links</h5>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Gallery</a></li>
                </ul>
            </div>

            <!-- Departments -->
            <div class="lg:col-span-3">
                <h5 class="text-lg font-bold mb-4 text-white">Departments</h5>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Computer Engineering</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Electrical Engineering</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Mechanical Engineering</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Civil Engineering</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Science &amp; Humanities</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="lg:col-span-3">
                <h5 class="text-lg font-bold mb-4 text-white">Contact Us</h5>
                <ul class="space-y-4 text-sm text-gray-300">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-kdp-orange shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        <span>K. D. Polytechnic, Patan<br>Opp. T. B. hospital, Hemchandracharya North University Road,<br>Patan - 384265, Gujarat</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-kdp-orange shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                        <a href="tel:02766220419" class="hover:text-white">9265105831</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-kdp-orange shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        <a href="mailto:kdp-patan-dte@gujarat.gov.in" class="hover:text-white">sindhishahejad@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 mt-12 pt-6 border-t border-gray-700 text-center text-sm text-gray-400">
            <p class="mb-1">&copy; 2026 K. D. POLYTECHNIC, PATAN. All Rights Reserved.</p>
            <p>Designed &amp; Developed by Sindhi Shahejad, Shekh Juned, Pathan Anas</p>
        </div>

        <!-- Floating Scroll to Top Button (Alpine controlled) -->
        <button x-show="showScrollTop" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-cloak x-transition class="fixed bottom-6 right-6 w-12 h-12 bg-kdp-orange hover:bg-orange-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
        </button>
    </footer>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>