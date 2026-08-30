<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with('user')->latest()->take(10)->get();
        $userDonations = Auth::user()->donations()->latest()->get();
        $totalRaised = Donation::sum('amount');

        return view('donations.index', compact('donations', 'userDonations', 'totalRaised'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'campaign' => ['required', 'string', 'max:255'],
        ]);

        Donation::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'campaign' => $request->campaign,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => 'completed',
        ]);

        return back()->with('success', 'Thank you for your generous contribution to K.D. Polytechnic!');
    }
}