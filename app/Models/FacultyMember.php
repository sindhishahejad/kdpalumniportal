<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'department',
        'qualification',
        'experience_years',
        'specialization',
        'email',
        'phone',
        'photo_path',
        'is_hod',
        'order_num',
    ];

    protected $casts = [
        'is_hod' => 'boolean',
        'experience_years' => 'integer',
        'order_num' => 'integer',
    ];
}
