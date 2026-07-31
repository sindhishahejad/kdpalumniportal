@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-16 pt-8" x-data="{ openUploadModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Action Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6 mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#1C3661]">Resource Vault (MOOCs & Exams)</h1>
                <p class="text-sm text-gray-500 mt-1">Access and share study notes, exam papers, MOOCs, and learning materials.</p>
            </div>
            <button @click="openUploadModal = true" class="bg-[#8b0000] hover:bg-[#6b0d0d] text-white font-bold py-2.5 px-6 rounded-sm shadow-md transition-colors text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Share a Resource
            </button>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-sm mb-6">
                <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Category Filters -->
        <div class="flex gap-2 overflow-x-auto pb-4 mb-6">
            <a href="{{ route('resources.index') }}" class="px-4 py-2 rounded-sm font-semibold text-xs uppercase tracking-wider whitespace-nowrap transition-colors {{ request('category') ? 'bg-white text-gray-700 border border-gray-300' : 'bg-[#1C3661] text-white' }}">All Resources</a>
            <a href="{{ route('resources.index', ['category' => 'study_notes']) }}" class="px-4 py-2 rounded-sm font-semibold text-xs uppercase tracking-wider whitespace-nowrap transition-colors {{ request('category') === 'study_notes' ? 'bg-[#1C3661] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">Study Notes</a>
            <a href="{{ route('resources.index', ['category' => 'exam_paper']) }}" class="px-4 py-2 rounded-sm font-semibold text-xs uppercase tracking-wider whitespace-nowrap transition-colors {{ request('category') === 'exam_paper' ? 'bg-[#1C3661] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">Exam Papers</a>
            <a href="{{ route('resources.index', ['category' => 'mooc']) }}" class="px-4 py-2 rounded-sm font-semibold text-xs uppercase tracking-wider whitespace-nowrap transition-colors {{ request('category') === 'mooc' ? 'bg-[#1C3661] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">MOOCs</a>
        </div>

        <!-- Resource Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($resources as $resource)
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-semibold uppercase px-2.5 py-1 rounded-sm bg-blue-50 text-[#1C3661] border border-blue-100">
                                {{ str_replace('_', ' ', $resource->category) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $resource->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h4 class="text-lg font-serif font-bold text-gray-900 mb-2">{{ $resource->title }}</h4>
                        @if($resource->description)
                            <p class="text-xs text-gray-600 mb-4 leading-relaxed">{{ $resource->description }}</p>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            By <strong class="text-gray-700">{{ $resource->user->name ?? 'User' }}</strong>
                        </div>
                        
                        <div class="flex gap-2">
                            @if($resource->url)
                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 bg-[#1C3661] hover:bg-[#152a4b] text-white rounded-sm font-semibold text-xs uppercase tracking-wider transition-colors">
                                    View Link
                                </a>
                            @endif
                            
                            @if($resource->file_path)
                                <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-[#8b0000] hover:bg-[#6b0d0d] text-white rounded-sm font-semibold text-xs uppercase tracking-wider transition-colors">
                                    Download File
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 text-center py-16 bg-white border border-dashed border-gray-300 rounded-sm text-gray-500">
                    <p class="text-sm">No resources found in this category. Be the first to share study material!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $resources->appends(request()->query())->links() }}
        </div>

        <!-- Upload Modal Popup -->
        <div x-show="openUploadModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div @click.outside="openUploadModal = false" class="bg-white rounded-sm shadow-xl max-w-lg w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-serif font-bold text-[#1C3661]">Share a Resource</h3>
                    <button @click="openUploadModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>
                
                <form method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Resource Title</label>
                        <input type="text" name="title" required placeholder="e.g. 2026 Database Exam Paper" class="w-full border border-gray-300 rounded-sm p-2.5 text-sm focus:outline-none focus:border-[#8b0000]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full border border-gray-300 rounded-sm p-2.5 text-sm focus:outline-none focus:border-[#8b0000]">
                            <option value="study_notes">Study Notes</option>
                            <option value="exam_paper">Exam Paper</option>
                            <option value="mooc">MOOC / Video Course</option>
                            <option value="other">Other Material</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Brief Description (Optional)</label>
                        <textarea name="description" rows="2" placeholder="Brief summary..." class="w-full border border-gray-300 rounded-sm p-2.5 text-sm focus:outline-none focus:border-[#8b0000]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">External URL (Optional)</label>
                            <input type="url" name="url" placeholder="https://..." class="w-full border border-gray-300 rounded-sm p-2.5 text-sm focus:outline-none focus:border-[#8b0000]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Upload File (Optional)</label>
                            <input type="file" name="file" class="w-full border border-gray-300 rounded-sm p-1.5 text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" @click="openUploadModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-sm text-sm font-medium">Cancel</button>
                        <button type="submit" class="bg-[#8b0000] hover:bg-[#6b0d0d] text-white px-6 py-2 rounded-sm text-sm font-bold">Upload Resource</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection