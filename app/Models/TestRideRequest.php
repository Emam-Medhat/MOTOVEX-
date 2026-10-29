<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A test-ride enquiry submitted from the homepage mini-form.
 */
class TestRideRequest extends Model
{
    protected $table = 'test_ride_requests';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'motorcycle_id',
        'preferred_showroom',
        'status',
        'ip_address',
    ];

    protected $casts = [
        'motorcycle_id' => 'integer',
    ];

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }
}
