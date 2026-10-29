<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'blood_group',
        'degree',
        'department',
        'year_joining',
        'graduation_year',
        'entry_no',
        'company',
        'designation',
        'work_industry',
        'skills',
        'photo_path',
        'is_approved', // Added for Admin Approval Workflow
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean', // Cast to boolean
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function postSubmissions()
    {
        return $this->hasMany(PostSubmission::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    // New relationship for the Mentorship Navigator
    public function mentorshipListing()
    {
        return $this->hasOne(MentorshipListing::class);
    }

    public function mentorshipRequestsSent()
    {
        return $this->hasMany(MentorshipRequest::class, 'mentee_id');
    }

    public function mentorshipRequestsReceived()
    {
        return $this->hasMany(MentorshipRequest::class, 'mentor_id');
    }

    // ✨ Messaging Relationships for WhatsApp-style Inbox ✨
    public function sentMessages() 
    { 
        return $this->hasMany(Message::class, 'sender_id'); 
    }

    public function receivedMessages() 
    { 
        return $this->hasMany(Message::class, 'recipient_id'); 
    }

    /**
     * Get count of unread incoming messages.
     */
    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function jobReferrals()
    {
        return $this->hasMany(JobReferral::class);
    }

    public function chapters()
    {
        return $this->belongsToMany(RegionalChapter::class, 'chapter_memberships')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function organizedReunions()
    {
        return $this->hasMany(BatchReunion::class);
    }

    public function reunionRsvps()
    {
        return $this->hasMany(ReunionRsvp::class);
    }

    public function awardNominationsMade()
    {
        return $this->hasMany(AwardNomination::class, 'nominator_user_id');
    }

    public function awardNominationsReceived()
    {
        return $this->hasMany(AwardNomination::class, 'nominee_user_id');
    }

    public function awardVotes()
    {
        return $this->hasMany(AwardVote::class);
    }

    public function hallOfFameInductions()
    {
        return $this->hasMany(HallOfFameInductee::class);
    }

    public function toolDonations()
    {
        return $this->hasMany(ToolDonation::class);
    }

    public function claimedToolDonations()
    {
        return $this->hasMany(ToolDonation::class, 'claimed_by_user_id');
    }

    public function toolDonationRequests()
    {
        return $this->hasMany(ToolDonationRequest::class);
    }

    public function toolGratitudes()
    {
        return $this->hasMany(ToolDonationGratitude::class);
    }
    
    // Helper method to check admin status
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}