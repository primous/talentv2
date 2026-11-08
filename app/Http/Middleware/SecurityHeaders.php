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

        // 2. Protection contre le Clickjacking (encadrement dans des iframes non autorisées)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. Politique de transmission du Referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Restriction des APIs et fonctionnalités matérielles du navigateur
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // 5. Forçage strict de HTTPS (HSTS) en environnement sécurisé
        if ($request->isSecure() || app()->environment('production') || str_contains(config('app.url', ''), 'https://')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // 6. Content-Security-Policy (Mode Report-Only adapté aux dépendances du projet)
        $cspPolicy = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https://images.unsplash.com",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy-Report-Only', $cspPolicy);

        return $response;
    }
}
