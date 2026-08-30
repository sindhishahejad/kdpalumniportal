<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'image_path', 'is_featured'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
