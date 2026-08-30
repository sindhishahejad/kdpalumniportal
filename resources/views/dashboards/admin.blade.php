@extends('layouts.app')

@section('content')
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#0f172a]">Admin Dashboard</h1>
                <p class="text-gray-500 mt-2 text-sm">Manage users, approve job postings, and oversee the KDP Alumni network.</p>
            </div>
            <span class="bg-red-50 text-red-700 text-xs font-bold uppercase tracking-widest py-2 px-4 rounded-full border border-red-200">
                Administrator Access
            </span>
        </div>

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

        <!-- Admin Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-sm shadow border border-gray-100">
                <span class="block text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Total Alumni</span>
                <span class="text-3xl font-black text-[#1C3661]">{{ $stats['total_alumni'] }}</span>
            </div>
            <div class="bg-white p-6 rounded-sm shadow border border-gray-100">
                <span class="block text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Pending Jobs</span>
                <span class="text-3xl font-black text-[#8b0000]">{{ $stats['pending_jobs'] }}</span>
            </div>
            <div class="bg-white p-6 rounded-sm shadow border border-gray-100">
                <span class="block text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Active Students</span>
                <span class="text-3xl font-black text-[#1C3661]">{{ $stats['active_students'] }}</span>
            </div>
        </div>

        <!-- Pending Registrations Queue -->
        @if(isset($pendingUsers) && $pendingUsers->count() > 0)
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
                                    <div class="text-xs text-gray-500">{{ $pending->email }} • {{ $pending->phone ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">ID/Roll: {{ $pending->entry_no ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $pending->role }}</span>
                                    <div class="text-xs text-gray-600 mt-1">{{ $pending->department ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.approve', $pending) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-sm text-xs font-bold transition-colors">Approve</button>
                                        </form>
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

        <!-- Document Requests Quick Access -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-[#1C3661] flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Document Requests</h3>
                <p class="text-sm text-gray-500">Manage alumni requests for transcripts, recommendation letters, and certificates.</p>
            </div>
            <a href="{{ route('admin.documents.index') }}" class="bg-[#1C3661] hover:bg-blue-800 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-colors whitespace-nowrap">
                Manage Requests &rarr;
            </a>
        </div>

        <!-- Contact Inquiries Quick Access -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Contact Inquiries</h3>
                <p class="text-sm text-gray-500">Track and manage public messages submitted through the contact page.</p>
            </div>
            <a href="{{ route('admin.inquiries.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-colors whitespace-nowrap">
                Manage Inquiries &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Post a Notice -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Broadcast Notice</h3>
                <form method="POST" action="{{ route('admin.notices.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Notice Title</label>
                        <input type="text" name="title" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                        <textarea name="body" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg text-sm">Publish Notice</button>
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

        <!-- GALLERY MANAGER -->
        <div class="mt-10 p-8 bg-gray-50 rounded-[20px] border border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Gallery</h2>
                <p class="text-gray-500 text-sm mt-1">Create new sections or edit existing memories.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Create New Section</h3>
                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-2">Section Name</label>
                            <input type="text" name="title" required placeholder="e.g. Sports Week 2026" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-2">Upload Photos</label>
                            <input type="file" name="images[]" multiple required accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 cursor-pointer">
                        </div>
                        <button type="submit" class="w-full bg-[#3b82f6] hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors">
                            Create Section & Upload
                        </button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Edit Existing Sections</h3>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                        @forelse($albums as $album)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $album->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $album->photos_count }} Photos</p>
                                </div>
                                <a href="{{ route('gallery.edit', $album->id) }}" class="text-xs font-bold text-[#3b82f6] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                    Edit / Preview
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">No sections created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- EVENT MANAGER -->
        <div class="mt-10 p-8 bg-gray-50 rounded-[20px] border border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Events</h2>
                <p class="text-gray-500 text-sm mt-1">Schedule upcoming campus activities and reunions.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Schedule New Event</h3>
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Event Title</label>
                            <input type="text" name="title" required placeholder="e.g. Annual Tech Symposium" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Category</label>
                                <select name="category" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm text-gray-600">
                                    <option value="Upcoming Event">Upcoming Event</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Seminar">Seminar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Event Date</label>
                                <input type="date" name="event_date" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm text-gray-600">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Time Display</label>
                            <input type="text" name="time_display" required placeholder="e.g. 10:00 AM - 04:00 PM" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Event Description (Optional)</label>
                            <textarea name="description" rows="2" placeholder="Brief details about the event..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Cover Image</label>
                            <input type="file" name="image" required accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-600 cursor-pointer">
                        </div>
                        <button type="submit" class="w-full bg-[#16a34a] hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors mt-2">
                            Publish Event
                        </button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Active Events</h3>
                    <div class="space-y-3 max-h-[450px] overflow-y-auto pr-2">
                        @forelse($events as $event)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-md overflow-hidden bg-gray-200 flex-shrink-0">
                                        @if($event->image_path)
                                            <img src="{{ asset('storage/' . $event->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-[#0f172a]">{{ $event->title }}</h4>
                                        <p class="text-xs text-[#f97316] font-bold">{{ $event->event_date->format('M d, Y') }} • <span class="text-gray-500 font-normal">{{ $event->category }}</span></p>
                                    </div>
                                </div>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel and delete this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">No upcoming events scheduled.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- JOB MANAGER -->
        <div class="mt-10 p-8 bg-gray-50 rounded-[20px] border border-gray-200 mb-12">
            <div class="mb-6">
                <h2 class="text-2xl font-serif font-bold text-[#0f172a]">Manage Job Board</h2>
                <p class="text-gray-500 text-sm mt-1">Post new career opportunities and remove expired listings.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-max">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Post New Job</h3>
                    <form action="{{ route('jobs.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Job Title</label>
                                <input type="text" name="title" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Company</label>
                                <input type="text" name="company" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Location</label>
                                <input type="text" name="location" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0f172a] mb-1">Emp. Type</label>
                                <select name="employment_type" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm text-gray-600">
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="internship">Internship</option>
                                    <option value="contract">Contract</option>
                                    <option value="apprenticeship">Apprenticeship</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Application Link or Email</label>
                            <input type="text" name="application_link_or_email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f172a] mb-1">Description</label>
                            <textarea name="description" required rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#16a34a] hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors mt-2">
                            Publish Job to Network
                        </button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 border-b pb-2">Active Jobs</h3>
                    <div class="space-y-3 max-h-[450px] overflow-y-auto pr-2">
                        @forelse($jobs as $job)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                                <div>
                                    <h4 class="font-bold text-sm text-[#0f172a]">{{ $job->title }}</h4>
                                    <p class="text-xs text-[#3b82f6] font-bold">{{ $job->company }} <span class="text-gray-500 font-normal">| {{ ucfirst($job->employment_type) }}</span></p>
                                </div>
                                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">No active jobs posted.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection