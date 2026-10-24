@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-sans" x-data="{ addModal: false, editPartner: null }">

    <!-- Breadcrumb & Top Bar -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1e3a8a] transition-colors">Portal Home</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800">Training &amp; Placement Cell (TPO)</span>
        </nav>

        @auth
            @if(auth()->user()->role === 'admin')
                <button type="button" @click="addModal = true" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all font-heading cursor-pointer">
                    <span>➕</span>
                    <span>Add Corporate Recruiter</span>
                </button>
            @endif
        @endauth
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-2xs mb-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Institutional Hero Banner (KDP Patan Reverse-Engineered Navy & Saffron) -->
    <div class="bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] rounded-2xl text-white p-6 sm:p-10 shadow-lg relative overflow-hidden mb-8 border-b-4 border-[#f97316]">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 mb-3 font-heading">
                Training &amp; Placement Cell (TPO) • Industry Linkages
            </span>
            <h1 class="text-2xl sm:text-4xl font-black font-heading tracking-tight leading-tight">
                Corporate Recruitment Partners &amp; Placement Highlights
            </h1>
            <p class="text-blue-100 mt-2 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                Connecting K. D. Polytechnic diploma engineers with premier infrastructure, automotive, green energy, and technology corporations across Gujarat and India.
            </p>
        </div>

        <!-- Crest Watermark Background -->
        <div class="hidden lg:block absolute right-8 -bottom-8 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-64 h-64 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
    </div>

    <!-- Placement Statistics Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-10">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🎓</span>
                <span class="text-[10px] font-black uppercase tracking-wider text-[#1e3a8a] bg-blue-50 px-2 py-0.5 rounded font-heading">Success</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-[#1e3a8a] font-heading">{{ $stats['placement_rate'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5 font-medium">Placement &amp; D2D Degree Transition</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">💰</span>
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-heading">Highest</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-700 font-heading">{{ $stats['highest_package'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5 font-medium">Avg: {{ $stats['average_package'] }} for Diploma Trainees</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🏢</span>
                <span class="text-[10px] font-black uppercase tracking-wider text-[#f97316] bg-orange-50 px-2 py-0.5 rounded font-heading">Network</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-[#f97316] font-heading">{{ $stats['corporate_partners'] }}+</div>
            <div class="text-xs text-slate-500 mt-0.5 font-medium">Active Corporate Hiring Partners</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">🏛️</span>
                <span class="text-[10px] font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-2 py-0.5 rounded font-heading">Govt. NATS</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-purple-700 font-heading">{{ $stats['govt_apprenticeship'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5 font-medium">National Apprenticeship Support</div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 mb-8">
        <form method="GET" action="{{ route('placement.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Search Corporate Recruiters</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company (L&T, Adani, Siemens), sector, or roles..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1 font-heading">Hiring Engineering Branch</label>
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] text-slate-700 font-medium">
                    <option value="">All Engineering Disciplines</option>
                    <option value="Computer" {{ request('department') === 'Computer' ? 'selected' : '' }}>💻 Computer Engineering</option>
                    <option value="Electrical" {{ request('department') === 'Electrical' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                    <option value="Mechanical" {{ request('department') === 'Mechanical' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                    <option value="Civil" {{ request('department') === 'Civil' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'department']))
                    <a href="{{ route('placement.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold" title="Reset Filters">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Recruiters Wall Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-14">
        @forelse($partners as $partner)
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <!-- Top Card Bar -->
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center text-xl font-black font-heading shrink-0 border border-blue-100">
                            {{ substr($partner->name, 0, 1) }}
                        </div>

                        <div class="flex flex-col items-end">
                            @if($partner->package_range)
                                <span class="text-[11px] font-black text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 font-heading">
                                    {{ $partner->package_range }}
                                </span>
                            @endif
                            <span class="text-[10px] text-slate-400 font-semibold mt-1">Order #{{ $partner->order_num }}</span>
                        </div>
                    </div>

                    <!-- Company Info -->
                    <h3 class="font-heading font-black text-lg text-[#0f172a] group-hover:text-[#1e3a8a] transition-colors mb-1 leading-snug">
                        {{ $partner->name }}
                    </h3>

                    <p class="text-xs font-bold text-[#f97316] mb-3 flex items-center gap-1 font-heading">
                        <span>🏭 {{ $partner->sector }}</span>
                    </p>

                    @if($partner->roles_offered)
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 mb-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-0.5 font-heading">Roles Offered</span>
                            <p class="text-xs text-slate-700 font-medium leading-relaxed">
                                {{ $partner->roles_offered }}
                            </p>
                        </div>
                    @endif

                    <!-- Hiring Branches Pills -->
                    @if($partner->hiring_departments)
                        <div class="space-y-1 mb-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-heading">Eligible Branches</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($partner->departments_list as $dept)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-[#1e3a8a] border border-blue-100">
                                        {{ $dept }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Action & Admin Controls -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2 mt-2">
                    @if($partner->website_url)
                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors flex items-center gap-1 font-heading">
                            <span>Official Website</span>
                            <span>&nearr;</span>
                        </a>
                    @else
                        <span class="text-xs text-slate-400 font-semibold italic">Campus Drive Partner</span>
                    @endif

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="flex items-center gap-2">
                                <button type="button" @click="editPartner = {{ json_encode($partner) }}" class="p-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer" title="Edit Partner">
                                    ✏️
                                </button>
                                <form action="{{ route('admin.placement.partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Remove recruiter from TPO wall?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer" title="Delete Partner">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center text-2xl">
                    🏢
                </div>
                <h3 class="text-base font-bold text-[#0f172a] mb-1 font-heading">No Recruiting Partners Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-sans">
                    No corporate partners match the selected branch criteria. Try selecting "All Engineering Disciplines" or clearing search filters.
                </p>
            </div>
        @endforelse
    </div>

    <!-- TPO Corporate Desk & Campus Drive Invitation Section -->
    <div class="bg-gradient-to-br from-slate-900 via-[#1e3a8a] to-[#0f172a] rounded-3xl p-8 sm:p-12 text-white shadow-xl border border-blue-900/60 relative overflow-hidden">
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8 space-y-4">
                <span class="inline-block text-[11px] font-black uppercase tracking-widest text-[#f97316] bg-orange-500/10 px-3 py-1 rounded-full border border-orange-400/30 font-heading">
                    Employer &amp; Industry Collaboration Desk
                </span>
                <h2 class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-white leading-tight">
                    Invite K. D. Polytechnic for Campus Recruitment &amp; Apprenticeship Drives
                </h2>
                <p class="text-blue-100 text-xs sm:text-sm font-sans leading-relaxed max-w-2xl">
                    Our diploma students undergo rigorous hands-on laboratory training, GTU curriculum foundations, and industrial workshop simulations across Civil, Mechanical, Electrical, and Computer disciplines.
                </p>
                <div class="pt-2 flex flex-wrap gap-4 text-xs font-semibold text-blue-200">
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> On-Campus &amp; Pooled Drives</span>
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> NATS / Apprenticeship Placement</span>
                    <span class="flex items-center gap-1.5"><span class="text-[#f97316]">✓</span> Pre-Placement Talks &amp; Hackathons</span>
                </div>
            </div>

            <div class="lg:col-span-4 bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 space-y-4">
                <h4 class="font-heading font-bold text-sm text-white uppercase tracking-wider">TPO Office Contact</h4>
                <div class="space-y-2 text-xs text-blue-100">
                    <p class="flex items-center gap-2">
                        <span>🏛️</span>
                        <span>Training &amp; Placement Cell, KDP Patan</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span>📞</span>
                        <span>+91 2766 220419</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span>✉️</span>
                        <a href="mailto:tpo@kdppatan.ac.in" class="hover:underline text-white font-bold">tpo@kdppatan.ac.in</a>
                    </p>
                    <p class="flex items-center gap-2">
                        <span>📍</span>
                        <span>Near Hemchandracharya North Gujarat University, Patan - 384265</span>
                    </p>
                </div>
                <a href="{{ route('contact') }}" class="block text-center w-full py-2.5 px-4 rounded-xl bg-[#f97316] hover:bg-orange-600 text-white font-bold text-xs uppercase tracking-wider font-heading transition-colors shadow-xs">
                    Contact Placement Desk &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Admin Add Partner Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="addModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🏢</span>
                    <h3 class="font-bold text-base font-heading">Add Corporate Recruiter</h3>
                </div>
                <button type="button" @click="addModal = false" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.placement.partners.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company / Organization Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Larsen & Toubro" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Industry Sector <span class="text-red-500">*</span></label>
                        <input type="text" name="sector" required placeholder="e.g. EPC & Infrastructure" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Package Range</label>
                        <input type="text" name="package_range" placeholder="e.g. ₹3.2 - 4.5 LPA" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Roles / Designations Offered</label>
                    <input type="text" name="roles_offered" placeholder="e.g. Diploma Engineer Trainee (DET)" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Eligible Branches (Comma-separated)</label>
                    <input type="text" name="hiring_departments" placeholder="Civil Engineering, Mechanical Engineering, Electrical Engineering" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Website URL</label>
                        <input type="url" name="website_url" placeholder="https://example.com" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Display Priority Order</label>
                        <input type="number" name="order_num" value="0" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Add Recruiter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Edit Partner Modal -->
    <div x-show="editPartner !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editPartner = null" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✏️</span>
                    <h3 class="font-bold text-base font-heading">Edit Corporate Recruiter</h3>
                </div>
                <button type="button" @click="editPartner = null" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/admin/placement/partners/' + (editPartner ? editPartner.id : '')" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editPartner.name" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sector <span class="text-red-500">*</span></label>
                        <input type="text" name="sector" x-model="editPartner.sector" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Package Range</label>
                        <input type="text" name="package_range" x-model="editPartner.package_range" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Roles Offered</label>
                    <input type="text" name="roles_offered" x-model="editPartner.roles_offered" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hiring Branches</label>
                    <input type="text" name="hiring_departments" x-model="editPartner.hiring_departments" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Website URL</label>
                        <input type="url" name="website_url" x-model="editPartner.website_url" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Order Priority</label>
                        <input type="number" name="order_num" x-model="editPartner.order_num" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editPartner = null" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
