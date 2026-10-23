@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">Administrative Services</span>
        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#1C3661] mt-0.5">Request Official Documents</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Submit requests for transcripts, bonafide certificates, and recommendation letters, and download fulfilled copies.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Submission Form -->
        <div class="bg-white shadow-sm rounded-2xl p-6 sm:p-8 col-span-1 border border-gray-100 border-t-4 border-t-[#8b0000] h-max">
            <h3 class="text-lg font-serif font-bold text-gray-900 mb-2">New Request</h3>
            <p class="text-xs text-gray-500 mb-6">Select the required document and specify your purpose (e.g. D2D admission, job background check, higher studies).</p>
            
            <form action="{{ route('documents.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Document Type <span class="text-red-500">*</span></label>
                    <select name="document_type" required class="w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-[#1C3661] focus:ring-[#1C3661]">
                        <option value="">Select Document...</option>
                        <option value="Official Transcript">Official Transcript</option>
                        <option value="Recommendation Letter">Recommendation Letter</option>
                        <option value="Bonafide Certificate">Bonafide Certificate</option>
                        <option value="Provisional Degree">Provisional Degree</option>
                        <option value="Medium of Instruction Certificate">Medium of Instruction Certificate</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Purpose of Request <span class="text-red-500">*</span></label>
                    <textarea name="purpose" rows="4" required class="w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-[#1C3661] focus:ring-[#1C3661]" placeholder="e.g., Applying for B.E. Degree Admission through D2D at GTU, or Company Joining Requirements..."></textarea>
                </div>

                <button type="submit" class="w-full bg-[#1C3661] hover:bg-blue-900 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-xs uppercase tracking-wider">
                    Submit Request &rarr;
                </button>
            </form>
        </div>

        <!-- Request History Table -->
        <div class="bg-white shadow-sm rounded-2xl p-6 sm:p-8 col-span-1 lg:col-span-2 border border-gray-100">
            <h3 class="text-lg font-serif font-bold text-gray-900 mb-4">My Request History</h3>
            
            @if($requests->isEmpty())
                <div class="py-12 text-center text-gray-400 text-sm italic">
                    You haven't requested any documents yet. Fill out the form to request official certificates or transcripts.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-xs uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-xs uppercase tracking-wider">Document</th>
                                <th class="px-4 py-3 text-xs uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-xs uppercase tracking-wider">Admin Notes</th>
                                <th class="px-4 py-3 text-xs uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($requests as $req)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-4 py-3 text-xs text-gray-600 whitespace-nowrap">{{ $req->created_at->format('d M, Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $req->document_type }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full 
                                        {{ $req->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $req->status === 'Processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $req->status === 'Ready' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $req->status === 'Rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ $req->admin_notes ?: '-' }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if($req->file_path)
                                        <a href="{{ route('documents.download', $req->id) }}" class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download
                                        </a>
                                    @elseif($req->status === 'Ready')
                                        <span class="text-[11px] text-green-700 font-bold bg-green-50 px-2.5 py-1 rounded-md border border-green-200">Ready at Office</span>
                                    @elseif($req->status === 'Processing')
                                        <span class="text-[11px] text-blue-600 font-medium">In Process</span>
                                    @elseif($req->status === 'Rejected')
                                        <span class="text-[11px] text-red-600 font-medium">Not Approved</span>
                                    @else
                                        <span class="text-[11px] text-gray-400 font-medium">Pending Review</span>
                                    @endif
                                </td>
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
