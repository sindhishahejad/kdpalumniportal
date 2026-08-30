<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'user_id',
        'graduation_year',
        'degree',
        'department',
        'current_company',
        'job_title',
        'location',
        'bio',
        'photo_path',
        'is_phone_public',
        'is_email_public',
    ];

    protected $casts = [
        'is_phone_public' => 'boolean',
        'is_email_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}