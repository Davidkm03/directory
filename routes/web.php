<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Installer\InstallerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de prueba para CSS
Route::get('/test-css', function () {
    return view('test');
});

// Rutas del instalador
if (!file_exists(storage_path('installed'))) {
    Route::get('/install', [InstallerController::class, 'index'])->name('installer.index');
    Route::get('/install/config', [InstallerController::class, 'showConfig'])->name('installer.config');
    Route::post('/install/config', [InstallerController::class, 'processConfig'])->name('installer.process-config');
    Route::get('/install/complete', [InstallerController::class, 'complete'])->name('installer.complete');
    
    // Redireccionar a instalador si no está instalado y se intenta acceder a otra ruta
    Route::get('/{any}', function () {
        return redirect()->route('installer.index');
    })->where('any', '.*');
} else {
    // API Routes for SPA
    Route::prefix('api')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
            
            // More protected API routes will go here
        });
    });

    // Auth routes
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('web');
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('web');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('web');
    
    // Admin and setup routes - protected with auth and role middleware
    Route::middleware(['auth:sanctum', 'role:admin', 'throttle:10,1'])->group(function () {
        Route::get('/setup/clear-cache', [\App\Http\Controllers\SetupController::class, 'clearCache']);
        Route::get('/setup/optimize', [\App\Http\Controllers\SetupController::class, 'optimizeCache']);
        Route::get('/setup/rebuild-assets', [\App\Http\Controllers\SetupController::class, 'rebuildAssets']);
    });

    // IMPORTANT: These routes must be BEFORE the SPA fallback

            
    // SPA Fallback - Todas las rutas que no coincidan con las anteriores 
    // se servirán a través del SPA de Vue
    Route::get('/{any}', function () {
        return view('app');
    })->where('any', '.*');
}
