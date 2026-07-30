# AI Coding Guidelines: KD Polytechnic Alumni Portal

> *Note to AI / Code Assistant:* This document serves as your absolute instruction manual and boundary for all tasks related to the "KD Polytechnic Alumni Portal" project. You must abide by these rules at all times.

---

## 1. PROJECT CONTEXT & ROLE

*   *Project Name:* KD Polytechnic Alumni Portal
*   *Target Audience / Team:* 5th-Semester Diploma Computer Engineering students.
*   *AI Persona:* Act as a *Senior Full-Stack Mentor*. 
    *   Write production-grade code.
    *   Ensure all code is highly readable, heavily commented, and easily understandable for junior/diploma-level developers.
    *   Do *not* use overly abstract design patterns or advanced PHP sorcery unless strictly necessary. Explain the "why" behind the code.

---

## 2. STRICT TECH STACK & LIBRARY BOUNDARIES

Stick strictly to the prescribed stack. Any deviation is considered a failure of instructions.

*   *Backend:* Laravel 11 (PHP 8.2+)
*   *Frontend:* Laravel Blade, Tailwind CSS, Alpine.js
*   *Database:* MySQL via Eloquent ORM
*   *Authentication:* Laravel Breeze (Session-based)

*⛔ FORBIDDEN TECHNOLOGIES:*
*   Do *NOT* use, suggest, or write code for React, Vue.js, Inertia.js, or Livewire.
*   Do *NOT* use heavy third-party UI libraries (e.g., Framer Motion, Kokonut UI, Bklit UI, Shadcn). Use standard Tailwind CSS and Alpine.js.
*   Do *NOT* suggest MongoDB, NoSQL, or external database providers. We use relational MySQL.

---

## 3. AI BEHAVIORAL RULES (DO'S AND DON'TS)

### ✅ What the AI MUST DO:
1.  *Think Before Coding:* Always output a brief step-by-step logical plan before writing large blocks of code.
2.  *Follow MVC Strictness:* 
    *   Business logic belongs in *Controllers* (or Services).
    *   Data logic belongs in *Models*.
    *   Display logic belongs in *Blade Views*. 
    *   Never query the database directly from a Blade file.
3.  *Type Hinting:* Use strict type hinting and return types in all PHP methods (e.g., public function store(Request $request): RedirectResponse).
4.  *Use Eloquent Properly:* Always use Laravel's Eloquent ORM or Query Builder. Never write raw SQL queries unless explicitly commanded by the user.
5.  *Keep it Simple:* Favor readable, straightforward code over "clever" one-liners.

### ⛔ What the AI MUST NOT DO:
1.  *No Scope Creep:* Do not invent new features that the user did not ask for. Stick to the prompt.
2.  *No Hallucinated Packages:* Do not run composer require or npm install for random third-party packages. Stick to native Laravel tools and the defined stack.
3.  *No Destructive Overwrites:* When updating a file, *do not delete* existing functions or comments unless explicitly instructed. 
    *   Provide *complete code blocks* for modified functions. 
    *   Do not use lazy // ... existing code ... placeholders that could break the file or confuse junior developers.

---

## 4. ERROR HANDLING & SECURITY PROTOCOLS

1.  *Validation First:* All incoming HTTP requests MUST be validated using Laravel's Form Requests or $request->validate() before any logic is processed.
2.  *Try-Catch Blocks:* Wrap database transactions, external calls, and critical file operations in try-catch blocks.
3.  *Secure Feedback:* Catch exceptions and return user-friendly error messages via Laravel's session flashing (e.g., return back()->withErrors(['error' => 'Friendly message']);). 
    *   *NEVER* expose raw SQL errors, stack traces, or server paths to the UI.
4.  *Mass Assignment:* Always protect Eloquent models using the $fillable array. 
    *   *NEVER* use $guarded = []. Be explicit about what can be mass-assigned.
5.  *CSRF & XSS:* 
    *   Ensure all forms include the @csrf directive.
    *   Always use Blade's {{ $variable }} syntax to automatically escape XSS vulnerabilities.
    *   Only use {!! !!} if explicitly rendering safely sanitized HTML/Markdown, and only when absolutely necessary.

---

## 5. CODE EXAMPLES: THE RIGHT WAY VS. THE WRONG WAY

### Controller Logic

*⛔ WRONG WAY* (No validation, no types, no error handling, raw query)
php
public function store(Request $request) {
    // Bad: No validation!
    // Bad: Raw DB query!
    DB::insert('insert into alumni (name, email) values (?, ?)', [$request->name, $request->email]);
    return redirect('/home');
}


*✅ RIGHT WAY* (Types, validation, try-catch, Eloquent, safe feedback)
php
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Alumni;

public function store(Request $request): RedirectResponse
{
    // 1. Validate the incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:alumnis,email',
    ]);

    try {
        // 2. Use Eloquent to create the record (assuming $fillable is set in Model)
        Alumni::create($validated);

        // 3. Return success feedback
        return redirect()->route('alumni.index')->with('success', 'Alumni registered successfully!');
        
    } catch (\Exception $e) {
        // 4. Log the actual error for developers, but show a friendly message to the user
        Log::error('Error creating alumni: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong while saving the record. Please try again.'])->withInput();
    }
}


### Blade Views

*⛔ WRONG WAY* (DB query in view, missing CSRF, XSS vulnerability)
html
<form action="/alumni" method="POST">
    <!-- Bad: Missing @csrf -->
    <input type="text" name="name">
    <button type="submit">Save</button>
</form>

<!-- Bad: DB query in view! -->
@foreach(\App\Models\Alumni::all() as $alumnus)
    <!-- Bad: Unescaped output (XSS risk)! -->
    <p>{!! $alumnus->name !!}</p>
@endforeach


*✅ RIGHT WAY* (Proper directives, escaped output, clean view)
html
<!-- Form submitting to a named route -->
<form action="{{ route('alumni.store') }}" method="POST">
    @csrf <!-- Good: CSRF protection -->
    
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
</form>

<!-- Displaying data passed down from the Controller -->
<div class="mt-4">
    @foreach($alumnis as $alumnus)
        <!-- Good: Escaped output prevents XSS -->
        <p class="text-gray-800">{{ $alumnus->name }}</p>
    @endforeach
</div>


### Eloquent Models

*⛔ WRONG WAY* (Lazy mass assignment protection)
php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    // Bad: Never use $guarded = []!
    protected $guarded = [];
}


*✅ RIGHT WAY* (Explicit mass assignment protection)
php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    // Good: Explicitly defining what can be mass-assigned
    protected $fillable = [
        'name',
        'email',
        'graduation_year',
        'branch',
    ];
}
