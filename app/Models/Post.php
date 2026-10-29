<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type',
        'question_type',
        'image_path',
        'options',
        'correct_answer',
        'explanation',
        'starter_code',
        'programming_language',
        'difficulty',
        'test_cases',
    ];

    protected $casts = [
        'options' => 'array',
        'test_cases' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add this relationship for threaded discussions:
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions()
    {
        return $this->hasMany(PostSubmission::class);
    }

    public function correctSubmissions()
    {
        return $this->hasMany(PostSubmission::class)->where('is_correct', true);
    }

    public function isMcq(): bool
    {
        return $this->question_type === 'mcq';
    }

    public function isFillBlank(): bool
    {
        return $this->question_type === 'fill_blank';
    }

    public function isCoding(): bool
    {
        return $this->question_type === 'coding';
    }

    public function isInteractiveChallenge(): bool
    {
        return in_array($this->question_type, ['mcq', 'fill_blank', 'coding']);
    }

    public function userSubmission(?int $userId = null)
    {
        $uid = $userId ?? auth()->id();
        if (!$uid) return null;
        return $this->submissions()->where('user_id', $uid)->latest()->first();
    }

    public function hasUserSolved(?int $userId = null): bool
    {
        $uid = $userId ?? auth()->id();
        if (!$uid) return false;
        return $this->submissions()->where('user_id', $uid)->where('is_correct', true)->exists();
    }

    public function successRate(): int
    {
        $total = $this->submissions()->count();
        if ($total === 0) return 0;
        $correct = $this->submissions()->where('is_correct', true)->count();
        return (int) round(($correct / $total) * 100);
    }
}