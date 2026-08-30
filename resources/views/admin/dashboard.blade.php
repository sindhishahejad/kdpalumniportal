<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Platform Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Feed Posts</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_posts'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Job Listings</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_jobs'] }}</div>
                </div>
            </div>

            <!-- ✨ PENDING REGISTRATIONS QUEUE ✨ -->
            @if($pendingUsers->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-yellow-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    Pending Registrations 
                    <span class="ml-3 bg-yellow-100 text-yellow-800 text-xs font-extrabold px-3 py-1 rounded-full">{{ $pendingUsers->count() }} Requires Action</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role / Dept</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Decision</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingUsers as $pending)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $pending->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $pending->email }} • {{ $pending->phone }}</div>
                                        <div class="text-xs text-gray-500 mt-1">ID/Roll: {{ $pending->entry_no }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $pending->role }}</span>
                                        <div class="text-xs text-gray-600 mt-1">{{ $pending->department ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <!-- Approve Form -->
                                            <form method="POST" action="{{ route('admin.users.approve', $pending) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-sm text-xs font-bold transition-colors">Approve</button>
                                            </form>
                                            <!-- Reject Form (Uses the existing destroy route) -->
                                            <form method="POST" action="{{ route('admin.users.destroy', $pending) }}" onsubmit="return confirm('Reject and delete this applicant forever?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-sm text-xs font-bold transition-colors">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Post a Notice -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Broadcast Notice</h3>
                    <form method="POST" action="{{ route('admin.notices.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="title" :value="__('Notice Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="body" :value="__('Message')" />
                            <textarea id="body" name="body" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <x-primary-button>Publish Notice</x-primary-button>
                        </div>
                    </form>

                    <div class="mt-6 space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700">Active Notices</h4>
                        @foreach($notices as $notice)
                            <div class="p-3 bg-gray-50 border rounded-md flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-sm text-gray-900">{{ $notice->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $notice->created_at->format('M d, Y') }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Active User Management Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Approved Users</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            @if($user->isAdmin())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 capitalize">{{ $user->role }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                            @if(!$user->isAdmin())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user completely?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>