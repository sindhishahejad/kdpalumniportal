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

        <div class="max-w-5xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Project Incubation Lifecycle
                </span>
            </div>

            <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </p>
            <h1 class="font-heading text-2xl sm:text-3xl font-black uppercase tracking-wide text-white leading-tight">
                {{ $pitch->title }}
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                Student innovation initiative under KDP Incubation &amp; Mentorship Desk.
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Back Link & Top Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-3 mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('pitches.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#1e3a8a] transition-colors font-heading uppercase tracking-wider">
                <span>&larr;</span>
                <span>Back to Incubation Board</span>
            </a>

            <span class="text-xs text-slate-500 font-mono">
                Submitted {{ $pitch->created_at->format('M d, Y') }} ({{ $pitch->created_at->diffForHumans() }})
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-base">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs sm:text-sm rounded-r-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-base">&times;</button>
            </div>
        @endif

        @php
            $statusStyles = [
                'open' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'badge' => 'Open for Mentorship', 'dot' => 'bg-emerald-500'],
                'in_progress' => ['bg' => 'bg-blue-50 text-[#1e3a8a] border-blue-200', 'badge' => 'In Progress / Mentoring Active', 'dot' => 'bg-[#1e3a8a]'],
                'completed' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'badge' => 'Completed / Successfully Incubated', 'dot' => 'bg-purple-500'],
            ][$pitch->status] ?? ['bg' => 'bg-slate-100 text-slate-800 border-slate-200', 'badge' => ucfirst($pitch->status), 'dot' => 'bg-slate-400'];

            $isOwner = auth()->check() && auth()->id() === $pitch->user_id;
            $canUpdateStatus = auth()->check() && ($isOwner || auth()->user()->role === 'faculty' || auth()->user()->isAdmin());
            $canDelete = auth()->check() && ($isOwner || auth()->user()->isAdmin());
            $canOfferGuidance = auth()->check() && !$isOwner && in_array(auth()->user()->role, ['alumni', 'faculty', 'admin']);
        @endphp

        <!-- Main Card Container -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 overflow-hidden mb-8">
            
            <!-- Status Header Strip -->
            <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border {{ $statusStyles['bg'] }} font-heading">
                        <span class="w-2 h-2 rounded-full {{ $statusStyles['dot'] }} animate-pulse"></span>
                        {{ $statusStyles['badge'] }}
                    </span>
                </div>

                <div class="text-xs text-slate-500 font-heading">
                    Tracking ID: <span class="font-mono text-slate-700 font-bold">KDP-PITCH-{{ str_pad($pitch->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Content Body -->
            <div class="p-6 sm:p-8 space-y-8">
                
                <!-- Student Innovator Profile Strip -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-[#1e3a8a] text-white flex items-center justify-center font-bold text-lg font-heading shadow-md">
                            {{ substr(optional($pitch->student)->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-[#0f172a] font-heading">{{ optional($pitch->student)->name ?? 'Student Innovator' }}</p>
                            <p class="text-xs text-slate-500 font-sans mt-0.5">
                                {{ optional($pitch->student)->department ?? 'KD Polytechnic' }}
                                @if(optional($pitch->student)->entry_no) &bull; Roll: {{ $pitch->student->entry_no }} @endif
                            </p>
                        </div>
                    </div>

                    @if(!$isOwner && auth()->check())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('messages.inbox', $pitch->user_id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] border border-blue-200 text-xs font-bold rounded-xl transition-colors font-heading shadow-xs">
                                <span>💬 Chat with Student</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Specific Assistance Requested Callout -->
                <div class="bg-orange-50/80 border-l-4 border-[#f97316] p-5 rounded-r-2xl border border-orange-200/80">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">💡</span>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider text-[#f97316] font-heading">Specific Assistance Requested</h3>
                            <p class="text-xs sm:text-sm text-slate-800 font-medium mt-1 leading-relaxed">{{ $pitch->assistance_needed }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tech Stack Section -->
                <div>
                    <h3 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-2 font-heading">Technologies &amp; Frameworks</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_map('trim', explode(',', $pitch->tech_stack)) as $tag)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-mono font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200/80 shadow-2xs">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Problem Statement & Description -->
                <div>
                    <h3 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-2 font-heading">Project Vision &amp; Problem Statement</h3>
                    <div class="prose max-w-none text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line bg-slate-50/60 p-6 rounded-2xl border border-slate-100 font-sans">
                        {{ $pitch->description }}
                    </div>
                </div>

                <!-- Guidance Section Anchor -->
                <div id="guidance"></div>

                <!-- Mentorship Guidance Submission Card -->
                @if($canOfferGuidance)
                    <div class="bg-blue-50/60 rounded-3xl border border-blue-200/80 p-6 sm:p-7 shadow-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#1e3a8a] text-white font-heading">
                                Alumni &amp; Faculty Mentorship Desk
                            </span>
                            <h3 class="text-sm sm:text-base font-bold font-heading text-[#0f172a]">Offer Technical Guidance &amp; Support</h3>
                        </div>
                        <p class="text-xs text-slate-600 mb-4 font-sans leading-relaxed">
                            Submit actionable advice, hardware suggestions, or review notes. This will instantly deliver an in-app message and notification to the student team.
                        </p>

                        <form method="POST" action="{{ route('pitches.offer-guidance', $pitch->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="guidance" rows="3" required placeholder="Write your mentorship advice, hardware recommendations, or guidance offer here..." class="w-full text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] p-3 shadow-xs font-sans leading-relaxed">{{ old('guidance') }}</textarea>
                                @error('guidance') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-sm transition-colors font-heading inline-flex items-center gap-2 cursor-pointer">
                                    <span>🤝 Deliver Guidance Message &rarr;</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Lifecycle Status Management -->
                @if($canUpdateStatus)
                    <div class="border-t border-slate-200 pt-6">
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <h3 class="text-sm font-bold font-heading text-[#0f172a] mb-1 flex items-center gap-2">
                                <span>⚙️</span>
                                <span>Incubation Lifecycle Stage</span>
                            </h3>
                            <p class="text-xs text-slate-500 mb-4 font-sans">
                                Update this project's status as the student and alumni collaborate through mentoring, prototyping, and final submission.
                            </p>

                            <form method="POST" action="{{ route('pitches.update-status', $pitch->id) }}" class="flex flex-wrap items-center gap-3">
                                @csrf
                                @method('PATCH')

                                <button type="submit" name="status" value="open" class="px-4 py-2 rounded-xl text-xs font-bold border transition-all cursor-pointer font-heading uppercase tracking-wider {{ $pitch->status === 'open' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-emerald-50' }}">
                                    &bull; Open for Mentorship
                                </button>

                                <button type="submit" name="status" value="in_progress" class="px-4 py-2 rounded-xl text-xs font-bold border transition-all cursor-pointer font-heading uppercase tracking-wider {{ $pitch->status === 'in_progress' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-blue-50' }}">
                                    &bull; In Progress / Mentoring
                                </button>

                                <button type="submit" name="status" value="completed" class="px-4 py-2 rounded-xl text-xs font-bold border transition-all cursor-pointer font-heading uppercase tracking-wider {{ $pitch->status === 'completed' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-purple-50' }}">
                                    &bull; Completed / Incubated
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Danger Zone: Delete Pitch -->
                @if($canDelete)
                    <div class="border-t border-slate-200 pt-4 flex justify-end">
                        <form method="POST" action="{{ route('pitches.destroy', $pitch->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this project pitch?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 hover:underline inline-flex items-center gap-1 font-bold font-heading cursor-pointer">
                                <span>🗑️ Delete Project Pitch</span>
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
