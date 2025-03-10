<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et a le rôle d'administrateur
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            // Rediriger vers le tableau de bord avec un message d'erreur
            return redirect()->route('dashboard')
                ->with('error', 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page.');
        }

        return $next($request);
    }
}
