<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable = ['user_id', 'document_type', 'purpose', 'status', 'admin_notes'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}