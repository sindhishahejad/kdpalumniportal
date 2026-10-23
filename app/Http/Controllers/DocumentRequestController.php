<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    // USER: View their requests & form
    public function index()
    {
        $requests = DocumentRequest::where('user_id', Auth::id())->latest()->get();
        return view('documents.index', compact('requests'));
    }

    // USER: Submit a new request
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'purpose' => 'required|string|max:1000',
        ]);

        DocumentRequest::create([
            'user_id' => Auth::id(),
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Document request submitted successfully. You will be notified once it is processed.');
    }

    // USER / ADMIN: Download fulfilled document
    public function download(DocumentRequest $document)
    {
        abort_if(auth()->id() !== $document->user_id && auth()->user()->role !== 'admin', 403);

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'The requested document file is not available for download.');
        }

        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION) ?: 'pdf';
        $downloadName = Str::slug($document->document_type) . '-' . $document->id . '.' . $extension;

        return Storage::disk('public')->download($document->file_path, $downloadName);
    }

    // ADMIN: View all requests
    public function adminIndex()
    {
        $requests = DocumentRequest::with('user')->latest()->get();
        return view('admin.documents.index', compact('requests'));
    }

    // ADMIN: Update request status and optionally upload fulfilled document
    public function update(Request $request, DocumentRequest $document)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Ready,Rejected',
            'admin_notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $filePath = $document->file_path;

        if ($request->hasFile('document_file')) {
            // Delete old file if present
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('document_file')->store('documents', 'public');
        }

        $document->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'file_path' => $filePath,
        ]);

        // Trigger notification to the student/alumnus when status updates
        if (in_array($request->status, ['Ready', 'Rejected'])) {
            $msg = $request->status === 'Ready'
                ? "Your {$document->document_type} has been fulfilled and is ready for download!"
                : "Your request for {$document->document_type} was not approved.";

            $document->user->notify(new PortalNotification(
                'Document Request Update',
                $msg,
                route('documents.index')
            ));
        }

        return back()->with('success', 'Document request updated successfully.');
    }
}
