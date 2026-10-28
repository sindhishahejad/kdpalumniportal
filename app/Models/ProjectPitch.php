<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPitch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'tech_stack',
        'assistance_needed',
        'funding_goal',
        'funds_raised',
        'status',
    ];

    protected $casts = [
        'funding_goal' => 'decimal:2',
        'funds_raised' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sponsorships()
    {
        return $this->morphMany(Donation::class, 'payable');
    }

    public function percentFunded(): float
    {
        if ($this->funding_goal <= 0) return 0.0;
        return (float) min(100.0, round(($this->funds_raised / $this->funding_goal) * 100, 1));
    }
}