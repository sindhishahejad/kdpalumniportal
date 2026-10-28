<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K. D. Polytechnic, Patan - Alumni Association</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>window.marked || document.write('<script src="{{ asset('js/marked.min.js') }}"><\/script>')</script>

    <!-- ✨ Add Laravel Vite Directive Here ✨ -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind Configuration here (Reverse-engineered from https://www.kdppatan.ac.in) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kdp: {
                            primary: '#1e3a8a',   /* Official KDP Navy Blue */
                            secondary: '#3b82f6', /* Official KDP Royal Blue */
                            orange: '#f97316',    /* Official KDP Saffron/Orange Accent */
                            topbar: '#1e3a8a',    /* Official Top Bar Gradient Start */
                            textblue: '#1e3a8a',  /* Official Deep Blue for Main Title */
                            footer: '#111827',    /* Official Dark Institutional Footer */
                            dark: '#111827',      /* High contrast text */
                            light: '#f3f4f6',     /* Light Gray Section Background */
                        }
                    },
                    fontFamily: {
                        sans: ['"Open Sans"', 'Inter', 'sans-serif'],
                        heading: ['"Poppins"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Signature Nav-link underline indicator from kdppatan.ac.in */
        .kdp-nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .kdp-nav-link:hover {
            color: #f97316 !important;
        }
        .kdp-nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: #f97316;
            transition: width 0.3s ease;
        }
        .kdp-nav-link:hover::after,
        .kdp-nav-link.active::after {
            width: 75%;
        }

        /* 🤖 Enhanced AI Chatbot Markdown Formatting & UI Styles */
        .chat-markdown {
            color: #1e293b;
            font-size: 0.8125rem;
            line-height: 1.55;
        }
        .chat-markdown p {
            margin-bottom: 0.45rem;
        }
        .chat-markdown p:last-child {
            margin-bottom: 0;
        }
        .chat-markdown h2, .chat-markdown h3, .chat-markdown h4 {
            color: #0f2042;
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            margin-top: 0.65rem;
            margin-bottom: 0.35rem;
            line-height: 1.3;
        }
        .chat-markdown h2 { font-size: 0.95rem; }
        .chat-markdown h3 { font-size: 0.875rem; }
        .chat-markdown h4 { font-size: 0.8125rem; }
        .chat-markdown strong {
            font-weight: 700;
            color: #0f2042;
        }
        .chat-markdown ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0.35rem 0;
        }
        .chat-markdown ol {
            padding-left: 1.25rem;
            margin: 0.35rem 0;
        }
        .chat-markdown ul > li {
            position: relative;
            padding-left: 1.15rem;
            margin-bottom: 0.3rem;
            line-height: 1.5;
        }
        .chat-markdown ul > li::before {
            content: "•";
            position: absolute;
            left: 0.25rem;
            color: #f97316;
            font-weight: 900;
            font-size: 1rem;
            line-height: 1;
        }
        .chat-markdown ol > li {
            margin-bottom: 0.3rem;
            line-height: 1.5;
        }
        .chat-markdown a {
            color: #1e3a8a;
            font-weight: 700;
            text-decoration: underline;
            text-decoration-color: #f97316;
            text-underline-offset: 2px;
            transition: color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }
        .chat-markdown a:hover {
            color: #f97316;
        }
        /* Responsive Crisp Tables */
        .chat-markdown .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin: 0.65rem 0;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .chat-markdown table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            text-align: left;
        }
        .chat-markdown th {
            background: linear-gradient(135deg, #0f2042, #1e3a8a);
            color: #ffffff;
            font-weight: 700;
            padding: 0.5rem 0.75rem;
            border-bottom: 2px solid #f97316;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            font-size: 0.7rem;
        }
        .chat-markdown td {
            padding: 0.45rem 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            background-color: #ffffff;
            vertical-align: top;
        }
        .chat-markdown tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .chat-markdown tr:hover td {
            background-color: #f1f5f9;
        }
        .chat-markdown code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #f1f5f9;
            color: #0f2042;
            padding: 0.15rem 0.35rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            border: 1px solid #e2e8f0;
        }
        .chat-markdown pre {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 0.75rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            font-size: 0.75rem;
            margin: 0.5rem 0;
        }
        .chat-markdown hr {
            border: 0;
            border-top: 1px dashed #cbd5e1;
            margin: 0.75rem 0;
        }
        .chat-markdown blockquote {
            border-left: 3px solid #f97316;
            padding-left: 0.75rem;
            margin: 0.5rem 0;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body class="font-sans bg-[#f8fafc] text-gray-800 antialiased flex flex-col min-h-screen relative" x-data="{ mobileMenuOpen: false, showScrollTop: false }" @scroll.window="showScrollTop = (window.pageYOffset > 300)">

    <!-- ============================================== -->
    <!-- TIER 1: Official Blue Top Contact Bar          -->
    <!-- ============================================== -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#2563eb] to-[#3b82f6] text-white w-full relative z-[60] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row justify-between items-center text-[13px] font-medium tracking-wide gap-2">
            
            <!-- Left Info (Official College Phone & Email) -->
            <div class="flex items-center space-x-6 text-xs sm:text-[13px]">
                <a href="tel:02766220419" class="flex items-center hover:text-orange-200 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-orange-300" fill="currentColor" viewBox="0 0 24 24"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"></path></svg>
                    <span>02766-220419</span>
                </a>
                <a href="mailto:kdp-patan-dte@gujarat.gov.in" class="flex items-center hover:text-orange-200 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-orange-300" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path></svg>
                    <span class="hidden md:inline">kdp-patan-dte@gujarat.gov.in</span>
                    <span class="md:hidden">Contact College</span>
                </a>
            </div>

            <!-- Right Controls (Search, Multilingual, Social) -->
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap justify-center sm:justify-end">
                
                <!-- ⚡ AlumSmart Natural Language Query Button -->
                <button type="button" 
                        @click="$dispatch('open-alum-smart')" 
                        class="bg-white/15 hover:bg-white/25 text-white px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 border border-white/20 shadow-xs cursor-pointer">
                    <span class="text-amber-300">⚡</span>
                    <span>AlumSmart Search</span>
                    <kbd class="hidden sm:inline px-1 py-0.2 bg-black/30 rounded text-[10px] font-mono text-white/80">Ctrl+K</kbd>
                </button>

                <!-- 🌐 Multilingual Language Switcher Dropdown (English, Gujarati, Hindi) -->
                <div x-data="{ langOpen: false, currentLang: localStorage.getItem('kdp_portal_lang') || 'en' }" 
                     @click.outside="langOpen = false" 
                     class="relative">
                    <button type="button" 
                            @click="langOpen = !langOpen" 
                            class="bg-white/15 hover:bg-white/25 text-white px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 border border-white/20 cursor-pointer shadow-xs select-none">
                        <span>🌐</span>
                        <span x-text="currentLang === 'gu' ? 'ગુજરાતી' : (currentLang === 'hi' ? 'हिन्दी' : 'English')">English</span>
                        <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="{ 'rotate-180': langOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="langOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         x-cloak 
                         class="absolute right-0 mt-2 w-52 bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-200/90 py-1.5 z-[100] text-xs font-semibold overflow-hidden">
                        
                        <div class="px-3.5 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                            <span>🌐 Select Language</span>
                            <span class="text-[9px] font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-bold">Auto AI</span>
                        </div>

                        <div class="p-1 space-y-0.5">
                            <button type="button" 
                                    @click="switchLanguage('en'); currentLang = 'en'; langOpen = false" 
                                    :class="currentLang === 'en' ? 'bg-blue-50 text-[#1e3a8a] font-bold' : 'hover:bg-slate-50 text-slate-700'"
                                    class="w-full text-left px-3 py-2 rounded-xl flex items-center justify-between transition-colors cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">🇬🇧</span>
                                    <div>
                                        <div class="font-bold text-slate-900">English</div>
                                        <div class="text-[10px] text-slate-400 font-normal">Default Language</div>
                                    </div>
                                </div>
                                <span x-show="currentLang === 'en'" class="text-[#1e3a8a] font-black text-sm">✓</span>
                            </button>

                            <button type="button" 
                                    @click="switchLanguage('gu'); currentLang = 'gu'; langOpen = false" 
                                    :class="currentLang === 'gu' ? 'bg-blue-50 text-[#1e3a8a] font-bold' : 'hover:bg-slate-50 text-slate-700'"
                                    class="w-full text-left px-3 py-2 rounded-xl flex items-center justify-between transition-colors cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">🇮🇳</span>
                                    <div>
                                        <div class="font-bold text-slate-900">ગુજરાતી</div>
                                        <div class="text-[10px] text-slate-400 font-normal">Gujarati Portal</div>
                                    </div>
                                </div>
                                <span x-show="currentLang === 'gu'" class="text-[#1e3a8a] font-black text-sm">✓</span>
                            </button>

                            <button type="button" 
                                    @click="switchLanguage('hi'); currentLang = 'hi'; langOpen = false" 
                                    :class="currentLang === 'hi' ? 'bg-blue-50 text-[#1e3a8a] font-bold' : 'hover:bg-slate-50 text-slate-700'"
                                    class="w-full text-left px-3 py-2 rounded-xl flex items-center justify-between transition-colors cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base">🇮🇳</span>
                                    <div>
                                        <div class="font-bold text-slate-900">हिन्दी</div>
                                        <div class="text-[10px] text-slate-400 font-normal">Hindi Portal</div>
                                    </div>
                                </div>
                                <span x-show="currentLang === 'hi'" class="text-[#1e3a8a] font-black text-sm">✓</span>
                            </button>
                        </div>
                    </div>
                </div>

                <a href="https://www.facebook.com/profile.php?id=100054398415159" target="_blank" rel="noopener noreferrer" aria-label="Official KDP Facebook Page" class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition-colors hidden sm:flex">
                    <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TIER 2: Authentic Institutional Brand Header   -->
    <!-- ============================================== -->
    <div class="bg-white w-full border-b-[3px] border-[#f97316] relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            
            <!-- Desktop Brand Header -->
            <div class="hidden md:flex justify-between items-center w-full">
                <!-- Left: Official College Crest Logo -->
                <div class="w-1/4 flex justify-start">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                        <img class="h-20 lg:h-24 w-auto object-contain transition-transform group-hover:scale-105" 
                             src="{{ asset('images/kdp_crest.png') }}" 
                             alt="K. D. Polytechnic Crest">
                    </a>
                </div>
                
                <!-- Center: College Official Name & Tagline -->
                <div class="w-2/4 text-center flex flex-col justify-center">
                    <h1 class="font-heading text-[#1e3a8a] text-2xl lg:text-[34px] font-extrabold tracking-wide uppercase leading-tight">
                        K. D. POLYTECHNIC, PATAN
                    </h1>
                    <h2 class="font-heading text-[#f97316] text-xs lg:text-[13px] font-bold tracking-[0.22em] uppercase mt-1">
                        Alumni Association &amp; Heritage Portal
                    </h2>
                    <p class="text-[11px] text-gray-500 font-medium tracking-wider hidden lg:block mt-0.5">
                        Government Polytechnic, Established 1961 • Affiliated to GTU &amp; Approved by AICTE
                    </p>
                </div>
                
                <!-- Right: Official Gujarat Technical Education Affiliation Seal -->
                <div class="w-1/4 flex justify-end items-center">
                    <img class="h-16 lg:h-20 w-auto object-contain hidden lg:block rounded-lg shadow-2xs border border-slate-100" 
                         src="{{ asset('images/gtu_cte_logo.jpg') }}" 
                         alt="Affiliation Seal">
                </div>
            </div>

            <!-- Mobile Brand Header -->
            <div class="md:hidden flex flex-col items-center text-center space-y-2 py-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img class="h-16 w-auto object-contain" 
                         src="{{ asset('images/kdp_crest.png') }}" 
                         alt="K. D. Polytechnic Logo">
                </a>
                <div>
                    <h1 class="font-heading text-[#1e3a8a] text-xl font-extrabold tracking-wide uppercase leading-none">
                        K. D. POLYTECHNIC, PATAN
                    </h1>
                    <p class="font-heading text-[#f97316] font-bold text-[11px] uppercase tracking-wider mt-1">
                        Alumni Association Portal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TIER 3: Official Sticky Navigation Bar         -->
    <!-- ============================================== -->
    <nav class="bg-white w-full shadow-md sticky top-0 z-40 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            <!-- Mobile Menu Toggle & Auth -->
            <div class="md:hidden flex items-center justify-between py-2.5">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-[#1e3a8a] p-1.5 focus:outline-none cursor-pointer">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-[#1e3a8a] uppercase tracking-wider">Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-1.5 px-3.5 rounded-lg uppercase tracking-wider">Login / Register</a>
                    @endauth
                </div>
            </div>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex flex-wrap justify-center items-center h-14 space-x-5 lg:space-x-8 font-heading">
                <a href="{{ route('dashboard') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('dashboard') ? 'text-[#f97316] active' : '' }}">
                    Home
                </a>
                
                <!-- Services Dropdown -->
                <div class="group relative flex items-center cursor-pointer h-full">
                    <a href="#" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide flex items-center h-full">
                        Services 
                        <svg class="w-3.5 h-3.5 ml-1 stroke-2 text-slate-500 group-hover:text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                    <div class="absolute top-14 left-0 w-64 bg-white shadow-xl border-t-2 border-[#1e3a8a] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 rounded-b-xl border border-slate-100 overflow-hidden">
                        <ul class="py-2 text-xs font-semibold">
                            <li><a href="{{ route('chatbot.index') }}" class="block px-4 py-2.5 text-[#1e3a8a] hover:bg-blue-50 transition-colors flex items-center gap-2 bg-blue-50/50 font-bold border-b border-slate-100"><span>🤖</span> KDP Mitra (AI Counselor)</a></li>
                            <li><a href="{{ route('id-card.show') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🪪</span> Digital Smart ID Card</a></li>
                            <li><a href="{{ route('alumni.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🎓</span> Alumni Directory</a></li>
                            <li><a href="{{ route('jobs.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>💼</span> Engineering Job Board</a></li>
                            <li><a href="{{ route('mentorship.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🤝</span> 1-on-1 Alumni Mentorship</a></li>
                            <li><a href="{{ route('tools.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🛠️</span> Student Tool &amp; Book Bank</a></li>
                            <li><a href="{{ route('documents.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🏛️</span> Request Official Documents</a></li>
                            <li><a href="{{ route('chapters.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>📍</span> Regional Chapters &amp; Map</a></li>
                            <li><a href="{{ route('reunions.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🎉</span> Batch Reunions &amp; RSVPs</a></li>
                            <li><a href="{{ route('pitches.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>💡</span> Student Project Pitches</a></li>
                            <li><a href="{{ route('events.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>📅</span> Campus Events &amp; Activities</a></li>
                            <li><a href="{{ route('elibrary.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>📖</span> Digital E-Library &amp; Capstones</a></li>
                            <li><a href="{{ route('resources.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>📚</span> Study Resources &amp; Notes</a></li>
                            <li><a href="{{ route('stories.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🌟</span> Alumni Success Stories</a></li>
                            <li><a href="{{ route('placement.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🏢</span> Training &amp; Placement (TPO)</a></li>
                            <li><a href="{{ route('faculty.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>👥</span> Faculty &amp; HOD Directory</a></li>
                            <li><a href="{{ route('calendar.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a] transition-colors flex items-center gap-2"><span>🗓️</span> GTU Academic Calendar</a></li>
                        </ul>
                    </div>
                </div>

                <a href="{{ route('donations.index') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('donations.*') ? 'text-[#f97316] active' : '' }}">
                    Giving Back
                </a>

                <a href="{{ route('awards.index') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('awards.*') ? 'text-[#f97316] active' : '' }}">
                    Awards &amp; Honors
                </a>

                <a href="{{ route('gallery.index') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('gallery.*') ? 'text-[#f97316] active' : '' }}">
                    Campus Time-Machine
                </a>

                <a href="{{ route('labs.index') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('labs.*') ? 'text-[#f97316] active' : '' }}">
                    Labs &amp; Workshops
                </a>

                <a href="{{ route('contact') }}" class="kdp-nav-link text-slate-800 text-[13px] lg:text-[14px] font-semibold uppercase tracking-wide h-full flex items-center {{ request()->routeIs('contact') ? 'text-[#f97316] active' : '' }}">
                    Contact
                </a>
            </div>

            <!-- Profile Dropdown (Desktop Right) -->
            <div class="hidden md:flex absolute right-4 top-0 h-14 items-center">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-slate-700 hover:text-[#1e3a8a] text-xs font-bold uppercase tracking-wider focus:outline-none cursor-pointer">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-1.5 border border-slate-100 z-50 text-xs font-semibold">
                            <div class="px-4 py-2 border-b border-slate-100 bg-slate-50/50">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Signed in as</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">🛡️ Admin Dashboard</a>
                                <a href="{{ route('admin.awards.index') }}" class="block px-4 py-2 text-amber-700 hover:bg-amber-50">🏆 Awards Management</a>
                                <a href="{{ route('admin.alerts.index') }}" class="block px-4 py-2 text-emerald-700 hover:bg-emerald-50">📱 WhatsApp Alert Center</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">👤 Edit Profile</a>
                            <a href="{{ route('id-card.show') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">🪪 My Smart ID Card</a>
                            <a href="{{ route('messages.inbox') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">💬 Messages &amp; Inbox</a>
                            <a href="{{ route('tools.my-donations') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">🛠️ My Donated Tools</a>
                            <a href="{{ route('tools.my-requests') }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#1e3a8a]">📦 My Tool Requests</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-5 rounded-lg shadow-sm transition-colors text-xs uppercase tracking-wider font-heading">
                        Login / Sign Up
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu (Alpine) -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full z-40 font-heading">
            <div class="px-3 pt-2 pb-4 space-y-1 text-sm font-semibold">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">Home</a>
                <a href="{{ route('id-card.show') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🪪 Smart I-Card</a>
                <a href="{{ route('alumni.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🎓 Alumni Directory</a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">💼 Job Board</a>
                <a href="{{ route('mentorship.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🤝 Mentorship</a>
                <a href="{{ route('donations.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🎁 Giving Back</a>
                <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">📅 Campus Events &amp; Activities</a>
                <a href="{{ route('elibrary.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">📖 Digital E-Library &amp; Vault</a>
                <a href="{{ route('stories.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🌟 Success Stories</a>
                <a href="{{ route('placement.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🏢 Training &amp; Placement (TPO)</a>
                <a href="{{ route('faculty.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">👥 Faculty &amp; HOD Directory</a>
                <a href="{{ route('calendar.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🗓️ GTU Academic Calendar</a>
                <a href="{{ route('awards.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🏆 Awards &amp; Hall of Fame</a>
                <a href="{{ route('gallery.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">⏳ Time-Machine &amp; Gallery</a>
                <a href="{{ route('labs.index') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">🔬 Labs &amp; Workshops</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-slate-800 hover:bg-blue-50 hover:text-[#1e3a8a]">📞 Contact</a>

                <!-- Mobile Language Selector -->
                <div class="px-3 pt-3 pb-2 border-t border-gray-100 mt-2 bg-slate-50 rounded-lg">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 block mb-2">🌐 Language / ભાષા / भाषा</span>
                    <div class="grid grid-cols-3 gap-1.5">
                        <button type="button" onclick="switchLanguage('en')" class="px-2 py-1.5 rounded-md text-xs font-bold text-center border transition-colors bg-white hover:bg-blue-50 text-slate-800 border-slate-200">
                            🇬🇧 English
                        </button>
                        <button type="button" onclick="switchLanguage('gu')" class="px-2 py-1.5 rounded-md text-xs font-bold text-center border transition-colors bg-white hover:bg-blue-50 text-slate-800 border-slate-200">
                            🇮🇳 ગુજરાતી
                        </button>
                        <button type="button" onclick="switchLanguage('hi')" class="px-2 py-1.5 rounded-md text-xs font-bold text-center border transition-colors bg-white hover:bg-blue-50 text-slate-800 border-slate-200">
                            🇮🇳 हिन्दी
                        </button>
                    </div>
                </div>

                @auth
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-xs font-bold text-slate-600 hover:text-[#1e3a8a]">Signed in as {{ Auth::user()->name }} (Edit Profile)</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg">Log Out</button>
                        </form>
                    </div>
                @endauth
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
    <!-- OFFICIAL INSTITUTIONAL FOOTER                  -->
    <!-- ============================================== -->
    <footer class="bg-[#1e3a8a] text-white py-12 border-t-4 border-[#f97316] relative font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
            
            <!-- About Section with Official Crest -->
            <div class="lg:col-span-4">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('images/kdp_crest.png') }}" alt="K. D. Polytechnic Logo" class="h-12 w-auto bg-white rounded-full p-1 shadow">
                    <div>
                        <h5 class="text-sm font-extrabold uppercase tracking-wider text-white font-heading leading-tight">કિલાચંદ દેવચંદ પોલિટેકનિક</h5>
                        <p class="text-xs font-bold text-blue-200 uppercase tracking-wide">K. D. Polytechnic, Patan</p>
                    </div>
                </div>
                <p class="text-xs text-blue-100/90 leading-relaxed mb-4">
                    Government Polytechnic under Commissionerate of Technical Education (CTE), Gandhinagar &amp; affiliated with Gujarat Technological University (GTU). Empowering diploma engineers since 1961.
                </p>
                <div class="flex items-center space-x-3">
                    <a href="https://www.facebook.com/profile.php?id=100054398415159" target="_blank" rel="noopener noreferrer" aria-label="Official Facebook" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#f97316] flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                    </a>
                    <a href="https://www.kdppatan.ac.in/" target="_blank" rel="noopener noreferrer" title="Official College Website" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-[#f97316] text-[11px] font-bold tracking-wide transition-colors">
                        <span>🌐 kdppatan.ac.in</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-2">
                <h5 class="text-sm font-bold uppercase tracking-wider mb-4 text-[#f97316] font-heading">Quick Links</h5>
                <ul class="space-y-2 text-xs text-blue-100">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white hover:underline transition-colors">Portal Home</a></li>
                    <li><a href="{{ route('alumni.index') }}" class="hover:text-white hover:underline transition-colors">Alumni Directory</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white hover:underline transition-colors">Job Opportunities</a></li>
                    <li><a href="{{ route('donations.index') }}" class="hover:text-white hover:underline transition-colors">Giving Back &amp; Support</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-white hover:underline transition-colors">Campus Events &amp; Activities</a></li>
                    <li><a href="{{ route('stories.index') }}" class="hover:text-white hover:underline transition-colors">Alumni Success Stories</a></li>
                    <li><a href="{{ route('placement.index') }}" class="hover:text-white hover:underline transition-colors">Training &amp; Placement (TPO)</a></li>
                    <li><a href="{{ route('calendar.index') }}" class="hover:text-white hover:underline transition-colors">GTU Academic Calendar &amp; Circulars</a></li>
                    <li><a href="{{ route('awards.index') }}" class="hover:text-white hover:underline transition-colors">Awards &amp; Recognition</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="hover:text-white hover:underline transition-colors">Campus Time-Machine</a></li>
                </ul>
            </div>

            <!-- Engineering Departments -->
            <div class="lg:col-span-3">
                <h5 class="text-sm font-bold uppercase tracking-wider mb-4 text-[#f97316] font-heading">Departments</h5>
                <ul class="space-y-2 text-xs text-blue-100">
                    <li><a href="{{ route('labs.index') }}#computer" class="hover:text-white hover:underline transition-colors">Computer Engineering</a></li>
                    <li><a href="{{ route('labs.index') }}#electrical" class="hover:text-white hover:underline transition-colors">Electrical Engineering</a></li>
                    <li><a href="{{ route('labs.index') }}#mechanical" class="hover:text-white hover:underline transition-colors">Mechanical Engineering</a></li>
                    <li><a href="{{ route('labs.index') }}#civil" class="hover:text-white hover:underline transition-colors">Civil Engineering</a></li>
                    <li><a href="{{ route('labs.index') }}#general" class="hover:text-white hover:underline transition-colors">General (Science &amp; Humanities)</a></li>
                    <li><a href="{{ route('faculty.index') }}" class="hover:text-white hover:underline transition-colors text-amber-300 font-bold flex items-center gap-1 mt-1"><span>👥</span> Department HOD Directory &rarr;</a></li>
                </ul>
            </div>

            <!-- Official College Contact Info -->
            <div class="lg:col-span-3">
                <h5 class="text-sm font-bold uppercase tracking-wider mb-4 text-[#f97316] font-heading">Contact College</h5>
                <ul class="space-y-3 text-xs text-blue-100">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-[#f97316] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        <span>Opp. T. B. Hospital, Hemchandracharya North Gujarat University Road, Patan - 384265 (Gujarat)</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#f97316] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                        <a href="tel:02766220419" class="hover:text-white font-medium">02766-220419</a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#f97316] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        <a href="mailto:kdp-patan-dte@gujarat.gov.in" class="hover:text-white font-medium">kdp-patan-dte@gujarat.gov.in</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 mt-10 pt-5 border-t border-blue-900/80 text-center text-xs text-blue-200">
            <p class="mb-1">&copy; {{ date('Y') }} K. D. POLYTECHNIC, PATAN. All Rights Reserved.</p>
            <p class="text-[11px] text-blue-300">Designed &amp; Developed with pride by Shahejad Sindhi</p>
        </div>

        <!-- Floating Scroll to Top Button (Alpine controlled) -->
        <button x-show="showScrollTop" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-cloak x-transition class="fixed bottom-6 right-24 w-11 h-11 bg-[#f97316] hover:bg-orange-600 text-white rounded-full flex items-center justify-center shadow-lg transition-transform hover:scale-105 z-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
        </button>
    </footer>

    <!-- 🤖 KDP Mitra AI Chatbot Floating Concierge -->
    <x-ai-chatbot-widget />

    <!-- 💳 Unified Razorpay Payment Gateway Component -->
    <x-razorpay-checkout />

    <!-- ⚡ Global AlumSmart Concierge Modal -->
    <x-alum-smart-modal />

    <!-- 🌐 Google Website Translation Integration (English, Gujarati, Hindi) -->
    <div id="google_translate_element" style="position:fixed; left:-9999px; top:-9999px; width:1px; height:1px; opacity:0; pointer-events:none;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,gu,hi',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function clearGoogleTransCookies() {
            const host = window.location.hostname;
            const domains = ['', host, '.' + host];
            domains.forEach(function(d) {
                const domainStr = d ? ('; domain=' + d) : '';
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/' + domainStr;
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=; domain=' + d;
            });
        }

        function setGoogleTransCookie(lang) {
            clearGoogleTransCookies();
            if (lang && lang !== 'en') {
                const cookieVal = '/en/' + lang;
                const host = window.location.hostname;
                document.cookie = "googtrans=" + cookieVal + "; path=/;";
                document.cookie = "googtrans=" + cookieVal + "; path=/; domain=" + host + ";";
            }
        }

        function switchLanguage(lang) {
            localStorage.setItem('kdp_portal_lang', lang);
            setGoogleTransCookie(lang);

            // If google combo exists, update it dynamically
            const combo = document.querySelector('.goog-te-combo');
            if (combo) {
                combo.value = lang;
                combo.dispatchEvent(new Event('change'));
            }

            // Reload page to re-translate all server-rendered DOM elements
            setTimeout(function() {
                window.location.reload();
            }, 100);
        }

        // Apply saved language preference on load
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('kdp_portal_lang') || 'en';
            if (savedLang !== 'en') {
                const expected = '/en/' + savedLang;
                if (!document.cookie.includes('googtrans=' + expected)) {
                    setGoogleTransCookie(savedLang);
                }
            } else {
                if (document.cookie.includes('googtrans=')) {
                    clearGoogleTransCookies();
                }
            }
        });
        // Suppress and remove Google Translate top banner iframe automatically
        const googleBannerObserver = new MutationObserver(function() {
            const banners = document.querySelectorAll('.goog-te-banner-frame, iframe.skiptranslate, body > .skiptranslate, .VIpgJd-ZVi9od-OR95jb-O2T7fe, #goog-gt-tt');
            banners.forEach(function(el) {
                el.style.setProperty('display', 'none', 'important');
                el.style.setProperty('visibility', 'hidden', 'important');
                el.style.setProperty('height', '0px', 'important');
                el.style.setProperty('opacity', '0', 'important');
            });
            if (document.body && document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.setProperty('top', '0px', 'important');
            }
        });
        googleBannerObserver.observe(document.documentElement, { 
            childList: true, 
            subtree: true, 
            attributes: true, 
            attributeFilter: ['style', 'class'] 
        });
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Completely suppress all Google Translate banners, iframes, tooltips, and toolbars */
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        iframe.goog-te-banner-frame,
        iframe.skiptranslate,
        body > .skiptranslate,
        .VIpgJd-ZVi9od-OR95jb-O2T7fe,
        .VIpgJd-yAWNEb-hvhGLb-hvhGLb,
        #goog-gt-tt,
        #goog-gt-vt,
        .goog-te-balloon-frame {
            display: none !important;
            visibility: hidden !important;
            position: fixed !important;
            top: -9999px !important;
            left: -9999px !important;
            width: 0 !important;
            height: 0 !important;
            opacity: 0 !important;
            pointer-events: none !important;
            z-index: -9999 !important;
        }

        /* Prevent Google Translate from shifting the page down */
        html, body {
            top: 0px !important;
            position: static !important;
        }

        /* Clean font and highlight styling */
        .goog-text-highlight { 
            background: none !important; 
            box-shadow: none !important; 
        }
        font { 
            background: transparent !important; 
            box-shadow: none !important; 
            display: inline !important; 
        }
    </style>
</body>
</html>
