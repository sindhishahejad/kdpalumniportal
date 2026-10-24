@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20">

    <!-- ============================================== -->
    <!-- 1. INSTITUTIONAL HERO HEADER                   -->
    <!-- ============================================== -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        
        <!-- Watermark Backgrounds -->
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
                    ✨ {{ $cycle->title }}
                </span>
            </div>

            <p class="text-orange-300 text-xs sm:text-sm font-bold uppercase tracking-wider font-heading mb-1">
                કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ
            </p>
            <h1 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-wide text-white">
                Nominate a Distinguished Alumnus
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 max-w-2xl mt-1.5 leading-relaxed font-sans">
                Recognize the exceptional technical grit, industrial milestones, and community leadership of a fellow KDP graduate.
            </p>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MAIN WORKSPACE                              -->
    <!-- ============================================== -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 space-y-6">

        <!-- Top Breadcrumbs Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-3 flex items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 font-heading uppercase tracking-wider">
                <a href="{{ route('awards.index') }}" class="hover:text-[#1e3a8a] transition-colors">Awards Hub</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-bold">Nomination Portal</span>
            </nav>

            <a href="{{ route('awards.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider font-heading">
                &larr; Back to Hub
            </a>
        </div>

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border-l-4 border-rose-500 p-4 text-rose-800 shadow-sm space-y-1">
                <div class="font-bold text-xs uppercase font-heading">Please correct the following errors:</div>
                <ul class="list-disc list-inside text-xs space-y-0.5 font-sans">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Column -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <form action="{{ route('awards.nominate.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- 1. Award Category -->
                    <div class="space-y-1.5">
                        <label for="award_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                            Award Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="award_category_id" id="award_category_id" required class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('award_category_id', $preselectedCategory) == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Registered Alumnus Quick-Select -->
                    @if($alumniList->count() > 0)
                        <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-200/80 space-y-2">
                            <label for="alumni_quick_select" class="block text-xs font-bold uppercase tracking-wider text-[#1e3a8a] font-heading">
                                🔗 Quick-Select Registered KDP Alumnus (Optional)
                            </label>
                            <select id="alumni_quick_select" onchange="autofillAlumnus(this)" class="w-full rounded-xl border border-blue-200 text-xs sm:text-sm py-2 px-3 bg-white focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                                <option value="">-- Choose from registered directory or fill manually below --</option>
                                @foreach($alumniList as $alm)
                                    <option value="{{ $alm->id }}" 
                                            data-name="{{ $alm->name }}" 
                                            data-department="{{ $alm->department }}" 
                                            data-gradyear="{{ $alm->graduation_year }}" 
                                            data-company="{{ $alm->company }}" 
                                            data-designation="{{ $alm->designation }}">
                                        {{ $alm->name }} ({{ $alm->department }} - Class of {{ $alm->graduation_year ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="nominee_user_id" id="nominee_user_id" value="{{ old('nominee_user_id') }}">
                            <p class="text-[11px] text-blue-700 font-sans">Selecting an alumnus links this nomination directly to their portal profile.</p>
                        </div>
                    @endif

                    <!-- 3. Nominee Personal & Academic Information -->
                    <div class="space-y-4 pt-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-[#0f172a] pb-2 border-b border-slate-100 font-heading">
                            Nominee Information
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="nominee_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nominee_name" id="nominee_name" value="{{ old('nominee_name') }}" required placeholder="e.g. Er. Hardik S. Patel" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            </div>

                            <div class="space-y-1">
                                <label for="nominee_department" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Engineering Branch <span class="text-rose-500">*</span>
                                </label>
                                <select name="nominee_department" id="nominee_department" required class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                                    <option value="">-- Select Branch --</option>
                                    @if(isset($departments))
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept }}" {{ old('nominee_department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                    @else
                                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        <option value="Electrical Engineering">Electrical Engineering</option>
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                        <option value="Chemical Engineering">Chemical Engineering</option>
                                    @endif
                                    <option value="Other" {{ old('nominee_department') === 'Other' ? 'selected' : '' }}>Other / Allied</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label for="nominee_batch_year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Graduation Year <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="nominee_batch_year" id="nominee_batch_year" value="{{ old('nominee_batch_year', 2015) }}" min="1970" max="{{ date('Y') + 1 }}" required class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-mono">
                            </div>

                            <div class="space-y-1">
                                <label for="nominee_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Email Address (Optional)
                                </label>
                                <input type="email" name="nominee_email" id="nominee_email" value="{{ old('nominee_email') }}" placeholder="alumnus@example.com" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="current_organization" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Current Organization / Enterprise
                                </label>
                                <input type="text" name="current_organization" id="current_organization" value="{{ old('current_organization') }}" placeholder="e.g. L&amp;T, Adani, or Self Enterprise" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            </div>

                            <div class="space-y-1">
                                <label for="designation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Current Designation / Role
                                </label>
                                <input type="text" name="designation" id="designation" value="{{ old('designation') }}" placeholder="e.g. Plant Head, Chief Engineer, Founder" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Achievements & Impact Statement -->
                    <div class="space-y-4 pt-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-[#0f172a] pb-2 border-b border-slate-100 font-heading">
                            Accomplishments &amp; Impact
                        </h3>

                        <div class="space-y-1">
                            <label for="achievement_summary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                Key Engineering &amp; Professional Achievements <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="achievement_summary" id="achievement_summary" rows="4" required minlength="20" placeholder="Detail the nominee's career highlights, patents, industrial breakthroughs, plant expansions, or significant projects..." class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-sans leading-relaxed">{{ old('achievement_summary') }}</textarea>
                            <p class="text-[11px] text-slate-400 font-sans">Provide specific technical accomplishments and scope of responsibility (minimum 20 characters).</p>
                        </div>

                        <div class="space-y-1">
                            <label for="impact_statement" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                Impact on Industry, Society, or KDP Mentorship
                            </label>
                            <textarea name="impact_statement" id="impact_statement" rows="3" placeholder="How has their work benefited Gujarat's industrial ecosystem, public welfare, or current polytechnic students?" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs font-sans leading-relaxed">{{ old('impact_statement') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="portfolio_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Portfolio / LinkedIn / News URL
                                </label>
                                <input type="url" name="portfolio_url" id="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://linkedin.com/in/username" class="w-full rounded-xl border border-slate-300 text-xs sm:text-sm py-2.5 px-3 focus:ring-1 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-xs">
                            </div>

                            <div class="space-y-1">
                                <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 font-heading">
                                    Nominee Photograph (JPG/PNG, Max 3MB)
                                </label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 cursor-pointer border border-slate-300 rounded-xl p-1">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('awards.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider font-heading">
                            &larr; Cancel &amp; Return
                        </a>

                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all font-heading cursor-pointer">
                            <span>🏆</span> Submit Official Nomination
                        </button>
                    </div>
                </form>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#0f172a] flex items-center gap-2 font-heading">
                        <span>📜</span> Nomination Standards
                    </h3>
                    <ul class="text-xs text-slate-600 space-y-3 leading-relaxed font-sans">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Authenticity:</strong> All nominees must be bonafide diploma graduates of K.D. Polytechnic Patan.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Verification:</strong> The KDP Awards Committee reviews achievement claims and verifies with company or public registries.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Self-Nominations:</strong> You may nominate yourself or peers. Honest citations and portfolios are encouraged.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Fair Voting:</strong> Once approved as a finalist, the nominee will be published for voting during Stage 2.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-[#c2410c] to-[#ea580c] rounded-3xl p-6 text-white shadow-md space-y-3">
                    <div class="text-2xl">🏛️</div>
                    <h4 class="font-black text-xs uppercase tracking-wider font-heading">Hall of Fame Induction</h4>
                    <p class="text-xs leading-relaxed font-sans opacity-95">
                        Winners announced at the Annual Alumni Convocation receive the prestigious KDP Medallion of Honor and permanent enshrinement in the Virtual Hall of Fame.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function autofillAlumnus(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption.value) return;

    document.getElementById('nominee_user_id').value = selectedOption.value;
    document.getElementById('nominee_name').value = selectedOption.dataset.name || '';
    if (selectedOption.dataset.department) {
        document.getElementById('nominee_department').value = selectedOption.dataset.department;
    }
    if (selectedOption.dataset.gradyear) {
        document.getElementById('nominee_batch_year').value = selectedOption.dataset.gradyear;
    }
    if (selectedOption.dataset.company) {
        document.getElementById('current_organization').value = selectedOption.dataset.company;
    }
    if (selectedOption.dataset.designation) {
        document.getElementById('designation').value = selectedOption.dataset.designation;
    }
}
</script>
@endsection
