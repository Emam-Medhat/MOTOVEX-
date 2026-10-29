<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TestRideRequest extends Model
{
    protected $table = 'test_ride_requests';

    protected $fillable = [
        'booking_reference',
        'user_id',
        'name',
        'phone',
        'email',
        'motorcycle_id',
        'showroom_id',
        'preferred_showroom',
        'preferred_date',
        'preferred_time',
        'notes',
        'admin_notes',
        'status',
        'ip_address',
    ];

    protected $casts = [
        'motorcycle_id' => 'integer',
        'showroom_id'   => 'integer',
        'user_id'       => 'integer',
        'preferred_date' => 'date',
    ];

    // ── Status constants ─────────────────────────────────────────────────────
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static array $statuses = [
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    // ── Boot: auto-generate booking reference ─────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->booking_reference)) {
                $model->booking_reference = static::generateReference();
            }
            if (empty($model->status)) {
                $model->status = self::STATUS_PENDING;
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'TRB-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('booking_reference', $ref)->exists());

        return $ref;
    }

    // ── Relationships ────────────────────────────────────────────────────────
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function showroom(): BelongsTo
    {
        return $this->belongsTo(Showroom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING   => 'badge-amber',
            self::STATUS_CONFIRMED => 'badge-blue',
            self::STATUS_COMPLETED => 'badge-green',
            self::STATUS_CANCELLED => 'badge-red',
            default                => 'badge-muted',
        };
    }
}
