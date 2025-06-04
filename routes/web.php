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
    
    // Admin and setup routes - Added before the fallback
    Route::get('/setup/clear-cache', [\App\Http\Controllers\SetupController::class, 'clearCache']);
    Route::get('/setup/optimize', [\App\Http\Controllers\SetupController::class, 'optimizeCache']);
    Route::get('/setup/rebuild-assets', [\App\Http\Controllers\SetupController::class, 'rebuildAssets']);
    
    // Usage example: /setup/clear-cache?token=your-secret-token
    // IMPORTANT: These routes must be BEFORE the SPA fallback
    Route::prefix('setup')->group(function () {
        // Basic authentication for these routes
        Route::get('/db', function () {
            // Verify security token
            if (request()->token !== 'eKzPKHshLSTV5tgd') {
                return "Access denied. Invalid token.";
            }
            
            echo "<h1>Database Configuration</h1>";
            
            try {
                // Create initial data
                echo "<h2>Creating initial data...</h2>";
                \Artisan::call('migrate', ['--force' => true]);
                echo "<pre>" . \Artisan::output() . "</pre>";
                
                echo "<h2>Creating admin user...</h2>";
                
                // Create admin user
                $user = \App\Models\User::where('email', 'davidkm0393@gmail.com')->first();
                
                if ($user) {
                    $user->update([
                        'name' => 'Administrador',
                        'password' => \Illuminate\Support\Facades\Hash::make('Dios1234'),
                        'role' => 'admin',
                        'email_verified_at' => now()
                    ]);
                    echo "<p style='color:green;'>Administrator user updated: davidkm0393@gmail.com</p>";
                } else {
                    \App\Models\User::create([
                        'name' => 'Administrador',
                        'email' => 'davidkm0393@gmail.com',
                        'password' => \Illuminate\Support\Facades\Hash::make('Dios1234'),
                        'role' => 'admin',
                        'email_verified_at' => now()
                    ]);
                    echo "<p style='color:green;'>Admin user created: davidkm0393@gmail.com</p>";
                }
                
                echo "<p style='color:green;'>Configuration completed successfully</p>";
                echo "<p><a href='/'>Go to home page</a></p>";
            } catch (\Exception $e) {
                echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
                echo "<p>Database information:</p>";
                echo "<ul>";
                echo "<li>Host: " . config('database.connections.mysql.host') . "</li>";
                echo "<li>Database: " . config('database.connections.mysql.database') . "</li>";
                echo "<li>User: " . config('database.connections.mysql.username') . "</li>";
                echo "</ul>";
            }
            
            return "";
        });
        
        // Database diagnostics
        Route::get('/diagnostics', function () {
            // Verify security token
            if (request()->token !== 'eKzPKHshLSTV5tgd') {
                return "Access denied. Invalid token.";
            }
            
            echo "<h1>Database Diagnostics</h1>";
            
            try {
                // Verify connection
                \DB::connection()->getPdo();
                echo "<p style='color:green;'>Successful connection to: " . \DB::connection()->getDatabaseName() . "</p>";
                
                // Show existing tables
                $tables = [];
                $result = \DB::select('SHOW TABLES');
                
                foreach ($result as $row) {
                    $tables[] = reset($row);
                }
                
                echo "<h2>Existing tables: " . count($tables) . "</h2>";
                
                if (count($tables) > 0) {
                    echo "<ul>";
                    foreach ($tables as $table) {
                        echo "<li>" . $table . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p style='color:orange;'>No tables in the database</p>";
                    echo "<p><a href='/setup/db?token=eKzPKHshLSTV5tgd'>Run migrations</a></p>";
                }
                
                // Show users if the table exists
                if (in_array('users', $tables)) {
                    $users = \App\Models\User::all();
                    echo "<h2>Users: " . count($users) . "</h2>";
                    
                    if (count($users) > 0) {
                        echo "<ul>";
                        foreach ($users as $user) {
                            echo "<li>" . $user->name . " (" . $user->email . ") - Role: " . $user->role . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p style='color:orange;'>No users in the database</p>";
                    }
                }
            } catch (\Exception $e) {
                echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
            }
            
            return "";
        });
    });

    // SPA Fallback - Todas las rutas que no coincidan con las anteriores 
    // se servirán a través del SPA de Vue
    Route::get('/{any}', function () {
        return view('app');
    })->where('any', '.*');
}
