<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Showroom;

class ShowroomPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function view(User $user, Showroom $showroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$showroom->brand_id) {
            return true;
        }

        return $user->canManageBrand($showroom->brand);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function update(User $user, Showroom $showroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$showroom->brand_id) {
            return false;
        }

        return $user->canManageBrand($showroom->brand);
    }

    public function delete(User $user, Showroom $showroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$showroom->brand_id) {
            return false;
        }

        return $user->canManageBrand($showroom->brand);
    }
}
