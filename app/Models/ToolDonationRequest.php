<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolDonationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_donation_id',
        'user_id',
        'reason',
        'status',
        'donor_response',
    ];

    public function donation()
    {
        return $this->belongsTo(ToolDonation::class, 'tool_donation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }
}
