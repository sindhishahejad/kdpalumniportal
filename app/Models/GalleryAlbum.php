<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = ['title'];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class);
    }
}