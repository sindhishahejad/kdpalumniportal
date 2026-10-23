<?php

use App\Http\Controllers\IdCardController;
use App\Http\Controllers\AlumniDirectoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobReferralController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\SuccessStoryController;
use App\Http\Controllers\ProjectPitchController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegionalChapterController;

// ✨ Register the broadcasting authentication route ✨
Broadcast::routes(['middleware' => ['auth']]);

Route::get('/', function () {
    return view('welcome');
});

// Public Contact Form Submission Route
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactInquiryController::class, 'store'])->name('contact.store');

// Public Success Stories Route
Route::get('/success-stories', [SuccessStoryController::class, 'index'])->name('stories.index');

// Public Alumni Verification Route (Scanned via Smart ID Card QR)
Route::get('/verify-alumni/{identifier}', [\App\Http\Controllers\IdCardController::class, 'verify'])->name('alumni.verify');

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
    $mapPins = \App\Models\RegionalChapter::getMapPins();
    $clusters = \App\Models\RegionalChapter::getClusterStats();

    if ($role === 'admin') {
        $albums = \App\Models\GalleryAlbum::withCount('photos')->latest()->get();
        $events = \App\Models\Event::orderBy('event_date', 'asc')->get();
        $jobs = \App\Models\JobPosting::latest()->get();
        $pendingUsers = \App\Models\User::where('is_approved', false)->latest()->get();
        $users = \App\Models\User::where('is_approved', true)->with('profile')->latest()->paginate(10);
        $notices = \App\Models\Notice::latest()->get();
        
        $stats = [
            'total_alumni' => \App\Models\User::where('role', 'alumni')->where('is_approved', true)->count(),
            'pending_jobs' => \App\Models\JobPosting::where('is_active', false)->count(),
            'active_students' => \App\Models\User::where('role', 'student')->where('is_approved', true)->count(),
        ];
        
        return view('dashboards.admin', compact('albums', 'events', 'jobs', 'pendingUsers', 'users', 'notices', 'stats', 'mapPins', 'clusters'));
        
    } elseif ($role === 'faculty') {
        $stats = [
            'total_resources' => \App\Models\Resource::count(),
            'total_labs' => \App\Models\Lab::count(),
            'open_pitches' => \App\Models\ProjectPitch::where('status', 'open')->count(),
            'upcoming_events' => \App\Models\Event::whereDate('event_date', '>=', today())->count(),
            'total_alumni' => \App\Models\User::where('role', 'alumni')->where('is_approved', true)->count(),
        ];
        $recentPitches = \App\Models\ProjectPitch::with('student')->latest()->take(3)->get();
        $upcomingEvents = \App\Models\Event::whereDate('event_date', '>=', today())->orderBy('event_date', 'asc')->take(3)->get();

        return view('dashboards.faculty', compact('stats', 'recentPitches', 'upcomingEvents', 'mapPins', 'clusters'));
        
    } elseif ($role === 'student') {
        return view('dashboards.student', compact('mapPins', 'clusters'));
        
    } elseif ($role === 'alumni') {
        $showcases = \App\Models\JobPosting::where('is_active', true)->latest()->take(4)->get();
        $events = \App\Models\Event::whereDate('event_date', '>=', today())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();
                                
        return view('dashboard', compact('showcases', 'events', 'mapPins', 'clusters'));
    }

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery/upload', [\App\Http\Controllers\GalleryController::class, 'store'])->name('gallery.store')->middleware(['auth']);
Route::get('/gallery/{album}/edit', [\App\Http\Controllers\GalleryController::class, 'edit'])->name('gallery.edit')->middleware(['auth']);
Route::put('/gallery/{album}', [\App\Http\Controllers\GalleryController::class, 'update'])->name('gallery.update')->middleware(['auth']);
Route::delete('/gallery/photos/{photo}', [\App\Http\Controllers\GalleryController::class, 'destroyPhoto'])->name('gallery.photos.destroy')->middleware(['auth']);
Route::post('/gallery/{album}/photos', [\App\Http\Controllers\GalleryController::class, 'addPhotos'])->name('gallery.photos.store')->middleware(['auth']);

// ✨ Event Routes (Including index for /events 404 fix) ✨
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::post('/events', [EventController::class, 'store'])->name('events.store')->middleware(['auth']);
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware(['auth']);
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show')->middleware(['auth']);

