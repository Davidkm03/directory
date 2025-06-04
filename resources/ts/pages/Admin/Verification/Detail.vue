<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-semibold text-gray-900">Detalle de Verificación</h1>
          <p class="mt-1 text-sm text-gray-500">
            Revisión detallada del perfil profesional y documentos
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <router-link 
            :to="{ name: 'admin-dentist-verification' }" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Volver a la Lista
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
          </div>
        </div>
      </div>

      <div v-else class="mt-6 space-y-6">
        <!-- Información básica del dentista -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Información del Dentista
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Datos personales y profesionales
              </p>
            </div>
            <span
              :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                profile.verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                profile.verification_status === 'approved' ? 'bg-green-100 text-green-800' : 
                'bg-red-100 text-red-800'
              ]"
            >
              {{ getStatusLabel(profile.verification_status) }}
            </span>
          </div>
          <div class="border-t border-gray-200">
            <dl>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.user?.name }}</dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.user?.email }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Cédula Profesional</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.registration_number }}</dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Universidad</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.university }} ({{ profile.graduation_year }})</dd>
              </div>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Especialidad</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.specialty || 'No especificada' }}</dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Años de experiencia</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.experience_years || 'No especificado' }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  {{ profile.city }}, {{ profile.state }}, {{ profile.postal_code }}
                  <div v-if="profile.office_address" class="mt-1">{{ profile.office_address }}</div>
                </dd>
              </div>
              <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Contacto</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <div>{{ profile.phone_number }}</div>
                  <div v-if="profile.website_url" class="mt-1">
                    <a :href="profile.website_url" target="_blank" class="text-blue-600 hover:text-blue-500">
                      {{ profile.website_url }}
                    </a>
                  </div>
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Servicios y Especialidades -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Servicios y Especialidades
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Servicios ofrecidos y áreas de especialidad
            </p>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <div v-if="profile.services && profile.services.length > 0">
              <h4 class="text-sm font-medium text-gray-500 mb-2">Servicios ofrecidos:</h4>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="service in profile.services" 
                  :key="service" 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                  {{ service }}
                </span>
              </div>
            </div>
            <div v-else class="text-sm text-gray-500">No se han especificado servicios</div>

            <div v-if="profile.languages && profile.languages.length > 0" class="mt-4">
              <h4 class="text-sm font-medium text-gray-500 mb-2">Idiomas:</h4>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="language in profile.languages" 
                  :key="language" 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                >
                  {{ language }}
                </span>
              </div>
            </div>

            <div v-if="profile.insurance_accepted" class="mt-4">
              <h4 class="text-sm font-medium text-gray-500 mb-2">Acepta seguros:</h4>
              <p class="text-sm text-gray-900">{{ profile.insurance_accepted }}</p>
            </div>
          </div>
        </div>

        <!-- Revisión de documentos -->
        <document-review 
          :profile-id="profileId" 
          :initial-documents="profile.documents || []"
          @document-updated="handleDocumentUpdated"
        />

        <!-- Formulario de verificación -->
        <verification-form 
          :profile="profile"
          @status-updated="handleStatusUpdated"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { DentistProfile, DentistDocument } from '../../../types/dentist';
import DocumentReview from '../../../components/admin/DocumentReview.vue';
import VerificationForm from '../../../components/admin/VerificationForm.vue';
import axios from 'axios';

export default defineComponent({
  name: 'AdminDentistVerificationDetail',
  components: {
    DocumentReview,
    VerificationForm
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const loading = ref(true);
    const error = ref<string | null>(null);
    const profile = ref<DentistProfile>({} as DentistProfile);

    const profileId = computed(() => Number(route.params.id));

    const loadProfile = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await axios.get(`/api/admin/dentist-profiles/${profileId.value}`);
        profile.value = response.data;
      } catch (err: any) {
        console.error('Error al cargar perfil:', err);
        if (err.response && err.response.status === 404) {
          error.value = "No se ha encontrado el perfil solicitado.";
        } else {
          error.value = "Error al cargar la información del perfil. Por favor, intenta nuevamente.";
        }
      } finally {
        loading.value = false;
      }
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

    const handleDocumentUpdated = (document: DentistDocument) => {
      if (profile.value.documents) {
        const index = profile.value.documents.findIndex(d => d.id === document.id);
        if (index !== -1) {
          profile.value.documents[index] = document;
        }
      }
    };

    const handleStatusUpdated = (updatedProfile: DentistProfile) => {
      profile.value = { ...profile.value, ...updatedProfile };
    };

    onMounted(loadProfile);

    return {
      loading,
      error,
      profile,
      profileId,
      getStatusLabel,
      handleDocumentUpdated,
      handleStatusUpdated
    };
  }
});
</script>
