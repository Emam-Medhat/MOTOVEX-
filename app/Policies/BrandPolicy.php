<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Brand;

class BrandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function view(User $user, Brand $brand): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->canManageBrand($brand);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Brand $brand): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false; // Data Entry users cannot update brands
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->isAdmin();
    }

    public function assignUsers(User $user): bool
    {
        return $user->isAdmin();
    }

    public function manage(User $user, Brand $brand): bool
    {
        return $user->isAdmin() || $user->canManageBrand($brand);
    }
}