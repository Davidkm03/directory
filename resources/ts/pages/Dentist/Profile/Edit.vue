<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-semibold text-gray-900">
            {{ isNew ? 'Crear Perfil Profesional' : 'Editar Perfil Profesional' }}
          </h1>
          <p class="mt-1 text-sm text-gray-500">
            {{ isNew ? 'Completa tu información profesional para ser verificado' : 'Actualiza tu información profesional' }}
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <router-link 
            :to="{ name: 'dentist-profile' }" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancelar
          </router-link>
        </div>
      </div>

      <div v-if="loading" class="mt-6 flex justify-center">
        <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else class="mt-6">
        <!-- Pasos de navegación -->
        <nav aria-label="Progress" class="mb-8">
          <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
            <li v-for="(step, index) in steps" :key="index" class="md:flex-1">
              <button 
                @click="currentStep = index"
                :class="[
                  'group flex flex-col border-l-4 py-2 pl-4 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4',
                  index <= currentStep ? 'border-blue-600' : 'border-gray-200',
                  'focus:outline-none w-full text-left'
                ]"
              >
                <span :class="[
                  'text-xs font-semibold tracking-wide uppercase',
                  index <= currentStep ? 'text-blue-600' : 'text-gray-500'
                ]">
                  Paso {{ index + 1 }}
                </span>
                <span class="text-sm font-medium">{{ step.title }}</span>
              </button>
            </li>
          </ol>
        </nav>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <!-- Mensajes de error global -->
            <div v-if="globalError" class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">{{ globalError }}</p>
                </div>
              </div>
            </div>

            <!-- Mensaje de éxito -->
            <div v-if="showSuccess" class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-green-700">
                    Tu perfil ha sido guardado con éxito.
                  </p>
                </div>
              </div>
            </div>

            <!-- Formulario multi-step -->
            <form @submit.prevent="submitForm">
              <keep-alive>
                <component 
                  :is="steps[currentStep].component" 
                  v-model="formData"
                  :server-errors="serverErrors"
                />
              </keep-alive>

              <div class="mt-8 pt-5 border-t border-gray-200 flex justify-between">
                <div>
                  <button
                    v-if="currentStep > 0"
                    type="button"
                    @click="prevStep"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Anterior
                  </button>
                </div>
                <div class="flex space-x-3">
                  <button
                    v-if="currentStep < steps.length - 1"
                    type="button"
                    @click="nextStep"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Siguiente
                  </button>
                  <button
                    v-if="currentStep === steps.length - 1"
                    type="submit"
                    :disabled="saving"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ saving ? 'Guardando...' : 'Guardar Perfil' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { DentistProfile } from '../../../types/dentist';
import dentistProfileService from '../../../services/dentistProfileService';
import ProfileStepBasic from '../../../components/dentist/ProfileStepBasic.vue';
import ProfileStepServices from '../../../components/dentist/ProfileStepServices.vue';
import ProfileStepContact from '../../../components/dentist/ProfileStepContact.vue';

export default defineComponent({
  name: 'DentistProfileEdit',
  components: {
    ProfileStepBasic,
    ProfileStepServices,
    ProfileStepContact
  },
  setup() {
    const router = useRouter();
    const loading = ref(true);
    const saving = ref(false);
    const globalError = ref<string | null>(null);
    const showSuccess = ref(false);
    const formData = ref<Partial<DentistProfile>>({});
    const serverErrors = ref<Record<string, string[]>>({});
    const currentStep = ref(0);
    const profileId = ref<number | null>(null);

    // Definir los pasos del formulario
    const steps = [
      {
        title: 'Información Básica',
        component: 'ProfileStepBasic'
      },
      {
        title: 'Servicios y Especialidades',
        component: 'ProfileStepServices'
      },
      {
        title: 'Contacto y Ubicación',
        component: 'ProfileStepContact'
      }
    ];

    const isNew = computed(() => !profileId.value);

    // Cargar el perfil si existe
    const loadProfile = async () => {
      loading.value = true;
      globalError.value = null;
      
      try {
        const response = await dentistProfileService.getProfile();
        if (response.data.profile) {
          formData.value = response.data.profile;
          profileId.value = response.data.profile.id || null;
        }
      } catch (err: any) {
        console.error('Error al cargar perfil:', err);
        // Si es un 404, significa que no hay perfil aún, lo cual es normal para nuevos usuarios
        if (err.response && err.response.status !== 404) {
          globalError.value = 'Error al cargar la información del perfil. Por favor, intenta nuevamente.';
        }
      } finally {
        loading.value = false;
      }
    };

    const nextStep = () => {
      if (currentStep.value < steps.length - 1) {
        currentStep.value++;
      }
    };

    const prevStep = () => {
      if (currentStep.value > 0) {
        currentStep.value--;
      }
    };

    const submitForm = async () => {
      saving.value = true;
      globalError.value = null;
      serverErrors.value = {};
      showSuccess.value = false;
      
      try {
        let response;
        
        if (isNew.value) {
          response = await dentistProfileService.createProfile(formData.value);
        } else {
          response = await dentistProfileService.updateProfile(formData.value);
        }
        
        profileId.value = response.data.id || profileId.value;
        showSuccess.value = true;
        
        // Redirigir después de un breve tiempo
        setTimeout(() => {
          router.push({ name: 'dentist-profile' });
        }, 2000);
      } catch (err: any) {
        console.error('Error al guardar perfil:', err);
        
        if (err.response && err.response.data && err.response.data.errors) {
          serverErrors.value = err.response.data.errors;
          // Navegar al primer paso con errores
          for (let i = 0; i < steps.length; i++) {
            const stepFields = Object.keys(serverErrors.value);
            if (stepFields.length > 0) {
              currentStep.value = i;
              break;
            }
          }
        } else {
          globalError.value = 'Error al guardar el perfil. Por favor, verifica tu información e intenta nuevamente.';
        }
      } finally {
        saving.value = false;
      }
    };

    onMounted(loadProfile);

    return {
      loading,
      saving,
      formData,
      globalError,
      showSuccess,
      currentStep,
      steps,
      serverErrors,
      isNew,
      nextStep,
      prevStep,
      submitForm
    };
  }
});
</script>
