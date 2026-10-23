<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_code',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class); // Adjust model name if your events model differs
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}