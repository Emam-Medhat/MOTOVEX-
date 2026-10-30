<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Ownership service offering (Test Rides, Financing, Service, etc.).
 * Content is fully data-driven — never hardcoded in Blade.
 */
class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'image_path',
        'description',
        'full_description',
        'cta_label',
        'cta_route',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Fully-qualified URL for the uploaded image (or null when none).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return asset('storage/' . ltrim($this->image_path, '/'));
    }

    /**
     * Resolve the stored icon value to a Font Awesome class.
     * Accepts either a ready "fa-*" class or a legacy Lucide icon name.
     */
    public function getIconClassAttribute(): string
    {
        $icon = trim((string) ($this->icon ?? ''));

        if ($icon === '') {
            return 'fa-star';
        }

        if (str_starts_with($icon, 'fa-')) {
            return $icon;
        }

        $map = [
            'bike'         => 'fa-motorcycle',
            'wallet'       => 'fa-wallet',
            'wrench'       => 'fa-wrench',
            'package'      => 'fa-box-open',
            'shield-check' => 'fa-shield-halved',
            'headset'      => 'fa-headset',
            'sparkles'     => 'fa-wand-magic-sparkles',
        ];

        return $map[$icon] ?? 'fa-star';
    }
}
