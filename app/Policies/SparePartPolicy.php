<?php

namespace App\Policies;

use App\Models\SparePart;
use App\Models\User;

/**
 * Spare Part authorization rules.
 *
 * Admin           → full access everywhere (admin area + public).
 * Data Entry      → can view/create/edit/delete in admin area (scoped to their brand assignments).
 * Approved Merchant → create/view/edit/delete their OWN parts only (merchant dashboard).
 * Sales Agent     → view only in admin area.
 * Pending/Rejected Merchant → no access.
 * Customer        → no management access.
 */
class SparePartPolicy
{
    // ── Admin area: who can access /admin/spare-parts ─────────────────────────
    public function viewAny(User $user): bool
    {
        // Admin staff can all access the admin spare parts section
        return $user->isAdmin() || $user->isDataEntry() || $user->isSalesAgent()
            || $user->isApprovedMerchant();
    }

    public function view(User $user, SparePart $sparePart): bool
    {
        if ($user->isAdmin() || $user->isSalesAgent()) {
            return true;
        }

        if ($user->isDataEntry()) {
            return true; // Data entry can view all to manage catalogue
        }

        if ($user->isApprovedMerchant()) {
            return $sparePart->created_by === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDataEntry() || $user->isApprovedMerchant();
    }

    public function update(User $user, SparePart $sparePart): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDataEntry()) {
            return true; // Data entry manages catalogue
        }

        if ($user->isApprovedMerchant()) {
            return $sparePart->created_by === $user->id;
        }

        return false;
    }

    public function delete(User $user, SparePart $sparePart): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDataEntry()) {
            return true;
        }

        // Merchants can delete only their own UNPUBLISHED parts
        if ($user->isApprovedMerchant()) {
            return $sparePart->created_by === $user->id && ! $sparePart->is_published;
        }

        return false;
    }

    /** Only Admins can publish/unpublish spare parts. */
    public function publish(User $user, SparePart $sparePart): bool
    {
        return $user->isAdmin();
    }
}
