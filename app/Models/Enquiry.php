<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    protected $fillable = [
        // Ownership
        'user_id',
        'order_reference',
        // Contact
        'name',
        'email',
        'phone',
        // Content
        'message',
        'subject',
        'enquiry_type',
        'status',
        'preferred_contact_time',
        // Motorcycle link
        'motorcycle_id',
        'motorcycle_snapshot',
        // Pricing snapshot (purchase orders only)
        'unit_price',
        'currency',
        'quantity',
        'total_amount',
        'delivery_fee',
        // Fulfillment
        'delivery_method',
        'delivery_address',
        'city',
        'governorate',
        // Payment
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_verified_at',
        'payment_verified_by',
        // Admin
        'admin_notes',
    ];

    protected $casts = [
        'preferred_contact_time' => 'datetime',
        'payment_verified_at'    => 'datetime',
        'unit_price'             => 'decimal:2',
        'total_amount'           => 'decimal:2',
        'delivery_fee'           => 'decimal:2',
        'quantity'               => 'integer',
    ];

    // ── Enquiry types ────────────────────────────────────────────────────────
    const TYPE_PURCHASE   = 'purchase';
    const TYPE_TEST_DRIVE = 'test_drive';
    const TYPE_SERVICE    = 'service';
    const TYPE_PARTS      = 'parts';
    const TYPE_GENERAL    = 'general';

    public static array $types = [
        self::TYPE_PURCHASE   => 'Purchase Order',
        self::TYPE_TEST_DRIVE => 'Test Drive',
        self::TYPE_SERVICE    => 'Service',
        self::TYPE_PARTS      => 'Parts',
        self::TYPE_GENERAL    => 'General',
    ];

    // ── Enquiry statuses ─────────────────────────────────────────────────────
    const STATUS_PENDING    = 'pending';
    const STATUS_CONTACTED  = 'contacted';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_CANCELLED  = 'cancelled';

    public static array $statuses = [
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_CONTACTED => 'Contacted',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    // ── Payment statuses ─────────────────────────────────────────────────────
    const PAY_PENDING  = 'pending';
    const PAY_VERIFIED = 'verified';
    const PAY_FAILED   = 'failed';
    const PAY_REFUNDED = 'refunded';

    public static array $paymentStatuses = [
        self::PAY_PENDING  => 'Pending',
        self::PAY_VERIFIED => 'Verified',
        self::PAY_FAILED   => 'Failed',
        self::PAY_REFUNDED => 'Refunded',
    ];

    // ── Relationships ────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────
    public function isPurchaseOrder(): bool
    {
        return $this->enquiry_type === self::TYPE_PURCHASE;
    }

    public function isPaymentVerified(): bool
    {
        return $this->payment_status === self::PAY_VERIFIED;
    }

    /**
     * Generate a unique, human-readable order reference.
     * Format: ORD-YYYYMMDD-XXXXX
     */
    public static function generateOrderReference(): string
    {
        do {
            $ref = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (static::where('order_reference', $ref)->exists());

        return $ref;
    }

    // ── Scopes ───────────────────────────────────────────────────────────────
    public function scopePurchaseOrders($query)
    {
        return $query->where('enquiry_type', self::TYPE_PURCHASE);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
