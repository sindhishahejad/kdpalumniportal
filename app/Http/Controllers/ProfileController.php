<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Fill the User model with ALL validated data 
        // (Includes name, email, phone, blood_group, company, skills, etc.)
        $user->fill($request->validated());

        // 2. Handle Email Verification if the email was changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 3. Handle Profile Photo Upload
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            
            // Delete old photo from storage if a new one is uploaded
            if (!empty($user->photo_path) && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $user->photo_path = $request->file('photo')->store('profile-photos', 'public');
        }

        // 4. Save everything directly to the users table
        $user->save();

        // 5. Update the privacy toggles in the associated profiles table
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'is_phone_public' => $request->has('is_phone_public'),
                'is_email_public' => $request->has('is_email_public'),
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo from storage before deleting user
        if (!empty($user->photo_path) && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}