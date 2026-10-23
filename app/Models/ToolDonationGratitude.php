<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolDonationGratitude extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_donation_id',
        'user_id',
        'message',
        'photo_path',
        'likes_count',
    ];

    public function donation()
    {
        return $this->belongsTo(ToolDonation::class, 'tool_donation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }
}
