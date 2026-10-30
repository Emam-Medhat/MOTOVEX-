<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

/**
 * Governs who can manage Service records in the Admin CMS.
 * Admin: full access.
 * Data Entry: read + write (no delete).
 * Everyone else: no access.
 */
class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function view(User $user, Service $service): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function update(User $user, Service $service): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->isAdmin();
    }
}
