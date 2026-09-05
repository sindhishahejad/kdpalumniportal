<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'faculty_in_charge',
        'location',
        'equipment_list',
        'description',
    ];
}