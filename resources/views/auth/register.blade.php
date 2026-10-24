@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-slate-100 flex flex-col justify-center py-10 sm:py-14 px-4 sm:px-6 lg:px-8 font-sans relative overflow-hidden">

    <!-- Subtle Background Top Gradient Strip -->
    <div class="absolute top-0 inset-x-0 h-80 bg-gradient-to-b from-[#0f2042] via-[#1e3a8a] to-transparent opacity-95 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto w-full">

        <!-- Top Header Institutional Badge -->
        <div class="text-center mb-6 text-white drop-shadow-md">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-200 text-[11px] font-bold uppercase tracking-widest font-heading mb-2">
                <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                <span>Alumni &amp; Student Registration • Established 1961</span>
            </div>
            <h1 class="font-heading text-xl sm:text-3xl font-black uppercase tracking-wide">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </h1>
            <p class="text-blue-200 text-xs sm:text-sm font-medium mt-1">
                K. D. Polytechnic Institutional Alumni Network Enrollment
            </p>
        </div>

        <!-- Registration Form Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden border-t-4 border-t-[#f97316]">
            
            <!-- Card Header Strip -->
            <div class="bg-gradient-to-r from-slate-50 via-white to-blue-50/40 p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-white p-1.5 shadow-xs border border-amber-300 shrink-0">
                        <img src="{{ asset('images/kdp_crest.png') }}" alt="KDP Crest" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h2 class="font-heading font-black text-lg sm:text-xl text-[#0f172a]">
                            New Member Registration
                        </h2>
                        <p class="text-xs text-slate-500 font-sans">
                            Apply for official institutional alumni membership and digital Smart ID Card.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs text-slate-500 font-medium">Already registered?</span>
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl bg-blue-50 text-[#1e3a8a] hover:bg-[#1e3a8a] hover:text-white border border-blue-200 font-bold text-xs uppercase tracking-wider font-heading transition-colors">
                        Sign In &rarr;
                    </a>
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="p-6 sm:p-10 space-y-10">
                @csrf

                <!-- ========================================== -->
                <!-- SECTION 1: PERSONAL & CONTACT CREDENTIALS  -->
                <!-- ========================================== -->
                <div>
                    <div class="flex items-center gap-2 border-b border-slate-200 pb-2.5 mb-6">
                        <span class="text-lg">👤</span>
                        <h3 class="text-base font-bold font-heading text-[#1e3a8a] uppercase tracking-wide">
                            1. Personal &amp; Account Details
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Ketan M. Patel"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans transition-all">
                            @error('name') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@domain.com"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans transition-all">
                            @error('email') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password with Eye Toggle -->
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••"
                                       class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 pr-10 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans transition-all">
                                <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <span x-show="!show" class="text-xs">👁️</span>
                                    <span x-show="show" style="display: none;" class="text-xs">🙈</span>
                                </button>
                            </div>
                            @error('password') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password with Eye Toggle -->
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••"
                                       class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 pr-10 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans transition-all">
                                <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                    <span x-show="!show" class="text-xs">👁️</span>
                                    <span x-show="show" style="display: none;" class="text-xs">🙈</span>
                                </button>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="flex space-x-2">
                            <div class="w-24">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Code</label>
                                <input type="text" name="country_code" value="{{ old('country_code', '+91') }}"
                                       class="w-full text-xs text-center rounded-xl border border-slate-300 py-2.5 px-2 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] font-sans">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="9876543210"
                                       class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs font-sans">
                                @error('phone') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Blood Group -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">Blood Group</label>
                            <select name="blood_group" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] text-slate-700 cursor-pointer">
                                <option value="">Select Blood Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Profile Photo for Smart ID Card -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Profile Photo (Embedded on your Digital Smart ID Card)
                            </label>
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full text-xs text-slate-500 border border-slate-300 rounded-xl p-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 cursor-pointer">
                            @error('photo') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SECTION 2: ACADEMIC DETAILS                -->
                <!-- ========================================== -->
                <div>
                    <div class="flex items-center gap-2 border-b border-slate-200 pb-2.5 mb-6">
                        <span class="text-lg">🎓</span>
                        <h3 class="text-base font-bold font-heading text-[#1e3a8a] uppercase tracking-wide">
                            2. Institutional &amp; Academic Details
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <!-- Role Selection -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                I am registering as: <span class="text-red-500">*</span>
                            </label>
                            <select name="role" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] text-slate-800 font-semibold cursor-pointer">
                                <option value="alumni" {{ old('role') == 'alumni' ? 'selected' : '' }}>🎓 Alumni Graduate</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>📚 Current Diploma Student</option>
                                <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>👨‍🏫 Faculty / Professor</option>
                            </select>
                            @error('role') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Enrollment / Roll Number -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Enrollment / Entry / Roll No <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="entry_no" value="{{ old('entry_no') }}" required placeholder="e.g. 216240107001 or KDP/ME/19"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('entry_no') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Degree / Course -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Course / Qualification <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="degree" value="{{ old('degree', 'Diploma in Engineering') }}" required placeholder="Diploma in Engineering"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('degree') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Engineering Department <span class="text-red-500">*</span>
                            </label>
                            <select name="department" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] text-slate-700 font-medium">
                                <option value="">Select Department</option>
                                <option value="Computer Engineering" {{ old('department') == 'Computer Engineering' ? 'selected' : '' }}>💻 Computer Engineering</option>
                                <option value="Mechanical Engineering" {{ old('department') == 'Mechanical Engineering' ? 'selected' : '' }}>⚙️ Mechanical Engineering</option>
                                <option value="Electrical Engineering" {{ old('department') == 'Electrical Engineering' ? 'selected' : '' }}>⚡ Electrical Engineering</option>
                                <option value="Civil Engineering" {{ old('department') == 'Civil Engineering' ? 'selected' : '' }}>🏗️ Civil Engineering</option>
                                <option value="Applied Mechanics" {{ old('department') == 'Applied Mechanics' ? 'selected' : '' }}>🔬 Applied Mechanics</option>
                                <option value="General Science" {{ old('department') == 'General Science' ? 'selected' : '' }}>📖 General Science &amp; Humanities</option>
                            </select>
                            @error('department') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Year of Joining -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Year of Joining <span class="text-red-500">*</span>
                            </label>
                            <select name="year_joining" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] text-slate-700 cursor-pointer">
                                <option value="">Select Joining Year</option>
                                @for($i = date('Y'); $i >= 1961; $i--)
                                    <option value="{{ $i }}" {{ old('year_joining') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year_joining') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Year of Graduation -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Year of Graduation <span class="text-red-500">*</span>
                            </label>
                            <select name="graduation_year" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] text-slate-700 cursor-pointer">
                                <option value="">Select Graduation Year</option>
                                @for($i = date('Y') + 4; $i >= 1961; $i--)
                                    <option value="{{ $i }}" {{ old('graduation_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('graduation_year') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SECTION 3: CAREER & PROFESSIONAL JOURNEY   -->
                <!-- ========================================== -->
                <div>
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2.5 mb-6">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">💼</span>
                            <h3 class="text-base font-bold font-heading text-[#1e3a8a] uppercase tracking-wide">
                                3. Professional Details &amp; Industry Profile
                            </h3>
                        </div>
                        <span class="text-[11px] text-slate-400 font-semibold font-sans">(For Alumni Directory &amp; Peer Networking)</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <!-- Company -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Current Company / Organization <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company" value="{{ old('company') }}" required placeholder="e.g. L&T, Siemens, Adani, or 'Student'"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('company') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Designation -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Designation / Role <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="designation" value="{{ old('designation') }}" required placeholder="e.g. Diploma Engineer Trainee, Senior Engineer"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('designation') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Work Industry -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Industry Sector <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="work_industry" value="{{ old('work_industry') }}" required placeholder="e.g. EPC & Construction, Power Grid, Automotive, IT"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('work_industry') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Core Skills -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 font-heading">
                                Core Technical Skills <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="skills" value="{{ old('skills') }}" required placeholder="e.g. AutoCAD, Python, PLC Automation, Surveying"
                                   class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] shadow-2xs font-sans">
                            @error('skills') <span class="text-red-500 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-200">
                    <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs sm:text-sm uppercase tracking-wider py-3.5 px-6 rounded-xl shadow-lg transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                        <span>Submit Registration &amp; Apply for Smart ID Card</span>
                        <span>&rarr;</span>
                    </button>
                    
                    <p class="text-center text-xs text-slate-500 mt-4 font-sans">
                        By submitting, your details will be verified by the K. D. Polytechnic Admin Office for official alumni enshrinement.
                    </p>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
