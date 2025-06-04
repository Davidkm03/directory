<template>
  <div>
    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Información de Contacto y Ubicación</h3>
    <p class="mb-5 text-sm text-gray-500">Proporciona la información de contacto y ubicación de tu consultorio.</p>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
      <div class="sm:col-span-6">
        <label for="office_address" class="block text-sm font-medium text-gray-700">Dirección del Consultorio</label>
        <div class="mt-1">
          <input
            type="text"
            id="office_address"
            v-model="form.office_address"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.office_address }"
            placeholder="Calle, número, colonia"
          />
        </div>
        <p v-if="errors.office_address" class="mt-2 text-sm text-red-600">{{ errors.office_address[0] }}</p>
      </div>

      <div class="sm:col-span-2">
        <label for="city" class="block text-sm font-medium text-gray-700">Ciudad *</label>
        <div class="mt-1">
          <input
            type="text"
            id="city"
            v-model="form.city"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.city }"
            required
          />
        </div>
        <p v-if="errors.city" class="mt-2 text-sm text-red-600">{{ errors.city[0] }}</p>
      </div>

      <div class="sm:col-span-2">
        <label for="state" class="block text-sm font-medium text-gray-700">Estado *</label>
        <div class="mt-1">
          <select
            id="state"
            v-model="form.state"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.state }"
            required
          >
            <option value="">Seleccionar estado</option>
            <option v-for="state in mexicanStates" :key="state" :value="state">{{ state }}</option>
          </select>
        </div>
        <p v-if="errors.state" class="mt-2 text-sm text-red-600">{{ errors.state[0] }}</p>
      </div>

      <div class="sm:col-span-2">
        <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal *</label>
        <div class="mt-1">
          <input
            type="text"
            id="postal_code"
            v-model="form.postal_code"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.postal_code }"
            required
          />
        </div>
        <p v-if="errors.postal_code" class="mt-2 text-sm text-red-600">{{ errors.postal_code[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono de Contacto *</label>
        <div class="mt-1">
          <input
            type="tel"
            id="phone_number"
            v-model="form.phone_number"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.phone_number }"
            placeholder="Ej: 55-1234-5678"
            required
          />
        </div>
        <p v-if="errors.phone_number" class="mt-2 text-sm text-red-600">{{ errors.phone_number[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label for="website_url" class="block text-sm font-medium text-gray-700">Sitio Web</label>
        <div class="mt-1">
          <input
            type="url"
            id="website_url"
            v-model="form.website_url"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.website_url }"
            placeholder="https://ejemplo.com"
          />
        </div>
        <p v-if="errors.website_url" class="mt-2 text-sm text-red-600">{{ errors.website_url[0] }}</p>
      </div>

      <div class="sm:col-span-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Redes Sociales</label>
        
        <div class="space-y-3">
          <div class="grid grid-cols-6 gap-3">
            <div class="col-span-2">
              <label for="social-facebook" class="block text-sm font-medium text-gray-500">Facebook</label>
            </div>
            <div class="col-span-4">
              <input
                type="text"
                id="social-facebook"
                v-model="socialMedia.facebook"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="@perfil o URL"
              />
            </div>
          </div>

          <div class="grid grid-cols-6 gap-3">
            <div class="col-span-2">
              <label for="social-instagram" class="block text-sm font-medium text-gray-500">Instagram</label>
            </div>
            <div class="col-span-4">
              <input
                type="text"
                id="social-instagram"
                v-model="socialMedia.instagram"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="@perfil o URL"
              />
            </div>
          </div>

          <div class="grid grid-cols-6 gap-3">
            <div class="col-span-2">
              <label for="social-twitter" class="block text-sm font-medium text-gray-500">Twitter</label>
            </div>
            <div class="col-span-4">
              <input
                type="text"
                id="social-twitter"
                v-model="socialMedia.twitter"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="@perfil o URL"
              />
            </div>
          </div>

          <div class="grid grid-cols-6 gap-3">
            <div class="col-span-2">
              <label for="social-linkedin" class="block text-sm font-medium text-gray-500">LinkedIn</label>
            </div>
            <div class="col-span-4">
              <input
                type="text"
                id="social-linkedin"
                v-model="socialMedia.linkedin"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="URL del perfil"
              />
            </div>
          </div>
        </div>
        <p v-if="errors.social_media" class="mt-2 text-sm text-red-600">{{ errors.social_media[0] }}</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, watch, computed } from 'vue';
import { DentistProfile } from '../../types/dentist';

export default defineComponent({
  name: 'ProfileStepContact',
  props: {
    modelValue: {
      type: Object as PropType<Partial<DentistProfile>>,
      required: true
    },
    serverErrors: {
      type: Object as PropType<Record<string, string[]>>,
      default: () => ({})
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const form = ref({
      office_address: '',
      city: '',
      state: '',
      postal_code: '',
      phone_number: '',
      website_url: ''
    });

    const socialMedia = ref({
      facebook: '',
      instagram: '',
      twitter: '',
      linkedin: ''
    });

    const errors = ref<Record<string, string[]>>({});

    // Lista de estados mexicanos
    const mexicanStates = [
      'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
      'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México',
      'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit',
      'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí',
      'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
    ];

    // Inicializar el formulario con los valores del modelo
    watch(() => props.modelValue, (newValue) => {
      if (newValue) {
        form.value = {
          office_address: newValue.office_address || '',
          city: newValue.city || '',
          state: newValue.state || '',
          postal_code: newValue.postal_code || '',
          phone_number: newValue.phone_number || '',
          website_url: newValue.website_url || ''
        };

        // Inicializar redes sociales si existen
        if (newValue.social_media) {
          socialMedia.value = {
            facebook: newValue.social_media.facebook || '',
            instagram: newValue.social_media.instagram || '',
            twitter: newValue.social_media.twitter || '',
            linkedin: newValue.social_media.linkedin || ''
          };
        }
      }
    }, { immediate: true });

    // Actualizar errores del servidor
    watch(() => props.serverErrors, (newErrors) => {
      errors.value = newErrors || {};
    }, { immediate: true });

    // Emitir cambios en el formulario al componente padre
    watch(form, (newValue) => {
      emit('update:modelValue', {
        ...props.modelValue,
        ...newValue
      });
    }, { deep: true });

    // Emitir cambios en redes sociales
    watch(socialMedia, (newValue) => {
      const social = {};
      Object.entries(newValue).forEach(([key, value]) => {
        if (value) {
          social[key] = value;
        }
      });

      emit('update:modelValue', {
        ...props.modelValue,
        social_media: Object.keys(social).length > 0 ? social : undefined
      });
    }, { deep: true });

    return {
      form,
      socialMedia,
      errors,
      mexicanStates
    };
  }
});
</script>
