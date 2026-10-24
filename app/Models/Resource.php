<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'department',
        'semester',
        'subject',
        'description',
        'url',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileExtensionAttribute(): ?string
    {
        if (!$this->file_path) return null;
        return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->file_extension === 'PDF';
    }
}