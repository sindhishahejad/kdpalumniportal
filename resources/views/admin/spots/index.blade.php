@extends('layouts.app')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    editMode: false,
    spotId: null,
    formAction: '{{ route('admin.spots.store') }}',
    name: '',
    category: 'academic',
    icon: 'building',
    description: '',
    x_percent: 50,
    y_percent: 50,
    then_year: '1985',
    then_title: 'Archival Heritage View',
    then_photo_url: '',
    now_year: '2026',
    now_title: 'Modern Upgraded View',
    now_photo_url: '',
    fun_fact: '',
    order_num: 0,

    openCreate() {
        this.editMode = false;
        this.spotId = null;
        this.formAction = '{{ route('admin.spots.store') }}';
        this.name = '';
        this.category = 'academic';
        this.icon = 'building';
        this.description = '';
        this.x_percent = 50;
        this.y_percent = 50;
        this.then_year = '1985';
        this.then_title = 'Archival Heritage View';
        this.then_photo_url = '';
        this.now_year = '2026';
        this.now_title = 'Modern Upgraded View';
        this.now_photo_url = '';
        this.fun_fact = '';
        this.order_num = {{ $spots->count() + 1 }};
        this.modalOpen = true;
    },

    openEdit(spot) {
        this.editMode = true;
        this.spotId = spot.id;
        this.formAction = '/admin/spots/' + spot.id;
        this.name = spot.name;
        this.category = spot.category;
        this.icon = spot.icon;
        this.description = spot.description;
        this.x_percent = spot.x_percent;
        this.y_percent = spot.y_percent;
        this.then_year = spot.then_year;
        this.then_title = spot.then_title;
        this.then_photo_url = spot.then_photo_path;
        this.now_year = spot.now_year;
        this.now_title = spot.now_title;
        this.now_photo_url = spot.now_photo_path;
        this.fun_fact = spot.fun_fact || '';
        this.order_num = spot.order_num;
        this.modalOpen = true;
    }
}" class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Dynamic Campus Vault</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800">Admin Control</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-[#1C3661] mt-0.5">
                Landmark Spots &amp; Time-Machine Management
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Add, reorder, or update historical campus landmarks on the interactive blueprint map and Time-Machine slider.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('gallery.index') }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-[#1c3272] bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1.5">
                <span>👁️</span> View Live Time-Machine
            </a>
            <button type="button" @click="openCreate()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#1c3272] hover:bg-blue-900 shadow-sm transition-colors flex items-center gap-1.5">
                <span>+</span> Add New Landmark Spot
            </button>
        </div>
    </div>

    <!-- Flash Status Messages -->
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-sm flex items-center justify-between">
            <span class="font-medium flex items-center gap-2">
                <span>✓</span> {{ session('status') }}
            </span>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-xs rounded-r-xl shadow-sm">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- LANDMARK SPOTS TABLE / CARDS -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🏛️</span>
                <h3 class="font-bold text-slate-900 text-base">Active Campus Landmark Spots</h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-600">
                    {{ $spots->count() }} Landmarks
                </span>
            </div>
            <span class="text-xs text-slate-400">Rendered dynamically on /gallery</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($spots as $spot)
                <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between space-y-4 shadow-2xs hover:border-slate-300 transition-all">
                    
                    <!-- Top Photos Comparison Thumbnail -->
                    <div class="grid grid-cols-2 gap-2 aspect-[16/9] rounded-xl overflow-hidden bg-slate-200 relative">
                        <div class="relative overflow-hidden">
                            <img src="{{ $spot->then_photo_path }}" class="w-full h-full object-cover filter contrast-125 sepia-[0.3]" alt="Then">
                            <span class="absolute bottom-1 left-1 bg-amber-950/80 text-amber-200 px-2 py-0.2 rounded text-[9px] font-black">
                                THEN '{{ substr($spot->then_year, -2) }}
                            </span>
                        </div>
                        <div class="relative overflow-hidden">
                            <img src="{{ $spot->now_photo_path }}" class="w-full h-full object-cover" alt="Now">
                            <span class="absolute bottom-1 right-1 bg-slate-900/80 text-emerald-400 px-2 py-0.2 rounded text-[9px] font-black">
                                NOW '{{ substr($spot->now_year, -2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Meta & Info -->
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-widest bg-orange-100 text-orange-800 rounded-full">
                                {{ $spot->category }}
                            </span>
                            <span class="text-[11px] font-mono text-slate-400">
                                Pin: {{ $spot->x_percent }}%, {{ $spot->y_percent }}%
                            </span>
                        </div>

                        <h4 class="font-bold text-slate-900 text-sm leading-snug">
                            {{ $spot->name }}
                        </h4>
                        
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $spot->description }}
                        </p>

                        <div class="pt-2 flex items-center justify-between text-[11px] text-slate-400">
                            <span>📖 {{ $spot->stories_count }} alumni memories</span>
                            <span class="font-semibold text-slate-600">Order #{{ $spot->order_num }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-200">
                        <button type="button" 
                                @click="openEdit({{ $spot->toJson() }})" 
                                class="flex-1 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-colors shadow-2xs text-center">
                            ✏️ Edit Spot
                        </button>

                        <form action="{{ route('admin.spots.destroy', $spot->id) }}" method="POST" onsubmit="return confirm('Delete landmark spot {{ $spot->name }}? This will also remove pinned stories.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors shadow-2xs">
                                🗑️
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-slate-400 italic bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    No landmark spots found. Click "Add New Landmark Spot" above to create one.
                </div>
            @endforelse
        </div>
    </div>

    <!-- RECENT SUBMITTED ALUMNI MEMORIES MODERATION -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">💬</span>
                <h3 class="font-bold text-slate-900 text-base">Alumni Throwback Memories Moderation</h3>
            </div>
            <span class="text-xs text-slate-400">Submitted through the public Time-Machine</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($recentStories as $story)
                <div class="py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900 text-xs">{{ $story->author_name }}</span>
                            <span class="text-[11px] text-slate-400">{{ $story->department }} • Class of {{ $story->batch_year }}</span>
                            <span class="px-2 py-0.2 rounded-full text-[9px] font-extrabold bg-blue-50 text-blue-700">
                                Pin: {{ $story->spot->name ?? 'Unknown Spot' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-700 italic max-w-3xl">
                            "{{ $story->story }}"
                        </p>
                        <span class="text-[10px] text-slate-400">
                            Posted {{ $story->created_at->diffForHumans() }} • ❤️ {{ $story->likes_count }} likes
                        </span>
                    </div>

                    <form action="{{ route('admin.spots.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Remove this throwback memory?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors shrink-0">
                            Remove Memory
                        </button>
                    </form>
                </div>
            @empty
                <div class="py-8 text-center text-xs text-slate-400 italic">
                    No throwback stories posted by alumni yet.
                </div>
            @endforelse
        </div>

        @if($recentStories->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $recentStories->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE / EDIT LANDMARK SPOT MODAL -->
    <div x-show="modalOpen" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-xs p-4 overflow-y-auto">
        <div @click.away="modalOpen = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-200 my-8">
            
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl" x-text="editMode ? '✏️' : '🏛️'"></span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" x-text="editMode ? 'Edit Landmark Spot' : 'Create New Landmark Spot'"></h3>
                        <p class="text-xs text-slate-500">Configure historical vs modern photos and map placement</p>
                    </div>
                </div>
                <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="formAction" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <!-- Spot Name & Category -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2 space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Landmark Name *</label>
                        <input type="text" name="name" x-model="name" required placeholder="e.g. LRUC Building & Central Library" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Category *</label>
                        <select name="category" x-model="category" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                            <option value="academic">Academic Wing</option>
                            <option value="workshop">Workshop / Shed</option>
                            <option value="landmark">Heritage Landmark</option>
                            <option value="recreation">Recreation / Canteen</option>
                            <option value="hostel">Residential Hostel</option>
                            <option value="admin">Administration Block</option>
                        </select>
                    </div>
                </div>

                <!-- Icon & Order -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Map Icon *</label>
                        <select name="icon" x-model="icon" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                            <option value="building">🏛️ Building / Administration</option>
                            <option value="wrench">🛠️ Workshop / Machinery</option>
                            <option value="book">📚 Library / Reading Room</option>
                            <option value="coffee">☕ Canteen / Neem Tree</option>
                            <option value="bolt">⚡ Electrical / Substation</option>
                            <option value="trophy">🏆 Sports Turf / Cricket</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Display Order #</label>
                        <input type="number" name="order_num" x-model="order_num" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                    </div>
                </div>

                <!-- MAP COORDINATES (X% and Y%) -->
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Campus Blueprint Map Coordinates (0 - 100%)</span>
                        <span class="text-[10px] text-slate-400">Position of the glowing pin on the map</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500">X Position (% from left)</label>
                            <input type="number" step="0.5" min="0" max="100" name="x_percent" x-model="x_percent" required class="w-full rounded-xl border-slate-300 text-xs font-mono">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500">Y Position (% from top)</label>
                            <input type="number" step="0.5" min="0" max="100" name="y_percent" x-model="y_percent" required class="w-full rounded-xl border-slate-300 text-xs font-mono">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Historical &amp; Architectural Description *</label>
                    <textarea name="description" x-model="description" rows="2" required placeholder="Significance of this spot in KDP's technical education legacy..." class="w-full rounded-xl border-slate-300 text-xs sm:text-sm"></textarea>
                </div>

                <!-- ARCHIVAL "THEN" ERA -->
                <div class="p-3.5 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-2.5">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-amber-900 block">
                        🏛️ Archival Heritage Era ("Then" Side)
                    </span>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="text-[10px] font-bold text-amber-800">Historic Year *</label>
                            <input type="text" name="then_year" x-model="then_year" required placeholder="e.g. 1985" class="w-full rounded-xl border-amber-300 text-xs">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-bold text-amber-800">Historical Caption *</label>
                            <input type="text" name="then_title" x-model="then_title" required placeholder="e.g. Original Stone Facade (1985)" class="w-full rounded-xl border-amber-300 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div>
                            <label class="text-[10px] font-bold text-amber-800">Upload "Then" Photo</label>
                            <input type="file" name="then_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-amber-100 file:text-amber-900">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-amber-800">OR Image URL</label>
                            <input type="url" name="then_photo_url" x-model="then_photo_url" placeholder="https://..." class="w-full rounded-xl border-amber-300 text-xs">
                        </div>
                    </div>
                </div>

                <!-- MODERN "NOW" ERA -->
                <div class="p-3.5 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 space-y-2.5">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-900 block">
                        🌱 Modern Upgraded Era ("Now" Side)
                    </span>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="text-[10px] font-bold text-emerald-800">Modern Year *</label>
                            <input type="text" name="now_year" x-model="now_year" required placeholder="e.g. 2026" class="w-full rounded-xl border-emerald-300 text-xs">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-bold text-emerald-800">Modern Caption *</label>
                            <input type="text" name="now_title" x-model="now_title" required placeholder="e.g. Solar-Powered Smart Campus Center (2026)" class="w-full rounded-xl border-emerald-300 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div>
                            <label class="text-[10px] font-bold text-emerald-800">Upload "Now" Photo</label>
                            <input type="file" name="now_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-emerald-100 file:text-emerald-900">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-emerald-800">OR Image URL</label>
                            <input type="url" name="now_photo_url" x-model="now_photo_url" placeholder="https://..." class="w-full rounded-xl border-emerald-300 text-xs">
                        </div>
                    </div>
                </div>

                <!-- Fun Fact / Campus Lore -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Campus Lore / Fun Fact (Optional)</label>
                    <input type="text" name="fun_fact" x-model="fun_fact" placeholder="e.g. Students in the 1990s lined up at 7:30 AM for the single copy of Khurmi..." class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                </div>

                <!-- Form Submit Buttons -->
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1c3272] hover:bg-blue-900 text-white shadow-sm" x-text="editMode ? 'Update Landmark Spot' : 'Create Landmark Spot'">
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
