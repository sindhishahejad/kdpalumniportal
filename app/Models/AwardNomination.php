<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardNomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_cycle_id',
        'award_category_id',
        'nominee_user_id',
        'nominator_user_id',
        'nominee_name',
        'nominee_email',
        'nominee_phone',
        'nominee_department',
        'nominee_batch_year',
        'current_organization',
        'designation',
        'achievement_summary',
        'impact_statement',
        'portfolio_url',
        'photo_path',
        'status',
        'is_winner',
        'admin_notes',
    ];

    protected $casts = [
        'nominee_batch_year' => 'integer',
        'is_winner' => 'boolean',
    ];

    public function cycle()
    {
        return $this->belongsTo(AwardCycle::class, 'award_cycle_id');
    }

    public function category()
    {
        return $this->belongsTo(AwardCategory::class, 'award_category_id');
    }

    public function nomineeUser()
    {
        return $this->belongsTo(User::class, 'nominee_user_id');
    }

    public function nominator()
    {
        return $this->belongsTo(User::class, 'nominator_user_id');
    }

    public function votes()
    {
        return $this->hasMany(AwardVote::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        if ($this->nomineeUser && $this->nomineeUser->photo_path) {
            return asset('storage/' . $this->nomineeUser->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nominee_name) . '&background=1e3a8a&color=fff&size=200';
    }
}
