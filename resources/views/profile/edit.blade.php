@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-12 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Institutional Watermark Background -->
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
                    Role: {{ ucfirst($user->role ?? 'Alumni') }}
                </span>
                @if($user->is_approved)
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold font-heading uppercase tracking-wider flex items-center gap-1">
                        <span>✓</span> Verified GTU Member
                    </span>
                @endif
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
                    </p>
                    <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                        Your Alumni Profile &amp; Records
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                        Manage your official GTU credentials, digital identity card information, and professional networking presence across the KDP engineering alumni network.
                    </p>
                </div>

                <!-- Quick Action: View Smart ID Card -->
                <div class="shrink-0 flex sm:flex-col items-center sm:items-end gap-2">
                    <a href="{{ route('id-card.show') }}" class="inline-flex items-center gap-2 bg-[#f97316] hover:bg-orange-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl shadow-md transition-all font-heading">
                        <span>🪪</span>
                        <span>Digital Smart ID Card</span>
                        <span>&rarr;</span>
                    </a>
                    <span class="text-[11px] text-blue-200/80 font-medium">Gate Verification Parity</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN PROFILE FORM CONTAINER                 -->
    <!-- ============================================== -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden">
            
            <!-- Top Gradient Accent Strip -->
            <div class="h-1.5 bg-gradient-to-r from-[#1e3a8a] via-[#f97316] to-[#f59e0b]"></div>

            <div class="p-6 sm:p-10 md:p-12 space-y-12">
                
                <!-- Success Alerts -->
                @if (session('status') === 'profile-updated')
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                        <span class="text-base">✅</span>
                        <div>
                            <strong class="font-heading">Profile Updated Successfully!</strong>
                            <p class="text-emerald-700 text-xs font-normal mt-0.5">Your updated records have been synchronized with your Smart ID Card and the Alumni Directory.</p>
                        </div>
                    </div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                        <span class="text-base">🔒</span>
                        <div>
                            <strong class="font-heading">Security Credentials Updated!</strong>
                            <p class="text-emerald-700 text-xs font-normal mt-0.5">Your portal account password has been updated successfully.</p>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-start gap-3 shadow-xs">
                        <span class="text-base">⚠️</span>
                        <div>
                            <strong class="font-heading">Please correct the following errors:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-700 text-xs font-normal">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- ============================================== -->
                <!-- PROFILE INFORMATION FORM                       -->
                <!-- ============================================== -->
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-12">
                    @csrf
                    @method('patch')

                    <!-- SECTION 1: PERSONAL DETAILS & PHOTOGRAPH -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-200">
                            <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#1e3a8a] border border-blue-200 flex items-center justify-center font-bold text-sm shrink-0">
                                01
                            </span>
                            <div>
                                <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                                    Personal Details &amp; Smart ID Photo
                                </h2>
                                <p class="text-xs text-slate-500 font-sans">
                                    Basic contact identification and photo for institutional gate pass and smart verification.
                                </p>
                            </div>
                        </div>

                        <!-- Profile Photo Upload Card -->
                        <div class="mb-6 bg-slate-50/80 border border-slate-200/90 rounded-2xl p-5 flex flex-col sm:flex-row items-center gap-5">
                            <div class="relative shrink-0">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white border-2 border-[#1e3a8a]/20 shadow-md flex items-center justify-center">
                                    @if($user->photo_path)
                                        <img src="{{ filter_var($user->photo_path, FILTER_VALIDATE_URL) ? $user->photo_path : asset('storage/' . $user->photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#1e3a8a] text-white flex items-center justify-center font-heading font-black text-2xl">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="absolute -bottom-1 -right-1 bg-[#f97316] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow uppercase font-heading">
                                    ID Photo
                                </span>
                            </div>

                            <div class="flex-1 w-full text-center sm:text-left">
                                <label for="photo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Upload Official Profile Photo (For Smart ID Card)
                                </label>
                                <p class="text-[11px] text-slate-500 mb-2 font-sans">
                                    Clear square passport-size photo with plain background recommended. Formats: JPG, PNG (Max: 2MB).
                                </p>
                                <input type="file" id="photo" name="photo" accept="image/*"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:font-heading file:uppercase file:tracking-wider file:bg-[#1e3a8a] file:text-white hover:file:bg-blue-900 cursor-pointer">
                                @error('photo')
                                    <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Input Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        👤
                                    </span>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('name') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Registered Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        ✉️
                                    </span>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('email') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Phone Number
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        📞
                                    </span>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('phone') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Blood Group -->
                            <div>
                                <label for="blood_group" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Blood Group (For Emergency Medical Record)
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🩸
                                    </span>
                                    <select id="blood_group" name="blood_group"
                                            class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all cursor-pointer">
                                        <option value="">Select Blood Group</option>
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('blood_group') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Current Role (Read Only) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Institutional Portal Role
                                </label>
                                <div class="flex items-center gap-3 bg-slate-100/80 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600">
                                    <span class="text-base">🛡️</span>
                                    <div>
                                        <strong class="font-heading uppercase tracking-wide text-[#0f172a]">{{ ucfirst($user->role ?? 'Alumni') }}</strong>
                                        <span class="text-[11px] text-slate-500 block">Assigned by institutional administration during GTU credential verification. Cannot be self-modified.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SECTION 2: ACADEMIC DETAILS -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-200">
                            <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#f97316] border border-orange-200 flex items-center justify-center font-bold text-sm shrink-0">
                                02
                            </span>
                            <div>
                                <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                                    Academic &amp; GTU Credentials
                                </h2>
                                <p class="text-xs text-slate-500 font-sans">
                                    Gujarat Technological University diploma records, enrollment number, and department.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <!-- Enrollment / Roll No -->
                            <div>
                                <label for="entry_no" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    GTU Enrollment No / Roll No
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🔢
                                    </span>
                                    <input type="text" id="entry_no" name="entry_no" value="{{ old('entry_no', $user->entry_no) }}" placeholder="e.g. 216250107001"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('entry_no') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Course / Degree -->
                            <div>
                                <label for="degree" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Course / Degree
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        📜
                                    </span>
                                    <input type="text" id="degree" name="degree" value="{{ old('degree', $user->degree) }}" placeholder="e.g. Diploma in Computer Engineering"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('degree') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Department
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🏛️
                                    </span>
                                    <select id="department" name="department"
                                            class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all cursor-pointer">
                                        <option value="">Select Department</option>
                                        @foreach(['Computer Engineering', 'Mechanical Engineering', 'Electrical Engineering', 'Civil Engineering', 'Applied Mechanics / General'] as $dept)
                                            <option value="{{ $dept }}" {{ old('department', $user->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('department') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Year of Joining & Graduation Year -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="year_joining" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                        Joining Year
                                    </label>
                                    <div class="relative">
                                        <select id="year_joining" name="year_joining"
                                                class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all cursor-pointer">
                                            <option value="">Select Year</option>
                                            @for($i = date('Y'); $i >= 1961; $i--)
                                                <option value="{{ $i }}" {{ old('year_joining', $user->year_joining) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    @error('year_joining') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="graduation_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                        Graduation Year
                                    </label>
                                    <div class="relative">
                                        <select id="graduation_year" name="graduation_year"
                                                class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all cursor-pointer">
                                            <option value="">Select Year</option>
                                            @for($i = date('Y') + 4; $i >= 1961; $i--)
                                                <option value="{{ $i }}" {{ old('graduation_year', $user->graduation_year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    @error('graduation_year') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SECTION 3: PROFESSIONAL DETAILS -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-200">
                            <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-bold text-sm shrink-0">
                                03
                            </span>
                            <div>
                                <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                                    Professional Trajectory &amp; Industry Placement
                                </h2>
                                <p class="text-xs text-slate-500 font-sans">
                                    Share your current company, designation, and engineering skills for TPO placements and student mentorship.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <!-- Company -->
                            <div>
                                <label for="company" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Current Organization / Employer
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🏢
                                    </span>
                                    <input type="text" id="company" name="company" value="{{ old('company', $user->company) }}" placeholder="e.g. L&T, Siemens, Adani, GETCO, Tata"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('company') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Designation -->
                            <div>
                                <label for="designation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Current Designation / Title
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🏷️
                                    </span>
                                    <input type="text" id="designation" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="e.g. Junior Engineer, Project Manager, Lead Developer"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('designation') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Work Industry -->
                            <div>
                                <label for="work_industry" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Work Industry
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🏭
                                    </span>
                                    <input type="text" id="work_industry" name="work_industry" value="{{ old('work_industry', $user->work_industry) }}" placeholder="e.g. Electrical Power, Information Technology, Infrastructure"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('work_industry') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Skills -->
                            <div>
                                <label for="skills" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Technical Skills &amp; Domain Expertise
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        ⚙️
                                    </span>
                                    <input type="text" id="skills" name="skills" value="{{ old('skills', $user->skills) }}" placeholder="e.g. AutoCAD, Python, PLC & SCADA, Structural Design"
                                           class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                </div>
                                @error('skills') <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <!-- SECTION 4: DIRECTORY PRIVACY CONTROLS -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-200">
                            <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center font-bold text-sm shrink-0">
                                04
                            </span>
                            <div>
                                <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                                    Directory Privacy Controls
                                </h2>
                                <p class="text-xs text-slate-500 font-sans">
                                    Control which contact details are visible to fellow alumni and current students in the public directory.
                                </p>
                            </div>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-6 space-y-5">
                            
                            <!-- Phone Toggle -->
                            <label class="flex items-center justify-between gap-4 cursor-pointer">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 font-heading block">
                                        Display Phone Number in Public Alumni Directory
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-sans">
                                        When enabled, verified batchmates and students can view your contact number for professional outreach.
                                    </span>
                                </div>
                                <div class="relative shrink-0">
                                    <input type="checkbox" name="is_phone_public" value="1" class="sr-only peer" {{ optional(auth()->user()->profile)->is_phone_public ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1e3a8a]"></div>
                                </div>
                            </label>

                            <hr class="border-slate-200/80">

                            <!-- Email Toggle -->
                            <label class="flex items-center justify-between gap-4 cursor-pointer">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 font-heading block">
                                        Display Email Address in Public Alumni Directory
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-sans">
                                        Allows registered alumni members to contact you directly via your verified email address.
                                    </span>
                                </div>
                                <div class="relative shrink-0">
                                    <input type="checkbox" name="is_email_public" value="1" class="sr-only peer" {{ optional(auth()->user()->profile)->is_email_public ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1e3a8a]"></div>
                                </div>
                            </label>

                        </div>
                    </div>

                    <!-- Submit Profile Changes Button -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200">
                        <span class="text-[11px] text-slate-500">
                            All updates take effect immediately on your digital card and directory profile.
                        </span>
                        <button type="submit" class="w-full sm:w-auto bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider py-3 px-8 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                            <span>Save Profile Changes</span>
                            <span>&rarr;</span>
                        </button>
                    </div>

                </form>

                <!-- ============================================== -->
                <!-- 5. SECURITY & PASSWORD UPDATE FORM             -->
                <!-- ============================================== -->
                <div class="pt-10 border-t border-slate-200">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                        @csrf
                        @method('put')

                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-200">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center font-bold text-sm shrink-0">
                                05
                            </span>
                            <div>
                                <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a] uppercase tracking-wide">
                                    Security Credentials &amp; Password
                                </h2>
                                <p class="text-xs text-slate-500 font-sans">
                                    Maintain account safety by updating your password regularly with at least 8 alphanumeric characters.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🔒
                                    </span>
                                    <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" required placeholder="••••••••"
                                           class="w-full pl-9 pr-9 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                        <span x-show="!showCurrent" class="text-xs">👁️</span>
                                        <span x-show="showCurrent" style="display: none;" class="text-xs">🙈</span>
                                    </button>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🔑
                                    </span>
                                    <input :type="showNew ? 'text' : 'password'" id="password" name="password" required placeholder="••••••••"
                                           class="w-full pl-9 pr-9 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                    <button type="button" @click="showNew = !showNew" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                        <span x-show="!showNew" class="text-xs">👁️</span>
                                        <span x-show="showNew" style="display: none;" class="text-xs">🙈</span>
                                    </button>
                                </div>
                                @error('password', 'updatePassword')
                                    <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        🛡️
                                    </span>
                                    <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                                           class="w-full pl-9 pr-9 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs text-slate-900 font-sans transition-all">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                        <span x-show="!showConfirm" class="text-xs">👁️</span>
                                        <span x-show="showConfirm" style="display: none;" class="text-xs">🙈</span>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-6 rounded-xl shadow-md transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                                <span>Update Portal Password</span>
                                <span>&rarr;</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ============================================== -->
                <!-- 6. DANGER ZONE / ACCOUNT CLOSURE               -->
                <!-- ============================================== -->
                <div class="pt-10 border-t border-slate-200" x-data="{ showDeleteModal: false }">
                    <div class="bg-rose-50/60 border border-rose-200 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold text-rose-900 font-heading uppercase tracking-wide block">
                                Account Deactivation &amp; Closure
                            </span>
                            <p class="text-xs text-rose-700 mt-1 font-sans">
                                Permanently delete your alumni profile, Smart ID records, and portal credentials. This action is irreversible.
                            </p>
                        </div>
                        <button type="button" @click="showDeleteModal = true" class="shrink-0 bg-white hover:bg-rose-100 text-rose-700 border border-rose-300 font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl transition-all font-heading cursor-pointer">
                            Delete Account
                        </button>
                    </div>

                    <!-- Deletion Confirmation Modal -->
                    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div @click="showDeleteModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 border border-slate-200">
                                <h3 class="font-heading font-black text-xl text-[#0f172a] uppercase tracking-wide mb-2">
                                    Confirm Account Deletion
                                </h3>
                                <p class="text-xs text-slate-600 font-sans mb-4 leading-relaxed">
                                    Are you sure you want to delete your K. D. Polytechnic alumni account? Once deleted, all associated mentorship sessions, tool requests, and digital Smart ID credentials will be permanently removed.
                                </p>

                                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                                    @csrf
                                    @method('delete')

                                    <div>
                                        <label for="delete_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                            Confirm Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="delete_password" name="password" required placeholder="Enter your current password"
                                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-red-500 focus:ring-red-500 text-slate-900">
                                        @error('password', 'userDeletion')
                                            <span class="text-red-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex items-center justify-end gap-3 pt-3">
                                        <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 font-heading">
                                            Cancel
                                        </button>
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider font-heading shadow-md">
                                            Permanently Delete
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection
