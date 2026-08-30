<?php

use App\Http\Controllers\IdCardController;
use App\Http\Controllers\AlumniDirectoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\DonationController;

// ✨ Register the broadcasting authentication route ✨
Broadcast::routes(['middleware' => ['auth']]);

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (Socialite & Email Login)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::post('/login/email', [LoginController::class, 'loginWithEmail'])->name('login.email');

// Onboarding Route
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $role = $request->user()->role;

    if ($role === 'admin') {
        $albums = \App\Models\GalleryAlbum::withCount('photos')->latest()->get();
        $events = \App\Models\Event::orderBy('event_date', 'asc')->get();
        $jobs = \App\Models\JobPosting::latest()->get();
        
        return view('dashboards.admin', compact('albums', 'events', 'jobs'));
        
    } elseif ($role === 'faculty') {
        return view('dashboards.faculty');
        
    } elseif ($role === 'student') {
        return view('dashboards.student');
        
    } elseif ($role === 'alumni') {
        $showcases = \App\Models\JobPosting::where('is_active', true)->latest()->take(4)->get();
        $events = \App\Models\Event::whereDate('event_date', '>=', today())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();
                                
        return view('dashboard', compact('showcases', 'events'));
    }

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery/upload', [\App\Http\Controllers\GalleryController::class, 'store'])->name('gallery.store')->middleware(['auth']);
Route::get('/gallery/{album}/edit', [\App\Http\Controllers\GalleryController::class, 'edit'])->name('gallery.edit')->middleware(['auth']);
Route::put('/gallery/{album}', [\App\Http\Controllers\GalleryController::class, 'update'])->name('gallery.update')->middleware(['auth']);
Route::delete('/gallery/photos/{photo}', [\App\Http\Controllers\GalleryController::class, 'destroyPhoto'])->name('gallery.photos.destroy')->middleware(['auth']);
Route::post('/gallery/{album}/photos', [\App\Http\Controllers\GalleryController::class, 'addPhotos'])->name('gallery.photos.store')->middleware(['auth']);

Route::post('/events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store')->middleware(['auth']);
Route::delete('/events/{event}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy')->middleware(['auth']);
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show')->middleware(['auth']);

Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');
Route::delete('/jobs/{job}', [JobPostingController::class, 'destroy'])->name('jobs.destroy');

// Standard User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/alumni', [AlumniDirectoryController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{user}', [AlumniDirectoryController::class, 'show'])->name('alumni.show');
    
    // ✨ Updated WhatsApp-style Inbox Route with optional user ID parameter ✨
    Route::get('/inbox/{user?}', [MessageController::class, 'inbox'])->name('messages.inbox');
    
    Route::post('/alumni/{user}/message', [MessageController::class, 'store'])->name('alumni.message');

    Route::get('/feed', [PostController::class, 'index'])->name('posts.index');
    Route::post('/feed', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/jobs', [JobPostingController::class, 'index'])->name('jobs.index');
    Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');

    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');

    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::post('/mentorship', [MentorshipController::class, 'store'])->name('mentorship.store');

    Route::get('/id-card', [IdCardController::class, 'show'])->name('id-card.show');

    // ✨ Giving Back & Donation Portal Routes ✨
    Route::get('/giving-back', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/giving-back', [DonationController::class, 'store'])->name('donations.store');
});

// Admin Only Routes 
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::post('/notices', [AdminController::class, 'storeNotice'])->name('notices.store');
    Route::delete('/notices/{notice}', [AdminController::class, 'destroyNotice'])->name('notices.destroy');    
});

require __DIR__.'/auth.php';