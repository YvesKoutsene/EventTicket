<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // N'oubliez pas d'importer Auth

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    //Ecrit par jean-yves
    /*public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role != $role) {
            return redirect('/home');
        }
        return $next($request);
    }*/

    /*public function handle(Request $request, Closure $next)
    {
        // Insérer ici la logique pour vérifier le rôle de l'utilisateur
        // et rediriger en cas d'accès non autorisé

        return $next($request);
    }*/


    //By Jean-Yves
    /*public function handle(Request $request, Closure $next, ...$roles)
    {
        if ($request->user()) {
            if (in_array($request->user()->role, $roles)) {
                return $next($request);
            }
        }
        abort(403, 'Unauthorized action.');
    }*/

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if ($user && $user->status === 'actif') {
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }

}
