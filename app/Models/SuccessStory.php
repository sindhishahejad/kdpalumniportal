<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alumni_name',
        'batch_year',
        'department',
        'story',
        'image_path',
        'is_featured',
    ];
}