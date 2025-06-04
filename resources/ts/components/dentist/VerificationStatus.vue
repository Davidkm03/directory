<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Estado de Verificación
      </h3>
      <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Estado actual de tu perfil profesional
      </p>
    </div>
    <div class="border-t border-gray-200">
      <div class="px-4 py-5 sm:p-6">
        <div v-if="loading" class="flex justify-center items-center py-4">
          <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-red-700">
                {{ error }}
              </p>
            </div>
          </div>
        </div>

        <div v-else>
          <div class="flex items-center">
            <div class="mr-4">
              <span v-if="status === 'approved'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                  <circle cx="4" cy="4" r="3" />
                </svg>
                Aprobado
              </span>
              <span v-else-if="status === 'rejected'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                  <circle cx="4" cy="4" r="3" />
                </svg>
                Rechazado
              </span>
              <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                  <circle cx="4" cy="4" r="3" />
                </svg>
                Pendiente
              </span>
            </div>
            <span class="text-sm text-gray-500">
              Última actualización: {{ formatDate(updatedAt) }}
            </span>
          </div>

          <div v-if="notes" class="mt-4 bg-gray-50 p-4 rounded-md">
            <h4 class="text-sm font-medium text-gray-900">Notas de verificación:</h4>
            <p class="mt-1 text-sm text-gray-600">{{ notes }}</p>
          </div>

          <div v-if="status === 'approved'" class="mt-4 bg-green-50 p-4 rounded-md">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-700">
                  Tu perfil profesional ha sido verificado y está disponible públicamente en la plataforma.
                </p>
              </div>
            </div>
          </div>

          <div v-else-if="status === 'rejected'" class="mt-4 bg-red-50 p-4 rounded-md">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">
                  Tu perfil ha sido rechazado. Por favor, revisa las notas de verificación y actualiza tu información o documentos según sea necesario.
                </p>
              </div>
            </div>
          </div>

          <div v-else class="mt-4 bg-yellow-50 p-4 rounded-md">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-yellow-700">
                  Tu perfil está en proceso de verificación. Este proceso puede tardar hasta 48 horas hábiles.
                </p>
              </div>
            </div>
          </div>

          <div v-if="showDocuments && documents && documents.length > 0" class="mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Documentos enviados:</h4>
            <ul class="divide-y divide-gray-200">
              <li v-for="doc in documents" :key="doc.id" class="py-3">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">{{ doc.document_type }}</p>
                      <p class="text-xs text-gray-500">{{ doc.original_filename }}</p>
                    </div>
                  </div>
                  <div>
                    <span v-if="doc.status === 'approved'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aprobado</span>
                    <span v-else-if="doc.status === 'rejected'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rechazado</span>
                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, onMounted, computed } from 'vue';
import { DentistDocument } from '../../types/dentist';
import dentistProfileService from '../../services/dentistProfileService';

export default defineComponent({
  name: 'VerificationStatus',
  props: {
    showDocuments: {
      type: Boolean,
      default: true
    }
  },
  emits: ['statusLoaded'],
  setup(props, { emit }) {
    const status = ref<'pending' | 'approved' | 'rejected'>('pending');
    const notes = ref<string | null>(null);
    const documents = ref<DentistDocument[]>([]);
    const loading = ref(true);
    const error = ref<string | null>(null);
    const updatedAt = ref<string | null>(null);
    
    const formatDate = (date: string | null) => {
      if (!date) return 'N/A';
      return new Date(date).toLocaleDateString('es-ES', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
      });
    };

    const loadVerificationStatus = async () => {
      loading.value = true;
      error.value = null;
      
      try {
        const response = await dentistProfileService.getVerificationStatus();
        const data = response.data;
        
        status.value = data.status;
        notes.value = data.notes;
        documents.value = data.documents || [];
        updatedAt.value = data.updated_at || null;
        
        emit('statusLoaded', {
          status: status.value,
          notes: notes.value,
          documents: documents.value
        });
      } catch (err) {
        console.error('Error al cargar el estado de verificación:', err);
        error.value = 'No se pudo cargar el estado de verificación. Por favor, intenta de nuevo más tarde.';
      } finally {
        loading.value = false;
      }
    };

    onMounted(loadVerificationStatus);

    return {
      status,
      notes,
      documents,
      loading,
      error,
      updatedAt,
      formatDate
    };
  }
});
</script>
