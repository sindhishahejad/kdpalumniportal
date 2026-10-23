@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10" x-data="{ guidanceModalOpen: false }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('pitches.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-[#8b0000] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Incubation Board
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-bold">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 font-bold">&times;</button>
            </div>
        @endif

        @php
            $statusStyles = [
                'open' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-300', 'badge' => 'Open for Mentorship'],
                'in_progress' => ['bg' => 'bg-blue-50 text-blue-800 border-blue-300', 'badge' => 'In Progress / Mentoring Active'],
                'completed' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-300', 'badge' => 'Completed / Successfully Incubated'],
            ][$pitch->status] ?? ['bg' => 'bg-gray-100 text-gray-800 border-gray-300', 'badge' => ucfirst($pitch->status)];

            $isOwner = auth()->check() && auth()->id() === $pitch->user_id;
            $canUpdateStatus = auth()->check() && ($isOwner || auth()->user()->role === 'faculty' || auth()->user()->isAdmin());
            $canDelete = auth()->check() && ($isOwner || auth()->user()->isAdmin());
            $canOfferGuidance = auth()->check() && !$isOwner && in_array(auth()->user()->role, ['alumni', 'faculty', 'admin']);
        @endphp

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            
            <!-- Header Banner -->
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusStyles['bg'] }}">
                        {{ $statusStyles['badge'] }}
                    </span>
                    <span class="text-xs text-gray-400">
                        Pitch submitted {{ $pitch->created_at->format('M d, Y') }} ({{ $pitch->created_at->diffForHumans() }})
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gray-900 leading-tight">
                    {{ $pitch->title }}
                </h1>
            </div>

            <!-- Content Grid -->
            <div class="p-6 sm:p-8 space-y-8">
                
                <!-- Student Innovator Profile Strip -->
                <div class="bg-gray-50 rounded-xl p-4 sm:p-5 border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-[#8b0000] text-white flex items-center justify-center font-bold text-lg">
                            {{ substr(optional($pitch->student)->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ optional($pitch->student)->name ?? 'Student Innovator' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ optional($pitch->student)->department ?? 'KD Polytechnic' }}
                                @if(optional($pitch->student)->entry_no) • Roll/ID: {{ $pitch->student->entry_no }} @endif
                            </p>
                        </div>
                    </div>

                    @if(!$isOwner && auth()->check())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('messages.inbox', $pitch->user_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Chat with Student
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Assistance Needed Callout -->
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-sm font-bold text-orange-900">Specific Assistance Requested</h3>
                            <p class="text-sm text-orange-800 mt-0.5">{{ $pitch->assistance_needed }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tech Stack Section -->
                <div>
                    <h3 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-2">Technologies &amp; Frameworks</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_map('trim', explode(',', $pitch->tech_stack)) as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-mono font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Problem Statement & Project Description -->
                <div>
                    <h3 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-2">Project Vision &amp; Problem Statement</h3>
                    <div class="prose max-w-none text-gray-700 text-sm leading-relaxed whitespace-pre-line bg-gray-50/60 p-5 rounded-xl border border-gray-100">
                        {{ $pitch->description }}
                    </div>
                </div>

                <!-- Guidance Section Anchor -->
                <div id="guidance"></div>

                <!-- Guidance Submission Card for Mentors -->
                @if($canOfferGuidance)
                    <div class="bg-blue-50/50 rounded-2xl border border-blue-200 p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase bg-blue-100 text-blue-800">Alumni &amp; Faculty Mentorship</span>
                            <h3 class="text-base font-bold text-gray-900">Offer Mentorship &amp; Guidance</h3>
                        </div>
                        <p class="text-xs text-gray-600 mb-4">
                            Submit actionable advice, technical suggestions, or offer to mentor the student team. This will automatically deliver an in-app message and notification to the student.
                        </p>

                        <form method="POST" action="{{ route('pitches.offer-guidance', $pitch->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="guidance" rows="3" required placeholder="Write your mentorship advice, hardware recommendations, or guidance offer here..." class="w-full text-sm border-gray-300 rounded-xl focus:outline-none focus:border-blue-600 p-3 shadow-sm">{{ old('guidance') }}</textarea>
                                @error('guidance') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-[#1C3661] hover:bg-blue-900 text-white font-bold px-5 py-2 rounded-xl text-xs shadow-sm transition-colors inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Deliver Guidance Message
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Lifecycle Status Management (for Creator, Faculty, or Admin) -->
                @if($canUpdateStatus)
                    <div class="border-t border-gray-200 pt-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-900 mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Incubation Lifecycle Status
                            </h3>
                            <p class="text-xs text-gray-500 mb-4">
                                Update this project's stage as student and alumni collaborate through mentoring and development.
                            </p>

                            <form method="POST" action="{{ route('pitches.update-status', $pitch->id) }}" class="flex flex-wrap items-center gap-3">
                                @csrf
                                @method('PATCH')

                                <button type="submit" name="status" value="open" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-all {{ $pitch->status === 'open' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-emerald-50' }}">
                                    • Open for Mentorship
                                </button>

                                <button type="submit" name="status" value="in_progress" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-all {{ $pitch->status === 'in_progress' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-50' }}">
                                    • In Progress / Mentoring
                                </button>

                                <button type="submit" name="status" value="completed" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-all {{ $pitch->status === 'completed' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-purple-50' }}">
                                    • Completed / Incubated
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Danger Zone: Delete Pitch -->
                @if($canDelete)
                    <div class="border-t border-gray-200 pt-4 flex justify-end">
                        <form method="POST" action="{{ route('pitches.destroy', $pitch->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this project pitch?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 hover:underline inline-flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete Project Pitch
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
