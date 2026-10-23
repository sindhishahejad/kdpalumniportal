<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallOfFameInductee extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_cycle_id',
        'award_category_id',
        'award_nomination_id',
        'user_id',
        'name',
        'department',
        'batch_year',
        'induction_year',
        'award_title',
        'citation',
        'current_role',
        'company',
        'photo_path',
        'is_featured',
    ];

    protected $casts = [
        'batch_year' => 'integer',
        'induction_year' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function cycle()
    {
        return $this->belongsTo(AwardCycle::class, 'award_cycle_id');
    }

    public function category()
    {
        return $this->belongsTo(AwardCategory::class, 'award_category_id');
    }

    public function nomination()
    {
        return $this->belongsTo(AwardNomination::class, 'award_nomination_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        if ($this->user && $this->user->photo_path) {
            return asset('storage/' . $this->user->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ca8a04&color=fff&size=200';
    }
}
