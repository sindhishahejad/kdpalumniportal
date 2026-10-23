<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusMemoryStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_memory_spot_id',
        'user_id',
        'author_name',
        'batch_year',
        'department',
        'story',
        'photo_path',
        'likes_count',
        'is_approved',
    ];

    public function spot()
    {
        return $this->belongsTo(CampusMemorySpot::class, 'campus_memory_spot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
