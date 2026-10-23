<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegionalChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'state',
        'country',
        'region_type',
        'description',
        'lead_user_id',
        'meeting_venue',
        'whatsapp_group_link',
        'contact_email',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'chapter_memberships')
            ->withPivot('role', 'created_at')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(ChapterActivity::class)->orderBy('event_date', 'asc');
    }

    public function isMember(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->members()->where('users.id', $user->id)->exists();
    }

    public static function getMapPins()
    {
        if (static::count() === 0) {
            (new \Database\Seeders\RegionalChapterSeeder())->run();
        }

        return static::withCount('members')
            ->where('is_active', true)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'city' => $c->city,
                    'state' => $c->state,
                    'lat' => $c->latitude,
                    'lng' => $c->longitude,
                    'members_count' => $c->members_count,
                    'url' => route('chapters.show', $c->slug),
                    'venue' => $c->meeting_venue,
                ];
            });
    }

    public static function getClusterStats(): array
    {
        $clusters = [
            'patan' => ['name' => 'Patan & North Gujarat', 'keywords' => ['patan', 'mehsana', 'palanpur', 'siddhpur', 'unjha', 'kadi', 'chhatral'], 'count' => 0],
            'ahmedabad' => ['name' => 'Ahmedabad & Gandhinagar (GIFT City)', 'keywords' => ['ahmedabad', 'gandhinagar', 'gift city', 'sanand'], 'count' => 0],
            'vadodara' => ['name' => 'Vadodara & Central Gujarat', 'keywords' => ['vadodara', 'baroda', 'anand', 'halol', 'bharuch', 'ankleshwar'], 'count' => 0],
            'surat' => ['name' => 'Surat & South Gujarat', 'keywords' => ['surat', 'hazira', 'navsari', 'vapi', 'valsad'], 'count' => 0],
            'saurashtra' => ['name' => 'Rajkot & Saurashtra', 'keywords' => ['rajkot', 'jamnagar', 'bhavnagar', 'morbi', 'junagadh'], 'count' => 0],
            'national' => ['name' => 'Bengaluru, Pune & National Hubs', 'keywords' => ['bangalore', 'bengaluru', 'pune', 'mumbai', 'delhi', 'hyderabad'], 'count' => 0],
            'global' => ['name' => 'Overseas & Global Diaspora', 'keywords' => ['usa', 'canada', 'dubai', 'uae', 'germany', 'uk', 'australia'], 'count' => 0],
        ];

        $locations = Profile::whereNotNull('location')->where('location', '!=', '')->pluck('location');
        foreach ($locations as $loc) {
            $locLower = strtolower($loc);
            foreach ($clusters as &$cluster) {
                foreach ($cluster['keywords'] as $kw) {
                    if (str_contains($locLower, $kw)) {
                        $cluster['count']++;
                        break 2;
                    }
                }
            }
        }
        unset($cluster);

        return $clusters;
    }
}
