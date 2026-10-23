@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            <a href="{{ route('tools.index') }}" class="hover:text-indigo-600 transition-colors">Tool Bank</a>
            <span>&rarr;</span>
            <span class="text-slate-800">Donate Equipment</span>
        </div>

        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-10 text-white shadow-xl border border-blue-500/20">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider border border-amber-400/30">
                    ✨ Pay-It-Forward Student Initiative
                </span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Donate Books or Engineering Tools
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Help a junior technician save money on expensive engineering drafters, calculators, textbooks, or Arduino boards.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 shadow-sm space-y-1">
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
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div class="space-y-1">
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Item Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="e.g. Rotring Mini Drafter + Drawing Board Set" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                    </div>

                    <!-- Category & Branch -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="item_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Equipment Category <span class="text-rose-500">*</span>
                            </label>
                            <select name="item_type" id="item_type" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="">-- Select Category --</option>
                                <option value="drawing_tools" {{ old('item_type') === 'drawing_tools' ? 'selected' : '' }}>📐 Mini Drafters &amp; Graphics Tools</option>
                                <option value="calculators_electronics" {{ old('item_type') === 'calculators_electronics' ? 'selected' : '' }}>🖩 Scientific Calculators</option>
                                <option value="textbook" {{ old('item_type') === 'textbook' ? 'selected' : '' }}>📚 GTU / Diploma Textbooks</option>
                                <option value="lab_hardware" {{ old('item_type') === 'lab_hardware' ? 'selected' : '' }}>⚡ Arduino / Microcontroller / IoT Kit</option>
                                <option value="workshop_safety" {{ old('item_type') === 'workshop_safety' ? 'selected' : '' }}>🦺 Workshop Safety Gear / Apron</option>
                                <option value="other" {{ old('item_type') === 'other' ? 'selected' : '' }}>📦 Other Engineering Equipment</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="department" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Engineering Branch <span class="text-rose-500">*</span>
                            </label>
                            <select name="department" id="department" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="">-- Select Branch --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Semester & Condition -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="semester_suitability" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Relevant Semester <span class="text-rose-500">*</span>
                            </label>
                            <select name="semester_suitability" id="semester_suitability" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="All Semesters" {{ old('semester_suitability') === 'All Semesters' ? 'selected' : '' }}>All Semesters / General</option>
                                <option value="Sem 1-2" {{ old('semester_suitability') === 'Sem 1-2' ? 'selected' : '' }}>Sem 1-2 (First Year Diploma)</option>
                                <option value="Sem 3-4" {{ old('semester_suitability') === 'Sem 3-4' ? 'selected' : '' }}>Sem 3-4 (Second Year Diploma)</option>
                                <option value="Sem 5-6" {{ old('semester_suitability') === 'Sem 5-6' ? 'selected' : '' }}>Sem 5-6 (Final Year Diploma)</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="condition" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Condition <span class="text-rose-500">*</span>
                            </label>
                            <select name="condition" id="condition" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="like_new" {{ old('condition') === 'like_new' ? 'selected' : '' }}>Like New (Flawless / Barely Used)</option>
                                <option value="gently_used" {{ old('condition', 'gently_used') === 'gently_used' ? 'selected' : '' }}>Gently Used (Good Functional Condition)</option>
                                <option value="brand_new" {{ old('condition') === 'brand_new' ? 'selected' : '' }}>Brand New (Never Opened)</option>
                                <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair (Functional with signs of use)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Estimated Value Saved -->
                    <div class="space-y-1">
                        <label for="estimated_value" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Estimated Market Value Saved for Junior (₹ INR) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="estimated_value" id="estimated_value" value="{{ old('estimated_value', 1200) }}" min="50" max="100000" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                        <p class="text-[11px] text-slate-500">How much money will this save a student from purchasing it new in the market?</p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Item Description &amp; What is Included <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required minlength="20" placeholder="List components included, brand name, model, condition of scales/buttons, any accessories..." class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('description') }}</textarea>
                    </div>

                    <!-- Pickup Method & Location -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="pickup_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Handover / Pickup Method <span class="text-rose-500">*</span>
                            </label>
                            <select name="pickup_method" id="pickup_method" required class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="campus_locker" {{ old('pickup_method') === 'campus_locker' ? 'selected' : '' }}>🏫 Campus Library / Workshop Locker</option>
                                <option value="handover_patan" {{ old('pickup_method') === 'handover_patan' ? 'selected' : '' }}>🤝 In-Person Handover in Patan City</option>
                                <option value="courier" {{ old('pickup_method') === 'courier' ? 'selected' : '' }}>📦 Courier Delivery to Student</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="pickup_location" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Specific Pickup Location Notes
                            </label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" placeholder="e.g. Mechanical Workshop Office or Patan City Gate" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">
                        </div>
                    </div>

                    <!-- Donor Encouragement Note -->
                    <div class="space-y-1">
                        <label for="donor_note" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Encouragement Note / Advice to Junior (Optional)
                        </label>
                        <textarea name="donor_note" id="donor_note" rows="2" placeholder="e.g. This drafter served me well in getting AA grade in Graphics. Best of luck for your diploma studies!" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('donor_note') }}</textarea>
                    </div>

                    <!-- Photo Upload -->
                    <div class="space-y-1">
                        <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Photograph of Equipment (JPG/PNG, Max 3MB)
                        </label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('tools.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider">
                            &larr; Return to Catalog
                        </a>

                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-700 to-blue-800 hover:from-indigo-800 hover:to-blue-900 text-white font-bold text-sm uppercase tracking-wider shadow-lg shadow-indigo-600/20 transition-all">
                            <span>✨</span> List Item for Juniors
                        </button>
                    </div>
                </form>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <span>💡</span> How It Works
                    </h3>
                    <ul class="text-xs text-slate-600 space-y-3 leading-relaxed">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">1.</span>
                            <span><strong>List Your Tool:</strong> Post any usable textbook, drafter, calculator, or kit with condition details.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">2.</span>
                            <span><strong>Junior Requests:</strong> KDP students browse and submit a claim explaining their semester need.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">3.</span>
                            <span><strong>Approve &amp; Handover:</strong> Review requests, accept a student, and drop the item at the campus locker or arrange meetup.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">4.</span>
                            <span><strong>Pay-It-Forward:</strong> The student leaves a gratitude note and agrees to pass it forward upon graduation!</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-indigo-900 to-blue-950 rounded-3xl p-6 text-white shadow-md space-y-2">
                    <div class="text-2xl">🌱</div>
                    <h4 class="font-bold text-sm">Every Donation Counts</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        A single scientific calculator or mini drafter saved can ease financial strain for a junior polytechnic student from rural North Gujarat.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
