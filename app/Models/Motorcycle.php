<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorcycle extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (self $motorcycle) {
            if ($motorcycle->wasChanged('assigned_manager_id')) {
                WorkflowActivity::record(
                    $motorcycle,
                    'manager_assigned',
                    $motorcycle->getOriginal('assigned_manager_id'),
                    $motorcycle->assigned_manager_id
                );
            }
        });
    }

    protected $fillable = [
        'brand_id',
        'created_by',
        'assigned_manager_id',
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
        'rental_price_per_day',
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
        'rental_price_per_day' => 'decimal:2',
        'year'                => 'integer',
        'specs'               => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function responsibleManagerId(): ?int
    {
        return $this->assigned_manager_id ?? $this->created_by;
    }

    public function scopeResponsibleFor(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $query) use ($user) {
            $query->where('assigned_manager_id', $user->id)
                ->orWhere(function (Builder $query) use ($user) {
                    $query->whereNull('assigned_manager_id')
                        ->where('created_by', $user->id);
                });
        });
    }

    public function scopeVisibleToManager(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $query) use ($user) {
            $query->where('created_by', $user->id)
                ->orWhere('assigned_manager_id', $user->id);
        });
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

    /**
     * Return additional images as full URLs, resolving storage paths automatically.
     */
    public function getAdditionalImagesAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        $paths = is_string($value) ? json_decode($value, true) : $value;

        if (! is_array($paths)) {
            return [];
        }

        return array_values(array_filter(array_map(function (string $path): ?string {
            if (empty($path)) return null;

            // Already absolute URL
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            // Relative storage path (e.g. "motorcycles/abc.jpg")
            return asset('storage/' . ltrim($path, '/'));
        }, $paths)));
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
