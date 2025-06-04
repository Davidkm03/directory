<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-semibold text-gray-900">Documentos de Verificación</h1>
          <p class="mt-1 text-sm text-gray-500">
            Gestiona tus documentos profesionales para el proceso de verificación
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <router-link 
            :to="{ name: 'dentist-profile' }" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Volver al Perfil
          </router-link>
        </div>
      </div>

      <div v-if="loading" class="mt-6 flex justify-center">
        <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else-if="error" class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ error }}</p>
            <p v-if="!profileExists" class="mt-2 text-sm text-red-700">
              <router-link :to="{ name: 'dentist-profile-edit' }" class="font-medium underline text-red-700 hover:text-red-600">
                Primero debes completar tu perfil profesional
              </router-link>
            </p>
          </div>
        </div>
      </div>

      <div v-else class="mt-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Gestión de Documentos
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Para la verificación de tu perfil, necesitamos validar los siguientes documentos
            </p>
          </div>

          <document-upload 
            :initial-documents="documents" 
            @document-added="handleDocumentAdded"
            @document-deleted="handleDocumentDeleted"
          />
          
          <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
            <div class="bg-blue-50 p-4 rounded-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                  <p class="text-sm text-blue-700">
                    Los documentos deben ser legibles y mostrar claramente tu información. Si algún documento es rechazado, recibirás instrucciones para corregirlo.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado de verificación -->
        <div class="mt-6">
          <verification-status />
        </div>

        <div class="mt-6">
          <router-link 
            v-if="isProfileComplete" 
            :to="{ name: 'dentist-profile-verification' }" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Ver Estado de Verificación
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import { DentistDocument } from '../../../types/dentist';
import dentistProfileService from '../../../services/dentistProfileService';
import DocumentUpload from '../../../components/dentist/DocumentUpload.vue';
import VerificationStatus from '../../../components/dentist/VerificationStatus.vue';

export default defineComponent({
  name: 'DentistProfileDocuments',
  components: {
    DocumentUpload,
    VerificationStatus
  },
  setup() {
    const loading = ref(true);
    const error = ref<string | null>(null);
    const documents = ref<DentistDocument[]>([]);
    const profileExists = ref(false);
    const profileComplete = ref(false);

    const isProfileComplete = computed(() => {
      return profileExists.value && profileComplete.value;
    });

    const loadDocuments = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await dentistProfileService.getProfile();
        
        if (response.data.profile) {
          profileExists.value = true;
          documents.value = response.data.profile.documents || [];
          
          // Verificar si el perfil está completo para habilitar la verificación
          const profile = response.data.profile;
          const requiredFields = [
            'registration_number', 
            'university', 
            'graduation_year',
            'city',
            'state',
            'postal_code',
            'phone_number'
          ];
          
          profileComplete.value = requiredFields.every(field => 
            profile[field] !== undefined && 
            profile[field] !== null &&
            profile[field] !== ''
          );
        } else {
          profileExists.value = false;
          error.value = "Debes completar tu perfil profesional antes de gestionar documentos.";
        }
      } catch (err: any) {
        console.error('Error al cargar documentos:', err);
        if (err.response && err.response.status === 404) {
          profileExists.value = false;
          error.value = "No se ha encontrado un perfil profesional.";
        } else {
          error.value = "Ha ocurrido un error al cargar tus documentos. Por favor, intenta nuevamente.";
        }
      } finally {
        loading.value = false;
      }
    };

    const handleDocumentAdded = (document: DentistDocument) => {
      const index = documents.value.findIndex(d => d.id === document.id);
      if (index !== -1) {
        documents.value.splice(index, 1, document);
      } else {
        documents.value.push(document);
      }
    };

    const handleDocumentDeleted = (documentId: number) => {
      const index = documents.value.findIndex(d => d.id === documentId);
      if (index !== -1) {
        documents.value.splice(index, 1);
      }
    };

    onMounted(loadDocuments);

    return {
      loading,
      error,
      documents,
      profileExists,
      isProfileComplete,
      handleDocumentAdded,
      handleDocumentDeleted
    };
  }
});
</script>
