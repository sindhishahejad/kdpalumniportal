<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'expertise_areas',
        'description',
        'is_available',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requests()
    {
        return $this->hasMany(MentorshipRequest::class, 'mentorship_listing_id');
    }
}