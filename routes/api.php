<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DentistProfileController;
use App\Http\Controllers\Admin\DentistVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas de autenticación
Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/dentist', [AuthController::class, 'registerDentist']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas del directorio de dentistas
Route::prefix('directory')->group(function () {
    Route::get('/', [\App\Http\Controllers\DirectoryController::class, 'index']);
    Route::get('/filter-options', [\App\Http\Controllers\DirectoryController::class, 'getFilterOptions']);
    Route::get('/{id}', [\App\Http\Controllers\DirectoryController::class, 'show']);
});

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener el usuario autenticado
    Route::get('/user', [AuthController::class, 'user']);
    
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rutas específicas para administradores
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Panel de administración']);
        });
        
        // Rutas antiguas de verificación (mantener por compatibilidad)
        Route::prefix('verification')->group(function () {
            Route::get('/statistics', [DentistVerificationController::class, 'getStatistics']);
            Route::get('/pending', [DentistVerificationController::class, 'getPendingProfiles']);
            Route::get('/profile/{id}', [DentistVerificationController::class, 'getProfile']);
            Route::put('/profile/{id}/approve', [DentistVerificationController::class, 'approveProfile']);
            Route::put('/profile/{id}/reject', [DentistVerificationController::class, 'rejectProfile']);
            Route::put('/document/{documentId}/approve', [DentistVerificationController::class, 'approveDocument']);
            Route::put('/document/{documentId}/reject', [DentistVerificationController::class, 'rejectDocument']);
        });
        
        // Nuevas rutas para gestión de perfiles dentales
        Route::get('/dentist-profiles', [\App\Http\Controllers\Admin\DentistProfileController::class, 'index']);
        Route::get('/dentist-profiles/{id}', [\App\Http\Controllers\Admin\DentistProfileController::class, 'show']);
        Route::patch('/dentist-profiles/{id}/verification', [\App\Http\Controllers\Admin\DentistProfileController::class, 'updateVerificationStatus']);
        
        // Rutas para gestión de documentos
        Route::patch('/dentist-documents/{id}', [\App\Http\Controllers\Admin\DentistDocumentController::class, 'updateStatus']);
        Route::get('/dentist-profiles/{profileId}/documents', [\App\Http\Controllers\Admin\DentistDocumentController::class, 'getProfileDocuments']);
    });
    
    // Rutas específicas para dentistas
    Route::middleware('role:dentist,admin')->prefix('dentist')->group(function () {
        // Rutas de perfil de dentista
        Route::get('/profile', [DentistProfileController::class, 'index']);
        Route::post('/profile', [DentistProfileController::class, 'store']);
        Route::put('/profile', [DentistProfileController::class, 'update']);
        Route::get('/verification-status', [DentistProfileController::class, 'verificationStatus']);
        
        // Rutas de documentos
        Route::post('/document', [DentistProfileController::class, 'uploadDocument']);
        Route::delete('/document/{documentId}', [DentistProfileController::class, 'removeDocument']);
    });
    
    // Rutas específicas para pacientes
    Route::middleware('role:patient,admin')->prefix('patient')->group(function () {
        // Favoritos
        Route::get('/favorites', [\App\Http\Controllers\DirectoryController::class, 'getFavorites']);
        Route::post('/favorites/{id}', [\App\Http\Controllers\DirectoryController::class, 'toggleFavorite']);
    });
    
    // Rutas públicas para perfiles de dentistas verificados
    Route::get('/dentist/public-profile/{userId}', [DentistProfileController::class, 'publicProfile']);
});
