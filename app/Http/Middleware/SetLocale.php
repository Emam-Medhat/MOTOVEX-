<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale — centralized locale resolution.
 *
 * Registered as global web middleware in bootstrap/app.php so every request
 * has its locale resolved before any controller or Blade template executes.
 *
 * Resolution order:
 *   1. Session value (set by the /lang/{locale} switcher route).
 *   2. Authenticated user's saved preferred_locale.
 *   3. Application default (config('app.locale')).
 *
 * The locale is always validated against SUPPORTED so arbitrary values from
 * the session or user record can never break the app.
 */
class SetLocale
{
    /**
     * Locales supported by the application.
     *
     * @var list<string>
     */
    public const SUPPORTED = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);

        // Expose locale + direction to every view so layouts can set
        // <html lang="..." dir="..."> without repeating the logic.
        View::share('locale', $locale);
        View::share('dir', $this->directionFor($locale));

        return $next($request);
    }

    /**
     * Determine the effective locale for this request.
     */
    protected function resolveLocale(Request $request): string
    {
        $sessionLocale = $request->session()->get('locale');

        if ($this->isSupported($sessionLocale)) {
            return $sessionLocale;
        }

        // Fall back to the authenticated user's stored preference and
        // sync it into the session so subsequent requests skip the lookup.
        $user = $request->user();

        if ($user && $this->isSupported($user->preferred_locale ?? null)) {
            $request->session()->put('locale', $user->preferred_locale);

            return $user->preferred_locale;
        }

        $default = config('app.locale');

        return $this->isSupported($default) ? $default : 'en';
    }

    protected function isSupported(?string $locale): bool
    {
        return is_string($locale) && in_array($locale, self::SUPPORTED, true);
    }

    protected function directionFor(string $locale): string
    {
        return $locale === 'ar' ? 'rtl' : 'ltr';
    }
}
