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

    // ✨ Messaging Relationships for WhatsApp-style Inbox ✨
    public function sentMessages() 
    { 
        return $this->hasMany(Message::class, 'sender_id'); 
    }

    public function receivedMessages() 
    { 
        return $this->hasMany(Message::class, 'recipient_id'); 
    }
    
    // Helper method to check admin status
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}