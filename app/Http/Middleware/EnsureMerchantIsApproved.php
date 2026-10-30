<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks merchant accounts that have not been approved yet.
 *
 * Pending merchants can log in to check their status but may not access
 * any merchant-specific routes (dashboard, catalogue management, etc.).
 * Rejected merchants are also permanently blocked.
 */
class EnsureMerchantIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Only applies to merchant-role users.
        if (! $user->isMerchant()) {
            return $next($request);
        }

        if ($user->isPendingMerchant()) {
            return redirect()->route('merchant.pending')
                ->withErrors(['status' => 'Your merchant account is awaiting administrator approval.']);
        }

        if ($user->isRejectedMerchant()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your merchant application has been rejected. Please contact support.']);
        }

        return $next($request);
    }
}
