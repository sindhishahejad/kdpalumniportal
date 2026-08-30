@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#1C3661]">Success Stories Management</h2>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow-sm transition-colors">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-sm rounded-r shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Form -->
        <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-200 h-max">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Publish Success Story</h3>
            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Headline / Title</label>
                    <input type="text" name="title" required placeholder="e.g. Alumnus leads AI initiative at Google" class="w-full text-sm rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alumni Name</label>
                    <input type="text" name="alumni_name" required class="w-full text-sm rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Batch Year</label>
                        <input type="text" name="batch_year" required placeholder="e.g. 2022" class="w-full text-sm rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" required placeholder="e.g. Computer Eng." class="w-full text-sm rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Story / Content</label>
                    <textarea name="story" rows="4" required class="w-full text-sm rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Photo</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 border border-gray-300 rounded-md p-1.5">
                </div>
                <button type="submit" class="w-full bg-[#1C3661] hover:bg-blue-900 text-white font-bold py-2.5 px-4 rounded-md text-sm transition-colors uppercase tracking-wider">
                    Publish Story
                </button>
            </form>
        </div>

        <!-- Stories List -->
        <div class="lg:col-span-2 bg-white p-6 shadow sm:rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Published Stories</h3>
            @if($stories->isEmpty())
                <p class="text-gray-500 italic text-sm">No success stories published yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($stories as $story)
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <span class="text-xs font-bold text-blue-600 uppercase">{{ $story->department }} • Batch of {{ $story->batch_year }}</span>
                                <h4 class="font-bold text-base text-gray-900 mt-0.5">{{ $story->title }}</h4>
                                <p class="text-xs text-gray-600 mt-1">By <span class="font-semibold">{{ $story->alumni_name }}</span></p>
                            </div>
                            <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Delete this story?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection