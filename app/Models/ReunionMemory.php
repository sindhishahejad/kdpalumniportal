<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionMemory extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_reunion_id',
        'user_id',
        'photo_path',
        'caption',
        'year_taken',
        'likes_count',
    ];

    public function reunion()
    {
        return $this->belongsTo(BatchReunion::class, 'batch_reunion_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
