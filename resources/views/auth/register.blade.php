@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <!-- Logo or Header -->
        <h2 class="mt-6 text-center text-3xl font-serif font-bold text-gray-900 tracking-wide">
            Join the Alumni Network
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Connect with batchmates, discover opportunities, and showcase your professional journey.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
        <div class="bg-white py-10 px-4 shadow-xl sm:rounded-sm sm:px-10 border-t-4 border-[#8b0000]">
            
            <form method="POST" action="{{ route('register') }}" class="space-y-10">
                @csrf

                <!-- ========================================== -->
                <!-- SECTION 1: ACCOUNT DETAILS                 -->
                <!-- ========================================== -->
                <div>
                    <h3 class="text-lg font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Account Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] transition-colors">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] transition-colors">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password Field with Eye Toggle -->
                        <div x-data="{ show: false }" class="relative">
                            <label class="block text-[13px] text-gray-600 mb-1">Password *</label>
                            <input :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] transition-colors pr-10">
                            <button type="button" @click="show = !show" class="absolute right-0 bottom-2 text-gray-400 hover:text-[#8b0000] focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password Field with Eye Toggle -->
                        <div x-data="{ show: false }" class="relative">
                            <label class="block text-[13px] text-gray-600 mb-1">Confirm Password *</label>
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] transition-colors pr-10">
                            <button type="button" @click="show = !show" class="absolute right-0 bottom-2 text-gray-400 hover:text-[#8b0000] focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="md:col-span-2 flex space-x-4">
                            <div class="w-1/3 md:w-1/4">
                                <label class="block text-[13px] text-gray-600 mb-1">Code</label>
                                <input type="text" name="country_code" value="{{ old('country_code', '+91') }}" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[13px] text-gray-600 mb-1">Phone Number *</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SECTION 2: ACADEMIC DETAILS                -->
                <!-- ========================================== -->
                <div>
                    <h3 class="text-lg font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Academic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">I am registering as a: *</label>
                            <select name="role" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] cursor-pointer">
                                <option value="alumni" {{ old('role') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Current Student</option>
                                <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Entry No / Roll No *</label>
                            <input type="text" name="entry_no" value="{{ old('entry_no') }}" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('entry_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Course / Degree *</label>
                            <input type="text" name="degree" value="{{ old('degree') }}" required placeholder="e.g. B.Tech, M.Tech, Ph.D" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('degree') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Department *</label>
                            <input type="text" name="department" value="{{ old('department') }}" required placeholder="e.g. Computer Science" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('department') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Year of Joining *</label>
                            <select name="year_joining" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] cursor-pointer">
                                <option value="">Select Year</option>
                                @for($i = date('Y'); $i >= 1970; $i--)
                                    <option value="{{ $i }}" {{ old('year_joining') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year_joining') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Year of Graduation *</label>
                            <select name="graduation_year" required class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px] cursor-pointer">
                                <option value="">Select Year</option>
                                @for($i = date('Y') + 5; $i >= 1970; $i--)
                                    <option value="{{ $i }}" {{ old('graduation_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('graduation_year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- SECTION 3: PROFESSIONAL DETAILS            -->
                <!-- ========================================== -->
                <div>
                    <h3 class="text-lg font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Professional Details <span class="text-xs text-gray-400 font-normal ml-2">(Required for Directory)</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Company *</label>
                            <input type="text" name="company" value="{{ old('company') }}" required placeholder="Your current company or 'Student'" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('company') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Designation *</label>
                            <input type="text" name="designation" value="{{ old('designation') }}" required placeholder="e.g. Software Engineer" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('designation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Work Industry *</label>
                            <input type="text" name="work_industry" value="{{ old('work_industry') }}" required placeholder="e.g. IT, Finance, Healthcare" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('work_industry') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] text-gray-600 mb-1">Top Skills *</label>
                            <input type="text" name="skills" value="{{ old('skills') }}" required placeholder="e.g. Management, Python, Marketing" class="w-full border-b border-gray-300 py-1.5 text-gray-900 focus:outline-none focus:border-[#8b0000] bg-transparent text-[15px]">
                            @error('skills') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-6">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-sm shadow-md text-sm font-bold text-white bg-[#8b0000] hover:bg-[#6b0d0d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8b0000] transition-colors uppercase tracking-wider">
                        Join Alumni Network
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Already registered? <a href="{{ route('login') }}" class="font-medium text-[#1C3661] hover:text-[#8b0000] transition-colors">Sign in here</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection