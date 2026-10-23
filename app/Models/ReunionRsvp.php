<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionRsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_reunion_id',
        'user_id',
        'status',
        'guests_count',
        'carpool_note',
        'nostalgic_quote',
    ];

    public function reunion()
    {
        return $this->belongsTo(BatchReunion::class, 'batch_reunion_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'attending' => 'Confirmed Attending 🎉',
            'maybe' => 'Interested / Tentative 🤔',
            'declined' => 'Cannot Make It 😢',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'attending' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'maybe' => 'bg-amber-50 text-amber-700 border-amber-200',
            'declined' => 'bg-gray-100 text-gray-500 border-gray-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }
}
