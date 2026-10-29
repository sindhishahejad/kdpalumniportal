# Engineering Guidelines & Technical Standards: K. D. Polytechnic Alumni Portal

**Document Version:** 2.0.0  
**Lead Software Architect:** **Sindhi Shahejad**  
**Institution:** K. D. Polytechnic, Patan (Estd. 1961)  
**Scope:** Engineering standards, security protocols, stack constraints, and code conventions.

---

## 1. Project Context & Architectural Standards

* **Target Audience:** K. D. Polytechnic, Patan community (Students, Alumni, Faculty, Administrators).
* **Code Quality Standard:** Production-grade, maintainable, self-documenting code with comprehensive automated tests.
* **Architecture Pattern:** Strict Model-View-Controller (MVC) with dedicated Domain Services in Laravel 11.

---

## 2. Tech Stack & Library Boundaries

The stack is fixed and strictly enforced across the portal:

* **Backend:** Laravel 11.x on PHP 8.2+
* **Frontend:** Laravel Blade, Tailwind CSS 3.x, Alpine.js 3.x
* **Database:** MySQL 8.0+ / SQLite (via Eloquent ORM)
* **Authentication:** Laravel Breeze (Session-based with custom RBAC)
* **Mail Protocol:** SMTP via Symfony Mailer (`smtp.gmail.com:587` with TLS)
* **Messaging:** Universal WhatsApp `wa.me` protocol + Meta Cloud API architecture

### ⛔ Forbidden Technologies:
* Do **NOT** introduce heavy SPA frameworks (React, Vue, Angular, Inertia.js, Livewire).
* Do **NOT** use heavy third-party UI component libraries (Framer Motion, Shadcn, Kokonut UI). Use native Tailwind CSS and Alpine.js.
* Do **NOT** use NoSQL or document databases. All relational models are managed via Eloquent.

---

## 3. Engineering Best Practices (Do's and Don'ts)

### ✅ What Every Developer / Assistant MUST DO:
1. **Maintain Documentation Integrity:** Keep markdown documentation (`README.md`, `PRD.md`, `Architecture.md`, `Design.md`, `Phases.md`, `Rules.md`) aligned with codebase reality.
2. **Strict MVC Separation:**
   * Business logic belongs in **Controllers** or **Services** (`WhatsAppAlertService`, `GroqChatService`).
   * Data schema and relationships belong in **Models**.
   * Display logic belongs in **Blade Views**. Never execute raw database queries in Blade views.
3. **Type Hinting & Return Types:** Enforce strict type hints on all PHP methods and functions.
4. **Eloquent Exclusivity:** Use Eloquent ORM and Query Builder with parameterized queries. Never write unparameterized raw SQL.
5. **Preserve Developer Authorship:** Maintain technical credit headers for **Sindhi Shahejad** across HTTP headers (`X-Developer`), `<head>` metadata, `/humans.txt`, and developer console greeting.
6. **Zero Watermark Policy:** Ensure no vendor watermarks (e.g. Leaflet attribution, Groq API logos) are rendered on the UI.
7. **Test Suite Hygiene:** Every modification must maintain 100% green tests (`php artisan test`).

### ⛔ What Every Developer / Assistant MUST NOT DO:
1. **No Scope Creep:** Never invent unsolicited architectural components or unauthorized dependencies.
2. **No Lazy Code Snippets:** Avoid `// ... existing code ...` placeholders that corrupt source files.
3. **No Breaking Database Overwrites:** Never run destructive migrations in production without backup protocols.
4. **No Bypassing Security / Access Control:** Never bypass authentication, role checks, or CSRF tokens.

---

## 4. Error Handling & Security Protocols

1. **Request Validation First:** Every incoming request must be validated using `$request->validate()` or dedicated Form Request classes before processing.
2. **Mass Assignment Protection:** Every Eloquent model must define an explicit `$fillable` array. Never use `$guarded = []`.
3. **CSRF & XSS Safeguards:**
   * Every HTML form must include `@csrf`.
   * Standard output must use `{{ $variable }}` to escape HTML entities.
   * Only use `{!! !!}` when rendering pre-sanitized Markdown or trusted HTML, and sanitize inputs beforehand.
4. **Database Exceptions:** Wrap all external API requests, file writes, and database transactions in `try-catch` blocks and log failures gracefully.
5. **No Stack Traces in Production:** Never expose raw SQL queries, file paths, or exceptions to users. Flash user-friendly session notifications.

---

## 5. Coding Standards & Reference Implementation

### Controller Implementation Example
```php
namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        try {
            $path = $request->hasFile('attachment')
                ? $request->file('attachment')->store('notices', 'public')
                : null;

            Notice::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'attachment_path' => $path,
                'published_by' => auth()->id(),
            ]);

            return back()->with('success', 'Official circular published successfully.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Unable to publish notice. Please try again.');
        }
    }
}
```

---

## 6. Automated Testing Standards

* All features must have corresponding Feature test suites under `tests/Feature/`.
* Test suites must assert:
  * Route accessibility and HTTP status codes.
  * Role authorization boundaries (unauthorized users redirected/rejected).
  * Database state mutations via `$this->assertDatabaseHas(...)`.
  * Response headers (`X-Developer`, `X-Lead-Architect`).
* The test suite must pass with zero failures:
  ```bash
  php artisan test
  ```
