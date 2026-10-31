<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect unauthenticated visitors to the login page for every
 * route except the homepage, auth flows, and the language switcher.
 *
 * Pages that remain public (no login required):
 *   /                        → homepage
 *   /login                   → login form + POST
 *   /register                → registration form + POST
 *   /register/pending        → pending merchant notice
 *   /forgot-password         → password reset request
 *   /reset-password/*        → password reset form
 *   /lang/*                  → locale switcher
 */
class RequireAuthForPublicPages
{
    /**
     * Route name prefixes / exact names that are always accessible
     * without authentication.
     */
    private const OPEN_ROUTE_NAMES = [
        'home',
        'login',
        'register',
        'register.pending',
        'password.request',
        'password.email',
        'password.reset',
        'password.store',
        'lang.switch',
        'logout',
    ];

    /**
     * URI prefixes that are always accessible without authentication.
     * Used as a fallback when a route has no name.
     */
    private const OPEN_URI_PREFIXES = [
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'lang',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Already authenticated — let the request through.
        if (auth()->check()) {
            return $next($request);
        }

        // Check by named route.
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::OPEN_ROUTE_NAMES, true)) {
            return $next($request);
        }

        // Check by URI prefix (handles unnamed / catch-all routes).
        $uri = ltrim($request->path(), '/');
        foreach (self::OPEN_URI_PREFIXES as $prefix) {
            if ($uri === $prefix || str_starts_with($uri, $prefix . '/')) {
                return $next($request);
            }
        }

        // Everything else — redirect to login, preserving the intended URL.
        return redirect()->guest(route('login'));
    }
}
