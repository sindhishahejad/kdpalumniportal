<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    // PUBLIC: Store public contact message
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactInquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'Unread',
        ]);

        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    // ADMIN: View all contact inquiries
    public function adminIndex()
    {
        $inquiries = ContactInquiry::latest()->get();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    // ADMIN: Update inquiry status
    public function update(Request $request, ContactInquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:Unread,Read,Replied',
        ]);

        $inquiry->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Inquiry status updated successfully.');
    }
}