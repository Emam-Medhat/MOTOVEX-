<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'brand_id',
        'motorcycle_id',
        'title',
        'slug',
        'description',
        'discount_type',
        'discount_value',
        'image_url',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    /**
     * Promotions that are safe to show publicly: active, started and not expired.
     */
    public function scopeLive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
            })
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            });
    }

    /**
     * Whether an admin-entered discount exists that can be priced.
     */
    public function hasPriceableDiscount(): bool
    {
        return $this->motorcycle
            && $this->discount_type
            && $this->discount_value !== null
            && (float) $this->discount_value > 0
            && (float) $this->motorcycle->price > 0;
    }

    /**
     * Discounted price derived from the linked motorcycle's stored price.
     * Never invented — returns null when there is nothing to compute from.
     */
    public function discountedPrice(): ?float
    {
        if (! $this->hasPriceableDiscount()) {
            return null;
        }

        $base  = (float) $this->motorcycle->price;
        $value = (float) $this->discount_value;

        $discounted = $this->discount_type === 'percentage'
            ? $base - ($base * $value / 100)
            : $base - $value;

        return round(max($discounted, 0), 2);
    }

    public function savingsAmount(): ?float
    {
        if (! $this->hasPriceableDiscount()) {
            return null;
        }

        return round((float) $this->motorcycle->price - (float) $this->discountedPrice(), 2);
    }
}
