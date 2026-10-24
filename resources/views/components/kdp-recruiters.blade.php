@props(['recruiters' => collect()])

<section class="py-16 bg-slate-50 font-sans border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 bg-orange-100 text-[#f97316] text-[11px] font-extrabold uppercase tracking-widest rounded-full mb-2 font-heading">
                Training &amp; Placement Cell (TPO) • Industry Linkages
            </span>
            <h3 class="font-heading text-2xl sm:text-3xl text-[#1e3a8a] font-extrabold uppercase tracking-tight">
                Major Corporate Recruiters &amp; Campus Placements
            </h3>
            <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full mt-2"></div>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-2xl mx-auto">
                Graduates from K. D. Polytechnic are hired by India's leading engineering, power generation, automotive, and infrastructure conglomerates.
            </p>
        </div>

        <!-- Metric Badges Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs text-center">
                <span class="text-xl sm:text-2xl font-black text-[#1e3a8a] font-heading block">85%+</span>
                <span class="text-[11px] text-slate-500 font-medium">Placement &amp; D2D Higher Studies</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs text-center">
                <span class="text-xl sm:text-2xl font-black text-emerald-600 font-heading block">₹4.50 LPA</span>
                <span class="text-[11px] text-slate-500 font-medium">Highest Diploma Package</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs text-center">
                <span class="text-xl sm:text-2xl font-black text-[#f97316] font-heading block">45+</span>
                <span class="text-[11px] text-slate-500 font-medium">Annual Corporate Hiring Partners</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs text-center">
                <span class="text-xl sm:text-2xl font-black text-purple-700 font-heading block">100%</span>
                <span class="text-[11px] text-slate-500 font-medium">NATS Apprenticeship Support</span>
            </div>
        </div>

        <!-- Recruiters Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-10">
            @forelse($recruiters as $recruiter)
                <div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-[#1e3a8a] hover:shadow-md transition-all text-center flex flex-col justify-between group">
                    <div class="w-12 h-12 mx-auto rounded-lg bg-blue-50 text-[#1e3a8a] group-hover:bg-[#1e3a8a] group-hover:text-white transition-colors flex items-center justify-center text-lg font-black font-heading mb-3 border border-blue-100">
                        {{ substr($recruiter->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-xs text-slate-900 group-hover:text-[#1e3a8a] line-clamp-1 mb-1">
                            {{ $recruiter->name }}
                        </h4>
                        <span class="text-[10px] text-slate-400 font-semibold block line-clamp-1">
                            {{ $recruiter->sector }}
                        </span>
                    </div>
                    @if($recruiter->package_range)
                        <span class="mt-2 text-[9px] font-black text-emerald-700 bg-emerald-50 py-0.5 px-2 rounded-full font-heading inline-block mx-auto border border-emerald-100">
                            {{ $recruiter->package_range }}
                        </span>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400 text-xs italic">
                    Corporate recruiter profiles are being updated by the placement cell.
                </div>
            @endforelse
        </div>

        <!-- TPO CTA Bar -->
        <div class="text-center flex flex-wrap justify-center items-center gap-4">
            <a href="{{ route('placement.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs uppercase tracking-wider transition-all font-heading shadow-xs">
                <span>🏢</span>
                <span>View Full TPO Wall &amp; Placement Report &rarr;</span>
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-[#1e3a8a] border border-slate-200 font-bold text-xs uppercase tracking-wider transition-all font-heading shadow-2xs">
                <span>📞</span>
                <span>Invite KDP for Campus Hiring</span>
            </a>
        </div>

    </div>
</section>
