<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Spare parts catalogue.
 * Admins can manage all records.
 * Approved Merchants can manage only their own records.
 * Publication is always controlled by Admin.
 */
class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'brand_id',
        'name',
        'slug',
        'sku',
        'category',
        'description',
        'compatibility_notes',
        'price',
        'currency',
        'stock_quantity',
        'availability_status',
        'image_path',
        'additional_images',
        'is_published',
    ];

    protected $casts = [
        'is_published'      => 'boolean',
        'price'             => 'decimal:2',
        'stock_quantity'    => 'integer',
        'additional_images' => 'array',
    ];

    // ── Availability statuses ─────────────────────────────────────────────
    const AVAIL_IN_STOCK     = 'in_stock';
    const AVAIL_LOW_STOCK    = 'low_stock';
    const AVAIL_OUT_OF_STOCK = 'out_of_stock';
    const AVAIL_DISCONTINUED = 'discontinued';

    public static array $availabilityStatuses = [
        self::AVAIL_IN_STOCK     => 'In Stock',
        self::AVAIL_LOW_STOCK    => 'Low Stock',
        self::AVAIL_OUT_OF_STOCK => 'Out of Stock',
        self::AVAIL_DISCONTINUED => 'Discontinued',
    ];

    public static array $categories = [
        'engine'          => 'Engine Parts',
        'brakes'          => 'Brakes',
        'suspension'      => 'Suspension',
        'exhaust'         => 'Exhaust',
        'electrical'      => 'Electrical',
        'body'            => 'Body & Fairings',
        'tires_wheels'    => 'Tires & Wheels',
        'transmission'    => 'Transmission',
        'filters'         => 'Filters & Fluids',
        'accessories'     => 'Accessories',
        'other'           => 'Other',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function compatibleMotorcycles(): BelongsToMany
    {
        return $this->belongsToMany(Motorcycle::class, 'spare_part_motorcycle');
    }

    // ── Image helpers ─────────────────────────────────────────────────────

    /**
     * Full public URL for the main image, or null if none uploaded.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/' . ltrim($this->image_path, '/'));
    }

    /**
     * Additional gallery images as full public URLs.
     */
    public function getAdditionalImageUrlsAttribute(): array
    {
        $paths = $this->additional_images ?? [];

        return array_values(array_filter(array_map(function (string $path): ?string {
            if (empty($path)) return null;

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        }, $paths)));
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    /** Only published parts (public-facing). */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** Parts that are not discontinued. */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('availability_status', '!=', self::AVAIL_DISCONTINUED);
    }

    /** Parts owned by a specific user (for Merchant scoping). */
    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        return $query->where('created_by', $user->id);
    }

    /** Admin sees everything; Merchants see only their own. */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('created_by', $user->id);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        return in_array($this->availability_status, [self::AVAIL_IN_STOCK, self::AVAIL_LOW_STOCK], true);
    }

    public function availabilityLabel(): string
    {
        return self::$availabilityStatuses[$this->availability_status] ?? 'Unknown';
    }

    public function categoryLabel(): string
    {
        return self::$categories[$this->category] ?? ($this->category ?? 'Uncategorized');
    }
}
