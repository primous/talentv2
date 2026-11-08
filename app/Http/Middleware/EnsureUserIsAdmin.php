<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Non authentifié.'], 401);
            }

            return redirect()->route('admin.login')->with('warning', 'Veuillez vous connecter avec votre compte administrateur.');
        }

        if (!Auth::user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès interdit. Droits administrateur requis.'], 403);
            }

            if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
                return redirect()->route('admin.login')->with('warning', 'Session candidat active : veuillez vous connecter avec le compte d’administration Talent Club.');
            }

            abort(403, 'Accès interdit. Droits administrateur requis.');
        }

        return $next($request);
    }
}