// Standard User & Event Routes
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
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/jobs', [JobPostingController::class, 'index'])->name('jobs.index');
    Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');
    Route::delete('/jobs/{job}', [JobPostingController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{job}/referral', [JobReferralController::class, 'store'])->name('jobs.referrals.store');
    Route::get('/jobs/pipeline', [JobReferralController::class, 'pipeline'])->name('jobs.pipeline');
    Route::get('/jobs/my-applications', [JobReferralController::class, 'myApplications'])->name('jobs.my-applications');
    Route::get('/jobs/referrals/{referral}/download-resume', [JobReferralController::class, 'downloadResume'])->name('jobs.referrals.download-resume');
    Route::patch('/jobs/referrals/{referral}', [JobReferralController::class, 'updateStatus'])->name('jobs.referrals.update-status');

    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');

    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::post('/mentorship', [MentorshipController::class, 'store'])->name('mentorship.store');
    Route::post('/mentorship/{listing}/request', [MentorshipController::class, 'sendRequest'])->name('mentorship.request');

    Route::get('/id-card', [IdCardController::class, 'show'])->name('id-card.show');
    Route::get('/id-card/download', [IdCardController::class, 'download'])->name('id-card.download');

    // ✨ Giving Back & Donation Portal Routes ✨
    Route::get('/giving-back', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/giving-back', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/giving-back/receipt/{donation}', [DonationController::class, 'downloadReceipt'])->name('donations.receipt');

    // 🗺️ Geographic Distribution Map & Regional Chapters
    Route::get('/chapters', [RegionalChapterController::class, 'index'])->name('chapters.index');
    Route::get('/chapters/{chapter:slug}', [RegionalChapterController::class, 'show'])->name('chapters.show');
    Route::post('/chapters/{chapter:slug}/join', [RegionalChapterController::class, 'join'])->name('chapters.join');
    Route::post('/chapters/{chapter:slug}/activities', [RegionalChapterController::class, 'storeActivity'])->name('chapters.activities.store');

    // ✨ Document Request Routes ✨
    Route::get('/documents', [DocumentRequestController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentRequestController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentRequestController::class, 'download'])->name('documents.download');

    // Project Incubation Routes
    Route::get('/incubation', [ProjectPitchController::class, 'index'])->name('pitches.index');
    Route::get('/incubation/pitch', [ProjectPitchController::class, 'create'])->name('pitches.create');
    Route::post('/incubation/pitch', [ProjectPitchController::class, 'store'])->name('pitches.store');
    Route::get('/incubation/pitch/{pitch}', [ProjectPitchController::class, 'show'])->name('pitches.show');
    Route::patch('/incubation/pitch/{pitch}/status', [ProjectPitchController::class, 'updateStatus'])->name('pitches.update-status');
    Route::post('/incubation/pitch/{pitch}/guidance', [ProjectPitchController::class, 'offerGuidance'])->name('pitches.offer-guidance');
    Route::delete('/incubation/pitch/{pitch}', [ProjectPitchController::class, 'destroy'])->name('pitches.destroy');

    Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
    Route::get('/labs/{lab}', [LabController::class, 'show'])->name('labs.show');
    Route::get('/labs-manage/create', [LabController::class, 'create'])->name('labs.create');
    Route::post('/labs-manage', [LabController::class, 'store'])->name('labs.store');
    Route::get('/labs/{lab}/edit', [LabController::class, 'edit'])->name('labs.edit');
    Route::put('/labs/{lab}', [LabController::class, 'update'])->name('labs.update');
    Route::delete('/labs/{lab}', [LabController::class, 'destroy'])->name('labs.destroy');

    // ✨ Event RSVP & Digital Ticket Routes ✨
    Route::get('/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
    Route::get('/events/ticket/{rsvp}', [EventController::class, 'showTicket'])->name('events.ticket');
    
    // ✨ Notification Read Route ✨
    Route::post('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');
});

// Admin Only Routes 
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::post('/notices', [AdminController::class, 'storeNotice'])->name('notices.store');
    Route::delete('/notices/{notice}', [AdminController::class, 'destroyNotice'])->name('notices.destroy');    

    // ✨ Admin Newsletter Dispatch & Test ✨
    Route::post('/newsletter/send', [AdminController::class, 'sendNewsletter'])->name('newsletter.send');    

    // ✨ Admin Document Request Management ✨
    Route::get('/documents', [DocumentRequestController::class, 'adminIndex'])->name('documents.index');
    Route::put('/documents/{document}', [DocumentRequestController::class, 'update'])->name('documents.update');

    // ✨ Admin Contact Inquiry Management ✨
    Route::get('/inquiries', [ContactInquiryController::class, 'adminIndex'])->name('inquiries.index');
    Route::put('/inquiries/{inquiry}', [ContactInquiryController::class, 'update'])->name('inquiries.update');

    // ✨ Admin Success Stories Management ✨
    Route::get('/success-stories', [SuccessStoryController::class, 'adminIndex'])->name('stories.index');
    Route::post('/success-stories', [SuccessStoryController::class, 'store'])->name('stories.store');
    Route::delete('/success-stories/{story}', [SuccessStoryController::class, 'destroy'])->name('stories.destroy');
});

require __DIR__.'/auth.php';