<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class InstallerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si la aplicación ya está instalada, permitir acceso normal
        if (file_exists(storage_path('installed'))) {
            return $next($request);
        }

        // Si estamos intentando acceder al instalador, permitir acceso
        if ($request->is('install') || $request->is('install/*')) {
            return $next($request);
        }

        // Redirigir a la página del instalador
        return redirect()->route('installer.index');
    }
}
