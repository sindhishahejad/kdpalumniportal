@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#1C3661]">Document Requests Management</h2>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow-sm transition-colors">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-sm rounded-r shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow sm:rounded-lg overflow-hidden border border-gray-200">
        @if($requests->isEmpty())
            <div class="p-8 text-gray-500 text-center text-base italic border-t-4 border-[#1C3661]">No document requests found.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-[#1C3661] text-white">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Date</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Alumni Details</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs">Document & Purpose</th>
                            <th class="px-5 py-3 font-semibold uppercase tracking-wider text-xs w-1/4">Status & Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($requests as $req)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $req->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-base font-bold text-gray-900">{{ $req->user->name }}</div>
                                <div class="text-sm text-gray-600 mt-0.5">{{ $req->user->email }}</div>
                                <div class="text-sm text-blue-700 font-semibold mt-1">ID: KDP-{{ str_pad($req->user->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-5 py-4 max-w-sm">
                                <div class="text-base font-bold text-gray-800">{{ $req->document_type }}</div>
                                <div class="text-sm text-gray-600 mt-1 bg-white p-2 rounded border border-gray-100 line-clamp-3" title="{{ $req->purpose }}">{{ $req->purpose }}</div>
                            </td>
                            <td class="px-5 py-4 bg-gray-50/50 border-l border-gray-100">
                                <form action="{{ route('admin.documents.update', $req->id) }}" method="POST" class="space-y-2.5">
                                    @csrf
                                    @method('PUT')
                                    
                                    <select name="status" class="w-full text-sm py-1.5 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 font-semibold cursor-pointer
                                        {{ $req->status === 'Pending' ? 'text-yellow-800 bg-yellow-50 border-yellow-300' : '' }}
                                        {{ $req->status === 'Processing' ? 'text-blue-800 bg-blue-50 border-blue-300' : '' }}
                                        {{ $req->status === 'Ready' ? 'text-green-800 bg-green-50 border-green-300' : '' }}
                                        {{ $req->status === 'Rejected' ? 'text-red-800 bg-red-50 border-red-300' : '' }}">
                                        <option value="Pending" {{ $req->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processing" {{ $req->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Ready" {{ $req->status == 'Ready' ? 'selected' : '' }}>Ready for Pickup</option>
                                        <option value="Rejected" {{ $req->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    
                                    <textarea name="admin_notes" rows="2" placeholder="Add admin notes..." class="w-full text-sm p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 placeholder-gray-400">{{ $req->admin_notes }}</textarea>
                                    
                                    <button type="submit" class="w-full bg-[#8b0000] hover:bg-red-800 text-white text-xs font-bold py-2 px-3 rounded-md shadow-sm transition-colors uppercase tracking-wider">
                                        Update Status
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection