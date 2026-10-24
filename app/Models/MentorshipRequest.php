<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorship_listing_id',
        'mentor_id',
        'mentee_id',
        'topic',
        'message',
        'status',
        'meeting_link',
        'session_time',
        'mentor_notes',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(MentorshipListing::class, 'mentorship_listing_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'accepted' => 'Accepted & Scheduled',
            'completed' => 'Completed',
            'declined' => 'Declined',
            default => 'Pending Review',
        };
    }
}
