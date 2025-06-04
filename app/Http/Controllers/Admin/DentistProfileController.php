<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentistProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProfileVerificationNotification;

class DentistProfileController extends Controller
{
    /**
     * Listar perfiles de dentistas para administración
     */
    public function index(Request $request)
    {
        $query = DentistProfile::with('user');

        // Filtrar por estado de verificación si se proporciona
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('verification_status', $request->status);
        }

        // Búsqueda por nombre o email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ordenar resultados (por defecto los más recientes primero)
        $query->orderBy('created_at', 'desc');

        // Paginar resultados
        return $query->paginate(10);
    }

    /**
     * Obtener un perfil de dentista específico con sus documentos
     */
    public function show($id)
    {
        $profile = DentistProfile::with(['user', 'documents'])
                                ->findOrFail($id);
        
        // Añadir URL pública a cada documento
        if ($profile->documents) {
            foreach ($profile->documents as $document) {
                $document->document_url = $document->getPublicUrl();
            }
        }

        return $profile;
    }

    /**
     * Actualizar el estado de verificación de un perfil
     */
    public function updateVerificationStatus(Request $request, $id)
    {
        $request->validate([
            'verification_status' => 'required|in:pending,approved,rejected',
            'verification_notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'send_notification' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $profile = DentistProfile::findOrFail($id);
            
            // Actualizar estado y notas
            $profile->verification_status = $request->verification_status;
            $profile->verification_notes = $request->verification_notes;
            $profile->admin_notes = $request->admin_notes;
            
            // Si el estado es aprobado, registrar la fecha de verificación
            if ($request->verification_status === 'approved') {
                $profile->verified_at = now();
                $profile->verified_by = auth()->id();
            }
            
            $profile->save();
            
            DB::commit();
            
            // Enviar notificación si se solicita
            if ($request->send_notification && $profile->user) {
                Notification::send($profile->user, new ProfileVerificationNotification($profile));
            }
            
            return $profile;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar estado de verificación: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el estado de verificación'], 500);
        }
    }
}
