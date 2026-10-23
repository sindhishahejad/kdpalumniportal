@extends('layouts.app')

@section('content')
<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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

        <!-- Success Stories Quick Access -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Success Stories</h3>
                <p class="text-sm text-gray-500">Highlight alumni achievements and career spotlights on the network.</p>
            </div>
            <a href="{{ route('admin.stories.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-colors whitespace-nowrap">
                Manage Stories &rarr;
            </a>
        </div>

        <!-- Department Labs & Workshops Quick Access -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-blue-600"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <h3 class="text-lg font-bold text-[#0f172a]">Department Labs & Workshops</h3>
                <p class="text-sm text-gray-500 mt-0.5">Manage laboratory equipment inventories, room locations, and faculty coordinator records.</p>
            </div>
            <div class="flex items-center gap-3 pl-3 sm:pl-0">
                <a href="{{ route('labs.index') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-all shadow-sm shrink-0">
                    Manage Labs &rarr;
                </a>
            </div>
        </div>

        <!-- Knowledge Feed & Challenge Moderation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-rose-600"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <h3 class="text-lg font-bold text-[#0f172a]">Knowledge Feed &amp; Challenge Moderation</h3>
                <p class="text-sm text-gray-500 mt-0.5">Review community discussions, code challenges, and remove flagged or inappropriate replies.</p>
            </div>
            <div class="flex items-center gap-3 pl-3 sm:pl-0">
                <a href="{{ route('posts.index') }}" class="bg-rose-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-rose-700 transition-all shadow-sm shrink-0">
                    Moderate Feed &rarr;
                </a>
            </div>
        </div>

        <!-- Weekly Newsletter & Campus Digest Automation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-2 bg-[#8b0000]"></div>
            <div class="pl-3 mb-4 sm:mb-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-[#0f172a]">Weekly Newsletter &amp; Campus Digest</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                        Scheduled: Mondays 8:00 AM
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-0.5">Automated digest of latest alumni spotlights, job postings, and campus announcements.</p>
            </div>
            <div class="flex items-center gap-2 pl-3 sm:pl-0 flex-wrap">
                <form method="POST" action="{{ route('admin.newsletter.send') }}">
                    @csrf
                    <input type="hidden" name="test_only" value="1">
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-4 py-2 rounded-xl font-semibold text-xs transition-colors shrink-0 flex items-center gap-1.5" title="Sends test copy to your email">
                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Test Email to Me
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.newsletter.send') }}" onsubmit="return confirm('Queue and send the weekly digest email to all approved alumni, students, and faculty?');">
                    @csrf
                    <button type="submit" class="bg-[#8b0000] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#6b0000] transition-all shadow-sm shrink-0 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Dispatch Digest Now
                    </button>
                </form>
            </div>
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

        <!-- ============================================== -->
        <!-- GEOGRAPHIC ALUMNI MAP & REGIONAL CHAPTERS      -->
        <!-- ============================================== -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                            <span>📍</span> Interactive Regional Chapter Map
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Click on any pin to inspect chapter details, member counts, and meetup venues.</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#1c3272]"></span> Gujarat Hubs</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> National & Global</span>
                        <a href="{{ route('chapters.index') }}" class="font-bold text-[#1c3272] hover:text-[#0f172a] hover:underline flex items-center gap-1 ml-2">
                            Explore All Chapters &rarr;
                        </a>
                    </div>
                </div>

                <div id="adminDashboardMap" class="w-full h-80 sm:h-96 bg-gray-50 z-10"></div>
            </div>

            <!-- Geographic Clusters Breakdown Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 font-serif">Alumni Presence by Industrial Corridor</h3>
                        <p class="text-xs text-gray-500">Estimated distribution of diploma graduates based on profile location.</p>
                    </div>
                    <a href="{{ route('chapters.index') }}" class="text-xs font-bold text-[#1c3272] hover:underline">
                        View Regional Directory &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
                    @if(isset($clusters))
                        @foreach($clusters as $key => $cluster)
                            <a href="{{ route('alumni.index', ['location' => $cluster['name']]) }}" 
                               class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm hover:border-[#1c3272] hover:shadow-md transition-all flex flex-col justify-between group">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 group-hover:text-[#1c3272] transition-colors block truncate" title="{{ $cluster['name'] }}">
                                        {{ $cluster['name'] }}
                                    </span>
                                    <div class="text-xl font-black text-gray-900 mt-1">
                                        {{ $cluster['count'] }}
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400 group-hover:text-blue-600 transition-colors font-medium mt-2">View Alumni &rarr;</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Leaflet Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;
            var mapEl = document.getElementById('adminDashboardMap');
            if (!mapEl) return;

            var map = L.map('adminDashboardMap').setView([22.8, 72.5], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var pins = @json($mapPins ?? []);

            pins.forEach(function (pin) {
                if (!pin.lat || !pin.lng) return;

                var marker = L.circleMarker([pin.lat, pin.lng], {
                    radius: 8,
                    fillColor: pin.city === 'Patan' ? '#ea580c' : '#1c3272',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map);

                var popupContent = `
                    <div style="font-family: inherit; min-width: 170px; padding: 2px;">
                        <div style="font-size: 11px; font-weight: 800; color: #f97316; text-transform: uppercase;">${pin.city}, ${pin.state}</div>
                        <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 2px;">${pin.name}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">👥 ${pin.members_count} Alumni Joined</div>
                        ${pin.venue ? `<div style="font-size: 10px; color: #475569; margin-top: 2px;">📍 ${pin.venue}</div>` : ''}
                        <div style="margin-top: 8px;">
                            <a href="${pin.url}" style="display: inline-block; background: #1c3272; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-decoration: none;">Explore Chapter &rarr;</a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
            });
        });
    </script>
@endsection