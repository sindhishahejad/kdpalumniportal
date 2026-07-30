<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdCardController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Load the profile so we don't hit null errors here.
        $user->load('profile');

        return view('id-card.show', compact('user'));
    }
}
