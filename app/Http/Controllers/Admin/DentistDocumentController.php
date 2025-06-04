<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentistDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DentistDocumentController extends Controller
{
    /**
     * Actualizar el estado de un documento
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        try {
            $document = DentistDocument::findOrFail($id);
            
            // Actualizar estado y notas
            $document->status = $request->status;
            $document->admin_notes = $request->admin_notes;
            $document->save();
            
            // Añadir URL pública para la respuesta
            $document->document_url = $document->getPublicUrl();
            
            return $document;
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado del documento: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el estado del documento'], 500);
        }
    }

    /**
     * Obtener todos los documentos de un perfil específico
     */
    public function getProfileDocuments($profileId)
    {
        $documents = DentistDocument::where('dentist_profile_id', $profileId)
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        // Añadir URLs públicas
        foreach ($documents as $document) {
            $document->document_url = $document->getPublicUrl();
        }
        
        return $documents;
    }
}
