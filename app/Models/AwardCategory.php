<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_cycle_id',
        'name',
        'slug',
        'description',
        'eligibility_criteria',
        'icon',
        'display_order',
    ];

    public function cycle()
    {
        return $this->belongsTo(AwardCycle::class, 'award_cycle_id');
    }

    public function nominations()
    {
        return $this->hasMany(AwardNomination::class);
    }

    public function approvedNominations()
    {
        return $this->hasMany(AwardNomination::class)->where('status', 'approved');
    }

    public function votes()
    {
        return $this->hasMany(AwardVote::class);
    }

    public function inductees()
    {
        return $this->hasMany(HallOfFameInductee::class);
    }

    public function hasUserVoted(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function userVotedNomination(int $userId): ?AwardNomination
    {
        $vote = $this->votes()->where('user_id', $userId)->with('nomination')->first();
        return $vote?->nomination;
    }
}
