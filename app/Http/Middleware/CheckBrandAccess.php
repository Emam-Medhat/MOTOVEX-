<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Brand;

class CheckBrandAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Admin can access all brands
        if ($user->isAdmin()) {
            return $next($request);
        }

        // For Data Entry users, check brand access
        $brandId = $request->route('brand') ?? $request->route('motorcycle')?->brand_id ?? $request->input('brand_id');

        if ($brandId) {
            $brand = Brand::find($brandId);
            
            if ($brand && $user->canManageBrand($brand)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to manage this brand.');
    }
}