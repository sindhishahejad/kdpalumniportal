<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'category', 'image_path', 'event_date', 'time_display', 'description'
    ];
    
    // Automatically cast the event_date to a Carbon instance so we can format it easily code
    protected $casts = [
        'event_date' => 'date',
    ];
}
