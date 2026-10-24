@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Watermark Backgrounds -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="{{ asset('images/kdp_crest.png') }}" class="w-80 h-80 object-contain filter invert" alt="KDP Crest Watermark">
        </div>
        <div class="absolute -left-12 -top-12 opacity-5 pointer-events-none">
            <img src="{{ asset('images/gtu_cte_logo.jpg') }}" class="w-64 h-64 object-contain filter invert" alt="GTU Seal Watermark">
        </div>

        <div class="max-w-4xl mx-auto relative z-10 text-center">
            <!-- Institutional Badges -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading mb-3">
                <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                <span>Government Polytechnic &bull; Estd. 1961</span>
            </div>
            
            <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </p>
            <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white mb-2">
                {{ $user->name }}
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 max-w-xl mx-auto leading-relaxed font-sans">
                {{ ucfirst($user->role ?? 'Alumni') }} &bull; {{ $user->department ?? 'Diploma Engineering' }}
                @if($user->graduation_year)
                    &bull; Class of {{ $user->graduation_year }}
                @endif
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN PROFILE DETAIL CARD                    -->
    <!-- ============================================== -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20" x-data="{ openMessageModal: false }">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden">
            
            <!-- Top Gradient Accent Strip -->
            <div class="h-1.5 bg-gradient-to-r from-[#1e3a8a] via-[#f97316] to-[#f59e0b]"></div>

            <div class="p-6 sm:p-10 space-y-8">
                
                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                        <span class="text-base">✅</span>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Profile Header with Avatar -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-6 border-b border-slate-100">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-white border-2 border-[#1e3a8a]/20 shadow-md flex items-center justify-center shrink-0">
                        @if(!empty($user->photo_path))
                            <img src="{{ filter_var($user->photo_path, FILTER_VALIDATE_URL) ? $user->photo_path : asset('storage/' . $user->photo_path) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-[#1e3a8a] text-white flex items-center justify-center font-heading font-black text-3xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-50 text-[#f97316] border border-orange-200 font-heading">
                                {{ ucfirst($user->role ?? 'Alumni') }}
                            </span>
                            @if($user->graduation_year)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-[#1e3a8a] border border-blue-200 font-heading">
                                    Class of {{ $user->graduation_year }}
                                </span>
                            @endif
                            @if($user->is_approved)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 font-heading flex items-center gap-1">
                                    <span>✓</span> Verified Member
                                </span>
                            @endif
                        </div>

                        <h2 class="font-heading font-black text-2xl text-[#0f172a]">
                            {{ $user->name }}
                        </h2>
                        <p class="text-xs text-slate-500 font-sans mt-0.5">
                            {{ $user->degree ?? 'Diploma in Engineering' }} &bull; {{ $user->department ?? 'KD Polytechnic' }}
                        </p>
                    </div>

                    <!-- Direct Contact / Message Action -->
                    <div class="shrink-0">
                        <a href="{{ route('messages.inbox', $user->id) }}" class="inline-flex items-center gap-2 bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md transition-all font-heading">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span>Send Message</span>
                        </a>
                    </div>
                </div>

                <!-- Academic Credentials Grid -->
                <div>
                    <h3 class="font-heading font-black text-xs uppercase tracking-wider text-[#1e3a8a] pb-2 mb-4 border-b border-slate-100 flex items-center gap-2">
                        <span>🎓</span>
                        <span>Academic &amp; GTU Credentials</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Degree / Program</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->degree ?? 'Diploma' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Department</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->department ?? 'General' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Year of Joining</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->year_joining ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Graduation Batch</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->graduation_year ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Professional Details Grid -->
                <div>
                    <h3 class="font-heading font-black text-xs uppercase tracking-wider text-[#1e3a8a] pb-2 mb-4 border-b border-slate-100 flex items-center gap-2">
                        <span>💼</span>
                        <span>Professional Trajectory</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Company / Organization</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->company ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Designation / Role</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->designation ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Work Industry</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->work_industry ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <span class="text-slate-400 font-medium block text-[10px] uppercase font-heading">Domain Skills</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $user->skills ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="font-heading font-black text-xs uppercase tracking-wider text-[#1e3a8a] pb-2 mb-4 border-b border-slate-100 flex items-center gap-2">
                        <span>📞</span>
                        <span>Contact &amp; Directory Outreach</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between">
                            <span class="text-slate-500 font-medium">✉️ Email:</span>
                            @if(optional($user->profile)->is_email_public || (auth()->check() && auth()->user()->role === 'admin'))
                                <a href="mailto:{{ $user->email }}" class="text-[#1e3a8a] font-bold hover:underline">{{ $user->email }}</a>
                            @else
                                <span class="text-slate-400 italic">Hidden by user</span>
                            @endif
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between">
                            <span class="text-slate-500 font-medium">📞 Phone:</span>
                            @if(optional($user->profile)->is_phone_public || (auth()->check() && auth()->user()->role === 'admin'))
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-[#1e3a8a] font-bold hover:underline">{{ $user->phone }}</a>
                                @else
                                    <span class="text-slate-400">Not Provided</span>
                                @endif
                            @else
                                <span class="text-slate-400 italic">Hidden by user</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('alumni.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#1e3a8a] font-heading">
                        <span>&larr;</span>
                        <span>Back to Alumni Directory</span>
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
