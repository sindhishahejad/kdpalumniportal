<section class="py-14 bg-white font-sans border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 bg-orange-100 text-[#f97316] text-[11px] font-extrabold uppercase tracking-widest rounded-full mb-2 font-heading">
                Academic Disciplines
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1e3a8a] tracking-tight uppercase font-heading">
                Engineering Departments
            </h2>
            <div class="h-1 w-20 bg-[#f97316] mx-auto rounded-full mt-2"></div>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-2xl mx-auto">
                Explore the specialized diploma engineering programs offered at K. D. Polytechnic, Patan, approved by AICTE and affiliated to GTU.
            </p>
        </div>

        <!-- Departments Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            
            <!-- 1. Computer Engineering -->
            <div class="group bg-slate-50/80 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-[#1e3a8a] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-[#1e3a8a] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-[#1e3a8a] group-hover:text-white transition-all shadow-xs">
                        💻
                    </div>
                    <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1">DIPLOMA PROGRAM</span>
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#1e3a8a] font-heading leading-tight mb-2">
                        Computer Engineering
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Software development, database architecture, AI fundamentals, networking &amp; GTU curriculum.
                    </p>
                </div>
                <div class="space-y-1.5 pt-3 border-t border-slate-200/60">
                    <a href="{{ route('labs.index') }}#computer" class="block text-center text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors py-1">
                        🔬 View Labs &rarr;
                    </a>
                    <a href="{{ route('alumni.index', ['department' => 'Computer Engineering']) }}" class="block text-center text-[11px] font-bold bg-[#1e3a8a] text-white rounded-lg py-1.5 hover:bg-blue-900 transition-colors shadow-2xs font-heading">
                        🎓 Find Alumni
                    </a>
                </div>
            </div>

            <!-- 2. Electrical Engineering -->
            <div class="group bg-slate-50/80 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-[#1e3a8a] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-xs">
                        ⚡
                    </div>
                    <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1">DIPLOMA PROGRAM</span>
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#1e3a8a] font-heading leading-tight mb-2">
                        Electrical Engineering
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Electric machines, power transmission, high voltage engineering &amp; industrial automation.
                    </p>
                </div>
                <div class="space-y-1.5 pt-3 border-t border-slate-200/60">
                    <a href="{{ route('labs.index') }}#electrical" class="block text-center text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors py-1">
                        🔬 View Labs &rarr;
                    </a>
                    <a href="{{ route('alumni.index', ['department' => 'Electrical Engineering']) }}" class="block text-center text-[11px] font-bold bg-[#1e3a8a] text-white rounded-lg py-1.5 hover:bg-blue-900 transition-colors shadow-2xs font-heading">
                        🎓 Find Alumni
                    </a>
                </div>
            </div>

            <!-- 3. Mechanical Engineering -->
            <div class="group bg-slate-50/80 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-[#1e3a8a] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-[#f97316] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-[#f97316] group-hover:text-white transition-all shadow-xs">
                        ⚙️
                    </div>
                    <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1">DIPLOMA PROGRAM</span>
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#1e3a8a] font-heading leading-tight mb-2">
                        Mechanical Engineering
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Thermodynamics, CAD/CAM drafting, manufacturing workshop &amp; precision instrumentation.
                    </p>
                </div>
                <div class="space-y-1.5 pt-3 border-t border-slate-200/60">
                    <a href="{{ route('labs.index') }}#mechanical" class="block text-center text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors py-1">
                        🔬 View Labs &rarr;
                    </a>
                    <a href="{{ route('alumni.index', ['department' => 'Mechanical Engineering']) }}" class="block text-center text-[11px] font-bold bg-[#1e3a8a] text-white rounded-lg py-1.5 hover:bg-blue-900 transition-colors shadow-2xs font-heading">
                        🎓 Find Alumni
                    </a>
                </div>
            </div>

            <!-- 4. Civil Engineering -->
            <div class="group bg-slate-50/80 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-[#1e3a8a] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-xs">
                        🏗️
                    </div>
                    <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1">DIPLOMA PROGRAM</span>
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#1e3a8a] font-heading leading-tight mb-2">
                        Civil Engineering
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Structural design, total station surveying, concrete tech &amp; environmental hydrology.
                    </p>
                </div>
                <div class="space-y-1.5 pt-3 border-t border-slate-200/60">
                    <a href="{{ route('labs.index') }}#civil" class="block text-center text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors py-1">
                        🔬 View Labs &rarr;
                    </a>
                    <a href="{{ route('alumni.index', ['department' => 'Civil Engineering']) }}" class="block text-center text-[11px] font-bold bg-[#1e3a8a] text-white rounded-lg py-1.5 hover:bg-blue-900 transition-colors shadow-2xs font-heading">
                        🎓 Find Alumni
                    </a>
                </div>
            </div>

            <!-- 5. Science & Humanities -->
            <div class="group bg-slate-50/80 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-[#1e3a8a] hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-xs">
                        📐
                    </div>
                    <span class="text-[10px] font-bold text-[#f97316] uppercase tracking-wider block mb-1">FOUNDATION DEPT</span>
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-[#1e3a8a] font-heading leading-tight mb-2">
                        Science &amp; Humanities
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Applied mathematics, engineering physics, chemistry, communication skills &amp; professional ethics.
                    </p>
                </div>
                <div class="space-y-1.5 pt-3 border-t border-slate-200/60">
                    <a href="{{ route('labs.index') }}#general" class="block text-center text-[11px] font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors py-1">
                        🔬 View Labs &rarr;
                    </a>
                    <a href="{{ route('alumni.index', ['department' => 'General']) }}" class="block text-center text-[11px] font-bold bg-[#1e3a8a] text-white rounded-lg py-1.5 hover:bg-blue-900 transition-colors shadow-2xs font-heading">
                        🎓 Find Alumni
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
