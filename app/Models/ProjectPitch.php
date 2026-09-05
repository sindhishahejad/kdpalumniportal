<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPitch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // <-- Add this line
        'title',
        'description',
        'tech_stack',
        'assistance_needed',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}