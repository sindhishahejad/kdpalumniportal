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

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Institutional Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold uppercase tracking-widest font-heading">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    Government Polytechnic &bull; Estd. 1961
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                    Administrative Services &bull; Student Section
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                    <span>📄</span> {{ $requests->count() }} Total Applications
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                        Official Document Request Desk
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                        Fast-track issuance of official GTU transcripts, bonafide certificates, recommendation letters, and medium of instruction certificates signed by the campus administration.
                    </p>
                </div>

                <!-- Office Guidance Box -->
                <div class="shrink-0 bg-white/10 backdrop-blur-xs border border-white/15 rounded-2xl p-4 text-xs max-w-xs text-blue-100 hidden sm:block">
                    <div class="flex items-center gap-2 font-bold text-white uppercase font-heading mb-1 text-[11px]">
                        <span>🏛️</span> Campus Student Section
                    </div>
                    <p class="text-[11px] leading-relaxed text-blue-200/90">
                        Turnaround: 2-3 working days. Digitally uploaded copies can be downloaded directly from your request history below.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE (SUBMIT FORM + HISTORY)      -->
    <!-- ============================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        
        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                <span class="text-base">✅</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                <span class="text-base">⚠️</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: SUBMISSION FORM (4 cols on lg) -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden sticky top-6">
                    
                    <div class="bg-[#1e3a8a] text-white p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📝</span>
                            <h3 class="font-heading font-black text-xs uppercase tracking-wider">
                                New Document Request
                            </h3>
                        </div>
                        <span class="text-[10px] uppercase font-bold text-orange-300 font-heading bg-white/10 px-2 py-0.5 rounded-full">
                            GTU Standard
                        </span>
                    </div>

                    <div class="p-6">
                        <p class="text-xs text-slate-500 mb-5 font-sans leading-relaxed">
                            Select your required institutional certificate and specify your purpose (e.g., D2D admission, company verification, or higher education).
                        </p>
                        
                        <form action="{{ route('documents.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Document Type -->
                            <div>
                                <label for="document_type" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Document Type <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="document_type" name="document_type" required 
                                            class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans cursor-pointer">
                                        <option value="">Select Document...</option>
                                        <option value="Official Transcript">Official Transcript (GTU Marks Record)</option>
                                        <option value="Recommendation Letter">Recommendation Letter (HOD / Faculty)</option>
                                        <option value="Bonafide Certificate">Bonafide Certificate (Identity / Passport)</option>
                                        <option value="Provisional Degree">Provisional Degree Certificate</option>
                                        <option value="Medium of Instruction Certificate">Medium of Instruction Certificate</option>
                                    </select>
                                </div>
                                @error('document_type')
                                    <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Purpose of Request -->
                            <div>
                                <label for="purpose" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Purpose of Request <span class="text-red-500">*</span>
                                </label>
                                <textarea id="purpose" name="purpose" rows="4" required 
                                          class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans" 
                                          placeholder="e.g., Applying for B.E. Degree Admission through D2D at GTU, or Company Joining Requirements at L&amp;T..."></textarea>
                                @error('purpose')
                                    <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Note Callout -->
                            <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-3 text-[11px] text-amber-800 space-y-1">
                                <span class="font-bold font-heading block uppercase text-[10px] text-amber-900">
                                    🏛️ Institutional Verification
                                </span>
                                <p class="leading-relaxed">
                                    All documents are signed by the Principal/Head of Department and stamped with the official seal of K. D. Polytechnic, Patan.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                                    <span>Submit Request</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN: REQUEST HISTORY & FULFILLMENT (8 cols on lg) -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200/90 overflow-hidden">
                    
                    <div class="bg-slate-50/80 p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-[#1e3a8a]"></span>
                            <h2 class="font-heading font-black text-sm uppercase tracking-wide text-[#0f172a]">
                                My Request History
                            </h2>
                        </div>
                        <span class="text-xs text-slate-500 font-medium">
                            {{ $requests->count() }} application(s) filed
                        </span>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if($requests->isEmpty())
                            <div class="py-16 text-center">
                                <span class="text-4xl block mb-2">📭</span>
                                <h3 class="font-heading font-black text-base text-[#0f172a] uppercase">No Document Requests Yet</h3>
                                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto font-sans leading-relaxed">
                                    You haven't requested any documents yet. Fill out the form on the left to request official certificates, GTU transcripts, or bonafide letters.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs text-left">
                                    <thead>
                                        <tr class="bg-slate-50 text-slate-700 font-heading font-bold uppercase tracking-wider border-b border-slate-200/80">
                                            <th class="px-4 py-3 text-[11px]">Date</th>
                                            <th class="px-4 py-3 text-[11px]">Document</th>
                                            <th class="px-4 py-3 text-[11px]">Status</th>
                                            <th class="px-4 py-3 text-[11px]">Admin Notes</th>
                                            <th class="px-4 py-3 text-[11px] text-right">Action / Download</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($requests as $req)
                                            <tr class="hover:bg-slate-50/80 transition-colors">
                                                
                                                <!-- Date & ID -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-slate-600 font-medium font-sans">
                                                    {{ $req->created_at->format('d M, Y') }}
                                                    <span class="block text-[10px] text-slate-400 font-heading uppercase tracking-wide">
                                                        KDP-DOC-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>

                                                <!-- Document & Purpose -->
                                                <td class="px-4 py-3.5">
                                                    <div class="font-heading font-bold text-slate-900 text-xs">
                                                        {{ $req->document_type }}
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 font-sans mt-0.5 line-clamp-1" title="{{ $req->purpose }}">
                                                        {{ $req->purpose }}
                                                    </p>
                                                </td>

                                                <!-- Status Badge -->
                                                <td class="px-4 py-3.5 whitespace-nowrap">
                                                    @if($req->status === 'Pending')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-heading">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                            Pending Review
                                                        </span>
                                                    @elseif($req->status === 'Processing')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-heading">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                            In Process
                                                        </span>
                                                    @elseif($req->status === 'Ready')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-heading">
                                                            <span>✓</span> Ready
                                                        </span>
                                                    @elseif($req->status === 'Rejected')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full bg-rose-50 text-rose-800 border border-rose-200 font-heading">
                                                            <span>✕</span> Rejected
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700 font-heading">
                                                            {{ $req->status }}
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- Admin Notes -->
                                                <td class="px-4 py-3.5 text-slate-600 font-sans max-w-xs">
                                                    @if($req->admin_notes)
                                                        <span class="text-[11px] leading-relaxed block italic text-slate-700">
                                                            {{ $req->admin_notes }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-300">-</span>
                                                    @endif
                                                </td>

                                                <!-- Action / Fulfillment Link -->
                                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                                    @if($req->file_path)
                                                        <a href="{{ route('documents.download', $req->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-bold uppercase tracking-wider py-1.5 px-3 rounded-xl shadow-xs transition-colors font-heading">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            <span>Download PDF</span>
                                                        </a>
                                                    @elseif($req->status === 'Ready')
                                                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-800 font-bold bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200 font-heading">
                                                            <span>🏛️</span> Ready at Office
                                                        </span>
                                                    @elseif($req->status === 'Processing')
                                                        <span class="text-[11px] text-blue-600 font-semibold font-heading">In Process</span>
                                                    @elseif($req->status === 'Rejected')
                                                        <span class="text-[11px] text-rose-600 font-semibold font-heading">Not Approved</span>
                                                    @else
                                                        <span class="text-[11px] text-slate-400 font-medium">Pending Review</span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection
