<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect user to Google's OAuth screen (with graceful configuration check).
     */
    public function redirectToGoogle()
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Google Sign-In is not configured on this environment. Please sign in using your portal password.');
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to initiate Google sign-in. Please use email and password.');
        }
    }

    /**
     * Handle the callback from Google after successful authentication.
     */
    public function handleGoogleCallback()
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Google Sign-In is not configured.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()),
                    'role' => 'alumni',
                    'is_approved' => false,
                ]);
            }

            if (!$user->is_approved) {
                return redirect()->route('login')->with('status', 'Your Google account was registered and is currently pending administrator verification.');
            }

            Auth::login($user);

            if (empty($user->role)) {
                return redirect()->route('onboarding');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please sign in with your portal password.');
        }
    }
    
    /**
     * Restricted debug email-only login (strictly disabled in production).
     */
    public function loginWithEmail(Request $request)
    {
        if (app()->isProduction()) {
            abort(403, 'Passwordless email login is disabled for security in production.');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!$user->is_approved) {
                return back()->withErrors(['email' => 'This account is pending administrator approval.']);
            }

            Auth::login($user);
            
            if (empty($user->role)) {
                return redirect()->route('onboarding');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'No account found with this email address.']);
    }
}
