<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_cycle_id',
        'award_category_id',
        'award_nomination_id',
        'user_id',
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
}
