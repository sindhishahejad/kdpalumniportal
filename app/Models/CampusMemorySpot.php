<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusMemorySpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'x_percent',
        'y_percent',
        'icon',
        'then_photo_path',
        'then_year',
        'then_title',
        'now_photo_path',
        'now_year',
        'now_title',
        'fun_fact',
        'order_num',
    ];

    public function stories()
    {
        return $this->hasMany(CampusMemoryStory::class)->where('is_approved', true)->latest();
    }
}
