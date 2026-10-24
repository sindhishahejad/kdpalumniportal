<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sector',
        'package_range',
        'roles_offered',
        'hiring_departments',
        'website_url',
        'logo_url',
        'is_active',
        'order_num',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    /**
     * Get array of hiring departments
     */
    public function getDepartmentsListAttribute(): array
    {
        if (empty($this->hiring_departments)) {
            return [];
        }

        return array_map('trim', explode(',', $this->hiring_departments));
    }
}
