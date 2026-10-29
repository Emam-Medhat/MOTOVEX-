<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Promotion;

class PromotionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function view(User $user, Promotion $promotion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$promotion->brand_id) {
            return true; // Allow viewing global promos
        }

        return $user->canManageBrand($promotion->brand);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry();
    }

    public function update(User $user, Promotion $promotion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$promotion->brand_id) {
            return false; // Only admin can update global promos
        }

        return $user->canManageBrand($promotion->brand);
    }

    public function delete(User $user, Promotion $promotion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$promotion->brand_id) {
            return false;
        }

        return $user->canManageBrand($promotion->brand);
    }
}
