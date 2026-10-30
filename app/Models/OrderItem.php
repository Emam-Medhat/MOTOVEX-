<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected static function booted(): void
    {
        static::updated(function (self $item) {
            if ($item->wasChanged('status')) {
                WorkflowActivity::record(
                    $item,
                    'fulfillment_status_changed',
                    $item->getOriginal('status'),
                    $item->status
                );
            }
        });
    }

    protected $fillable = [
        'order_id',
        'motorcycle_id',
        'merchant_id',
        'motorcycle_snapshot',
        'unit_price',
        'quantity',
        'subtotal',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function scopeForMerchant($query, User $user)
    {
        return $query->where('merchant_id', $user->id);
    }
}
