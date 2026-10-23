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
                        @if(request()->anyFilled(['search', 'role', 'graduation_year', 'degree', 'department', 'company', 'location', 'designation', 'work_industry', 'skills']) || request()->has('batchmates'))
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

                        <select name="location" onchange="this.form.submit()" class="w-full bg-[#e9ecef] text-gray-700 py-3 px-4 text-sm font-medium hover:bg-gray-300 transition-colors border-none outline-none cursor-pointer appearance-none">
                            <option value="">City / Region</option>
                            @if(isset($locations))
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
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
                
                <!-- Members Count Banner & Batchmates Shortcut -->
                <div class="bg-[#8b0000] text-white px-4 py-3 text-sm font-medium shadow-sm mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div>
                        <span>{{ $alumni->total() }} Members in community</span>
                        @if(request('batchmates'))
                            <span class="ml-2 bg-white/20 text-white text-xs px-2 py-0.5 rounded font-normal">Filtered: My Batchmates</span>
                        @endif
                    </div>
                    @auth
                        <div>
                            @if(request('batchmates'))
                                <a href="{{ route('alumni.index') }}" class="text-xs text-white/90 underline hover:text-white inline-flex items-center gap-1">
                                    <span>Show All Alumni</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @else
                                <a href="{{ route('alumni.index', ['batchmates' => 1]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-[#8b0000] hover:bg-red-50 text-xs font-semibold rounded shadow-sm transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                    Find My Batchmates
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <!-- Subheading -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl text-gray-600 font-light">
                        @if(request('batchmates'))
                            My Batchmates
                            @php
                                $batchYear = auth()->user()->graduation_year ?? optional(auth()->user()->profile)->graduation_year;
                                $batchDept = auth()->user()->department ?? optional(auth()->user()->profile)->department;
                            @endphp
                            @if($batchYear || $batchDept)
                                <span class="text-xs text-gray-500 font-normal">({{ $batchYear }} {{ $batchDept }})</span>
                            @endif
                        @else
                            All Profiles
                        @endif
                    </h2>
                </div>

                <!-- 4-Column Grid matching the screenshot -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    
                    @forelse($alumni as $alum)
                        @php
                            // Generate a random background color for the avatar fallback
                            $colors = ['3b5998', 'd93025', '1C3661', '0f9d58', 'f4b400'];
                            $randomColor = $colors[crc32($alum->name) % count($colors)];
                            $profile = $alum->profile;
                            $desig = $alum->designation ?? optional($profile)->job_title;
                            $comp = $alum->company ?? optional($profile)->current_company;
                            $city = optional($profile)->location;
                            $gradYear = $alum->graduation_year ?? optional($profile)->graduation_year;
                            $degree = $alum->degree ?? optional($profile)->degree;
                            $dept = $alum->department ?? optional($profile)->department;
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
                                
                                @if($gradYear)
                                    <p class="text-[11px] text-gray-500 mb-1">Class of {{ $gradYear }}</p>
                                @else
                                    <p class="text-[11px] text-gray-500 mb-1 capitalize">{{ $alum->role ?? 'Alumni' }}</p>
                                @endif
                                
                                <p class="text-[11px] text-gray-500 mb-2 leading-relaxed line-clamp-2">
                                    {{ $degree ?? 'KD Polytechnic' }}@if($dept), {{ $dept }}@endif
                                </p>
                                
                                <!-- Company, Designation & Location Details -->
                                <div class="mt-auto">
                                    @if($desig || $comp || $city)
                                        <div class="pt-2 border-t border-gray-100 space-y-0.5">
                                            @if($desig)
                                                <p class="text-[11px] text-gray-700 font-medium truncate" title="{{ $desig }}">{{ $desig }}</p>
                                            @endif
                                            @if($comp)
                                                <p class="text-[11px] text-gray-500 truncate" title="{{ $comp }}">{{ $comp }}</p>
                                            @endif
                                            @if($city)
                                                <p class="text-[11px] text-gray-500 flex items-center gap-1 truncate" title="{{ $city }}">
                                                    <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    <span class="truncate">{{ $city }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Contact Details with Privacy Masking -->
                                <div class="pt-3 mt-3 border-t border-gray-100 space-y-1">
                                    <!-- Email Display -->
                                    <div class="text-[11px] truncate flex items-center">
                                        <span class="font-bold text-gray-700 w-4">E:</span>
                                        @if(optional($profile)->is_email_public || (auth()->check() && auth()->user()->role === 'admin'))
                                            <a href="mailto:{{ $alum->email }}" class="text-[#8b0000] hover:underline ml-1 truncate">{{ $alum->email }}</a>
                                        @else
                                            <span class="text-gray-400 italic ml-1">Hidden</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Phone Display -->
                                    <div class="text-[11px] truncate flex items-center">
                                        <span class="font-bold text-gray-700 w-4">P:</span>
                                        @if(optional($profile)->is_phone_public || (auth()->check() && auth()->user()->role === 'admin'))
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
