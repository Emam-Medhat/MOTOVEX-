<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motorcycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'model_name',
        'slug',
        'short_description',
        'description',
        'image_url',
        'image_path',
        'additional_images',
        'specs',
        'engine_capacity',
        'horsepower',
        'torque',
        'transmission',
        'price',
        'currency',
        'year',
        'category',
        'availability_status',
        'condition',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'is_published'        => 'boolean',
        'is_featured'         => 'boolean',
        'engine_capacity'     => 'decimal:2',
        'price'               => 'decimal:2',
        'year'                => 'integer',
        'specs'               => 'array',
        'additional_images'   => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // Return uploaded image if available, otherwise fall back to image_url
    public function getImageUrlAttribute($value): ?string
    {
        // Uploaded file via admin panel — served from storage
        if ($this->attributes['image_path'] ?? null) {
            return asset('storage/' . $this->attributes['image_path']);
        }

        if (! $value) {
            return null;
        }

        // Already absolute URL (external) — return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // Relative path starting with / — build absolute URL using asset()
        if (str_starts_with($value, '/')) {
            return asset(ltrim($value, '/'));
        }

        return $value;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereHas('brand', fn ($q) => $q->where('is_published', true));
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
