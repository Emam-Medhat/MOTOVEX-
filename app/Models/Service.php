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
        'description',
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
}
