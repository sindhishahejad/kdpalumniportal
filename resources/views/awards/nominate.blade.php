@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs & Header -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('awards.index') }}" class="hover:text-indigo-600 transition-colors">Awards Hub</a>
            <span>&rarr;</span>
            <span class="text-slate-800">Nomination Portal</span>
        </div>

        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-10 text-white shadow-xl border border-amber-500/20">
            <div class="max-w-2xl space-y-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider border border-amber-400/30">
                    ✨ {{ $cycle->title }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Nominate a Distinguished Alumnus
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Recognize the exceptional technical grit, industrial milestones, and community leadership of a fellow KDP graduate.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 shadow-sm space-y-1">
                <div class="font-bold text-sm">Please correct the following errors:</div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
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
                        <label for="award_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Award Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="award_category_id" id="award_category_id" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
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
                        <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-2">
                            <label for="alumni_quick_select" class="block text-xs font-bold uppercase tracking-wider text-indigo-900">
                                🔗 Quick-Select Registered KDP Alumnus (Optional)
                            </label>
                            <select id="alumni_quick_select" onchange="autofillAlumnus(this)" class="w-full rounded-xl border-indigo-200 text-xs sm:text-sm bg-white focus:border-indigo-600 focus:ring-indigo-600">
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
                            <p class="text-[11px] text-indigo-700">Selecting an alumnus links this nomination directly to their portal profile.</p>
                        </div>
                    @endif

                    <!-- 3. Nominee Personal & Academic Information -->
                    <div class="space-y-4 pt-2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100">
                            Nominee Information
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="nominee_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nominee_name" id="nominee_name" value="{{ old('nominee_name') }}" required placeholder="e.g. Er. Hardik S. Patel" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>

                            <div class="space-y-1">
                                <label for="nominee_department" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Engineering Branch <span class="text-rose-500">*</span>
                                </label>
                                <select name="nominee_department" id="nominee_department" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
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
                                <label for="nominee_batch_year" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Graduation Year <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="nominee_batch_year" id="nominee_batch_year" value="{{ old('nominee_batch_year', 2015) }}" min="1970" max="{{ date('Y') + 1 }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>

                            <div class="space-y-1">
                                <label for="nominee_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Email Address (Optional)
                                </label>
                                <input type="email" name="nominee_email" id="nominee_email" value="{{ old('nominee_email') }}" placeholder="alumnus@example.com" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="current_organization" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Current Organization / Enterprise
                                </label>
                                <input type="text" name="current_organization" id="current_organization" value="{{ old('current_organization') }}" placeholder="e.g. L&amp;T, Adani, or Self Enterprise" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>

                            <div class="space-y-1">
                                <label for="designation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Current Designation / Role
                                </label>
                                <input type="text" name="designation" id="designation" value="{{ old('designation') }}" placeholder="e.g. Plant Head, Chief Engineer, Founder" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Achievements & Impact Statement -->
                    <div class="space-y-4 pt-2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-100">
                            Accomplishments &amp; Impact
                        </h3>

                        <div class="space-y-1">
                            <label for="achievement_summary" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Key Engineering &amp; Professional Achievements <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="achievement_summary" id="achievement_summary" rows="4" required minlength="20" placeholder="Detail the nominee's career highlights, patents, industrial breakthroughs, plant expansions, or significant projects..." class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('achievement_summary') }}</textarea>
                            <p class="text-[11px] text-slate-500">Provide specific technical accomplishments and scope of responsibility (minimum 20 characters).</p>
                        </div>

                        <div class="space-y-1">
                            <label for="impact_statement" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Impact on Industry, Society, or KDP Mentorship
                            </label>
                            <textarea name="impact_statement" id="impact_statement" rows="3" placeholder="How has their work benefited Gujarat's industrial ecosystem, public welfare, or current polytechnic students?" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('impact_statement') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="portfolio_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Portfolio / LinkedIn / News URL
                                </label>
                                <input type="url" name="portfolio_url" id="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://linkedin.com/in/username" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                            </div>

                            <div class="space-y-1">
                                <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Nominee Photograph (JPG/PNG, Max 3MB)
                                </label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('awards.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider">
                            &larr; Cancel &amp; Return
                        </a>

                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-700 to-blue-800 hover:from-indigo-800 hover:to-blue-900 text-white font-bold text-sm uppercase tracking-wider shadow-lg shadow-indigo-600/20 transition-all">
                            <span>🏆</span> Submit Official Nomination
                        </button>
                    </div>
                </form>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <span>📜</span> Nomination Standards
                    </h3>
                    <ul class="text-xs text-slate-600 space-y-3 leading-relaxed">
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

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl p-6 text-slate-950 shadow-md space-y-3">
                    <div class="text-2xl">🏛️</div>
                    <h4 class="font-black text-sm uppercase tracking-wider">Hall of Fame Induction</h4>
                    <p class="text-xs leading-relaxed font-medium">
                        Winners announced at the Annual Alumni Convocation receive the prestigious KDP Medallion of Honor and permanent inclusion in the Virtual Hall of Fame.
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
