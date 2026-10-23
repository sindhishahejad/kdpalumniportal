<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'item_type',
        'department',
        'semester_suitability',
        'condition',
        'description',
        'pickup_method',
        'pickup_location',
        'estimated_value',
        'photo_path',
        'donor_note',
        'status',
        'claimed_by_user_id',
        'claimed_at',
        'handed_over_at',
    ];

    protected $casts = [
        'estimated_value' => 'integer',
        'claimed_at' => 'datetime',
        'handed_over_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function claimedBy()
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function requests()
    {
        return $this->hasMany(ToolDonationRequest::class);
    }

    public function gratitude()
    {
        return $this->hasOne(ToolDonationGratitude::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isClaimed(): bool
    {
        return $this->status === 'claimed';
    }

    public function isHandedOver(): bool
    {
        return $this->status === 'handed_over';
    }

    public function userHasRequested(int $userId): bool
    {
        return $this->requests()->where('user_id', $userId)->exists();
    }

    public function userRequest(int $userId): ?ToolDonationRequest
    {
        return $this->requests()->where('user_id', $userId)->first();
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }

        return match ($this->item_type) {
            'drawing_tools' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=600&q=80',
            'calculators_electronics' => 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=600&q=80',
            'lab_hardware' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            'workshop_safety' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80',
            default => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
        };
    }

    public function getItemTypeLabelAttribute(): string
    {
        return match ($this->item_type) {
            'textbook' => '📚 Engineering Textbook',
            'drawing_tools' => '📐 Mini Drafter & Drawing Kit',
            'calculators_electronics' => '🖩 Scientific Calculator',
            'lab_hardware' => '⚡ Arduino / IoT Hardware Kit',
            'workshop_safety' => '🦺 Workshop Safety Gear',
            default => '📦 Other Engineering Equipment',
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'brand_new' => 'Brand New (Unused)',
            'like_new' => 'Like New (Flawless)',
            'gently_used' => 'Gently Used (Great Condition)',
            'fair' => 'Fair (Usable & Functional)',
            default => ucfirst(str_replace('_', ' ', $this->condition)),
        };
    }

    public function getPickupMethodLabelAttribute(): string
    {
        return match ($this->pickup_method) {
            'campus_locker' => 'Campus Library / Workshop Locker',
            'handover_patan' => 'In-Person Patan Handover',
            'courier' => 'Courier Delivery to Student',
            default => ucfirst(str_replace('_', ' ', $this->pickup_method)),
        };
    }
}
