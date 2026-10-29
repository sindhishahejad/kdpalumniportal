@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="relative bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-red-50/50 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                            <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                            કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                        </span>
                        <span class="bg-red-50 text-red-800 text-xs font-extrabold uppercase tracking-wider py-1 px-3 rounded-full border border-red-100 font-heading">
                            Administrative Secretariat
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight">
                        Admin Central Command
                    </h1>
                    <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Manage user approvals, oversee job postings, moderate circulars, and monitor alumni donations and campus operations.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0">
                    <a href="{{ route('admin.alerts.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all font-heading">
                        <span>📱</span> WhatsApp Alert Hub &rarr;
                    </a>
                    <span class="bg-red-50 text-red-700 text-xs font-extrabold uppercase tracking-widest py-3 px-4 rounded-2xl border border-red-200 font-heading">
                        Root Administrator
                    </span>
                </div>
            </div>

            <!-- Admin Stats Cards Grid (6 Metric Cards) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-8 pt-8 border-t border-slate-100">
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Alumni</span>
                    <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['total_alumni'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Verified Graduates</span>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Students</span>
                    <span class="text-2xl sm:text-3xl font-black text-[#0f2042] mt-1 block font-heading">{{ $stats['active_students'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Enrolled Diploma</span>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Pending Jobs</span>
                    <span class="text-2xl sm:text-3xl font-black text-[#f97316] mt-1 block font-heading">{{ $stats['pending_jobs'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Requires Approval</span>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Tool Bank</span>
                    <span class="text-2xl sm:text-3xl font-black text-blue-700 mt-1 block font-heading">{{ $stats['available_tools'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Available Items</span>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Pitches</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-600 mt-1 block font-heading">{{ $stats['open_pitches'] ?? 0 }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Open Capstones</span>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block font-heading">Giving Pool</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700 mt-1 block font-heading">₹{{ number_format($stats['total_donations'] ?? 0, 0) }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Completed Fund</span>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center gap-3">
                <span class="text-emerald-600 text-lg">✓</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center gap-3">
                <span class="text-red-600 text-lg">⚠</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Pending Registrations Queue -->
        @if(isset($pendingUsers) && $pendingUsers->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-yellow-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                Pending Registrations 
                <span class="ml-3 bg-yellow-100 text-yellow-800 text-xs font-extrabold px-3 py-1 rounded-full">{{ $pendingUsers->count() }} Requires Action</span>
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role / Dept</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Decision</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pendingUsers as $pending)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $pending->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $pending->email }} • {{ $pending->phone ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">ID/Roll: {{ $pending->entry_no ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $pending->role }}</span>
                                    <div class="text-xs text-gray-600 mt-1">{{ $pending->department ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.approve', $pending) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-sm text-xs font-bold transition-colors">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $pending) }}" onsubmit="return confirm('Reject and delete this applicant forever?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-sm text-xs font-bold transition-colors">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Direct WhatsApp & Email Alert Control Center Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden bg-gradient-to-r from-emerald-50/40 via-white to-white">
            <div class="absolute inset-y-0 left-0 w-2 bg-emerald-500"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📱</span>
                    <h3 class="text-lg font-bold text-[#0f172a]">Direct WhatsApp &amp; Email Alert Center</h3>
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 rounded-full">Zero Cost &bull; Instant</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Dispatch 1-click WhatsApp alerts to students &amp; alumni, test live SMTP email delivery, and view broadcast dispatch logs.</p>
            </div>
            <div class="flex items-center gap-3 pl-3 sm:pl-0">
                <a href="{{ route('admin.alerts.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm shrink-0 flex items-center gap-2">
                    <span>⚡</span> Open Alert Hub &rarr;
                </a>
            </div>
        </div>


        <!-- Document Requests Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-[#0f2042] border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">📜</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Document Requests Desk</h3>
                    @if(($stats['pending_documents'] ?? 0) > 0)
                        <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 rounded-full font-heading">{{ $stats['pending_documents'] }} Pending</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-sans">Manage alumni requests for official transcripts, recommendation letters, and bonafide certificates.</p>
            </div>
            <a href="{{ route('admin.documents.index') }}" class="bg-[#0f2042] hover:bg-[#1e3a8a] text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Requests &rarr;
            </a>
        </div>

        <!-- Contact Inquiries Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-indigo-600 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">✉️</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Contact Inquiries</h3>
                    @if(($stats['pending_inquiries'] ?? 0) > 0)
                        <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 rounded-full font-heading">{{ $stats['pending_inquiries'] }} New</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-sans">Review, reply to, and resolve public messages and admission queries submitted through the contact portal.</p>
            </div>
            <a href="{{ route('admin.inquiries.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Inquiries &rarr;
            </a>
        </div>

        <!-- Alumni Giving Back & Donations Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-600 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">💰</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Giving Back &amp; Donations Hub</h3>
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 rounded-full font-heading">Razorpay Verified</span>
                </div>
                <p class="text-xs text-slate-500 font-sans">Track alumni contributions to student scholarships, department lab upgrades, and download official 80G tax receipts.</p>
            </div>
            <a href="{{ route('donations.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                View Donations &rarr;
            </a>
        </div>

        <!-- Pay-It-Forward Tool & Book Bank Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-[#f97316] border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">📐</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Pay-It-Forward Tool &amp; Book Bank</h3>
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-orange-100 text-orange-800 rounded-full font-heading">{{ $stats['available_tools'] ?? 0 }} Items Active</span>
                </div>
                <p class="text-xs text-slate-500 font-sans">Oversee engineering calculators, mini-drafters, and GTU textbooks shared between alumni and juniors.</p>
            </div>
            <a href="{{ route('tools.index') }}" class="bg-[#f97316] hover:bg-[#ea580c] text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Tool Bank &rarr;
            </a>
        </div>

        <!-- Student Project Incubation & Pitches Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-600 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">💡</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Student Capstone &amp; Project Incubation</h3>
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 rounded-full font-heading">{{ $stats['open_pitches'] ?? 0 }} Open Pitches</span>
                </div>
                <p class="text-xs text-slate-500 font-sans">Review student minor/major project submissions seeking alumni hardware funding or technical guidance.</p>
            </div>
            <a href="{{ route('pitches.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Review Pitches &rarr;
            </a>
        </div>

        <!-- GTU Academic Calendar Management Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-indigo-700 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">🗓️</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">GTU Academic Calendar &amp; Circulars</h3>
                </div>
                <p class="text-xs text-slate-500 font-sans">Publish term dates, mid-semester exams, practical submissions, and official GTU notices for students and faculty.</p>
            </div>
            <a href="{{ route('calendar.index') }}" class="bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Calendar &rarr;
            </a>
        </div>

        <!-- Faculty & HOD Directory Management Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-[#0f2042] border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">👥</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Department Faculty &amp; HOD Directory</h3>
                    <span class="bg-blue-50 text-[#1e3a8a] text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border border-blue-200 font-heading">
                        {{ $stats['total_faculty'] ?? \App\Models\FacultyMember::count() }} Faculty ({{ $stats['total_hods'] ?? \App\Models\FacultyMember::where('is_hod', true)->count() }} HODs)
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-sans">Add new faculty members, appoint Heads of Departments (HOD), update qualifications, teaching focus, email contacts, or remove profiles.</p>
            </div>
            <a href="{{ route('faculty.index') }}" class="bg-[#0f2042] hover:bg-[#1e3a8a] text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs flex items-center gap-1.5">
                <span>Manage Faculty &amp; HODs &rarr;</span>
            </a>
        </div>

        <!-- Training & Placement (TPO) Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-cyan-600 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">🏢</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Training &amp; Placement (TPO) Partner Cell</h3>
                </div>
                <p class="text-xs text-slate-500 font-sans">Maintain campus recruiting partners, drive dates, package figures, and annual placement achievements.</p>
            </div>
            <a href="{{ route('placement.index') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Placement Cell &rarr;
            </a>
        </div>

        <!-- Success Stories Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-[#1e3a8a] border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">🌟</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Alumni Success Stories &amp; Spotlights</h3>
                </div>
                <p class="text-xs text-slate-500 font-sans">Highlight alumni achievements, executive appointments, and career spotlights across all engineering branches.</p>
            </div>
            <a href="{{ route('admin.stories.index') }}" class="bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Stories &rarr;
            </a>
        </div>

        <!-- Alumni Awards & Nominations Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-500 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xl">🏆</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Annual Alumni Awards &amp; Hall of Fame</h3>
                </div>
                <p class="text-xs text-slate-500 font-sans">Review community nominations, manage voting stages, and induct distinguished graduates into the permanent Hall of Fame.</p>
            </div>
            <a href="{{ route('admin.awards.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider transition-colors whitespace-nowrap font-heading shadow-xs">
                Manage Awards &amp; Nominations &rarr;
            </a>
        </div>

        <!-- Department Labs & Workshops Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-blue-600"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <h3 class="text-lg font-bold text-[#0f2042] font-heading">Department Labs &amp; Workshops</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage laboratory equipment inventories, room locations, and faculty coordinator records.</p>
            </div>
            <div class="flex items-center gap-3 pl-3 sm:pl-0">
                <a href="{{ route('labs.index') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-blue-700 transition-all shadow-xs shrink-0 font-heading">
                    Manage Labs &rarr;
                </a>
            </div>
        </div>

        <!-- Knowledge Feed & Challenge Moderation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-rose-600"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <h3 class="text-lg font-bold text-[#0f172a]">Knowledge Feed &amp; Challenge Moderation</h3>
                <p class="text-sm text-gray-500 mt-0.5">Review community discussions, code challenges, and remove flagged or inappropriate replies.</p>
            </div>
            <div class="flex items-center gap-3 pl-3 sm:pl-0">
                <a href="{{ route('posts.index') }}" class="bg-rose-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-rose-700 transition-all shadow-sm shrink-0">
                    Moderate Feed &rarr;
                </a>
            </div>
        </div>

        <!-- Weekly Newsletter & Campus Digest Automation -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-[#0f2042]"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📰</span>
                    <h3 class="text-lg font-bold text-[#0f2042] font-heading">Weekly Newsletter &amp; Campus Digest</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 font-heading">
                        Scheduled: Mondays 8:00 AM
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-0.5">Automated digest of latest alumni spotlights, job postings, and campus announcements.</p>
            </div>
            <div class="flex items-center gap-2 pl-3 sm:pl-0 flex-wrap">
                <form method="POST" action="{{ route('admin.newsletter.send') }}">
                    @csrf
                    <input type="hidden" name="test_only" value="1">
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-4 py-2 rounded-xl font-semibold text-xs transition-colors shrink-0 flex items-center gap-1.5 font-heading" title="Sends test copy to your email">
                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Test Email to Me
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.newsletter.send') }}" onsubmit="return confirm('Queue and send the weekly digest email to all approved alumni, students, and faculty?');">
                    @csrf
                    <button type="submit" class="bg-[#0f2042] text-white px-5 py-2.5 rounded-xl font-semibold text-xs uppercase tracking-wider hover:bg-[#1e3a8a] transition-all shadow-xs shrink-0 flex items-center gap-1.5 font-heading">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Dispatch Digest Now
                    </button>
                </form>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 📋 DYNAMIC NOTICE BOARD & CIRCULARS CONTROL CENTER (Full CRUD) -->
        <!-- ============================================================== -->
        <div id="notice-center" 
             x-data="{ 
                 showCreateModal: false, 
                 editNotice: null, 
                 search: '', 
                 filterStatus: 'all',
                 notices: {{ json_encode($notices) }},
                 matchesNotice(item) {
                     const q = this.search.toLowerCase().trim();
                     const matchesQuery = !q || item.title.toLowerCase().includes(q) || item.body.toLowerCase().includes(q);
                     const matchesFilter = this.filterStatus === 'all' 
                         || (this.filterStatus === 'active' && item.is_active) 
                         || (this.filterStatus === 'inactive' && !item.is_active);
                     return matchesQuery && matchesFilter;
                 }
             }" 
             class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Header Banner -->
            <div class="p-6 bg-gradient-to-r from-[#1e3a8a] via-[#1e293b] to-[#1e3a8a] text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-white/10 text-xl shadow-xs">📋</span>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold font-heading">Notice Board &amp; Circulars Control Center</h2>
                            <p class="text-xs sm:text-sm text-blue-200 mt-0.5">
                                Fully dynamic management of GTU circulars, exam notices, and fee alerts broadcast to the homepage and alumni network.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                    <a href="{{ url('/') }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all flex items-center gap-1.5 font-heading">
                        <span>👁️</span> View on Portal &rarr;
                    </a>
                    <button type="button" 
                            @click="showCreateModal = true" 
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-[#f97316] hover:bg-orange-600 text-white shadow-md transition-all flex items-center gap-1.5 font-heading cursor-pointer transform hover:scale-105">
                        <span class="text-base font-black">+</span> Publish New Circular
                    </button>
                </div>
            </div>

            <!-- Stats & Filter Bar -->
            <div class="p-4 sm:p-6 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <!-- Stat chips -->
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" 
                            @click="filterStatus = 'all'" 
                            :class="filterStatus === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span>All Notices</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="filterStatus === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $notices->count() }}</span>
                    </button>

                    <button type="button" 
                            @click="filterStatus = 'active'" 
                            :class="filterStatus === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Live on Portal</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="filterStatus === 'active' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $notices->where('is_active', true)->count() }}</span>
                    </button>

                    <button type="button" 
                            @click="filterStatus = 'inactive'" 
                            :class="filterStatus === 'inactive' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Hidden / Draft</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="filterStatus === 'inactive' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $notices->where('is_active', false)->count() }}</span>
                    </button>
                </div>

                <!-- Instant Search Input -->
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        🔍
                    </span>
                    <input type="text" 
                           x-model="search" 
                           placeholder="Filter circulars by title..." 
                           class="w-full pl-9 pr-4 py-1.5 text-xs rounded-xl border-slate-300 bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs">
                    <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Notices Feed List -->
            <div class="p-6">
                <div class="space-y-3.5">
                    @forelse($notices as $notice)
                        <div x-show="matchesNotice({{ json_encode($notice) }})" 
                             class="group p-4 rounded-xl border border-slate-200/90 hover:border-slate-300 transition-all bg-white hover:shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $notice->is_active ? 'border-l-4 border-l-[#f97316]' : 'border-l-4 border-l-slate-300 opacity-75' }}">
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2.5 mb-1.5 flex-wrap">
                                    @if($notice->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Live on Portal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Hidden / Inactive
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400 flex items-center gap-1 font-mono">
                                        📅 {{ $notice->created_at->format('M d, Y • h:i A') }}
                                    </span>

                                    @if($notice->attachment_path)
                                        <a href="{{ $notice->attachment_url }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border transition-colors {{ $notice->is_pdf ? 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' : 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100' }}">
                                            <span>{{ $notice->is_pdf ? '📄' : '🖼️' }}</span>
                                            <span>{{ $notice->is_pdf ? 'PDF' : 'Photo' }}: {{ $notice->attachment_name ?? 'Attachment' }}</span>
                                        </a>
                                    @endif
                                </div>

                                <h4 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#1e3a8a] transition-colors leading-snug">
                                    {{ $notice->title }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $notice->body }}
                                </p>
                            </div>

                            <!-- Actions Row -->
                            <div class="flex items-center gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 justify-end">
                                <!-- Quick Toggle Active Status -->
                                <form method="POST" action="{{ route('admin.notices.toggle', $notice) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 border {{ $notice->is_active ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}"
                                            title="{{ $notice->is_active ? 'Hide notice from portal' : 'Make notice live on portal' }}">
                                        <span>{{ $notice->is_active ? '👁️ Hide' : '🟢 Make Live' }}</span>
                                    </button>
                                </form>

                                <!-- Edit Button -->
                                <button type="button" 
                                        @click="editNotice = {{ json_encode($notice) }}" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1 cursor-pointer">
                                    <span>✏️</span> Edit
                                </button>

                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" onsubmit="return confirm('Permanently remove this circular?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer" title="Delete notice">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <span class="text-3xl block mb-2">📋</span>
                            <p class="font-bold text-slate-600">No notices published yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Click "+ Publish New Circular" to create your first announcement.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- CREATE NOTICE MODAL -->
            <div x-show="showCreateModal" 
                 x-cloak 
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                
                <div @click.away="showCreateModal = false" 
                     class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
                    <div class="p-5 bg-[#1e3a8a] text-white flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">📢</span>
                            <h3 class="font-bold font-heading text-base">Broadcast New College Circular</h3>
                        </div>
                        <button type="button" @click="showCreateModal = false" class="text-white/80 hover:text-white text-xl font-bold p-1 cursor-pointer">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Circular Title *</label>
                            <input type="text" 
                                   name="title" 
                                   required 
                                   placeholder="e.g. Regular Exam Form Notice for 3rd & 5th Sem A.Y. 26-1" 
                                   class="w-full rounded-xl border-slate-300 text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Notice Message / Details *</label>
                            <textarea name="body" 
                                      rows="4" 
                                      required 
                                      placeholder="Write full circular instructions, due dates, student requirements, or GTU portal guidelines..." 
                                      class="w-full rounded-xl border-slate-300 text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-xs"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Attach Document or Photo (PDF / JPG / PNG)
                            </label>
                            <input type="file" 
                                   name="attachment" 
                                   accept=".pdf,image/png,image/jpeg,image/webp,image/jpg" 
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 border border-slate-300 rounded-xl cursor-pointer p-1">
                            <p class="text-[11px] text-slate-400 mt-1">Upload official circular circular file (.pdf) or image photo (.jpg, .png, .webp). Max 10MB.</p>
                        </div>

                        <div class="flex items-center gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="w-4 h-4 text-[#1e3a8a] rounded border-slate-300 focus:ring-[#1e3a8a]">
                            <label for="create_is_active" class="text-xs font-bold text-slate-700 cursor-pointer">
                                Publish immediately to live Notice Board
                            </label>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-sm transition-colors cursor-pointer font-heading">
                                Broadcast Notice &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- EDIT NOTICE MODAL -->
            <div x-show="editNotice" 
                 x-cloak 
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                
                <div @click.away="editNotice = null" 
                     class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
                    <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#2563eb] text-white flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">✏️</span>
                            <h3 class="font-bold font-heading text-base">Edit Circular Announcement</h3>
                        </div>
                        <button type="button" @click="editNotice = null" class="text-white/80 hover:text-white text-xl font-bold p-1 cursor-pointer">&times;</button>
                    </div>

                    <form method="POST" :action="'{{ url('admin/notices') }}/' + (editNotice ? editNotice.id : '')" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Circular Title *</label>
                            <input type="text" 
                                   name="title" 
                                   x-model="editNotice.title" 
                                   required 
                                   class="w-full rounded-xl border-slate-300 text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Notice Message / Details *</label>
                            <textarea name="body" 
                                      rows="4" 
                                      x-model="editNotice.body" 
                                      required 
                                      class="w-full rounded-xl border-slate-300 text-sm focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-xs"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Attached File (PDF or Photo)
                            </label>
                            <template x-if="editNotice && editNotice.attachment_path">
                                <div class="p-2.5 mb-2 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-[#1e3a8a] truncate mr-2">
                                        <span x-text="editNotice.is_pdf ? '📄' : '🖼️'"></span>
                                        <a :href="editNotice.attachment_url" target="_blank" class="hover:underline font-bold truncate max-w-[200px]" x-text="editNotice.attachment_name || 'View Attachment'"></a>
                                    </div>
                                    <label class="flex items-center gap-1.5 text-xs text-red-600 font-bold cursor-pointer shrink-0">
                                        <input type="checkbox" name="remove_attachment" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                        <span>Remove</span>
                                    </label>
                                </div>
                            </template>
                            <input type="file" 
                                   name="attachment" 
                                   accept=".pdf,image/png,image/jpeg,image/webp,image/jpg" 
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 border border-slate-300 rounded-xl cursor-pointer p-1">
                            <p class="text-[11px] text-slate-400 mt-1">Upload to replace or attach a document / image (Max 10MB).</p>
                        </div>

                        <div class="flex items-center gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editNotice && editNotice.is_active" class="w-4 h-4 text-[#1e3a8a] rounded border-slate-300 focus:ring-[#1e3a8a]">
                            <label for="edit_is_active" class="text-xs font-bold text-slate-700 cursor-pointer">
                                Notice is Live on Portal
                            </label>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="editNotice = null" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-sm transition-colors cursor-pointer font-heading">
                                Update Circular &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Active User Management Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approved Users</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        @if($user->isAdmin())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 capitalize">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                        @if(!$user->isAdmin())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user completely?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>

        <!-- GALLERY & CAMPUS TIME-MACHINE MANAGER HUB -->
        <div id="gallery-hub" x-data="{ galleryTab: 'spots' }" class="mt-10 p-6 sm:p-8 bg-gray-50 rounded-[20px] border border-gray-200 space-y-6">
            
            <!-- Hub Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🏛️</span>
                        <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Gallery &amp; Campus Time-Machine</h2>
                        <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-[#1c3272] rounded-full">Unified Vault</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        Control both the interactive <strong>Campus Time-Machine ("Then vs Now" &amp; Nostalgia Blueprint Map)</strong> and traditional <strong>Photo Albums</strong>.
                    </p>
                </div>
                
                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('gallery.index') }}" target="_blank" class="px-3.5 py-2 text-xs font-bold text-[#1c3272] bg-white hover:bg-blue-50 border border-blue-200 rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                        <span>👁️</span> Public Time-Machine &rarr;
                    </a>
                    <a href="{{ route('admin.spots.index') }}" class="px-3.5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                        <span>⚙️</span> Full Landmark Editor &rarr;
                    </a>
                </div>
            </div>

            <!-- Stats Ribbon -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-2xs">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block tracking-wider">Landmark Spots</span>
                    <span class="text-xl font-black text-[#1c3272]">{{ ($spots ?? collect())->count() }} Active</span>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-2xs">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block tracking-wider">Blueprint Layout</span>
                    <span class="text-xl font-black text-emerald-600">Patan Calibrated</span>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-2xs">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block tracking-wider">Photo Albums</span>
                    <span class="text-xl font-black text-[#0f172a]">{{ ($albums ?? collect())->count() }} Sections</span>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-2xs">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block tracking-wider">Pinned Memories</span>
                    <span class="text-xl font-black text-[#f97316]">{{ ($recentStories ?? collect())->count() }} Stories</span>
                </div>
            </div>

            <!-- Sub-Navigation Tabs -->
            <div class="flex items-center gap-2 border-b border-gray-200 pb-2 overflow-x-auto">
                <button type="button" 
                        @click="galleryTab = 'spots'"
                        :class="galleryTab === 'spots' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 bg-white hover:bg-slate-100 border border-gray-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0">
                    <span>🏛️</span>
                    <span>Campus Time-Machine Spots ({{ ($spots ?? collect())->count() }})</span>
                </button>

                <button type="button" 
                        @click="galleryTab = 'albums'"
                        :class="galleryTab === 'albums' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 bg-white hover:bg-slate-100 border border-gray-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0">
                    <span>📸</span>
                    <span>Event Photo Albums ({{ ($albums ?? collect())->count() }})</span>
                </button>

                <button type="button" 
                        @click="galleryTab = 'stories'"
                        :class="galleryTab === 'stories' ? 'bg-[#1c3272] text-white shadow-sm' : 'text-slate-600 bg-white hover:bg-slate-100 border border-gray-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0">
                    <span>✍️</span>
                    <span>Pinned Throwback Memories ({{ ($recentStories ?? collect())->count() }})</span>
                </button>
            </div>

            <!-- TAB 1: CAMPUS TIME-MACHINE & LANDMARK SPOTS -->
            <div x-show="galleryTab === 'spots'" class="space-y-5">
                <div class="bg-blue-50/60 border border-blue-200 p-4 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h4 class="font-bold text-sm text-[#1c3272] flex items-center gap-1.5">
                            <span>🗺️</span> K. D. Polytechnic Authentic Campus Blueprint &amp; "Then vs Now" Spots
                        </h4>
                        <p class="text-xs text-slate-600 mt-0.5">
                            Each spot below is mapped to its exact geographic pin coordinates on the campus layout and displays side-by-side archival comparison photos.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.spots.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[#1c3272] text-white text-xs font-bold hover:bg-blue-900 transition-colors shadow-xs">
                            + Add / Calibrate Spot
                        </a>
                    </div>
                </div>

                <!-- Spot Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($spots ?? [] as $spot)
                        <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-gray-300 shadow-2xs flex flex-col justify-between space-y-3 transition-all">
                            
                            <!-- Split Photo Thumbnails -->
                            <div class="grid grid-cols-2 gap-1.5 aspect-[16/9] rounded-lg overflow-hidden bg-slate-900 relative">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $spot->then_photo_path }}" class="w-full h-full object-cover filter sepia-[0.3] contrast-125" alt="Then">
                                    <span class="absolute bottom-1 left-1 bg-amber-950/80 text-amber-200 px-1.5 py-0.5 rounded text-[8px] font-black">
                                        THEN '{{ substr($spot->then_year, -2) }}
                                    </span>
                                </div>
                                <div class="relative overflow-hidden">
                                    <img src="{{ $spot->now_photo_path }}" class="w-full h-full object-cover" alt="Now">
                                    <span class="absolute bottom-1 right-1 bg-slate-900/80 text-emerald-400 px-1.5 py-0.5 rounded text-[8px] font-black">
                                        NOW '{{ substr($spot->now_year, -2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-widest bg-orange-100 text-orange-800 rounded-full">
                                        {{ $spot->category }}
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-400">
                                        Pin: {{ $spot->x_percent }}%, {{ $spot->y_percent }}%
                                    </span>
                                </div>
                                <h4 class="font-bold text-sm text-[#0f172a] leading-tight">
                                    {{ $spot->name }}
                                </h4>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $spot->description }}
                                </p>
                            </div>

                            <!-- Footer & Action -->
                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-500">
                                    💬 {{ $spot->stories_count ?? 0 }} memories
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.spots.index') }}" class="px-2.5 py-1 text-[11px] font-bold text-[#1c3272] bg-blue-50 hover:bg-blue-100 rounded-md transition-colors">
                                        Edit / Change Photos
                                    </a>
                                    <form action="{{ route('admin.spots.destroy', $spot) }}" method="POST" onsubmit="return confirm('Delete this landmark spot from the map and time-machine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 text-[11px] font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center bg-white rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-500 italic">No landmark spots configured yet.</p>
                            <a href="{{ route('admin.spots.index') }}" class="mt-2 inline-block px-4 py-2 bg-[#1c3272] text-white text-xs font-bold rounded-lg">
                                Open Landmark Spots Manager
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: EVENT PHOTO ALBUMS & ARCHIVES -->
            <div x-show="galleryTab === 'albums'" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Create Section Form -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Create New Album Section</h3>
                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-2">Section / Event Title</label>
                            <input type="text" name="title" required placeholder="e.g. Sports Week 2026, Convocation 2025" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-2">Upload Photos</label>
                            <input type="file" name="images[]" multiple required accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 cursor-pointer">
                            <span class="text-[11px] text-gray-400 mt-1 block">You can select multiple photos at once.</span>
                        </div>
                        <button type="submit" class="w-full bg-[#3b82f6] hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors shadow-xs">
                            Create Section &amp; Upload Photos
                        </button>
                    </form>
                </div>

                <!-- Existing Sections List -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Existing Album Sections ({{ ($albums ?? collect())->count() }})</h3>
                    <div class="space-y-3 max-h-[350px] overflow-y-auto pr-2">
                        @forelse($albums ?? [] as $album)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $album->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $album->photos_count }} Photos uploaded</p>
                                </div>
                                <a href="{{ route('gallery.edit', $album->id) }}" class="text-xs font-bold text-[#3b82f6] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                    Edit / Preview
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">No album sections created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: PINNED ALUMNI MEMORIES & MODERATION -->
            <div x-show="galleryTab === 'stories'" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <h3 class="text-lg font-bold text-[#0f172a]">Alumni Throwback Memories</h3>
                        <p class="text-xs text-gray-500">Student &amp; alumni stories pinned to campus landmark spots on the Nostalgia Blueprint Map.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ ($recentStories ?? collect())->count() }} Recent Submissions</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($recentStories ?? [] as $story)
                        <div class="py-3.5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-[#1c3272]">{{ $story->user->name ?? 'Anonymous Alumni' }}</span>
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                        Batch {{ $story->batch_year ?? 'Alumni' }}
                                    </span>
                                    <span class="text-xs text-slate-400">&bull; at</span>
                                    <span class="text-xs font-bold text-[#f97316]">{{ $story->spot->name ?? 'Campus Landmark' }}</span>
                                </div>
                                <p class="text-xs text-slate-600 italic">
                                    "{{ $story->story }}"
                                </p>
                                <div class="text-[11px] text-slate-400 flex items-center gap-3">
                                    <span>❤️ {{ $story->likes_count ?? 0 }} Likes</span>
                                    <span>&bull; {{ $story->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <form action="{{ route('admin.spots.stories.destroy', $story) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this story?');" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">
                                    Remove Story
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic py-4 text-center">No alumni stories submitted yet.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- EVENT MANAGER (KD Polytechnic Activities & Seminars Control) -->
        <div id="events-section" class="mt-10 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden"
             x-data="{ 
                 eventFilter: 'all', 
                 eventSearch: '', 
                 editEvent: null, 
                 matchesEvent(evt) {
                     if (this.eventFilter === 'upcoming') {
                         const todayStr = '{{ now()->toDateString() }}';
                         const evtDateStr = (evt.event_date || '').substring(0, 10);
                         if (evtDateStr < todayStr) return false;
                     }
                     if (this.eventFilter === 'past') {
                         const todayStr = '{{ now()->toDateString() }}';
                         const evtDateStr = (evt.event_date || '').substring(0, 10);
                         if (evtDateStr >= todayStr) return false;
                     }
                     if (!this.eventSearch) return true;
                     const q = this.eventSearch.toLowerCase();
                     return (evt.title && evt.title.toLowerCase().includes(q)) ||
                            (evt.category && evt.category.toLowerCase().includes(q)) ||
                            (evt.description && evt.description.toLowerCase().includes(q));
                 }
             }">

            <!-- Top Header Banner -->
            <div class="p-6 bg-gradient-to-r from-[#1e3a8a] via-[#1c3272] to-[#0f172a] text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xl">📅</span>
                        <h2 class="text-xl font-bold tracking-tight font-heading">Campus Events &amp; Activities Control Center</h2>
                        <span class="ml-2 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-[#f97316] text-white shadow-xs">
                            {{ $events->count() }} Events
                        </span>
                    </div>
                    <p class="text-xs text-blue-100 font-sans">
                        Schedule campus activities, guest lectures, technical symposiums, and track alumni RSVP attendance.
                    </p>
                </div>

                <a href="{{ route('events.index') }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all flex items-center gap-1.5 font-heading">
                    <span>🌐</span> View Public Events Hub &rarr;
                </a>
            </div>

            <!-- Stats & Filter Bar -->
            <div class="p-4 sm:p-6 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <!-- Stat chips -->
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" 
                            @click="eventFilter = 'all'" 
                            :class="eventFilter === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span>All Activities</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="eventFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $events->count() }}</span>
                    </button>

                    <button type="button" 
                            @click="eventFilter = 'upcoming'" 
                            :class="eventFilter === 'upcoming' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                        <span>Upcoming</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="eventFilter === 'upcoming' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $events->where('event_date', '>=', today())->count() }}</span>
                    </button>

                    <button type="button" 
                            @click="eventFilter = 'past'" 
                            :class="eventFilter === 'past' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Past Archive</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="eventFilter === 'past' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">{{ $events->where('event_date', '<', today())->count() }}</span>
                    </button>
                </div>

                <!-- Instant Search Input -->
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        🔍
                    </span>
                    <input type="text" 
                           x-model="eventSearch" 
                           placeholder="Filter activities by title or category..." 
                           class="w-full pl-9 pr-4 py-1.5 text-xs rounded-xl border-slate-300 bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs">
                    <button x-show="eventSearch" @click="eventSearch = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Content Grid: Form (Left) & List (Right) -->
            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Schedule New Event Form -->
                <div class="lg:col-span-5 bg-slate-50 p-6 rounded-xl border border-slate-200/80 shadow-2xs h-max">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-200 pb-3">
                        <span class="text-base">✨</span>
                        <h3 class="text-base font-bold text-[#0f172a] font-heading">Schedule New Event</h3>
                    </div>

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Event / Activity Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required placeholder="e.g. Annual Polytechnic TechFest &amp; Project Expo" class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                    <option value="Technical Fest">Technical Fest</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Reunion">Alumni Reunion</option>
                                    <option value="Sports &amp; Cultural">Sports &amp; Cultural</option>
                                    <option value="Career &amp; Placement">Career &amp; Placement</option>
                                    <option value="Webinar">Webinar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Event Date <span class="text-red-500">*</span></label>
                                <input type="date" name="event_date" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Time &amp; Venue <span class="text-red-500">*</span></label>
                            <input type="text" name="time_display" required placeholder="e.g. 10:00 AM - 04:00 PM • Main Campus Auditorium" class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                            <textarea name="description" rows="3" placeholder="Provide background, agenda, guest speaker details, or participation criteria..." class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Cover Photo (Optional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-white border border-slate-300 rounded-lg p-2 text-xs text-slate-600 cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100">
                            <p class="text-[10px] text-slate-500 mt-1">Accepts JPG, PNG, WEBP (Max 5MB). Default KDP branded graphic will be used if omitted.</p>
                        </div>

                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-sm hover:shadow flex items-center justify-center gap-1.5">
                            <span>🚀</span> Publish Campus Activity
                        </button>
                    </form>
                </div>

                <!-- Right: Active Events List -->
                <div class="lg:col-span-7">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-3">
                        <h3 class="text-base font-bold text-[#0f172a] font-heading flex items-center gap-2">
                            <span>📋</span> Campus Activities Schedule
                        </h3>
                        <span class="text-xs text-slate-500 font-sans">
                            Showing {{ $events->count() }} total events
                        </span>
                    </div>

                    <div class="space-y-3.5 max-h-[600px] overflow-y-auto pr-1">
                        @forelse($events as $event)
                            <div x-show="matchesEvent({{ json_encode($event) }})" 
                                 class="p-4 rounded-xl border border-slate-200/90 hover:border-slate-300 transition-all bg-white hover:shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $event->event_date >= today() ? 'border-l-4 border-l-[#f97316]' : 'border-l-4 border-l-slate-300 opacity-80' }}">
                                
                                <div class="flex items-start gap-3.5 flex-1 min-w-0">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative">
                                        @if($event->image_path)
                                            <img src="{{ asset('storage/' . $event->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-[#1e3a8a] to-[#2e53a3] flex flex-col items-center justify-center text-white">
                                                <span class="text-xs font-black">{{ $event->event_date->format('M') }}</span>
                                                <span class="text-sm font-black">{{ $event->event_date->format('d') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ $event->event_date >= today() ? 'bg-orange-50 text-[#f97316] border border-orange-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                {{ $event->category }}
                                            </span>

                                            <span class="text-[11px] font-bold text-slate-700 flex items-center gap-1 font-sans">
                                                📅 {{ $event->event_date->format('M d, Y') }}
                                            </span>

                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                                🎟️ {{ $event->rsvps_count ?? 0 }} RSVPs
                                            </span>
                                        </div>

                                        <h4 class="font-bold text-sm text-[#0f172a] hover:text-[#1e3a8a] transition-colors leading-snug line-clamp-1">
                                            {{ $event->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                            ⏰ {{ $event->time_display }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions Row -->
                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                    <a href="{{ route('events.show', $event->id) }}" target="_blank" class="p-2 rounded-lg text-slate-600 hover:text-[#1e3a8a] hover:bg-blue-50 text-xs font-bold border border-slate-200 transition-colors" title="View Public Page">
                                        👁️
                                    </a>

                                    <button type="button" 
                                            @click="editEvent = {{ json_encode($event) }}" 
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1 cursor-pointer">
                                        <span>✏️</span> Edit
                                    </button>

                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel and delete this event? All associated RSVPs will also be removed.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400 italic text-sm bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                No campus events scheduled yet. Use the form on the left to schedule one.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Edit Event Modal -->
            <div x-show="editEvent !== null" 
                 x-cloak 
                 style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden transform transition-all"
                     @click.away="editEvent = null">
                    
                    <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">✏️</span>
                            <h3 class="font-bold text-base font-heading">Edit Campus Activity</h3>
                        </div>
                        <button type="button" @click="editEvent = null" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">
                            &times;
                        </button>
                    </div>

                    <form :action="'{{ url('/events') }}/' + (editEvent ? editEvent.id : '')" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Event Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" x-model="editEvent.title" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category" x-model="editEvent.category" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                    <option value="Technical Fest">Technical Fest</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Reunion">Alumni Reunion</option>
                                    <option value="Sports &amp; Cultural">Sports &amp; Cultural</option>
                                    <option value="Career &amp; Placement">Career &amp; Placement</option>
                                    <option value="Webinar">Webinar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Event Date <span class="text-red-500">*</span></label>
                                <input type="date" name="event_date" :value="editEvent ? (editEvent.event_date || '').substring(0, 10) : ''" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Time &amp; Venue <span class="text-red-500">*</span></label>
                            <input type="text" name="time_display" x-model="editEvent.time_display" required class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="3" x-model="editEvent.description" class="w-full rounded-lg border-slate-300 shadow-2xs text-xs py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Replace Cover Photo (Optional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-white border border-slate-300 rounded-lg p-2 text-xs text-slate-600 cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a]">
                            <p class="text-[10px] text-slate-500 mt-1">Leave empty to keep existing photo.</p>
                        </div>

                        <div class="pt-3 border-t border-slate-200 flex justify-end gap-2">
                            <button type="button" @click="editEvent = null" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 cursor-pointer transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs cursor-pointer transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- JOB MANAGER -->
        <div class="mt-10 p-8 bg-gray-50 rounded-[20px] border border-gray-200 mb-12">
            <div class="mb-6">
                <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Job Board</h2>
                <p class="text-gray-500 text-sm mt-1">Post new career opportunities and remove expired listings.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Post New Job</h3>
                    <form action="{{ route('jobs.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Job Title</label>
                                <input type="text" name="title" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Company</label>
                                <input type="text" name="company" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Location</label>
                                <input type="text" name="location" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Emp. Type</label>
                                <select name="employment_type" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm text-gray-600">
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="internship">Internship</option>
                                    <option value="contract">Contract</option>
                                    <option value="apprenticeship">Apprenticeship</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Application Link or Email</label>
                            <input type="text" name="application_link_or_email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Description</label>
                            <textarea name="description" required rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#16a34a] hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors mt-2">
                            Publish Job to Network
                        </button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Active Jobs</h3>
                    <div class="space-y-3 max-h-[450px] overflow-y-auto pr-2">
                        @forelse($jobs as $job)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $job->title }}</h4>
                                    <p class="text-xs text-[#3b82f6] font-bold">{{ $job->company }} <span class="text-gray-500 font-normal">| {{ ucfirst($job->employment_type) }}</span></p>
                                </div>
                                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">No active jobs posted.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- GEOGRAPHIC ALUMNI MAP & REGIONAL CHAPTERS      -->
        <!-- ============================================== -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                            <span>📍</span> Interactive Regional Chapter Map
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Click on any pin to inspect chapter details, member counts, and meetup venues.</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#1c3272]"></span> Gujarat Hubs</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> National & Global</span>
                        <a href="{{ route('chapters.index') }}" class="font-bold text-[#1c3272] hover:text-[#0f172a] hover:underline flex items-center gap-1 ml-2">
                            Explore All Chapters &rarr;
                        </a>
                    </div>
                </div>

                <div id="adminDashboardMap" class="w-full h-80 sm:h-96 bg-gray-50 z-10"></div>
            </div>

            <!-- Geographic Clusters Breakdown Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 font-serif">Alumni Presence by Industrial Corridor</h3>
                        <p class="text-xs text-gray-500">Estimated distribution of diploma graduates based on profile location.</p>
                    </div>
                    <a href="{{ route('chapters.index') }}" class="text-xs font-bold text-[#1c3272] hover:underline">
                        View Regional Directory &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
                    @if(isset($clusters))
                        @foreach($clusters as $key => $cluster)
                            <a href="{{ route('alumni.index', ['location' => $cluster['name']]) }}" 
                               class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm hover:border-[#1c3272] hover:shadow-md transition-all flex flex-col justify-between group">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 group-hover:text-[#1c3272] transition-colors block truncate" title="{{ $cluster['name'] }}">
                                        {{ $cluster['name'] }}
                                    </span>
                                    <div class="text-xl font-black text-gray-900 mt-1">
                                        {{ $cluster['count'] }}
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400 group-hover:text-blue-600 transition-colors font-medium mt-2">View Alumni &rarr;</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Leaflet Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;
            var mapEl = document.getElementById('adminDashboardMap');
            if (!mapEl) return;

            var map = L.map('adminDashboardMap', { attributionControl: false }).setView([22.8, 72.5], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: ''
            }).addTo(map);

            var pins = @json($mapPins ?? []);

            pins.forEach(function (pin) {
                if (!pin.lat || !pin.lng) return;

                var marker = L.circleMarker([pin.lat, pin.lng], {
                    radius: 8,
                    fillColor: pin.city === 'Patan' ? '#ea580c' : '#1c3272',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map);

                var popupContent = `
                    <div style="font-family: inherit; min-width: 170px; padding: 2px;">
                        <div style="font-size: 11px; font-weight: 800; color: #f97316; text-transform: uppercase;">${pin.city}, ${pin.state}</div>
                        <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 2px;">${pin.name}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">👥 ${pin.members_count} Alumni Joined</div>
                        ${pin.venue ? `<div style="font-size: 10px; color: #475569; margin-top: 2px;">📍 ${pin.venue}</div>` : ''}
                        <div style="margin-top: 8px;">
                            <a href="${pin.url}" style="display: inline-block; background: #1c3272; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-decoration: none;">Explore Chapter &rarr;</a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
            });
        });
    </script>
@endsection