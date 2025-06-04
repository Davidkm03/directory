<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-semibold text-gray-900">Estado de Verificación</h1>
          <p class="mt-1 text-sm text-gray-500">
            Consulta el estado actual de verificación de tu perfil profesional
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

      <div class="mt-6">
        <verification-status :show-documents="true" @status-loaded="handleStatusLoaded" />
        
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Proceso de Verificación
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Conoce los pasos del proceso de verificación
            </p>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Paso 1</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <div class="flex items-center">
                    <div v-if="profileComplete" class="flex-shrink-0">
                      <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div v-else class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">Completar tu perfil profesional</p>
                      <p class="text-sm text-gray-500">Ingresa toda la información requerida en tu perfil profesional.</p>
                      <router-link v-if="!profileComplete" :to="{ name: 'dentist-profile-edit' }" class="mt-1 text-sm text-blue-600 hover:text-blue-500">
                        Completar perfil
                      </router-link>
                    </div>
                  </div>
                </dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Paso 2</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <div class="flex items-center">
                    <div v-if="documentsComplete" class="flex-shrink-0">
                      <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div v-else class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">Subir documentos de verificación</p>
                      <p class="text-sm text-gray-500">Sube los documentos necesarios para validar tu identidad y credenciales profesionales.</p>
                      <router-link v-if="!documentsComplete" :to="{ name: 'dentist-profile-documents' }" class="mt-1 text-sm text-blue-600 hover:text-blue-500">
                        Gestionar documentos
                      </router-link>
                    </div>
                  </div>
                </dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Paso 3</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <div class="flex items-center">
                    <div v-if="status === 'approved'" class="flex-shrink-0">
                      <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div v-else-if="status === 'rejected'" class="flex-shrink-0">
                      <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div v-else class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">Revisión por el equipo administrativo</p>
                      <p class="text-sm text-gray-500">
                        <span v-if="status === 'approved'">Tu perfil ha sido verificado y aprobado.</span>
                        <span v-else-if="status === 'rejected'">Tu perfil ha sido revisado pero no cumple con los requisitos. Revisa las notas de verificación.</span>
                        <span v-else-if="profileComplete && documentsComplete">En espera de revisión por nuestro equipo. Este proceso puede tomar hasta 48 horas hábiles.</span>
                        <span v-else>Pendiente de completar los pasos anteriores.</span>
                      </p>
                    </div>
                  </div>
                </dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Paso 4</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <div class="flex items-center">
                    <div v-if="status === 'approved'" class="flex-shrink-0">
                      <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div v-else class="flex-shrink-0">
                      <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-gray-900">Perfil activo en la plataforma</p>
                      <p class="text-sm text-gray-500">
                        <span v-if="status === 'approved'">Tu perfil está visible públicamente y disponible para pacientes en la plataforma.</span>
                        <span v-else>Tu perfil estará disponible públicamente una vez que sea verificado y aprobado.</span>
                      </p>
                    </div>
                  </div>
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { DentistDocument } from '../../../types/dentist';
import VerificationStatus from '../../../components/dentist/VerificationStatus.vue';
import dentistProfileService from '../../../services/dentistProfileService';

export default defineComponent({
  name: 'DentistProfileVerification',
  components: {
    VerificationStatus
  },
  setup() {
    const status = ref<'pending' | 'approved' | 'rejected'>('pending');
    const profileComplete = ref(false);
    const documentsComplete = ref(false);
    const documents = ref<DentistDocument[]>([]);

    const handleStatusLoaded = (data: { status: string; documents: DentistDocument[] }) => {
      status.value = data.status as 'pending' | 'approved' | 'rejected';
      if (data.documents) {
        documents.value = data.documents;
        documentsComplete.value = documents.value.length >= 2; // Al menos cédula profesional e identificación
      }
    };

    const checkProfileCompletion = async () => {
      try {
        const response = await dentistProfileService.getProfile();
        if (response.data.profile) {
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
          
          if (profile.documents) {
            documents.value = profile.documents;
            documentsComplete.value = documents.value.length >= 2;
          }
        }
      } catch (err) {
        console.error('Error al verificar la compleción del perfil:', err);
      }
    };

    onMounted(checkProfileCompletion);

    return {
      status,
      profileComplete,
      documentsComplete,
      documents,
      handleStatusLoaded
    };
  }
});
</script>
