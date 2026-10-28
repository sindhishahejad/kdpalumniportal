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
use App\Http\Controllers\BatchReunionController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\AdminAwardController;
use App\Http\Controllers\ToolDonationController;
use App\Http\Controllers\AdminAlertController;

// ✨ Register the broadcasting authentication route ✨
Broadcast::routes(['middleware' => ['auth']]);

Route::get('/', function () {
    $events = collect();
    $notices = collect();
    $recruiters = collect();
    $schedules = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('events')) {
            $events = \App\Models\Event::orderBy('event_date', 'desc')->take(6)->get();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('notices')) {
            $notices = \App\Models\Notice::where('is_active', true)->latest()->take(8)->get();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('placement_partners')) {
            $recruiters = \App\Models\PlacementPartner::where('is_active', true)->orderBy('order_num')->take(12)->get();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('academic_schedules')) {
            $schedules = \App\Models\AcademicSchedule::where('is_active', true)->orderBy('start_date', 'asc')->take(6)->get();
        }
    } catch (\Throwable $e) {}

    return view('welcome', compact('events', 'notices', 'recruiters', 'schedules'));
});

// Public Contact Form Submission Route
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactInquiryController::class, 'store'])->name('contact.store');

// Public Training & Placement (TPO) Wall & Recruiters Route
Route::get('/placement', [\App\Http\Controllers\PlacementController::class, 'index'])->name('placement.index');

// Public Faculty & HOD Directory Route
Route::get('/faculty', [\App\Http\Controllers\FacultyController::class, 'index'])->name('faculty.index');

// Public GTU Academic Calendar & Circulars Route
Route::get('/academic-calendar', [\App\Http\Controllers\AcademicCalendarController::class, 'index'])->name('calendar.index');

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
        
        $spots = \App\Models\CampusMemorySpot::withCount('stories')->orderBy('order_num')->get();
        $recentStories = \App\Models\CampusMemoryStory::with(['user', 'spot'])->latest()->take(8)->get();
        
        $stats = [
            'total_alumni' => \App\Models\User::where('role', 'alumni')->where('is_approved', true)->count(),
            'pending_jobs' => \App\Models\JobPosting::where('is_active', false)->count(),
            'active_students' => \App\Models\User::where('role', 'student')->where('is_approved', true)->count(),
            'available_tools' => \App\Models\ToolDonation::where('status', 'available')->count(),
            'open_pitches' => \App\Models\ProjectPitch::where('status', 'open')->count(),
            'total_donations' => (float) \App\Models\Donation::where('status', 'completed')->sum('amount'),
            'pending_inquiries' => \App\Models\ContactInquiry::where('status', 'new')->count(),
            'pending_documents' => \App\Models\DocumentRequest::where('status', 'pending')->count(),
        ];
        
        return view('dashboards.admin', compact('albums', 'events', 'jobs', 'pendingUsers', 'users', 'notices', 'stats', 'mapPins', 'clusters', 'spots', 'recentStories'));
        
    } elseif ($role === 'faculty') {
        $stats = [
            'total_resources' => \App\Models\Resource::count(),
            'total_labs' => \App\Models\Lab::count(),
            'open_pitches' => \App\Models\ProjectPitch::where('status', 'open')->count(),
            'upcoming_events' => \App\Models\Event::whereDate('event_date', '>=', today())->count(),
            'total_alumni' => \App\Models\User::where('role', 'alumni')->where('is_approved', true)->count(),
            'available_tools' => \App\Models\ToolDonation::where('status', 'available')->count(),
        ];
        $recentPitches = \App\Models\ProjectPitch::with('student')->latest()->take(3)->get();
        $upcomingEvents = \App\Models\Event::whereDate('event_date', '>=', today())->orderBy('event_date', 'asc')->take(3)->get();

        return view('dashboards.faculty', compact('stats', 'recentPitches', 'upcomingEvents', 'mapPins', 'clusters'));
        
    } elseif ($role === 'student') {
        $stats = [
            'available_tools' => \App\Models\ToolDonation::where('status', 'available')->count(),
            'active_jobs' => \App\Models\JobPosting::where('is_active', true)->count(),
            'upcoming_events' => \App\Models\Event::whereDate('event_date', '>=', today())->count(),
            'total_resources' => \App\Models\Resource::count(),
        ];
        $notices = \App\Models\Notice::where('is_active', true)->latest()->take(5)->get();
        $upcomingEvents = \App\Models\Event::whereDate('event_date', '>=', today())->orderBy('event_date', 'asc')->take(3)->get();
        $availableTools = \App\Models\ToolDonation::where('status', 'available')->with('donor')->latest()->take(3)->get();
        $recentJobs = \App\Models\JobPosting::where('is_active', true)->latest()->take(3)->get();

        return view('dashboards.student', compact('stats', 'notices', 'upcomingEvents', 'availableTools', 'recentJobs', 'mapPins', 'clusters'));
        
    } elseif ($role === 'alumni') {
        $showcases = \App\Models\JobPosting::where('is_active', true)->latest()->take(4)->get();
        $events = \App\Models\Event::orderBy('event_date', 'desc')->take(6)->get();
        $notices = \App\Models\Notice::where('is_active', true)->latest()->take(8)->get();
                                
        return view('dashboard', compact('showcases', 'events', 'notices', 'mapPins', 'clusters'));
    }

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery/upload', [\App\Http\Controllers\GalleryController::class, 'store'])->name('gallery.store')->middleware(['auth']);
Route::get('/gallery/{album}/edit', [\App\Http\Controllers\GalleryController::class, 'edit'])->name('gallery.edit')->middleware(['auth']);
Route::put('/gallery/{album}', [\App\Http\Controllers\GalleryController::class, 'update'])->name('gallery.update')->middleware(['auth']);
Route::delete('/gallery/photos/{photo}', [\App\Http\Controllers\GalleryController::class, 'destroyPhoto'])->name('gallery.photos.destroy')->middleware(['auth']);
Route::post('/gallery/{album}/photos', [\App\Http\Controllers\GalleryController::class, 'addPhotos'])->name('gallery.photos.store')->middleware(['auth']);
Route::post('/gallery/stories', [\App\Http\Controllers\GalleryController::class, 'storeStory'])->name('gallery.stories.store')->middleware(['auth']);
Route::post('/gallery/stories/{story}/like', [\App\Http\Controllers\GalleryController::class, 'likeStory'])->name('gallery.stories.like');

