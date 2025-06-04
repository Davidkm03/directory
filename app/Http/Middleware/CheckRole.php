<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        $user = Auth::user();
        
        foreach($roles as $role) {
            // Si el usuario tiene uno de los roles permitidos
            if($user->role === $role) {
                return $next($request);
            }
        }

        // Si llegamos aquí, el usuario no tiene ninguno de los roles permitidos
        return response()->json(['message' => 'Acceso denegado. No tienes permisos para esta operación.'], 403);
    }
}
