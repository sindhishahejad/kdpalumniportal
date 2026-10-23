<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Knowledge Feed & Challenge Board') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl shadow-sm text-sm" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm text-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Create Post Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Create a Post or Challenge</h3>
                
                <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="type" :value="__('Post Type')" />
                        <select id="type" name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="knowledge">Knowledge Feed (Discussion / Question)</option>
                            <option value="challenge">Challenge Board (Coding / Puzzle)</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('type')" />
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title (Optional)')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" placeholder="e.g. Best practices for Laravel scaling or Coding Puzzle #1" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="body" :value="__('Content / Details (Markdown Supported)')" />
                        <textarea id="body" name="body" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" placeholder="Use Markdown for code blocks like ```php ... ```" required></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('body')" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Publish Post') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Tabs -->
            <div class="flex gap-4">
                <a href="{{ route('posts.index') }}" class="px-4 py-2 rounded-md font-semibold text-sm {{ request('type') ? 'bg-white text-gray-700 shadow-sm' : 'bg-indigo-600 text-white' }}">All Posts</a>
                <a href="{{ route('posts.index', ['type' => 'knowledge']) }}" class="px-4 py-2 rounded-md font-semibold text-sm {{ request('type') === 'knowledge' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 shadow-sm' }}">Knowledge Feed</a>
                <a href="{{ route('posts.index', ['type' => 'challenge']) }}" class="px-4 py-2 rounded-md font-semibold text-sm {{ request('type') === 'challenge' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 shadow-sm' }}">Challenge Board</a>
            </div>

            <!-- Posts List -->
            <div class="space-y-6">
                @forelse ($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Post Header -->
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold uppercase px-2 py-1 rounded {{ $post->type === 'challenge' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $post->type }}
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $post->user_id))
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to remove this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 py-1 px-2.5 rounded-md transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Post Body -->
                        @if($post->title)
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h4>
                        @endif
                        <div class="text-gray-700 prose max-w-none mb-4">
                            {!! Str::markdown($post->body) !!}
                        </div>
                        <div class="text-sm text-gray-500 border-t pt-3 flex justify-between items-center">
                            <span>Posted by: <strong class="text-gray-700">{{ $post->user->name }}</strong> ({{ optional($post->user->profile)->current_company ?? 'Alumni' }})</span>
                        </div>

                        <!-- Threaded Discussions (Comments) -->
                        <div class="mt-4 pt-4 border-t border-gray-100 bg-gray-50 rounded-md p-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-3">Replies ({{ $post->comments->count() }})</h5>
                            
                            <!-- Comment Loop -->
                            <div class="space-y-4 mb-4">
                                @foreach ($post->comments as $comment)
                                    <div class="bg-white p-3 rounded-md shadow-sm text-sm border border-gray-100">
                                        <div class="flex items-center justify-between font-bold text-gray-900 mb-1">
                                            <div>
                                                {{ $comment->user->name }} 
                                                <span class="text-xs text-gray-400 font-normal ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id))
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Remove this reply?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[11px] font-semibold text-red-500 hover:text-red-700 hover:underline">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="text-gray-700 prose prose-sm max-w-none">
                                            {!! Str::markdown($comment->body) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Comment Form -->
                            <form method="POST" action="{{ route('comments.store', $post) }}" class="mt-2">
                                @csrf
                                <div class="flex gap-2 items-start">
                                    <textarea name="body" rows="2" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Write a reply... (Markdown supported)" required></textarea>
                                    <x-primary-button class="mt-1 h-10">
                                        {{ __('Reply') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        No posts found in the feed yet. Be the first to share something!
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
