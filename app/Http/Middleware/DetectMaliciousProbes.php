<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectMaliciousProbes
{
    /**
     * Patterns de chemins malveillants couramment scannés par Google Dorking ou scanners automatisés.
     *
     * @var array<int, string>
     */
    protected array $probePatterns = [
        '#^/?\.env#i',
        '#^/?\.git#i',
        '#^/?wp-admin#i',
        '#^/?wp-login#i',
        '#^/?phpmyadmin#i',
        '#^/?pma#i',
        '#^/?actuator#i',
        '#^/?telescope#i',
        '#^/?horizon#i',
        '#^/?pulse#i',
        '#^/?phpinfo#i',
        '#^/?server-status#i',
        '#\.(sql|bak|log|key|phar|sh|env|json|lock)$#i',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . ltrim($request->path(), '/');

        // Ne pas intercepter les requêtes légitimes vers .well-known
        if (str_starts_with($path, '/.well-known/')) {
            return $next($request);
        }

        // Vérifier si l'URL correspond à une sonde malveillante ou tentative d'accès Dorking
        foreach ($this->probePatterns as $pattern) {
            if (preg_match($pattern, $path) || preg_match($pattern, $request->getRequestUri())) {
                $ip = $request->ip() ?? '0.0.0.0';
                $userAgent = (string) $request->userAgent();
                $cacheKey = "security:probes:{$ip}";

                $attempts = (int) Cache::get($cacheKey, 0) + 1;
                Cache::put($cacheKey, $attempts, now()->addMinutes(5));

                if ($attempts >= 5) {
                    Log::critical("ALERTE ATTAQUE DORKING / SCAN : L'adresse IP {$ip} a dépassé le seuil de sondes malveillantes sur {$path} [Tentatives: {$attempts}, User-Agent: {$userAgent}]");

                    return response()->json([
                        'error' => 'Accès formellement interdit. Votre adresse IP a été signalée et journalisée.',
                    ], 403, [
                        'X-Robots-Tag' => 'noindex, nofollow, noarchive',
                    ]);
                }

                Log::warning("SONDE MALVEILLANTE INTERCEPTÉE : L'adresse IP {$ip} a tenté d'accéder à la ressource sensible {$path} [User-Agent: {$userAgent}]");

                abort(404);
            }
        }

        return $next($request);
    }
}

