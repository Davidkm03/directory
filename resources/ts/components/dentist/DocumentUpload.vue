<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Documentos de Verificación
      </h3>
      <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Sube tus documentos profesionales para verificación
      </p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
      <dl class="sm:divide-y sm:divide-gray-200">
        <div v-for="(documentType, index) in documentTypes" :key="index" class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">{{ documentType.label }}</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <div v-if="findDocument(documentType.value)" class="flex items-center justify-between">
              <div>
                <p>{{ findDocument(documentType.value).original_filename }}</p>
                <p class="text-xs text-gray-500">
                  Subido el {{ formatDate(findDocument(documentType.value).created_at) }}
                </p>
              </div>
              <div class="flex items-center">
                <span v-if="findDocument(documentType.value).status === 'approved'" class="mr-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Aprobado
                </span>
                <span v-else-if="findDocument(documentType.value).status === 'rejected'" class="mr-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  Rechazado
                </span>
                <span v-else class="mr-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                  Pendiente
                </span>
                <button 
                  type="button"
                  @click="confirmDelete(findDocument(documentType.value).id)"
                  class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
            <div v-else>
              <div class="flex items-center">
                <div class="w-full">
                  <label 
                    :for="`file-upload-${index}`"
                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                  >
                    <div class="flex max-w-lg justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                      <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="text-sm text-gray-600">
                          <span>Arrastrar archivo aquí o</span>
                          <span class="text-blue-600"> seleccionar archivo</span>
                        </div>
                        <p class="text-xs text-gray-500">PDF, JPG o PNG hasta 10MB</p>
                      </div>
                    </div>
                    <input 
                      :id="`file-upload-${index}`"
                      type="file"
                      class="sr-only"
                      @change="handleFileUpload($event, documentType.value)"
                      accept=".pdf,.jpg,.jpeg,.png"
                    >
                  </label>
                </div>
              </div>
              <p v-if="uploadingFile === documentType.value" class="mt-2 text-sm text-blue-500">
                Subiendo archivo... {{ uploadProgress }}%
              </p>
              <p v-if="uploadErrors[documentType.value]" class="mt-2 text-sm text-red-500">
                {{ uploadErrors[documentType.value] }}
              </p>
            </div>
          </dd>
        </div>
      </dl>
    </div>
    
    <!-- Modal de confirmación -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Eliminar documento
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    ¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" @click="deleteDocument" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Eliminar
            </button>
            <button type="button" @click="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, onMounted, computed, reactive } from 'vue';
import { DentistDocument } from '../../types/dentist';
import dentistProfileService from '../../services/dentistProfileService';

export default defineComponent({
  name: 'DocumentUpload',
  props: {
    initialDocuments: {
      type: Array as PropType<DentistDocument[]>,
      default: () => []
    },
  },
  emits: ['document-added', 'document-deleted', 'update:documents'],
  setup(props, { emit }) {
    const documents = ref<DentistDocument[]>(props.initialDocuments);
    const documentTypes = [
      { value: 'license', label: 'Licencia Profesional' },
      { value: 'degree', label: 'Título Universitario' },
      { value: 'specialty', label: 'Certificado de Especialidad' },
      { value: 'identification', label: 'Identificación Oficial' }
    ];
    
    const uploadingFile = ref('');
    const uploadProgress = ref(0);
    const uploadErrors = reactive<Record<string, string>>({});
    const showDeleteModal = ref(false);
    const documentToDelete = ref<number | null>(null);

    const findDocument = (documentType: string) => {
      return documents.value.find(doc => doc.document_type === documentType);
    };

    const formatDate = (date: string | undefined) => {
      if (!date) return 'N/A';
      return new Date(date).toLocaleDateString('es-ES');
    };

    const handleFileUpload = async (event: Event, documentType: string) => {
      const target = event.target as HTMLInputElement;
      if (!target.files || target.files.length === 0) return;
      
      const file = target.files[0];
      
      // Validaciones de archivo
      if (file.size > 10 * 1024 * 1024) {
        uploadErrors[documentType] = 'El archivo no debe superar los 10MB';
        return;
      }
      
      const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
      if (!validTypes.includes(file.type)) {
        uploadErrors[documentType] = 'Formato de archivo no válido. Sólo PDF, JPG o PNG';
        return;
      }
      
      uploadingFile.value = documentType;
      uploadProgress.value = 0;
      uploadErrors[documentType] = '';
      
      const formData = new FormData();
      formData.append('document', file);
      formData.append('document_type', documentType);
      
      try {
        // Simular progreso de carga
        const interval = setInterval(() => {
          if (uploadProgress.value < 90) {
            uploadProgress.value += 10;
          }
        }, 300);
        
        const response = await dentistProfileService.uploadDocument(formData);
        clearInterval(interval);
        uploadProgress.value = 100;
        
        // Agregar el documento a la lista
        const newDocument = response.data;
        
        // Reemplazar si ya existe
        const existingIndex = documents.value.findIndex(doc => doc.document_type === documentType);
        if (existingIndex !== -1) {
          documents.value.splice(existingIndex, 1, newDocument);
        } else {
          documents.value.push(newDocument);
        }
        
        emit('document-added', newDocument);
        emit('update:documents', documents.value);
        
        // Resetear después de éxito
        setTimeout(() => {
          uploadingFile.value = '';
          uploadProgress.value = 0;
        }, 1000);
      } catch (error) {
        clearInterval(interval);
        console.error('Error al subir el documento:', error);
        uploadErrors[documentType] = 'Error al subir el documento. Intente nuevamente.';
        uploadingFile.value = '';
      }
    };

    const confirmDelete = (documentId: number) => {
      documentToDelete.value = documentId;
      showDeleteModal.value = true;
    };

    const cancelDelete = () => {
      showDeleteModal.value = false;
      documentToDelete.value = null;
    };

    const deleteDocument = async () => {
      if (!documentToDelete.value) return;
      
      try {
        await dentistProfileService.deleteDocument(documentToDelete.value);
        
        // Eliminar el documento de la lista
        const index = documents.value.findIndex(doc => doc.id === documentToDelete.value);
        if (index !== -1) {
          documents.value.splice(index, 1);
        }
        
        emit('document-deleted', documentToDelete.value);
        emit('update:documents', documents.value);
      } catch (error) {
        console.error('Error al eliminar el documento:', error);
      } finally {
        showDeleteModal.value = false;
        documentToDelete.value = null;
      }
    };

    onMounted(() => {
      // Si no hay documentos iniciales, intentar cargarlos
      if (documents.value.length === 0) {
        dentistProfileService.getProfile()
          .then(response => {
            if (response.data.documents) {
              documents.value = response.data.documents;
              emit('update:documents', documents.value);
            }
          })
          .catch(error => {
            console.error('Error al cargar los documentos:', error);
          });
      }
    });

    return {
      documents,
      documentTypes,
      uploadingFile,
      uploadProgress,
      uploadErrors,
      showDeleteModal,
      documentToDelete,
      findDocument,
      formatDate,
      handleFileUpload,
      confirmDelete,
      cancelDelete,
      deleteDocument
    };
  }
});
</script>
