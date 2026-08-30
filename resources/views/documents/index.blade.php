@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-[#1C3661] mb-6">Request Official Documents</h2>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Submission Form -->
        <div class="bg-white shadow sm:rounded-lg p-6 col-span-1 border-t-4 border-[#8b0000]">
            <h3 class="text-lg font-semibold mb-4">New Request</h3>
            <form action="{{ route('documents.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                    <select name="document_type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Select Document...</option>
                        <option value="Official Transcript">Official Transcript</option>
                        <option value="Recommendation Letter">Recommendation Letter</option>
                        <option value="Bonafide Certificate">Bonafide Certificate</option>
                        <option value="Provisional Degree">Provisional Degree</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose of Request</label>
                    <textarea name="purpose" rows="3" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="e.g., Higher studies application to XYZ University..."></textarea>
                </div>

                <button type="submit" class="w-full bg-[#1C3661] hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition-colors">
                    Submit Request
                </button>
            </form>
        </div>

        <!-- Request History Table -->
        <div class="bg-white shadow sm:rounded-lg p-6 col-span-2">
            <h3 class="text-lg font-semibold mb-4">My Request History</h3>
            
            @if($requests->isEmpty())
                <p class="text-gray-500 text-sm italic">You haven't requested any documents yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 font-bold">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Document Type</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Admin Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($requests as $req)
                            <tr>
                                <td class="px-4 py-3">{{ $req->created_at->format('d M, Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $req->document_type }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full 
                                        {{ $req->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $req->status === 'Processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $req->status === 'Ready' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $req->status === 'Rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $req->admin_notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection