<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enquiry;

class EnquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSalesAgent();
    }

    public function view(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin() || $user->isSalesAgent();
    }

    public function update(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin() || $user->isSalesAgent();
    }

    public function delete(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }
}