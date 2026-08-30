<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return back()->with('success', 'Document request submitted successfully. We will notify you when it is ready.');
    }

    // ADMIN: View all requests
    public function adminIndex()
    {
        $requests = DocumentRequest::with('user')->latest()->get();
        return view('admin.documents.index', compact('requests'));
    }

    // ADMIN: Update request status
    public function update(Request $request, DocumentRequest $document)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Ready,Rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $document->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Document request updated successfully.');
    }
}