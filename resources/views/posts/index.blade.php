@extends('layouts.app')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 font-sans" 
     x-data="{
         category: 'knowledge',
         questionType: 'discussion',
         numOptions: 4,
         mcqOptions: ['', '', '', ''],
         correctOptionIndex: 0,
         imagePreview: null,
         setNumOptions(n) {
             this.numOptions = n;
             while (this.mcqOptions.length < n) {
                 this.mcqOptions.push('');
             }
             while (this.mcqOptions.length > n) {
                 this.mcqOptions.pop();
             }
             if (this.correctOptionIndex >= n) {
                 this.correctOptionIndex = 0;
             }
         },
         handleImage(e) {
             const file = e.target.files[0];
             if (file) {
                 this.imagePreview = URL.createObjectURL(file);
             } else {
                 this.imagePreview = null;
             }
         }
     }">
    
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
                        Community Feed &amp; Challenges
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f2042] font-heading tracking-tight">
                    Knowledge Feed &amp; Challenge Board
                </h1>
                <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Collaborate with fellow KDP diploma engineers, post algorithmic coding challenges, exchange technical insights, ask MCQs, and verify student solutions.
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
                @if(request('question_type'))
                    <input type="hidden" name="question_type" value="{{ request('question_type') }}">
                @endif
                <div class="relative w-full">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Search discussions, challenges, code, or tags..." 
                           class="w-full bg-slate-50 border border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm pl-10 pr-4 py-2.5 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#0f2042] text-xs font-bold rounded-2xl transition-colors font-heading shrink-0 cursor-pointer">
                    Search
                </button>
                @if(request('q'))
                    <a href="{{ route('posts.index', request()->only(['type', 'question_type'])) }}" class="text-xs text-rose-600 font-bold hover:underline shrink-0 font-heading">
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
                <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-xl border border-amber-200">
                    🔘 MCQs: <strong class="text-amber-900">{{ $counts['mcq'] ?? 0 }}</strong>
                </span>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200">
                    💻 Coding: <strong class="text-emerald-900">{{ $counts['coding'] ?? 0 }}</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Status Alerts -->
    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-emerald-600 text-lg">✓</span>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-2xl text-sm font-medium flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-rose-600 text-lg">⚠</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Create Post / Challenge Composer -->
    <div id="compose-section" class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">✍️</span>
                <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">
                    Share Knowledge or Challenge the Campus
                </h3>
            </div>
            <span class="text-xs text-slate-400 font-medium">Markdown &amp; Rich Media Supported</span>
        </div>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <!-- Question Type / Format Switcher -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-2">
                    Format &amp; Question Type
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <button type="button" 
                            @click="category = 'knowledge'; questionType = 'discussion'"
                            :class="questionType === 'discussion' ? 'bg-[#0f2042] text-white shadow-xs border-[#0f2042]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2.5 px-3 rounded-2xl border text-xs font-bold text-center transition-all font-heading flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>💡</span>
                        <span>Discussion Note</span>
                    </button>
                    <button type="button" 
                            @click="category = 'challenge'; questionType = 'mcq'"
                            :class="questionType === 'mcq' ? 'bg-amber-600 text-white shadow-xs border-amber-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2.5 px-3 rounded-2xl border text-xs font-bold text-center transition-all font-heading flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>🔘</span>
                        <span>MCQ Challenge</span>
                    </button>
                    <button type="button" 
                            @click="category = 'challenge'; questionType = 'fill_blank'"
                            :class="questionType === 'fill_blank' ? 'bg-sky-600 text-white shadow-xs border-sky-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2.5 px-3 rounded-2xl border text-xs font-bold text-center transition-all font-heading flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>✍️</span>
                        <span>Fill in Blanks</span>
                    </button>
                    <button type="button" 
                            @click="category = 'challenge'; questionType = 'coding'"
                            :class="questionType === 'coding' ? 'bg-purple-700 text-white shadow-xs border-purple-700' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                            class="py-2.5 px-3 rounded-2xl border text-xs font-bold text-center transition-all font-heading flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>💻</span>
                        <span>Coding Challenge</span>
                    </button>
                </div>
                <input type="hidden" name="type" :value="category">
                <input type="hidden" name="question_type" :value="questionType">
            </div>

            <!-- Title & Topic -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">
                    Topic / Problem Title
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       placeholder="e.g. Microcontroller Interfacing, Big-O Complexity, or Lathe Speed Formula" 
                       class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm py-2.5 px-4 transition-all">
                @error('title')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Problem / Content Description -->
            <div>
                <label for="body" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">
                    Question / Discussion Text &amp; Details <span class="text-rose-500">*</span>
                </label>
                <textarea id="body" 
                          name="body" 
                          rows="4" 
                          placeholder="Write the full question, discussion context, or problem statement. For fill-in-the-blanks, use '___' or '[blank]'." 
                          required 
                          class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm p-4 transition-all">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 🔘 MCQ OPTIONS BUILDER (Shown when MCQ is selected) -->
            <div x-show="questionType === 'mcq'" x-cloak class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900 font-heading">
                            🔘 Multiple Choice Options Structure
                        </h4>
                        <p class="text-[11px] text-amber-700 mt-0.5">
                            Select the number of options (2 to 6) and select the radio button for the correct answer.
                        </p>
                    </div>

                    <!-- Number of Options Selector -->
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-amber-800 font-heading">Number of Options:</span>
                        <div class="flex gap-1">
                            <template x-for="n in [2, 3, 4, 5, 6]" :key="n">
                                <button type="button" 
                                        @click="setNumOptions(n)"
                                        :class="numOptions === n ? 'bg-amber-600 text-white font-bold' : 'bg-white text-slate-700 border border-amber-300 hover:bg-amber-100'"
                                        class="w-7 h-7 rounded-lg text-xs font-heading flex items-center justify-center transition-colors cursor-pointer"
                                        x-text="n"></button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Option Inputs -->
                <div class="space-y-2.5">
                    <template x-for="(opt, idx) in mcqOptions" :key="idx">
                        <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-200 shadow-2xs">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0">
                                <input type="radio" 
                                       name="correct_option" 
                                       :value="idx" 
                                       :checked="correctOptionIndex === idx" 
                                       @change="correctOptionIndex = idx"
                                       class="text-amber-600 focus:ring-amber-500">
                                <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-900 text-xs font-black flex items-center justify-center font-heading" 
                                      x-text="String.fromCharCode(65 + idx)"></span>
                            </label>
                            
                            <input type="text" 
                                   name="options[]" 
                                   x-model="mcqOptions[idx]" 
                                   :placeholder="'Option ' + String.fromCharCode(65 + idx) + ' text...'" 
                                   class="w-full text-xs sm:text-sm bg-transparent border-0 focus:ring-0 p-1 placeholder:text-slate-400">
                            
                            <span x-show="correctOptionIndex === idx" class="text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md shrink-0 font-heading">
                                ✓ Correct
                            </span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ✍️ FILL IN THE BLANKS BUILDER (Shown when Fill Blank is selected) -->
            <div x-show="questionType === 'fill_blank'" x-cloak class="p-5 rounded-2xl bg-sky-50/60 border border-sky-200/80 space-y-3">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-sky-900 font-heading">
                        ✍️ Fill in the Blank Verification Setting
                    </h4>
                    <p class="text-[11px] text-sky-700 mt-0.5">
                        Specify accepted answer(s). Multiple acceptable variations can be separated by commas (e.g. "ACC, Accumulator, Register A").
                    </p>
                </div>
                <div>
                    <label for="correct_blank" class="block text-[11px] font-bold uppercase tracking-wider text-sky-900 font-heading mb-1">
                        Accepted Answer(s) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="correct_blank" 
                           name="correct_blank" 
                           placeholder="e.g. Accumulator, ACC" 
                           class="w-full bg-white border-sky-300 focus:border-sky-600 focus:ring-sky-600 rounded-xl text-xs sm:text-sm py-2 px-3">
                </div>
            </div>

            <!-- 💻 CODING CHALLENGE BUILDER (Shown when Coding is selected) -->
            <div x-show="questionType === 'coding'" x-cloak class="p-5 rounded-2xl bg-purple-50/60 border border-purple-200/80 space-y-4">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-purple-900 font-heading">
                        💻 Algorithmic Coding Challenge Configuration
                    </h4>
                    <p class="text-[11px] text-purple-700 mt-0.5">
                        Set the target language, difficulty, starter boilerplate, and expected test output for automatic verification.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="programming_language" class="block text-[11px] font-bold uppercase tracking-wider text-purple-900 font-heading mb-1">
                            Target Programming Language
                        </label>
                        <select id="programming_language" name="programming_language" class="w-full bg-white border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl text-xs sm:text-sm py-2 px-3">
                            <option value="python">🐍 Python 3</option>
                            <option value="c">⚙️ C (GCC Diploma Syllabus)</option>
                            <option value="cpp">⚡ C++</option>
                            <option value="java">☕ Java</option>
                            <option value="javascript">🌐 JavaScript</option>
                            <option value="sql">🗄️ SQL</option>
                            <option value="php">🐘 PHP</option>
                        </select>
                    </div>

                    <div>
                        <label for="difficulty" class="block text-[11px] font-bold uppercase tracking-wider text-purple-900 font-heading mb-1">
                            Difficulty Level
                        </label>
                        <select id="difficulty" name="difficulty" class="w-full bg-white border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl text-xs sm:text-sm py-2 px-3">
                            <option value="easy">🟢 Easy (Diploma Basics)</option>
                            <option value="medium" selected>🟡 Medium (GTU D2D / Placement Prep)</option>
                            <option value="hard">🔴 Hard (Advanced Algorithm / Competitive)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="starter_code" class="block text-[11px] font-bold uppercase tracking-wider text-purple-900 font-heading mb-1">
                        Starter Template / Function Signature
                    </label>
                    <textarea id="starter_code" 
                              name="starter_code" 
                              rows="3" 
                              placeholder="def solve(arr):&#10;    # Write your solution here&#10;    pass" 
                              class="w-full bg-white font-mono text-xs border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl p-3"></textarea>
                </div>

                <div>
                    <label for="expected_output" class="block text-[11px] font-bold uppercase tracking-wider text-purple-900 font-heading mb-1">
                        Expected Output / Test Result <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="expected_output" 
                           name="expected_output" 
                           placeholder="e.g. 42 or [1, 2, 3] or Hello World" 
                           class="w-full bg-white font-mono text-xs sm:text-sm border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl py-2 px-3">
                </div>
            </div>

            <!-- 📷 IMAGE UPLOAD SECTION (For Admin, Alumni, Faculty) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                <div class="flex items-center justify-between">
                    <label for="feed_image" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                        📷 Attach Diagram / Circuit / Schematic Image (Optional)
                    </label>
                    <span class="text-[10px] text-slate-400">JPG, PNG, GIF, WEBP up to 4MB</span>
                </div>
                
                <input type="file" 
                       id="feed_image" 
                       name="image" 
                       accept="image/*" 
                       @change="handleImage"
                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0f2042] file:text-white hover:file:bg-[#1e3a8a] cursor-pointer">

                <!-- Image Live Preview -->
                <template x-if="imagePreview">
                    <div class="relative w-max mt-2">
                        <img :src="imagePreview" alt="Upload preview" class="max-h-48 rounded-xl object-contain border border-slate-300 shadow-sm">
                        <button type="button" 
                                @click="imagePreview = null; $refs.fileInput ? $refs.fileInput.value = '' : null; document.getElementById('feed_image').value = ''" 
                                class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shadow hover:bg-rose-700 cursor-pointer">
                            &times;
                        </button>
                    </div>
                </template>
            </div>

            <!-- Optional Explanation (Revealed upon solving) -->
            <div>
                <label for="explanation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 font-heading mb-1.5">
                    Solution Explanation &amp; Teaching Note (Optional)
                </label>
                <textarea id="explanation" 
                          name="explanation" 
                          rows="2" 
                          placeholder="Provide the rationale or underlying engineering concept. This is revealed to students after they verify their answer." 
                          class="w-full bg-slate-50 border-slate-200 focus:border-[#0f2042] focus:ring-[#0f2042] rounded-2xl text-xs sm:text-sm p-3 transition-all"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2">
                <span class="text-xs text-slate-400">
                    Pro-tip: Enclose code snippets between triple backticks (```) for clean formatting.
                </span>
                <button type="submit" class="px-7 py-3 bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider rounded-2xl shadow-sm hover:shadow transition-all font-heading cursor-pointer">
                    Publish to Feed &rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Tabs Ribbon -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <a href="{{ route('posts.index', request()->only('q')) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ !request('type') && !request('question_type') ? 'bg-[#0f2042] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            All Feed ({{ $counts['all'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['type' => 'knowledge'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('type') === 'knowledge' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            💡 Knowledge ({{ $counts['knowledge'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['type' => 'challenge'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('type') === 'challenge' && !request('question_type') ? 'bg-purple-700 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            ⚡ All Challenges ({{ $counts['challenge'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['question_type' => 'mcq'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('question_type') === 'mcq' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            🔘 MCQs ({{ $counts['mcq'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['question_type' => 'fill_blank'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('question_type') === 'fill_blank' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            ✍️ Fill in Blanks ({{ $counts['fill_blank'] ?? 0 }})
        </a>
        <a href="{{ route('posts.index', array_merge(request()->only('q'), ['question_type' => 'coding'])) }}" 
           class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition-all font-heading shrink-0 {{ request('question_type') === 'coding' ? 'bg-purple-900 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
            💻 Coding ({{ $counts['coding'] ?? 0 }})
        </a>
    </div>

    <!-- Posts Stream -->
    <div class="space-y-6">
        @forelse ($posts as $post)
            @php
                $mySubmission = auth()->check() ? $post->userSubmission() : null;
                $hasSolved = auth()->check() ? $post->hasUserSolved() : false;
            @endphp
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 hover:border-slate-200 transition-all space-y-5">
                
                <!-- Post Top Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        @if($post->user->photo_path)
                            <img src="{{ filter_var($post->user->photo_path, FILTER_VALIDATE_URL) ? $post->user->photo_path : asset('storage/' . $post->user->photo_path) }}" 
                                 alt="{{ $post->user->name }}" 
                                 class="w-10 h-10 rounded-2xl object-cover border border-slate-200 shadow-2xs shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#0f2042] to-[#1e3a8a] text-white font-black text-sm flex items-center justify-center font-heading shadow-xs shrink-0">
                                {{ strtoupper(substr($post->user->name ?? 'K', 0, 1)) }}
                            </div>
                        @endif

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

                    <div class="flex items-center gap-2.5 flex-wrap">
                        @if($post->question_type === 'mcq')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider font-heading bg-amber-50 text-amber-800 border border-amber-200">
                                🔘 MCQ Challenge
                            </span>
                        @elseif($post->question_type === 'fill_blank')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider font-heading bg-sky-50 text-sky-800 border border-sky-200">
                                ✍️ Fill in Blanks
                            </span>
                        @elseif($post->question_type === 'coding')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider font-heading bg-purple-50 text-purple-800 border border-purple-200">
                                💻 {{ strtoupper($post->programming_language ?? 'Code') }}
                            </span>
                            @if($post->difficulty)
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $post->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($post->difficulty === 'hard' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ $post->difficulty }}
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider font-heading bg-blue-50 text-blue-700 border border-blue-200">
                                💡 Knowledge
                            </span>
                        @endif

                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $post->user_id))
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to remove this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 py-1 px-2.5 rounded-xl transition-colors font-heading cursor-pointer">
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

                <!-- Attached Image (if present) -->
                @if($post->image_path)
                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 max-w-xl">
                        <img src="{{ asset('storage/' . $post->image_path) }}" 
                             alt="Attached Diagram" 
                             class="w-full max-h-96 object-contain hover:scale-102 transition-transform duration-300">
                    </div>
                @endif

                <!-- ============================================== -->
                <!-- INTERACTIVE CHALLENGE SOLVER SECTION           -->
                <!-- ============================================== -->
                @if($post->isInteractiveChallenge())
                    <div class="p-5 sm:p-6 rounded-2xl border transition-all space-y-4 {{ $hasSolved ? 'bg-emerald-50/50 border-emerald-200' : 'bg-slate-50 border-slate-200' }}"
                         x-data="{
                             selectedOption: '{{ $mySubmission ? $mySubmission->answer : '' }}',
                             blankInput: '{{ $mySubmission ? $mySubmission->answer : '' }}',
                             codeInput: `{{ $mySubmission ? addslashes($mySubmission->answer) : addslashes($post->starter_code ?? '') }}`,
                             codeOutput: '',
                             isTesting: false
                         }">
                        
                        <!-- Header & Accuracy Bar -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-800 font-heading">
                                    🎯 Interactive Student Challenge
                                </span>
                                @if($hasSolved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-600 text-white font-heading">
                                        ✓ Solved
                                    </span>
                                @elseif($mySubmission)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-rose-600 text-white font-heading">
                                        Attempted
                                    </span>
                                @endif
                            </div>

                            <div class="text-[11px] font-semibold text-slate-500 font-heading">
                                <span>👥 {{ $post->submissions->count() }} attempts</span>
                                <span class="mx-1">•</span>
                                <span class="text-emerald-700 font-bold">{{ $post->successRate() }}% Success Rate</span>
                            </div>
                        </div>

                        <!-- 🔘 MCQ INTERACTIVE FORM -->
                        @if($post->isMcq())
                            <form method="POST" action="{{ route('posts.submit', $post) }}" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach($post->options ?? [] as $index => $option)
                                        @php
                                            $isMyPick = $mySubmission && ($mySubmission->answer === $option || (string)$mySubmission->answer === (string)$index);
                                            $isCorrectAnswer = (string)$post->correct_answer === (string)$option || (string)$post->correct_answer === (string)$index;
                                        @endphp
                                        <label class="p-3 rounded-xl border flex items-center gap-3 transition-all cursor-pointer {{ $hasSolved && $isCorrectAnswer ? 'bg-emerald-100/70 border-emerald-400 text-emerald-950 font-bold' : ($mySubmission && $isMyPick && !$mySubmission->is_correct ? 'bg-rose-100/70 border-rose-400 text-rose-950' : 'bg-white border-slate-200 hover:border-amber-400') }}">
                                            <input type="radio" 
                                                   name="answer" 
                                                   value="{{ $option }}" 
                                                   x-model="selectedOption"
                                                   class="text-amber-600 focus:ring-amber-500">
                                            <span class="w-6 h-6 rounded-md bg-amber-100 text-amber-900 text-xs font-black flex items-center justify-center shrink-0 font-heading">
                                                {{ chr(65 + $index) }}
                                            </span>
                                            <span class="text-xs sm:text-sm font-medium flex-1">{{ $option }}</span>

                                            @if($hasSolved && $isCorrectAnswer)
                                                <span class="text-emerald-700 text-xs font-black">✓ Correct</span>
                                            @elseif($mySubmission && $isMyPick && !$mySubmission->is_correct)
                                                <span class="text-rose-600 text-xs font-black">✗ Your choice</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>

                                <div class="pt-2 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-500">
                                        Select your option and click submit to verify immediately.
                                    </span>
                                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors font-heading cursor-pointer shadow-xs">
                                        Verify &amp; Submit Answer
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- ✍️ FILL IN THE BLANKS INTERACTIVE FORM -->
                        @if($post->isFillBlank())
                            <form method="POST" action="{{ route('posts.submit', $post) }}" class="space-y-3">
                                @csrf
                                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                        Enter your answer:
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               name="answer" 
                                               x-model="blankInput" 
                                               placeholder="Type the missing word / technical value..." 
                                               class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-300 focus:border-sky-600 focus:ring-sky-600 rounded-xl py-2.5 px-3">
                                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors font-heading shrink-0 cursor-pointer shadow-xs">
                                            Verify
                                        </button>
                                    </div>
                                    @if($mySubmission)
                                        <p class="text-[11px] {{ $mySubmission->is_correct ? 'text-emerald-700 font-bold' : 'text-rose-600' }}">
                                            Previous submission: "{{ $mySubmission->answer }}" &bull; {{ $mySubmission->feedback }}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        @endif

                        <!-- 💻 CODING CHALLENGE INTERACTIVE FORM -->
                        @if($post->isCoding())
                            <form method="POST" action="{{ route('posts.submit', $post) }}" class="space-y-3">
                                @csrf
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 font-heading">
                                        <span>💻 Solution Editor ({{ strtoupper($post->programming_language ?? 'Code') }})</span>
                                        @if($post->correct_answer)
                                            <span class="text-purple-700 font-mono text-[11px]">Expected: {{ Str::limit($post->correct_answer, 30) }}</span>
                                        @endif
                                    </div>

                                    <textarea name="answer" 
                                              rows="5" 
                                              x-model="codeInput" 
                                              class="w-full font-mono text-xs bg-slate-900 text-emerald-400 p-4 rounded-xl border border-slate-800 focus:ring-purple-500 focus:border-purple-500" 
                                              placeholder="// Write your solution here..."></textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase text-slate-600 font-heading mb-1">
                                            Console Output / Return Value
                                        </label>
                                        <input type="text" 
                                               name="code_output" 
                                               x-model="codeOutput" 
                                               placeholder="e.g. 42 or output result" 
                                               class="w-full font-mono text-xs bg-white border-slate-300 rounded-xl py-2 px-3">
                                    </div>
                                    <div class="flex items-end justify-end">
                                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors font-heading cursor-pointer shadow-xs">
                                            Run Test &amp; Verify Code
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                        <!-- Verification Feedback Report -->
                        @if($mySubmission)
                            <div class="p-3.5 rounded-xl border text-xs sm:text-sm font-medium flex items-center justify-between {{ $mySubmission->is_correct ? 'bg-emerald-100/80 border-emerald-300 text-emerald-900' : 'bg-rose-100/80 border-rose-300 text-rose-900' }}">
                                <div class="flex items-center gap-2">
                                    <span>{{ $mySubmission->is_correct ? '🎉' : '⚠️' }}</span>
                                    <span>{{ $mySubmission->feedback }}</span>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider font-heading">
                                    {{ $mySubmission->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        @endif

                        <!-- Author Solution Explanation (Revealed after solving or attempting) -->
                        @if($post->explanation && ($hasSolved || (auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $post->user_id))))
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs sm:text-sm space-y-1">
                                <strong class="font-bold uppercase tracking-wider text-[11px] font-heading flex items-center gap-1.5 text-amber-950">
                                    <span>📖</span> Author's Solution Explanation:
                                </strong>
                                <p class="leading-relaxed">{{ $post->explanation }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- ============================================== -->
                <!-- THREADED REPLIES & DISCUSSIONS                 -->
                <!-- ============================================== -->
                <div class="mt-4 pt-4 border-t border-slate-100 bg-slate-50/70 rounded-2xl p-4 sm:p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 font-heading">
                            💬 Discussions &amp; Answers ({{ $post->comments->count() }})
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
                                            <button type="submit" class="text-[11px] font-bold text-rose-500 hover:text-rose-700 hover:underline font-heading cursor-pointer">
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
                                      placeholder="Write your answer, discussion insight, or code tip... (Markdown supported)" 
                                      required></textarea>
                            <button type="submit" class="px-5 py-3 bg-[#0f2042] hover:bg-[#1e3a8a] text-white text-xs font-bold uppercase tracking-wider rounded-2xl transition-all shadow-xs shrink-0 font-heading cursor-pointer">
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
                    <h3 class="font-extrabold text-[#0f2042] text-lg font-heading">No discussions or challenges found</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                        {{ request('q') ? 'No discussions matching "' . request('q') . '". Try searching for something else.' : 'Be the first to share an engineering insight, ask an MCQ, or post a coding challenge!' }}
                    </p>
                </div>
                <a href="#compose-section" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#0f2042] text-white text-xs font-bold uppercase tracking-wider font-heading hover:bg-[#1e3a8a] transition-all">
                    Start a Discussion / Challenge &rarr;
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
