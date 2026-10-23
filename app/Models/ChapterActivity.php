<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_chapter_id',
        'user_id',
        'title',
        'event_date',
        'venue',
        'description',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function chapter()
    {
        return $this->belongsTo(RegionalChapter::class, 'regional_chapter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
