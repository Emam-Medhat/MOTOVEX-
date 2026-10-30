<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'city',
        'preferred_brand_id',
        'password',
        'is_active',
        'newsletter_opted_in',
        'account_status',
        'approved_at',
        'approved_by',
    ];

    /**
     * Newly instantiated accounts are active unless explicitly set otherwise.
     */
    protected $attributes = [
        'is_active'      => true,
        'account_status' => 'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'approved_at'        => 'datetime',
            'password'           => 'hashed',
            'is_active'          => 'boolean',
            'newsletter_opted_in' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withTimestamps();
    }

    public function preferredBrand()
    {
        return $this->belongsTo(Brand::class, 'preferred_brand_id');
    }

    public function createdMotorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class, 'created_by');
    }

    public function managedMotorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class, 'assigned_manager_id');
    }

    public function operationalMotorcycles()
    {
        return Motorcycle::query()->responsibleFor($this);
    }

    public function isOperationalManager(): bool
    {
        return $this->isDataEntry() || $this->isSalesAgent();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isDataEntry(): bool
    {
        return $this->hasRole('data_entry');
    }

    public function isSalesAgent(): bool
    {
        return $this->hasRole('sales_agent');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function isMerchant(): bool
    {
        return $this->hasRole('merchant');
    }

    // ── Account Status Helpers ─────────────────────────────────────────────────

    public function isAccountActive(): bool
    {
        return $this->account_status === 'active';
    }

    public function isAccountPending(): bool
    {
        return $this->account_status === 'pending';
    }

    public function isAccountRejected(): bool
    {
        return $this->account_status === 'rejected';
    }

    /**
     * A merchant is considered approved when their account_status is 'active'.
     */
    public function isApprovedMerchant(): bool
    {
        return $this->isMerchant() && $this->isAccountActive();
    }

    /**
     * A merchant is pending approval.
     */
    public function isPendingMerchant(): bool
    {
        return $this->isMerchant() && $this->isAccountPending();
    }

    /**
     * A merchant was rejected.
     */
    public function isRejectedMerchant(): bool
    {
        return $this->isMerchant() && $this->isAccountRejected();
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopePendingMerchants($query)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('slug', 'merchant'))
                     ->where('account_status', 'pending');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('account_status', $status);
    }

    /**
     * Human readable label for the account's primary role.
     */
    public function roleName(): string
    {
        return $this->roles->first()?->name ?? 'No role';
    }

    /**
     * Staff members are allowed to reach the admin dashboard.
     */
    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isDataEntry() || $this->isSalesAgent();
    }

    public function assignedBrands()
    {
        return $this->brands()->wherePivot('role', 'data_entry');
    }

    public function canManageBrand(Brand $brand): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->assignedBrands()->where('brands.id', $brand->id)->exists();
    }
}