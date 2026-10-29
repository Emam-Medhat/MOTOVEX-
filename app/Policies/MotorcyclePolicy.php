<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Motorcycle;

class MotorcyclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function view(User $user, Motorcycle $motorcycle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->canManageBrand($motorcycle->brand);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function update(User $user, Motorcycle $motorcycle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->canManageBrand($motorcycle->brand);
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }
}