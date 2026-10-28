@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Hero Header Banner -->
    <div class="relative bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-blue-50/50 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f97316]/10 border border-[#f97316]/20 text-[#c2410c] text-xs font-bold tracking-wide font-heading">
                        <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                        કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                    </span>
                    <span class="bg-blue-50 text-blue-800 text-xs font-extrabold uppercase tracking-wider py-1 px-3 rounded-full border border-blue-100 font-heading">
                        Community Feed
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight">
                    Knowledge Feed &amp; Challenge Board
                </h1>
                <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Collaborate with fellow KDP diploma engineers, post algorithmic coding challenges, exchange technical insights, and mentor junior students.
                </p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                <a href="#compose-section" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all font-heading">
                    <span>✍️</span> Create Post / Challenge
                </a>
            </div>
        </div>

        <!-- Search Bar and Counts Ribbon -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('posts.index') }}" class="flex-1 max-w-lg flex items-center gap-2">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <div class="relative w-full">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Search discussions, challenges, or tags..." 
                           class="w-full bg-slate-50 border border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm pl-10 pr-4 py-2.5 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#0f2042] text-xs font-bold rounded-2xl transition-colors font-heading shrink-0">
                    Search
                </button>
                @if(request('q'))
                    <a href="{{ route('posts.index', request()->only('type')) }}" class="text-xs text-rose-600 font-bold hover:underline shrink-0 font-heading">
                        Clear
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2 text-xs text-slate-500 font-heading flex-wrap">
                <span class="px-3 py-1 bg-slate-50 rounded-xl border border-slate-200">
                    Total: <strong class="text-[#0f2042]">{{ $counts['all'] ?? 0 }}</strong>
                </span>
                <span class="px-3 py-1 bg-blue-50 text-blue-800 rounded-xl border border-blue-200">
                    💡 Knowledge: <strong class="text-blue-900">{{ $counts['knowledge'] ?? 0 }}</strong>
                </span>
                <span class="px-3 py-1 bg-purple-50 text-purple-800 rounded-xl border border-purple-200">
                    ⚡ Challenges: <strong class="text-purple-900">{{ $counts['challenge'] ?? 0 }}</strong>
                </span>
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
        <div class="bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center gap-3">
            <span class="text-rose-600 text-lg">⚠</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Create Post / Challenge Composer -->
    <div id="compose-section" class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">✍️</span>
                <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">Share Knowledge or Challenge the Campus</h3>
            </div>
            <span class="text-xs text-slate-400 font-medium">Markdown Supported</span>
        </div>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">Category</label>
                    <select id="type" name="type" class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm py-2.5 transition-all">
                        <option value="knowledge" {{ old('type') === 'knowledge' ? 'selected' : '' }}>💡 Knowledge Feed (Discussion / Notes)</option>
                        <option value="challenge" {{ old('type') === 'challenge' ? 'selected' : '' }}>⚡ Challenge Board (Coding / Engineering Puzzle)</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">Topic / Problem Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="e.g. Optimized Sorting in Embedded Systems or Diploma Microcontroller Project Query" 
                           class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm py-2.5 transition-all">
                    @error('title')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="body" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">Content &amp; Description</label>
                <textarea id="body" 
                          name="body" 
                          rows="4" 
                          placeholder="Write your explanation or puzzle. Use Markdown code blocks like ```python ... ``` for syntax highlighting." 
                          required 
                          class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm p-4 transition-all">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2">
                <span class="text-xs text-slate-400">
                    Pro-tip: Enclose code snippets between triple backticks (```) for clean formatting.
                </span>
                <button type="submit" class="px-6 py-2.5 bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider rounded-2xl shadow-sm hover:shadow transition-all font-heading">
                    Publish to Feed &rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <a href="{{ route('posts.index', request()->only('q')) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ !request('type') ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            All Feed ({{ $counts['all'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['type' => 'knowledge'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('type') === 'knowledge' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            💡 Knowledge ({{ $counts['knowledge'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['type' => 'challenge'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('type') === 'challenge' ? 'bg-purple-700 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            ⚡ Challenge Board ({{ $counts['challenge'] ?? 0 }})
        </a>
    </div>

    <!-- Posts Stream -->
    <div class="space-y-6">
        @forelse ($posts as $post)
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 hover:border-slate-200 transition-all space-y-4">
                
                <!-- Post Top Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#0f2042] to-[#1e3a8a] text-white font-black text-sm flex items-center justify-center font-heading shadow-xs">
                            {{ strtoupper(substr($post->user->name ?? 'K', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-[#0f2042] text-sm font-heading">{{ $post->user->name }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $post->user->role === 'alumni' ? 'bg-orange-50 text-[#c2410c] border border-orange-200' : ($post->user->role === 'faculty' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }} font-heading">
                                    {{ $post->user->role }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ optional($post->user->profile)->current_company ?? optional($post->user->profile)->department ?? 'K. D. Polytechnic' }} &bull; {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider font-heading {{ $post->type === 'challenge' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            {{ $post->type === 'challenge' ? '⚡ Challenge' : '💡 Knowledge' }}
                        </span>

                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $post->user_id))
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to remove this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 py-1.5 px-3 rounded-xl transition-colors font-heading">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Post Title & Body -->
                <div>
                    @if($post->title)
                        <h3 class="text-lg sm:text-xl font-extrabold text-[#0f2042] font-heading mb-2">
                            {{ $post->title }}
                        </h3>
                    @endif
                    
                    <div class="text-slate-700 text-sm leading-relaxed prose prose-sm max-w-none prose-pre:bg-slate-900 prose-pre:text-slate-100 prose-pre:rounded-2xl">
                        {!! Str::markdown($post->body) !!}
                    </div>
                </div>

                <!-- Threaded Replies Section -->
                <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50/70 rounded-2xl p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 font-heading">
                            💬 Discussions &amp; Replies ({{ $post->comments->count() }})
                        </h4>
                    </div>

                    <!-- Comment List -->
                    <div class="space-y-3">
                        @foreach ($post->comments as $comment)
                            <div class="bg-white p-3.5 rounded-2xl shadow-xs text-sm border border-slate-100">
                                <div class="flex items-center justify-between font-bold text-slate-900 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-extrabold text-[#0f2042] font-heading">{{ $comment->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id))
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Remove this reply?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-bold text-rose-500 hover:text-rose-700 hover:underline font-heading">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="text-slate-700 text-xs sm:text-sm prose prose-xs max-w-none">
                                    {!! Str::markdown($comment->body) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Comment Form -->
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="mt-3">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-start">
                            <textarea name="body" 
                                      rows="2" 
                                      class="block w-full bg-white border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm p-3 transition-all" 
                                      placeholder="Write your answer, solution, or comment... (Markdown supported)" 
                                      required></textarea>
                            <button type="submit" class="px-5 py-3 bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider rounded-2xl transition-all shadow-xs shrink-0 font-heading">
                                Reply
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl shadow-sm border border-slate-100 text-center space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-3xl flex items-center justify-center mx-auto">
                    💬
                </div>
                <div>
                    <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">No discussions found in feed</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                        {{ request('q') ? 'No discussions matching "' . request('q') . '". Try searching for something else.' : 'Be the first to share an engineering insight, ask a question, or post a code puzzle!' }}
                    </p>
                </div>
                <a href="#compose-section" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#0f2042] text-white text-xs font-bold uppercase tracking-wider font-heading hover:bg-[#1e3a8a] transition-all">
                    Start a Discussion &rarr;
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>

</div>
@endsection
