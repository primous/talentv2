<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach recommended security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Prévention du détournement de type MIME
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 2. Protection contre le Clickjacking (interdiction totale d'encadrement en iframe)
        $response->headers->set('X-Frame-Options', 'DENY');

        // 3. Politique de transmission du Referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Restriction des APIs et fonctionnalités matérielles du navigateur
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // 5. Forçage strict de HTTPS (HSTS) en environnement sécurisé
        if ($request->isSecure() || app()->environment('production') || str_contains(config('app.url', ''), 'https://')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // 6. Content-Security-Policy (Directive stricte adaptée à la stack Laravel Blade / Alpine.js)
        $cspPolicy = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdn.tailwindcss.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https://images.unsplash.com https:",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $cspPolicy);
        $response->headers->set('Content-Security-Policy-Report-Only', $cspPolicy);

        // 7. Directive Anti-Google Dorking : noindex sur les interfaces privées et sensibles
        if ($request->is('admin*', 'candidat*', 'login*', 'register*', 'mes-demandes*', 'up*')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }

        return $response;
    }
}

