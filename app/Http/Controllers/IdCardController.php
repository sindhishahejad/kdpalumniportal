<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    /**
     * Display the authenticated user's digital ID card with real-time QR code.
     */
    public function show()
    {
        $user = Auth::user()->load('profile');
        
        $verificationUrl = route('alumni.verify', $user->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($verificationUrl);

        return view('id-card.show', compact('user', 'verificationUrl', 'qrCodeUrl'));
    }

    /**
     * Download the official alumni ID card as a PDF document.
     */
    public function download()
    {
        $user = Auth::user()->load('profile');
        
        $verificationUrl = route('alumni.verify', $user->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($verificationUrl);

        // Fetch QR image as base64 data URI for reliable DomPDF embedding
        $qrBase64 = null;
        try {
            $qrContent = @file_get_contents($qrCodeUrl);
            if ($qrContent) {
                $qrBase64 = 'data:image/png;base64,' . base64_encode($qrContent);
            }
        } catch (\Exception $e) {
            $qrBase64 = null;
        }

        // Fetch crest and GTU logos as base64 for reliable DomPDF embedding
        $crestBase64 = null;
        $crestPath = public_path('images/kdp_crest.png');
        if (file_exists($crestPath)) {
            $crestBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($crestPath));
        }

        $gtuBase64 = null;
        $gtuPath = public_path('images/gtu_cte_logo.jpg');
        if (file_exists($gtuPath)) {
            $gtuBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($gtuPath));
        }

        $pdf = Pdf::loadView('id-card.pdf', compact('user', 'verificationUrl', 'qrBase64', 'crestBase64', 'gtuBase64'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download('KDP-Alumni-ID-' . ($user->entry_no ?? $user->id) . '.pdf');
    }

    /**
     * Public verification endpoint scanned via QR Code on the ID Card.
     */
    public function verify($identifier)
    {
        // Lookup by user ID or entry_no (Roll Number / Alumni ID)
        $user = User::where('id', $identifier)
            ->orWhere('entry_no', $identifier)
            ->with('profile')
            ->first();

        $isValid = $user && $user->is_approved;

        return view('id-card.verify', compact('user', 'isValid', 'identifier'));
    }
}
