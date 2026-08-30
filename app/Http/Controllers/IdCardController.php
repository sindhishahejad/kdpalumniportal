<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Load the profile so we don't hit null errors here.
        if (method_exists($user, 'profile')) {
            $user->load('profile');
        }

        return view('id-card.show', compact('user'));
    }
    public function download()
    {
        $user = Auth::user()->load('profile');
        
        $pdf = Pdf::loadView('id-card.pdf', compact('user'));
        
        return $pdf->download('KDP-Alumni-ID-' . ($user->entry_no ?? $user->id) . '.pdf');
    }
}