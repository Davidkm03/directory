<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Estado de Verificación
      </h3>
      <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Actualiza el estado de verificación del perfil profesional.
      </p>
    </div>

    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
      <form @submit.prevent="submitForm">
        <div class="space-y-6">
          <div>
            <label class="text-base font-medium text-gray-900">Estado Actual</label>
            <p class="text-sm text-gray-500">El estado actual del perfil es:</p>
            <div class="mt-2">
              <span
                :class="[
                  'px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full',
                  profile.verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                  profile.verification_status === 'approved' ? 'bg-green-100 text-green-800' : 
                  'bg-red-100 text-red-800'
                ]"
              >
                {{ getStatusLabel(profile.verification_status) }}
              </span>
            </div>
          </div>

          <div>
            <label class="text-base font-medium text-gray-900">Cambiar Estado</label>
            <p class="text-sm text-gray-500">Selecciona el nuevo estado para este perfil</p>
            <fieldset class="mt-4">
              <legend class="sr-only">Estado de verificación</legend>
              <div class="space-y-4">
                <div class="flex items-center">
                  <input
                    id="status-pending"
                    name="verification-status"
                    type="radio"
                    v-model="formData.status"
                    value="pending"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                  />
                  <label for="status-pending" class="ml-3 block text-sm font-medium text-gray-700">
                    Pendiente de Revisión
                  </label>
                </div>

                <div class="flex items-center">
                  <input
                    id="status-approved"
                    name="verification-status"
                    type="radio"
                    v-model="formData.status"
                    value="approved"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                  />
                  <label for="status-approved" class="ml-3 block text-sm font-medium text-gray-700">
                    Aprobado
                  </label>
                </div>

                <div class="flex items-center">
                  <input
                    id="status-rejected"
                    name="verification-status"
                    type="radio"
                    v-model="formData.status"
                    value="rejected"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                  />
                  <label for="status-rejected" class="ml-3 block text-sm font-medium text-gray-700">
                    Rechazado
                  </label>
                </div>
              </div>
            </fieldset>
          </div>

          <div>
            <label for="admin-notes" class="block text-sm font-medium text-gray-700">
              Notas Administrativas (visibles solo para administradores)
            </label>
            <div class="mt-1">
              <textarea
                id="admin-notes"
                name="admin-notes"
                rows="3"
                v-model="formData.admin_notes"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Notas internas sobre este perfil, solo visibles para administradores"
              ></textarea>
            </div>
          </div>

          <div>
            <label for="dentist-notes" class="block text-sm font-medium text-gray-700">
              Notas para el Dentista (se mostrarán al dentista)
            </label>
            <div class="mt-1">
              <textarea
                id="dentist-notes"
                name="dentist-notes"
                rows="3"
                v-model="formData.verification_notes"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Instrucciones o comentarios para el dentista sobre su perfil y documentos"
              ></textarea>
            </div>
            <p class="mt-2 text-sm text-gray-500">
              Estas notas serán visibles para el dentista. Incluye instrucciones claras si se necesitan correcciones.
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              Enviar Notificación
            </label>
            <div class="mt-1">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="send-notification"
                    name="send-notification"
                    type="checkbox"
                    v-model="formData.send_notification"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="send-notification" class="font-medium text-gray-700">Notificar al dentista</label>
                  <p class="text-gray-500">Se enviará un correo electrónico al dentista con el nuevo estado de su perfil.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-5">
          <button
            type="submit"
            :disabled="isSubmitting"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
          </button>
        </div>

        <div v-if="error" class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
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

        <div v-if="success" class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-green-700">Los cambios se han guardado correctamente.</p>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, watch } from 'vue';
import { DentistProfile } from '../../types/dentist';
import axios from 'axios';

export default defineComponent({
  name: 'VerificationForm',
  props: {
    profile: {
      type: Object as PropType<DentistProfile>,
      required: true
    }
  },
  emits: ['status-updated'],
  setup(props, { emit }) {
    const formData = ref({
      status: '',
      admin_notes: '',
      verification_notes: '',
      send_notification: true
    });
    
    const isSubmitting = ref(false);
    const error = ref<string | null>(null);
    const success = ref(false);

    // Inicializar formulario con los datos del perfil
    watch(() => props.profile, (newProfile) => {
      if (newProfile) {
        formData.value = {
          status: newProfile.verification_status || 'pending',
          admin_notes: newProfile.admin_notes || '',
          verification_notes: newProfile.verification_notes || '',
          send_notification: true
        };
      }
    }, { immediate: true });

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

    const submitForm = async () => {
      isSubmitting.value = true;
      error.value = null;
      success.value = false;

      try {
        const response = await axios.patch(`/api/admin/dentist-profiles/${props.profile.id}/verification`, {
          verification_status: formData.value.status,
          admin_notes: formData.value.admin_notes,
          verification_notes: formData.value.verification_notes,
          send_notification: formData.value.send_notification
        });

        success.value = true;
        
        // Emitir evento para notificar al componente padre
        emit('status-updated', response.data);
      } catch (err: any) {
        console.error('Error al actualizar estado de verificación:', err);
        
        if (err.response && err.response.data && err.response.data.message) {
          error.value = err.response.data.message;
        } else {
          error.value = 'Error al guardar los cambios. Por favor, intenta nuevamente.';
        }
      } finally {
        isSubmitting.value = false;
      }
    };

    return {
      formData,
      isSubmitting,
      error,
      success,
      getStatusLabel,
      submitForm
    };
  }
});
</script>
