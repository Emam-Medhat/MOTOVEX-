<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // ── Product type constants ────────────────────────────────────────────
    const TYPE_MOTORCYCLE = 'motorcycle';
    const TYPE_SPARE_PART = 'spare_part';

    // ── Fulfillment status constants ──────────────────────────────────────
    const STATUS_PENDING          = 'pending';
    const STATUS_ACCEPTED         = 'accepted';
    const STATUS_PREPARING        = 'preparing';
    const STATUS_READY            = 'ready_for_shipping';
    const STATUS_SHIPPED          = 'shipped';
    const STATUS_DELIVERED        = 'delivered';
    const STATUS_REJECTED         = 'rejected';
    const STATUS_CANCELLED        = 'cancelled';

    /** Statuses that a merchant can set (ordered workflow). */
    public static array $merchantStatuses = [
        self::STATUS_ACCEPTED  => 'Accepted',
        self::STATUS_PREPARING => 'Preparing',
        self::STATUS_READY     => 'Ready for Shipping',
        self::STATUS_SHIPPED   => 'Shipped',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_REJECTED  => 'Rejected',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    /** Valid forward transitions per status. */
    public static array $transitions = [
        self::STATUS_PENDING   => [self::STATUS_ACCEPTED,  self::STATUS_REJECTED, self::STATUS_CANCELLED],
        self::STATUS_ACCEPTED  => [self::STATUS_PREPARING, self::STATUS_REJECTED, self::STATUS_CANCELLED],
        self::STATUS_PREPARING => [self::STATUS_READY,     self::STATUS_CANCELLED],
        self::STATUS_READY     => [self::STATUS_SHIPPED,   self::STATUS_CANCELLED],
        self::STATUS_SHIPPED   => [self::STATUS_DELIVERED],
        self::STATUS_DELIVERED => [],
        self::STATUS_REJECTED  => [],
        self::STATUS_CANCELLED => [],
    ];

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

                // Stamp fulfillment timestamps
                if ($item->status === self::STATUS_SHIPPED && ! $item->shipped_at) {
                    $item->timestamps = false;
                    $item->shipped_at = now();
                    $item->save();
                    $item->timestamps = true;
                }
                if ($item->status === self::STATUS_DELIVERED && ! $item->delivered_at) {
                    $item->timestamps = false;
                    $item->delivered_at = now();
                    $item->save();
                    $item->timestamps = true;
                }

                // Auto-complete order when all items are delivered or cancelled
                $order = $item->order;
                if ($order) {
                    $activeItems = $order->items()
                        ->whereNotIn('status', [self::STATUS_DELIVERED, self::STATUS_CANCELLED, self::STATUS_REJECTED])
                        ->count();

                    if ($activeItems === 0) {
                        $order->update(['status' => Order::STATUS_COMPLETED]);
                    }
                }
            }
        });
    }

    protected $fillable = [
        'order_id',
        'product_type',
        'motorcycle_id',
        'spare_part_id',
        'merchant_id',
        'motorcycle_snapshot',   // kept for backward compat
        'product_snapshot',      // canonical snapshot going forward
        'sku_snapshot',
        'unit_price',
        'quantity',
        'subtotal',
        'status',
        'fulfillment_notes',
        'rejection_reason',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'status_updated_by',
        'status_updated_at',
    ];

    protected $casts = [
        'unit_price'        => 'decimal:2',
        'subtotal'          => 'decimal:2',
        'quantity'          => 'integer',
        'shipped_at'        => 'datetime',
        'delivered_at'      => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeForMerchant($query, User $user)
    {
        return $query->where('merchant_id', $user->id);
    }

    public function scopeSpareParts($query)
    {
        return $query->where('product_type', self::TYPE_SPARE_PART);
    }

    public function scopeMotorcycles($query)
    {
        return $query->where('product_type', self::TYPE_MOTORCYCLE);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isSparePartItem(): bool
    {
        return $this->product_type === self::TYPE_SPARE_PART;
    }

    public function displayName(): string
    {
        return $this->product_snapshot ?? $this->motorcycle_snapshot ?? '—';
    }

    public function productImageUrl(): ?string
    {
        if ($this->isSparePartItem()) {
            return $this->sparePart?->image_url;
        }
        return $this->motorcycle?->image_url;
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::$transitions[$this->status] ?? [], true);
    }

    /**
     * Validate status transition and update with audit trail.
     * Returns true on success, throws on failure.
     */
    public function transitionStatus(string $newStatus, int $actorId, ?string $notes = null, ?string $rejectionReason = null): void
    {
        if (! $this->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition from '{$this->status}' to '{$newStatus}'."
            );
        }

        if ($newStatus === self::STATUS_REJECTED && empty($rejectionReason)) {
            throw new \InvalidArgumentException('A rejection reason is required.');
        }

        $updates = [
            'status'             => $newStatus,
            'status_updated_by'  => $actorId,
            'status_updated_at'  => now(),
        ];

        if ($notes) {
            $updates['fulfillment_notes'] = $notes;
        }

        if ($rejectionReason) {
            $updates['rejection_reason'] = $rejectionReason;
        }

        $this->update($updates);
    }
}
