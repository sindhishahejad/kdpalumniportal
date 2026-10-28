<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ELibraryItem extends Model
{
    use HasFactory;

    protected $table = 'e_library_items';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'department',
        'semester',
        'academic_year',
        'author_or_team',
        'guide_faculty',
        'subject_code',
        'subject_name',
        'tech_stack',
        'description',
        'file_path',
        'file_name',
        'file_format',
        'file_size',
        'external_url',
        'downloads_count',
        'is_featured',
        'is_approved',
    ];

    protected $casts = [
        'semester' => 'integer',
        'downloads_count' => 'integer',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->slug)) {
                $baseSlug = Str::slug($item->title);
                $uniqueSlug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $uniqueSlug)->exists()) {
                    $uniqueSlug = "{$baseSlug}-" . ($counter++);
                }
                $item->slug = $uniqueSlug;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'ebook' => 'GTU E-Book / Text',
            'capstone_project' => 'Capstone Project Synopsis',
            'lab_manual' => 'Lab Reference Manual',
            default => 'Engineering Resource',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'ebook' => '📖',
            'capstone_project' => '🚀',
            'lab_manual' => '🔬',
            default => '📄',
        };
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'ebook' => 'bg-blue-50 text-[#1e3a8a] border-blue-200',
            'capstone_project' => 'bg-amber-50 text-amber-800 border-amber-200',
            'lab_manual' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    public function getDepartmentIconAttribute(): string
    {
        return match (strtolower($this->department)) {
            'computer' => '💻',
            'mechanical' => '⚙️',
            'electrical' => '⚡',
            'civil' => '🏗️',
            default => '🔬',
        };
    }

    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }
}
