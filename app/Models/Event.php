<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'category', 'image_path', 'event_date', 'time_display', 'description'
    ];
    
    // Automatically cast the event_date to a Carbon instance so we can format it easily code here.
    protected $casts = [
        'event_date' => 'date',
    ];

    public function rsvps()
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_rsvps', 'event_id', 'user_id');
    }
}
