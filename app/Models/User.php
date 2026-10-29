<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    /**
     * Newly instantiated accounts are active unless explicitly suspended.
     */
    protected $attributes = [
        'is_active' => true,
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
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