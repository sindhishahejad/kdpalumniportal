<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:alumni,student,faculty'],
            'phone' => ['required', 'string', 'max:255'],
            'entry_no' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'year_joining' => ['required', 'integer'],
            'graduation_year' => ['required', 'integer'],
            'company' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'work_industry' => ['required', 'string', 'max:255'],
            'skills' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'entry_no' => $request->entry_no,
            'degree' => $request->degree,
            'department' => $request->department,
            'year_joining' => $request->year_joining,
            'graduation_year' => $request->graduation_year,
            'company' => $request->company,
            'designation' => $request->designation,
            'work_industry' => $request->work_industry,
            'skills' => $request->skills,
            // is_approved is automatically false because of our database migration
        ]);

        event(new Registered($user));

        // ❌ REMOVED: Auth::login($user); 
        // We want them to wait for admin approval!

        // ✅ Redirect to login with a status message instead
        return redirect()->route('login')->with('status', 'Registration successful! Your account is currently pending administrator approval. You will be able to log in once verified.');
    }
}