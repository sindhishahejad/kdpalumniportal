<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'semester_scope',
        'start_date',
        'end_date',
        'description',
        'attachment_url',
        'is_important',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_important' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Category label with badge styling
     */
    public function getCategoryBadgeAttribute(): array
    {
        return match ($this->category) {
            'exam' => ['label' => 'GTU Examination', 'color' => 'bg-red-50 text-red-700 border-red-200', 'icon' => '📝'],
            'term' => ['label' => 'Academic Term', 'color' => 'bg-blue-50 text-[#1e3a8a] border-blue-200', 'icon' => '🗓️'],
            'circular' => ['label' => 'University Circular', 'color' => 'bg-amber-50 text-amber-800 border-amber-200', 'icon' => '📜'],
            default => ['label' => 'Academic Schedule', 'color' => 'bg-slate-50 text-slate-700 border-slate-200', 'icon' => '📌'],
        };
    }
}
