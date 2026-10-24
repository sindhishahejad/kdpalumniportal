@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans" 
     x-data="{ 
         search: '', 
         editStory: null, 
         matches(story) {
             if (!this.search) return true;
             const q = this.search.toLowerCase();
             return (story.title && story.title.toLowerCase().includes(q)) ||
                    (story.alumni_name && story.alumni_name.toLowerCase().includes(q)) ||
                    (story.department && story.department.toLowerCase().includes(q));
         }
     }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">🌟</span>
                <h1 class="text-2xl font-bold font-heading text-[#0f172a]">Success Stories Management</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                    {{ $stories->count() }} Total
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Publish notable alumni milestones, leadership stories, and institutional spotlights.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('stories.index') }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors font-heading">
                🌐 View Public Feed &rarr;
            </a>
            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors font-heading">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs sm:text-sm rounded-r-xl shadow-2xs mb-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Publish Form -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl shadow-xs border border-slate-200/90 h-max">
            <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                <span class="text-base">📝</span>
                <h3 class="text-base font-bold font-heading text-[#0f172a]">Publish Success Story</h3>
            </div>

            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Headline / Achievement Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Alumnus leads smart grid initiative at Siemens" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alumni Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="alumni_name" required placeholder="e.g. Chirag Patel" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Batch Year <span class="text-red-500">*</span></label>
                        <input type="text" name="batch_year" required placeholder="e.g. 2021" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Story Content / Milestone Details <span class="text-red-500">*</span></label>
                    <textarea name="story" rows="4" required placeholder="Describe career progress, innovations, awards won, or message to junior diploma students..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alumnus Photo (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                        <span class="text-xs font-bold text-slate-800">Feature this story in top spotlight banner</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-xs font-heading">
                    Publish Success Story &rarr;
                </button>
            </form>
        </div>

        <!-- Right: Published Stories Feed -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl shadow-xs border border-slate-200/90">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold font-heading text-[#0f172a]">Published Alumni Spotlights</h3>

                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">🔍</span>
                    <input type="text" x-model="search" placeholder="Filter stories..." class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border-slate-300 bg-slate-50/50 focus:bg-white focus:border-[#1e3a8a] focus:ring-[#1e3a8a] shadow-2xs">
                </div>
            </div>

            <div class="space-y-3.5 max-h-[600px] overflow-y-auto pr-1">
                @forelse($stories as $story)
                    <div x-show="matches({{ json_encode($story) }})" 
                         class="p-4 rounded-xl border border-slate-200/90 hover:border-slate-300 transition-all bg-white shadow-2xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 {{ $story->is_featured ? 'border-l-4 border-l-[#f97316]' : 'border-l-4 border-l-slate-300' }}">
                        
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                @if($story->image_path)
                                    <img src="{{ asset('storage/' . $story->image_path) }}" alt="{{ $story->alumni_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[#1e3a8a] text-white font-bold text-lg flex items-center justify-center font-heading">
                                        {{ substr($story->alumni_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded uppercase">
                                        {{ $story->department }} • Batch {{ $story->batch_year }}
                                    </span>
                                    @if($story->is_featured)
                                        <span class="text-[9px] font-black uppercase tracking-wider text-[#f97316] bg-orange-50 px-2 py-0.5 rounded border border-orange-200 font-heading">
                                            ★ Featured
                                        </span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-sm text-[#0f172a] truncate">{{ $story->title }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">By <span class="font-semibold text-slate-700">{{ $story->alumni_name }}</span></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            <button type="button" 
                                    @click="editStory = {{ json_encode($story) }}" 
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-[#1e3a8a] hover:bg-blue-100 border border-blue-200 transition-colors flex items-center gap-1 cursor-pointer">
                                ✏️ Edit
                            </button>

                            <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Delete this story?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 italic text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        No success stories published yet. Use the form on the left to publish one.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Story Modal -->
    <div x-show="editStory !== null" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div @click.outside="editStory = null" class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all">
            <div class="p-5 bg-gradient-to-r from-[#1e3a8a] to-[#0f172a] text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✏️</span>
                    <h3 class="font-bold text-base font-heading">Edit Success Story</h3>
                </div>
                <button type="button" @click="editStory = null" class="text-white/70 hover:text-white text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/admin/success-stories/' + (editStory ? editStory.id : '')" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Headline / Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" x-model="editStory.title" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alumni Name <span class="text-red-500">*</span></label>
                    <input type="text" name="alumni_name" x-model="editStory.alumni_name" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Batch Year <span class="text-red-500">*</span></label>
                        <input type="text" name="batch_year" x-model="editStory.batch_year" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department" x-model="editStory.department" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 text-slate-700 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Story Content <span class="text-red-500">*</span></label>
                    <textarea name="story" rows="4" x-model="editStory.story" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] shadow-2xs font-sans"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Replace Photo (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 border border-slate-300 rounded-xl p-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1e3a8a] cursor-pointer">
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" :checked="editStory && editStory.is_featured" class="h-4 w-4 text-[#1e3a8a] border-slate-300 rounded focus:ring-[#1e3a8a]">
                        <span class="text-xs font-bold text-slate-800">Feature this story in top spotlight banner</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editStory = null" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1e3a8a] hover:bg-blue-900 text-white shadow-xs transition-colors cursor-pointer font-heading">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
