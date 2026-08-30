@extends('layouts.app')

@section('content')
    <div class="bg-white min-h-screen pb-16 pt-8">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
            
            <!-- LEFT SIDEBAR: Filters -->
            <div class="w-full lg:w-1/4 lg:max-w-[280px]">
                <h3 class="text-base font-medium text-gray-800 mb-4">Filters</h3>
                
                <form action="{{ route('alumni.index') }}" method="GET" id="filter-form" class="mb-6">
                    
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="flex">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Enter Keyword.." class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-[#8b0000]">
                            <button type="submit" class="bg-[#8b0000] text-white px-4 hover:bg-[#6b0000] transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                        @if(request()->anyFilled(['search', 'role', 'year_joining', 'graduation_year', 'degree', 'department', 'company', 'designation', 'work_industry', 'skills']))
                            <a href="{{ route('alumni.index') }}" class="text-xs text-[#8b0000] mt-2 inline-block hover:underline">Clear All Filters</a>
                        @endif
                    </div>

                    <!-- Dynamic Filter Dropdowns -->
                    <div class="space-y-1">
                        
                        <select name="role" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Search by Role</option>
                            @if(isset($roles))
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="year_joining" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Year of Joining</option>
                            @if(isset($joinYears))
                                @foreach($joinYears as $year)
                                    <option value="{{ $year }}" {{ request('year_joining') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="graduation_year" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Year of Graduation</option>
                            @if(isset($gradYears))
                                @foreach($gradYears as $year)
                                    <option value="{{ $year }}" {{ request('graduation_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="degree" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Course/Degree</option>
                            @if(isset($degrees))
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree }}" {{ request('degree') == $degree ? 'selected' : '' }}>{{ $degree }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="department" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Department</option>
                            @if(isset($departments))
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="company" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Company</option>
                            @if(isset($companies))
                                @foreach($companies as $company)
                                    <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>{{ $company }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="designation" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Designation</option>
                            @if(isset($designations))
                                @foreach($designations as $desig)
                                    <option value="{{ $desig }}" {{ request('designation') == $desig ? 'selected' : '' }}>{{ $desig }}</option>
                                @endforeach
                            @endif
                        </select>

                        <select name="work_industry" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">Work Industry</option>
                            @if(isset($industries))
                                @foreach($industries as $industry)
                                    <option value="{{ $industry }}" {{ request('work_industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                @endforeach
                            @endif
                        </select>

                        <!-- Skills Input -->
                        <div class="flex">
                            <input type="text" name="skills" value="{{ request('skills') }}" placeholder="Search Skills..." class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none">
                            <button type="submit" class="bg-[#e9ecef] hover:bg-gray-300 text-gray-500 px-3 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- RIGHT MAIN CONTENT: Directory Grid -->
            <div class="w-full lg:flex-1">
                
                <!-- Members Count Banner -->
                <div class="bg-[#8b0000] text-white px-4 py-3 text-sm font-medium shadow-sm mb-6">
                    {{ $alumni->total() }} Members in community
                </div>

                <!-- Subheading -->
                <h2 class="text-xl text-gray-500 mb-6 font-light">All Profiles</h2>

                <!-- 4-Column Grid matching the screenshot -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    
                    @forelse($alumni as $alum)
                        @php
                            // Generate a random background color for the avatar fallback
                            $colors = ['3b5998', 'd93025', '1C3661', '0f9d58', 'f4b400'];
                            $randomColor = $colors[crc32($alum->name) % count($colors)];
                        @endphp

                        <!-- Profile Card -->
                        <div class="bg-white border border-gray-200 overflow-hidden shadow-sm flex flex-col group hover:shadow-md transition-shadow">
                            
                            <!-- Square Aspect Ratio Image -->
                            <div class="w-full aspect-square bg-gray-100 relative">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($alum->name) }}&color=fff&background={{ $randomColor }}&size=512&font-size=0.4" class="w-full h-full object-cover" alt="{{ $alum->name }}">
                            </div>
                            
                            <!-- Profile Details -->
                            <div class="p-4 flex-grow flex flex-col">
                                <h3 class="text-[15px] font-medium text-gray-900 mb-1 truncate" title="{{ $alum->name }}">
                                    <a href="{{ route('alumni.show', $alum->id) }}" class="hover:text-[#8b0000] transition-colors">
                                        {{ $alum->name }}
                                    </a>
                                </h3>
                                
                                @if($alum->graduation_year)
                                    <p class="text-[11px] text-gray-500 mb-1">Class of {{ $alum->graduation_year }}</p>
                                @else
                                    <p class="text-[11px] text-gray-500 mb-1 capitalize">{{ $alum->role ?? 'Alumni' }}</p>
                                @endif
                                
                                <p class="text-[11px] text-gray-500 mb-2 leading-relaxed line-clamp-2">
                                    {{ $alum->degree ?? 'Degree N/A' }}@if($alum->department), {{ $alum->department }}@endif
                                </p>
                                
                                <!-- Company & Designation Details -->
                                <div class="mt-auto">
                                    @if($alum->company || $alum->designation)
                                        <div class="pt-2 border-t border-gray-100">
                                            <p class="text-[11px] text-gray-700 font-medium truncate">{{ $alum->designation }}</p>
                                            <p class="text-[11px] text-gray-500 truncate">{{ $alum->company }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- ✨ Contact Details with Privacy Masking ✨ -->
                                <div class="pt-3 mt-3 border-t border-gray-100 space-y-1">
                                    <!-- Email Display -->
                                    <div class="text-[11px] truncate flex items-center">
                                        <span class="font-bold text-gray-700 w-4">E:</span>
                                        @if(optional($alum->profile)->is_email_public || auth()->user()->role === 'admin')
                                            <a href="mailto:{{ $alum->email }}" class="text-[#8b0000] hover:underline ml-1 truncate">{{ $alum->email }}</a>
                                        @else
                                            <span class="text-gray-400 italic ml-1">Hidden</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Phone Display -->
                                    <div class="text-[11px] truncate flex items-center">
                                        <span class="font-bold text-gray-700 w-4">P:</span>
                                        @if(optional($alum->profile)->is_phone_public || auth()->user()->role === 'admin')
                                            @if($alum->phone)
                                                <a href="tel:{{ $alum->phone }}" class="text-[#8b0000] hover:underline ml-1 truncate">{{ $alum->phone }}</a>
                                            @else
                                                <span class="text-gray-500 ml-1">N/A</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 italic ml-1">Hidden</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center py-16 text-gray-500 border border-gray-200">
                            No alumni profiles found matching your search criteria.
                        </div>
                    @endforelse

                </div>

                <!-- Pagination Links -->
                <div class="mt-8">
                    {{ $alumni->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection