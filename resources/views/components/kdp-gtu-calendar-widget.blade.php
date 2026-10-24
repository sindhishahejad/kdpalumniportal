@props(['schedules' => collect()])

<div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 mb-8 font-sans">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">🗓️</span>
                <h3 class="font-heading font-black text-lg text-[#0f172a]">
                    GTU Academic Calendar &amp; Circulars
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                    Live GTU Timeline
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Stay informed on diploma exam schedules, term commencement dates, and re-checking notices.</p>
        </div>

        <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-[#1e3a8a] hover:text-[#f97316] transition-colors flex items-center gap-1 font-heading whitespace-nowrap">
            <span>View Full Academic Timeline</span>
            <span>&rarr;</span>
        </a>
    </div>

    <!-- Quick Feed Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        @forelse($schedules->take(3) as $item)
            @php $badge = $item->category_badge; @endphp
            <div class="p-4 rounded-xl border border-slate-200 hover:border-slate-300 transition-all bg-slate-50/50 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $badge['color'] }} font-heading">
                            {{ $badge['label'] }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-500 font-heading">
                            {{ $item->start_date->format('d M, Y') }}
                        </span>
                    </div>

                    <h4 class="font-heading font-bold text-xs text-slate-900 leading-snug line-clamp-2 mb-1">
                        {{ $item->title }}
                    </h4>

                    <span class="text-[10px] text-slate-400 font-semibold block">
                        {{ $item->semester_scope }}
                    </span>
                </div>

                @if($item->attachment_url)
                    <div class="mt-3 pt-2 border-t border-slate-200/60">
                        <a href="{{ $item->attachment_url }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-bold text-[#1e3a8a] hover:underline flex items-center gap-1">
                            <span>📄 View Circular</span>
                            <span>&nearr;</span>
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-4 text-center text-xs text-slate-400 italic">
                No upcoming GTU schedules published.
            </div>
        @endforelse
    </div>
</div>
