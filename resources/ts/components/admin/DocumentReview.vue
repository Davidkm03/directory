<template>
  <div>
    <div class="border-b border-gray-200 pb-5 mb-5">
      <h3 class="text-lg leading-6 font-medium text-gray-900">Documentos del Dentista</h3>
      <p class="mt-2 max-w-4xl text-sm text-gray-500">
        Revisa cada documento y marca como aprobado o rechazado según corresponda.
      </p>
    </div>

    <div v-if="loading" class="py-6 flex justify-center">
      <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <div v-else-if="documents.length === 0" class="py-6 bg-gray-50 rounded-lg text-center">
      <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No hay documentos</h3>
      <p class="mt-1 text-sm text-gray-500">El dentista aún no ha subido ningún documento.</p>
    </div>

    <div v-else class="space-y-6">
      <div v-for="document in documents" :key="document.id" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ getDocumentTypeLabel(document.document_type) }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Subido el {{ formatDate(document.created_at) }}
            </p>
          </div>
          <div>
            <span
              :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                document.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                document.status === 'approved' ? 'bg-green-100 text-green-800' : 
                'bg-red-100 text-red-800'
              ]"
            >
              {{ getStatusLabel(document.status) }}
            </span>
          </div>
        </div>
        <div class="border-t border-gray-200">
          <div class="px-4 py-5 sm:p-6">
            <div class="mb-4">
              <div class="flex justify-center">
                <div class="w-full p-2 border border-gray-300 rounded-lg overflow-hidden">
                  <a 
                    :href="document.document_url" 
                    target="_blank" 
                    class="flex flex-col items-center p-4 bg-gray-50 rounded hover:bg-gray-100"
                  >
                    <!-- Icono según tipo de documento -->
                    <svg v-if="isPDF(document.document_url)" class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <svg v-else-if="isImage(document.document_url)" class="h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <svg v-else class="h-12 w-12 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    
                    <span class="mt-2 text-sm text-gray-900">Ver documento</span>
                  </a>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <label for="notes" class="block text-sm font-medium text-gray-700">Notas sobre este documento</label>
              <div class="mt-1">
                <textarea
                  :id="'notes-' + document.id"
                  v-model="documentNotes[document.id]"
                  rows="3"
                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Añadir notas o comentarios sobre este documento"
                ></textarea>
              </div>
            </div>

            <div class="mt-4 flex justify-end space-x-3">
              <button
                v-if="document.status !== 'rejected'"
                @click="updateDocumentStatus(document.id, 'rejected')"
                :disabled="isUpdating"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              >
                <svg v-if="isUpdating && updatingDocumentId === document.id" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Rechazar Documento
              </button>

              <button
                v-if="document.status !== 'approved'"
                @click="updateDocumentStatus(document.id, 'approved')"
                :disabled="isUpdating"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                <svg v-if="isUpdating && updatingDocumentId === document.id" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Aprobar Documento
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, computed, watch } from 'vue';
import { DentistDocument } from '../../types/dentist';
import axios from 'axios';

export default defineComponent({
  name: 'DocumentReview',
  props: {
    profileId: {
      type: Number,
      required: true
    },
    initialDocuments: {
      type: Array as PropType<DentistDocument[]>,
      default: () => []
    }
  },
  emits: ['document-updated'],
  setup(props, { emit }) {
    const loading = ref(false);
    const documents = ref<DentistDocument[]>([]);
    const documentNotes = ref<Record<number, string>>({});
    const isUpdating = ref(false);
    const updatingDocumentId = ref<number | null>(null);
    const error = ref<string | null>(null);

    // Inicializar con documentos proporcionados
    watch(() => props.initialDocuments, (newDocs) => {
      documents.value = [...newDocs];
      
      // Inicializar notas para cada documento
      newDocs.forEach(doc => {
        if (doc.admin_notes && !documentNotes.value[doc.id]) {
          documentNotes.value[doc.id] = doc.admin_notes;
        }
      });
    }, { immediate: true });

    const getDocumentTypeLabel = (type: string): string => {
      const labels = {
        'license': 'Cédula Profesional',
        'degree': 'Título Universitario',
        'specialty': 'Certificado de Especialidad',
        'id': 'Identificación Oficial',
        'other': 'Otro Documento'
      };
      return labels[type] || type;
    };

    const getStatusLabel = (status: string): string => {
      switch (status) {
        case 'pending':
          return 'Pendiente';
        case 'approved':
          return 'Aprobado';
        case 'rejected':
          return 'Rechazado';
        default:
          return status;
      }
    };

    const formatDate = (dateString: string): string => {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
      }).format(date);
    };

    const isPDF = (url: string): boolean => {
      return url.toLowerCase().endsWith('.pdf');
    };

    const isImage = (url: string): boolean => {
      const extensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
      return extensions.some(ext => url.toLowerCase().endsWith(ext));
    };

    const updateDocumentStatus = async (documentId: number, status: 'approved' | 'rejected') => {
      isUpdating.value = true;
      updatingDocumentId.value = documentId;
      error.value = null;

      try {
        const response = await axios.patch(`/api/admin/dentist-documents/${documentId}`, {
          status,
          admin_notes: documentNotes.value[documentId] || ''
        });

        // Actualizar documento en la lista local
        const index = documents.value.findIndex(d => d.id === documentId);
        if (index !== -1) {
          documents.value[index] = response.data;
        }

        // Emitir evento para notificar al componente padre
        emit('document-updated', response.data);
      } catch (err) {
        console.error('Error al actualizar estado del documento:', err);
        error.value = 'Error al actualizar el estado del documento. Intenta nuevamente.';
      } finally {
        isUpdating.value = false;
        updatingDocumentId.value = null;
      }
    };

    return {
      loading,
      documents,
      documentNotes,
      isUpdating,
      updatingDocumentId,
      error,
      getDocumentTypeLabel,
      getStatusLabel,
      formatDate,
      isPDF,
      isImage,
      updateDocumentStatus
    };
  }
});
</script>
