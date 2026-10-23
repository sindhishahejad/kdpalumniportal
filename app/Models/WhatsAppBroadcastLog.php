<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppBroadcastLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_broadcast_logs';

    protected $fillable = [
        'sender_id',
        'recipient_phone',
        'recipient_name',
        'alert_type',
        'message',
        'channel',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