// ✨ Event Routes (Including index for /events 404 fix) ✨
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::post('/events', [EventController::class, 'store'])->name('events.store')->middleware(['auth']);
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update')->middleware(['auth']);
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware(['auth']);
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show')->middleware(['auth']);

// 📖 Digital E-Library & Project Vault (/e-library)
Route::get('/e-library', [\App\Http\Controllers\ELibraryController::class, 'index'])->name('elibrary.index');
Route::get('/e-library/{identifier}', [\App\Http\Controllers\ELibraryController::class, 'show'])->name('elibrary.show');
Route::get('/e-library/{item}/download', [\App\Http\Controllers\ELibraryController::class, 'download'])->name('elibrary.download');
Route::post('/e-library', [\App\Http\Controllers\ELibraryController::class, 'store'])->name('elibrary.store')->middleware('auth');
Route::delete('/e-library/{item}', [\App\Http\Controllers\ELibraryController::class, 'destroy'])->name('elibrary.destroy')->middleware('auth');

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
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');

    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::post('/mentorship', [MentorshipController::class, 'store'])->name('mentorship.store');
    Route::post('/mentorship/{listing}/request', [MentorshipController::class, 'sendRequest'])->name('mentorship.request');
    Route::patch('/mentorship/requests/{mentorshipRequest}/accept', [MentorshipController::class, 'acceptRequest'])->name('mentorship.requests.accept');
    Route::patch('/mentorship/requests/{mentorshipRequest}/complete', [MentorshipController::class, 'completeSession'])->name('mentorship.requests.complete');
    Route::patch('/mentorship/requests/{mentorshipRequest}/decline', [MentorshipController::class, 'declineRequest'])->name('mentorship.requests.decline');
    Route::delete('/mentorship/requests/{mentorshipRequest}', [MentorshipController::class, 'cancelRequest'])->name('mentorship.requests.cancel');

    Route::get('/id-card', [IdCardController::class, 'show'])->name('id-card.show');
    Route::get('/id-card/download', [IdCardController::class, 'download'])->name('id-card.download');

    // ✨ Giving Back & Donation Portal Routes ✨
    Route::get('/giving-back', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/giving-back', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/giving-back/receipt/{donation}', [DonationController::class, 'downloadReceipt'])->name('donations.receipt');
    Route::get('/giving-back/whatsapp/{donation}', [DonationController::class, 'whatsappReceipt'])->name('donations.whatsapp');

    // 💳 Unified Razorpay Payment Gateway Routes (Donations, Pitch Grants, Tool Bank, Reunion Fees)
    Route::post('/payments/create-order', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payments.create-order');
    Route::post('/payments/verify', [\App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payments.verify');

    // 🗺️ Geographic Distribution Map & Regional Chapters
    Route::get('/chapters', [RegionalChapterController::class, 'index'])->name('chapters.index');
    Route::get('/chapters/{chapter:slug}', [RegionalChapterController::class, 'show'])->name('chapters.show');
    Route::post('/chapters/{chapter:slug}/join', [RegionalChapterController::class, 'join'])->name('chapters.join');
    Route::post('/chapters/{chapter:slug}/activities', [RegionalChapterController::class, 'storeActivity'])->name('chapters.activities.store');

    // 🎉 Batch Reunion Planner & Memory Wall
    Route::get('/reunions', [BatchReunionController::class, 'index'])->name('reunions.index');
    Route::post('/reunions', [BatchReunionController::class, 'store'])->name('reunions.store');
    Route::get('/reunions/{reunion:slug}', [BatchReunionController::class, 'show'])->name('reunions.show');
    Route::post('/reunions/{reunion:slug}/rsvp', [BatchReunionController::class, 'rsvp'])->name('reunions.rsvp');
    Route::post('/reunions/{reunion:slug}/memories', [BatchReunionController::class, 'storeMemory'])->name('reunions.memories.store');
    Route::post('/reunions/{reunion:slug}/memories/{memory}/like', [BatchReunionController::class, 'likeMemory'])->name('reunions.memories.like');

    // 🏆 Annual Alumni Awards & Hall of Fame Voting
    Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');
    Route::get('/awards/nominate', [AwardController::class, 'createNomination'])->name('awards.nominate');
    Route::post('/awards/nominate', [AwardController::class, 'storeNomination'])->name('awards.nominate.store');
    Route::get('/awards/category/{slug}', [AwardController::class, 'showCategory'])->name('awards.category');
    Route::post('/awards/vote', [AwardController::class, 'vote'])->name('awards.vote');
    Route::get('/hall-of-fame', [AwardController::class, 'hallOfFame'])->name('awards.hall-of-fame');

    // 📚 Alumni Book & Engineering Tool Donation Bank
    Route::get('/tool-bank', [ToolDonationController::class, 'index'])->name('tools.index');
    Route::get('/tool-bank/donate', [ToolDonationController::class, 'create'])->name('tools.create');
    Route::post('/tool-bank/donate', [ToolDonationController::class, 'store'])->name('tools.store');
    Route::get('/tool-bank/my-donations', [ToolDonationController::class, 'myDonations'])->name('tools.my-donations');
    Route::get('/tool-bank/my-requests', [ToolDonationController::class, 'myRequests'])->name('tools.my-requests');
    Route::get('/tool-bank/{slug}', [ToolDonationController::class, 'show'])->name('tools.show');
    Route::post('/tool-bank/{toolDonation}/request', [ToolDonationController::class, 'requestItem'])->name('tools.request');
    Route::patch('/tool-bank/requests/{donationRequest}/accept', [ToolDonationController::class, 'acceptRequest'])->name('tools.requests.accept');
    Route::patch('/tool-bank/{toolDonation}/handover', [ToolDonationController::class, 'markHandedOver'])->name('tools.handover');
    Route::post('/tool-bank/{toolDonation}/gratitude', [ToolDonationController::class, 'storeGratitude'])->name('tools.gratitude');
    Route::delete('/tool-bank/{toolDonation}', [ToolDonationController::class, 'destroy'])->name('tools.destroy');

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
    Route::put('/notices/{notice}', [AdminController::class, 'updateNotice'])->name('notices.update');
    Route::patch('/notices/{notice}/toggle', [AdminController::class, 'toggleNotice'])->name('notices.toggle');
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
    Route::put('/success-stories/{story}', [SuccessStoryController::class, 'update'])->name('stories.update');
    Route::delete('/success-stories/{story}', [SuccessStoryController::class, 'destroy'])->name('stories.destroy');

    // 🏢 Admin Training & Placement (TPO) Partner Management
    Route::post('/placement/partners', [\App\Http\Controllers\PlacementController::class, 'store'])->name('placement.partners.store');
    Route::put('/placement/partners/{partner}', [\App\Http\Controllers\PlacementController::class, 'update'])->name('placement.partners.update');
    Route::delete('/placement/partners/{partner}', [\App\Http\Controllers\PlacementController::class, 'destroy'])->name('placement.partners.destroy');

    // 👥 Admin Faculty & HOD Directory Management
    Route::post('/faculty', [\App\Http\Controllers\FacultyController::class, 'store'])->name('faculty.store');
    Route::put('/faculty/{faculty}', [\App\Http\Controllers\FacultyController::class, 'update'])->name('faculty.update');
    Route::delete('/faculty/{faculty}', [\App\Http\Controllers\FacultyController::class, 'destroy'])->name('faculty.destroy');

    // 🗓️ Admin GTU Academic Calendar Management
    Route::post('/academic-calendar', [\App\Http\Controllers\AcademicCalendarController::class, 'store'])->name('calendar.store');
    Route::put('/academic-calendar/{schedule}', [\App\Http\Controllers\AcademicCalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/academic-calendar/{schedule}', [\App\Http\Controllers\AcademicCalendarController::class, 'destroy'])->name('calendar.destroy');

    // 🏆 Dynamic Admin Awards Management
    Route::get('/awards', [AdminAwardController::class, 'index'])->name('awards.index');
    Route::post('/awards/cycles', [AdminAwardController::class, 'storeCycle'])->name('awards.cycles.store');
    Route::put('/awards/cycles/{cycle}', [AdminAwardController::class, 'updateCycle'])->name('awards.cycles.update');
    Route::patch('/awards/cycles/{cycle}/status', [AdminAwardController::class, 'updateCycleStatus'])->name('awards.cycles.update-status');
    Route::post('/awards/categories', [AdminAwardController::class, 'storeCategory'])->name('awards.categories.store');
    Route::put('/awards/categories/{category}', [AdminAwardController::class, 'updateCategory'])->name('awards.categories.update');
    Route::delete('/awards/categories/{category}', [AdminAwardController::class, 'destroyCategory'])->name('awards.categories.destroy');
    Route::patch('/awards/nominations/{nomination}/status', [AdminAwardController::class, 'updateNominationStatus'])->name('awards.nominations.update-status');
    Route::post('/awards/nominations/{nomination}/induct', [AdminAwardController::class, 'inductWinner'])->name('awards.nominations.induct');
    Route::post('/awards/inductees', [AdminAwardController::class, 'storeInductee'])->name('awards.inductees.store');
    Route::put('/awards/inductees/{inductee}', [AdminAwardController::class, 'updateInductee'])->name('awards.inductees.update');
    Route::delete('/awards/inductees/{inductee}', [AdminAwardController::class, 'destroyInductee'])->name('awards.inductees.destroy');

    // 📱 Direct WhatsApp & Email Alert Center
    Route::get('/alerts', [AdminAlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts/test-email', [AdminAlertController::class, 'sendTestEmail'])->name('alerts.test-email');
    Route::post('/alerts/whatsapp-broadcast', [AdminAlertController::class, 'dispatchWhatsAppBroadcast'])->name('alerts.whatsapp-broadcast');

    // 🏛️ Dynamic Campus Time-Machine & Landmark Spots Management
    Route::get('/spots', [\App\Http\Controllers\AdminCampusSpotController::class, 'index'])->name('spots.index');
    Route::post('/spots', [\App\Http\Controllers\AdminCampusSpotController::class, 'store'])->name('spots.store');
    Route::put('/spots/{spot}', [\App\Http\Controllers\AdminCampusSpotController::class, 'update'])->name('spots.update');
    Route::delete('/spots/{spot}', [\App\Http\Controllers\AdminCampusSpotController::class, 'destroy'])->name('spots.destroy');
    Route::delete('/spots/stories/{story}', [\App\Http\Controllers\AdminCampusSpotController::class, 'destroyStory'])->name('spots.stories.destroy');
});

// ⚡ AlumSmart Natural Language Query Concierge
Route::get('/api/alum-smart/search', [\App\Http\Controllers\AlumSmartController::class, 'search'])->name('alum-smart.search');

// 🤖 KDP Mitra AI Chatbot (Powered by Groq API)
Route::get('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.message');
Route::get('/chatbot/history', [\App\Http\Controllers\ChatbotController::class, 'history'])->name('chatbot.history');
Route::delete('/chatbot/clear', [\App\Http\Controllers\ChatbotController::class, 'clear'])->name('chatbot.clear');

require __DIR__.'/auth.php';