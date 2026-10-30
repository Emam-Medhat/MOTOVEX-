<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected static function booted(): void
    {
        static::updated(function (self $order) {
            foreach (['status', 'payment_status'] as $field) {
                if ($order->wasChanged($field)) {
                    WorkflowActivity::record(
                        $order,
                        $field . '_changed',
                        $order->getOriginal($field),
                        $order->{$field}
                    );
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'order_reference',
        'name',
        'email',
        'phone',
        'city',
        'governorate',
        'delivery_method',
        'delivery_address',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_verified_at',
        'payment_verified_by',
        'status',
        'admin_notes',
        'idempotency_key',
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAY_PENDING  = 'pending';
    const PAY_PAID     = 'paid';
    const PAY_FAILED   = 'failed';
    const PAY_REFUNDED = 'refunded';

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'MVX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('order_reference', $ref)->exists());

        return $ref;
    }
}
