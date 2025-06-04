<template>
  <div>
    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Información Profesional Básica</h3>
    <p class="mb-5 text-sm text-gray-500">Ingresa la información básica de tu práctica profesional.</p>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
      <div class="sm:col-span-3">
        <label for="registration_number" class="block text-sm font-medium text-gray-700">Número de Cédula Profesional *</label>
        <div class="mt-1">
          <input
            type="text"
            id="registration_number"
            v-model="form.registration_number"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.registration_number }"
            required
          />
        </div>
        <p v-if="errors.registration_number" class="mt-2 text-sm text-red-600">{{ errors.registration_number[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label for="university" class="block text-sm font-medium text-gray-700">Universidad *</label>
        <div class="mt-1">
          <input
            type="text"
            id="university"
            v-model="form.university"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.university }"
            required
          />
        </div>
        <p v-if="errors.university" class="mt-2 text-sm text-red-600">{{ errors.university[0] }}</p>
      </div>

      <div class="sm:col-span-3">
        <label for="graduation_year" class="block text-sm font-medium text-gray-700">Año de Graduación *</label>
        <div class="mt-1">
          <select
            id="graduation_year"
            v-model="form.graduation_year"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.graduation_year }"
            required
          >
            <option value="">Seleccionar año</option>
            <option v-for="year in graduationYears" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>
        <p v-if="errors.graduation_year" class="mt-2 text-sm text-red-600">{{ errors.graduation_year[0] }}</p>
      </div>

      <div class="sm:col-span-6">
        <label for="professional_experience" class="block text-sm font-medium text-gray-700">Experiencia Profesional</label>
        <div class="mt-1">
          <textarea
            id="professional_experience"
            v-model="form.professional_experience"
            rows="3"
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
            :class="{ 'border-red-300': errors.professional_experience }"
          ></textarea>
        </div>
        <p class="mt-2 text-sm text-gray-500">Describe brevemente tu experiencia profesional, especialidades y enfoques de práctica.</p>
        <p v-if="errors.professional_experience" class="mt-2 text-sm text-red-600">{{ errors.professional_experience[0] }}</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, PropType, watch } from 'vue';
import { DentistProfile } from '../../types/dentist';

export default defineComponent({
  name: 'ProfileStepBasic',
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
      registration_number: '',
      university: '',
      graduation_year: '',
      professional_experience: ''
    });

    const errors = ref<Record<string, string[]>>({});

    // Generar años para el selector, desde el año actual hasta 60 años atrás
    const graduationYears = computed(() => {
      const years = [];
      const currentYear = new Date().getFullYear();
      for (let i = 0; i <= 60; i++) {
        years.push(currentYear - i);
      }
      return years;
    });

    // Inicializar el formulario con los valores del modelo
    watch(() => props.modelValue, (newValue) => {
      if (newValue) {
        form.value = {
          registration_number: newValue.registration_number || '',
          university: newValue.university || '',
          graduation_year: newValue.graduation_year || '',
          professional_experience: newValue.professional_experience || ''
        };
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

    return {
      form,
      errors,
      graduationYears
    };
  }
});
</script>
