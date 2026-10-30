<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enquiry;

class EnquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOperationalManager();
    }

    public function view(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin() || $user->isSalesAgent()
            || ($user->isDataEntry() && $enquiry->motorcycle?->responsibleManagerId() === $user->id);
    }

    public function update(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin() || $user->isSalesAgent()
            || ($user->isDataEntry() && $enquiry->motorcycle?->responsibleManagerId() === $user->id);
    }

    public function delete(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }
}