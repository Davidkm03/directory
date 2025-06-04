<template>
  <div>
    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Servicios y Especialidades</h3>
    <p class="mb-5 text-sm text-gray-500">Indica los servicios que ofreces y tus especialidades.</p>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
      <div class="sm:col-span-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Servicios ofrecidos</label>
        <div class="space-y-2">
          <div v-for="(service, index) in services" :key="index" class="flex items-start">
            <div class="flex items-center h-5">
              <input
                :id="`service-${index}`"
                type="checkbox"
                v-model="selectedServices"
                :value="service"
                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
              />
            </div>
            <div class="ml-3 text-sm">
              <label :for="`service-${index}`" class="font-medium text-gray-700">{{ service }}</label>
            </div>
          </div>
          
          <div class="mt-4">
            <label for="custom-service" class="block text-sm font-medium text-gray-700">Añadir servicio personalizado</label>
            <div class="mt-1 flex">
              <input
                type="text"
                id="custom-service"
                v-model="customService"
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Ej: Odontología cosmética"
              />
              <button
                type="button"
                @click="addCustomService"
                class="ml-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Añadir
              </button>
            </div>
          </div>
        </div>
        <p v-if="errors.services_offered" class="mt-2 text-sm text-red-600">{{ errors.services_offered[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label for="languages" class="block text-sm font-medium text-gray-700">Idiomas</label>
        <div class="mt-1">
          <select
            id="languages"
            v-model="selectedLanguages"
            multiple
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.languages }"
          >
            <option v-for="(language, index) in languages" :key="index" :value="language">
              {{ language }}
            </option>
          </select>
        </div>
        <p class="mt-2 text-sm text-gray-500">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples idiomas.</p>
        <p v-if="errors.languages" class="mt-2 text-sm text-red-600">{{ errors.languages[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">¿Aceptas seguros médicos?</label>
        <div class="mt-1">
          <div class="flex items-center">
            <input
              id="accepts-insurance-yes"
              type="radio"
              v-model="form.accepts_insurance"
              :value="true"
              class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
            />
            <label for="accepts-insurance-yes" class="ml-3 block text-sm font-medium text-gray-700">
              Sí
            </label>
          </div>
          <div class="flex items-center mt-2">
            <input
              id="accepts-insurance-no"
              type="radio"
              v-model="form.accepts_insurance"
              :value="false"
              class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
            />
            <label for="accepts-insurance-no" class="ml-3 block text-sm font-medium text-gray-700">
              No
            </label>
          </div>
        </div>
      </div>

      <div v-if="form.accepts_insurance" class="sm:col-span-6">
        <label for="insurance-networks" class="block text-sm font-medium text-gray-700">Redes de seguro aceptadas</label>
        <div class="mt-1">
          <input
            type="text"
            id="insurance-network"
            v-model="insuranceNetwork"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            placeholder="Nombre de la aseguradora"
          />
          <button
            type="button"
            @click="addInsuranceNetwork"
            class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Añadir aseguradora
          </button>
        </div>

        <div v-if="form.insurance_networks && form.insurance_networks.length > 0" class="mt-3">
          <div class="flex flex-wrap gap-2">
            <div
              v-for="(network, index) in form.insurance_networks"
              :key="index"
              class="inline-flex rounded-full items-center py-0.5 pl-2.5 pr-1 text-sm font-medium bg-blue-100 text-blue-700"
            >
              {{ network }}
              <button
                type="button"
                @click="removeInsuranceNetwork(index)"
                class="flex-shrink-0 ml-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white"
              >
                <span class="sr-only">Eliminar {{ network }}</span>
                <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                  <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, PropType, watch, computed } from 'vue';
import { DentistProfile } from '../../types/dentist';

export default defineComponent({
  name: 'ProfileStepServices',
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
      services_offered: [] as string[],
      languages: [] as string[],
      accepts_insurance: false,
      insurance_networks: [] as string[]
    });

    // Servicios predefinidos
    const services = [
      'Odontología general',
      'Odontología pediátrica',
      'Ortodoncia',
      'Endodoncia',
      'Periodoncia',
      'Cirugía oral',
      'Implantes dentales',
      'Prostodoncia',
      'Estética dental',
      'Blanqueamiento dental',
      'Radiología dental',
      'Tratamiento de ATM',
      'Tratamiento del dolor orofacial',
      'Odontología preventiva'
    ];

    // Idiomas predefinidos
    const languages = [
      'Español',
      'Inglés',
      'Francés',
      'Alemán',
      'Portugués',
      'Italiano',
      'Chino',
      'Japonés',
      'Ruso',
      'Árabe'
    ];

    const errors = ref<Record<string, string[]>>({});
    const selectedServices = ref<string[]>([]);
    const selectedLanguages = ref<string[]>([]);
    const customService = ref('');
    const insuranceNetwork = ref('');

    // Inicializar el formulario con los valores del modelo
    watch(() => props.modelValue, (newValue) => {
      if (newValue) {
        form.value = {
          services_offered: newValue.services_offered || [],
          languages: newValue.languages || [],
          accepts_insurance: newValue.accepts_insurance || false,
          insurance_networks: newValue.insurance_networks || []
        };
        selectedServices.value = [...(newValue.services_offered || [])];
        selectedLanguages.value = [...(newValue.languages || [])];
      }
    }, { immediate: true });

    // Actualizar errores del servidor
    watch(() => props.serverErrors, (newErrors) => {
      errors.value = newErrors || {};
    }, { immediate: true });

    // Funciones para manipular servicios
    const addCustomService = () => {
      if (customService.value.trim() && !selectedServices.value.includes(customService.value.trim())) {
        selectedServices.value.push(customService.value.trim());
        updateModelServices();
        customService.value = '';
      }
    };

    const updateModelServices = () => {
      form.value.services_offered = [...selectedServices.value];
      emit('update:modelValue', {
        ...props.modelValue,
        services_offered: form.value.services_offered
      });
    };

    // Observar cambios en servicios seleccionados
    watch(selectedServices, () => {
      updateModelServices();
    }, { deep: true });

    // Observar cambios en idiomas seleccionados
    watch(selectedLanguages, (newValue) => {
      form.value.languages = [...newValue];
      emit('update:modelValue', {
        ...props.modelValue,
        languages: form.value.languages
      });
    }, { deep: true });

    // Funciones para manipular redes de seguro
    const addInsuranceNetwork = () => {
      if (insuranceNetwork.value.trim() && !form.value.insurance_networks.includes(insuranceNetwork.value.trim())) {
        form.value.insurance_networks.push(insuranceNetwork.value.trim());
        emit('update:modelValue', {
          ...props.modelValue,
          insurance_networks: form.value.insurance_networks,
          accepts_insurance: form.value.accepts_insurance
        });
        insuranceNetwork.value = '';
      }
    };

    const removeInsuranceNetwork = (index: number) => {
      form.value.insurance_networks.splice(index, 1);
      emit('update:modelValue', {
        ...props.modelValue,
        insurance_networks: form.value.insurance_networks
      });
    };

    // Observar cambios en aceptación de seguros
    watch(() => form.value.accepts_insurance, (newValue) => {
      emit('update:modelValue', {
        ...props.modelValue,
        accepts_insurance: newValue
      });
    });

    return {
      form,
      services,
      languages,
      errors,
      selectedServices,
      selectedLanguages,
      customService,
      insuranceNetwork,
      addCustomService,
      addInsuranceNetwork,
      removeInsuranceNetwork
    };
  }
});
</script>
