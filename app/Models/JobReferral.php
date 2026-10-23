<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'user_id',
        'candidate_name',
        'candidate_email',
        'candidate_phone',
        'candidate_linkedin',
        'resume_path',
        'cover_note',
        'status',
        'feedback',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reviewed' => 'Resume Reviewed',
            'referred' => 'Referred to HR',
            'shortlisted' => 'Shortlisted',
            'declined' => 'Not Referred',
            default => 'Under Review',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
            'referred' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'shortlisted' => 'bg-purple-50 text-purple-700 border-purple-200',
            'declined' => 'bg-gray-100 text-gray-600 border-gray-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}
