<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BatchReunion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'batch_year',
        'department',
        'event_date',
        'venue',
        'city',
        'description',
        'milestone_tag',
        'banner_image',
        'fee_per_person',
        'whatsapp_group_link',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'fee_per_person' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rsvps()
    {
        return $this->hasMany(ReunionRsvp::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'reunion_rsvps')
            ->withPivot('status', 'guests_count', 'carpool_note', 'nostalgic_quote')
            ->withTimestamps();
    }

    public function memories()
    {
        return $this->hasMany(ReunionMemory::class)->latest();
    }

    public function userRsvp(?User $user)
    {
        if (!$user) {
            return null;
        }

        return $this->rsvps()->where('user_id', $user->id)->first();
    }
}
