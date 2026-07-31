@extends('layouts.app')

@section('content')
<!-- Banner with Gradient -->
<div class="relative w-full h-64 flex items-center justify-center overflow-hidden shadow-md">
    <!-- Background Image -->
    <img src="https://picsum.photos/id/1025/1920/600" class="absolute inset-0 w-full h-full object-cover" alt="Profile Background">
    <!-- Gradient Overlay blending your Navy and Maroon -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#1C3661] via-[#1C3661]/90 to-[#8b0000]/90"></div>
    
    <!-- Header Content -->
    <div class="relative z-10 text-center text-white px-4 mt-8">
        <h1 class="font-serif text-3xl md:text-5xl font-bold mb-4 tracking-wide">Your Alumni Profile</h1>
        <p class="text-sm md:text-lg font-light text-gray-200 max-w-3xl mx-auto">
            Keep your information updated to strengthen your connections, discover new opportunities, and stay engaged with your community.
        </p>
    </div>
</div>

<!-- Main Content Area -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 pb-16">
    <div class="bg-white shadow-xl sm:rounded-sm border-t-4 border-[#8b0000] p-8 md:p-10">
        
        <!-- Success Message for Profile Update -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-sm flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <p class="text-sm text-green-700 font-medium">Profile updated successfully!</p>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-10">
            @csrf
            @method('patch')

            <!-- 1. Personal Details -->
            <div>
                <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Current Role</label>
                        <input type="text" value="{{ ucfirst($user->role ?? 'Alumni') }}" disabled class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-sm py-2 px-3 text-sm cursor-not-allowed">
                        <span class="text-xs text-gray-400 mt-1 block">Role cannot be changed after registration.</span>
                    </div>
                </div>
            </div>

            <!-- 2. Academic Details -->
            <div>
                <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Academic Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Entry No / Roll No</label>
                        <input type="text" name="entry_no" value="{{ old('entry_no', $user->entry_no) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('entry_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Course / Degree</label>
                        <input type="text" name="degree" value="{{ old('degree', $user->degree) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('degree') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" value="{{ old('department', $user->department) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('department') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Year of Joining</label>
                            <select name="year_joining" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm cursor-pointer">
                                <option value="">Select</option>
                                @for($i = date('Y'); $i >= 1970; $i--)
                                    <option value="{{ $i }}" {{ old('year_joining', $user->year_joining) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year_joining') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Graduation Year</label>
                            <select name="graduation_year" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm cursor-pointer">
                                <option value="">Select</option>
                                @for($i = date('Y') + 5; $i >= 1970; $i--)
                                    <option value="{{ $i }}" {{ old('graduation_year', $user->graduation_year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('graduation_year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Professional Details -->
            <div>
                <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Professional Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Company</label>
                        <input type="text" name="company" value="{{ old('company', $user->company) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('company') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('designation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Work Industry</label>
                        <input type="text" name="work_industry" value="{{ old('work_industry', $user->work_industry) }}" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('work_industry') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Top Skills</label>
                        <input type="text" name="skills" value="{{ old('skills', $user->skills) }}" placeholder="e.g. Management, Python, Marketing" class="w-full border border-gray-300 rounded-sm py-2 px-3 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                        @error('skills') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button for Profile -->
            <div class="pt-4 flex justify-end border-b border-gray-200 pb-10">
                <button type="submit" class="bg-[#8b0000] hover:bg-[#6b0d0d] text-white font-bold py-3 px-8 rounded-sm shadow-md transition-colors text-sm">
                    Save Profile Changes
                </button>
            </div>
        </form>

        <!-- ========================================== -->
        <!-- 4. SECURITY DETAILS (PASSWORD UPDATE)      -->
        <!-- ========================================== -->
        <form method="POST" action="{{ route('password.update') }}" class="pt-10 space-y-6">
            @csrf
            @method('put')

            <div>
                <h3 class="text-xl font-serif font-bold text-[#1C3661] border-b border-gray-200 pb-2 mb-6">Security Details</h3>
                
                <!-- Success Message for Password Update -->
                @if (session('status') === 'password-updated')
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-sm flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <p class="text-sm text-green-700 font-medium">Password updated successfully!</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" required class="w-full border border-gray-300 rounded-sm py-2 px-3 pr-10 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#8b0000] focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>
                    
                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">New Password <span class="text-red-500">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required class="w-full border border-gray-300 rounded-sm py-2 px-3 pr-10 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#8b0000] focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('password', 'updatePassword') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>
                    
                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required class="w-full border border-gray-300 rounded-sm py-2 px-3 pr-10 focus:outline-none focus:border-[#8b0000] focus:ring-1 focus:ring-[#8b0000] text-sm">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#8b0000] focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-[#1C3661] hover:bg-[#122442] text-white font-bold py-3 px-8 rounded-sm shadow-md transition-colors text-sm">
                    Update Password
                </button>
            </div>
        </form>

    </div>
</div>
@endsection