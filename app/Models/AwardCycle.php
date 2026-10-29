<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'theme',
        'description',
        'status',
        'nominations_start_at',
        'nominations_end_at',
        'voting_start_at',
        'voting_end_at',
        'ceremony_date',
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'nominations_start_at' => 'datetime',
        'nominations_end_at' => 'datetime',
        'voting_start_at' => 'datetime',
        'voting_end_at' => 'datetime',
        'ceremony_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function categories()
    {
        return $this->hasMany(AwardCategory::class)->orderBy('display_order');
    }

    public function nominations()
    {
        return $this->hasMany(AwardNomination::class);
    }

    public function votes()
    {
        return $this->hasMany(AwardVote::class);
    }

    public function inductees()
    {
        return $this->hasMany(HallOfFameInductee::class);
    }

    public function isNominationsOpen(): bool
    {
        return $this->status === 'nominations_open';
    }

    public function isJuryReview(): bool
    {
        return in_array($this->status, ['draft', 'jury_review']);
    }

    public function isVotingOpen(): bool
    {
        return $this->status === 'voting_open';
    }

    public function isConcluded(): bool
    {
        return $this->status === 'concluded';
    }

    public function currentStepNumber(): int
    {
        return match ($this->status) {
            'nominations_open' => 1,
            'draft', 'jury_review' => 2,
            'voting_open' => 3,
            'concluded' => 4,
            default => 1,
        };
    }

    public function stageLabel(): string
    {
        return match ($this->status) {
            'nominations_open' => 'Stage 1: Open Nominations',
            'draft', 'jury_review' => 'Stage 2: Jury Review & Shortlisting',
            'voting_open' => 'Stage 3: Community Voting Active',
            'concluded' => 'Stage 4: Concluded & Hall of Fame Enshrined',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function daysRemaining(): ?int
    {
        if ($this->isVotingOpen() && $this->voting_end_at) {
            return max(0, (int) now()->diffInDays($this->voting_end_at, false));
        }
        if ($this->isNominationsOpen() && $this->nominations_end_at) {
            return max(0, (int) now()->diffInDays($this->nominations_end_at, false));
        }
        return null;
    }

    public static function makeActive(self $cycle): void
    {
        static::where('id', '!=', $cycle->id)->update(['is_active' => false]);
        $cycle->update(['is_active' => true]);
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->latest('year')->first();
    }
}
