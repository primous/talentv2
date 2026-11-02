<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale (fr or en).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Query parameter (?lang=fr ou ?lang=en)
        if ($request->has('lang')) {
            $candidate = strtolower(trim((string) $request->query('lang')));
            if (in_array($candidate, ['fr', 'en'])) {
                $locale = $candidate;
                session(['locale' => $locale]);
                cookie()->queue(cookie('talent_locale', $locale, 60 * 24 * 365, '/', null, false, false));
            }
        }

        // 2. Session locale
        if (!$locale && session()->has('locale')) {
            $candidate = strtolower((string) session('locale'));
            if (in_array($candidate, ['fr', 'en'])) {
                $locale = $candidate;
            }
        }

        // 3. Cookie talent_locale
        if (!$locale && $request->hasCookie('talent_locale')) {
            $candidate = strtolower((string) $request->cookie('talent_locale'));
            if (in_array($candidate, ['fr', 'en'])) {
                $locale = $candidate;
                session(['locale' => $locale]);
            }
        }

        // 4. Fallback: app config or default to 'fr'
        if (!$locale) {
            $locale = config('app.locale', 'fr');
            if (!in_array($locale, ['fr', 'en'])) {
                $locale = 'fr';
            }
        }

        App::setLocale($locale);

        $response = $next($request);

        // Ensure the cookie is queued if not already present
        if (!$request->hasCookie('talent_locale') || $request->cookie('talent_locale') !== $locale) {
            $response->headers->setCookie(cookie('talent_locale', $locale, 60 * 24 * 365, '/', null, false, false));
        }

        return $response;
    }
}
