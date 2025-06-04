<template>
  <div
    class="bg-white rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg"
    :class="{ 'cursor-pointer': clickable }"
    @click="clickable ? $emit('click', dentist) : null"
  >
    <!-- Imagen de portada o banner -->
    <div class="h-24 bg-gradient-to-r from-teal-500 to-blue-500 relative">
      <!-- Insignia de verificado -->
      <div class="absolute top-2 right-2 bg-white rounded-full p-1 shadow">
        <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
      </div>
    </div>
    
    <div class="relative px-4 pt-12 pb-4">
      <!-- Foto de perfil -->
      <div class="absolute -top-10 left-4">
        <div class="h-20 w-20 rounded-full border-4 border-white shadow overflow-hidden">
          <img
            :src="dentist.profile_image || '/images/default-profile.jpg'"
            :alt="dentist.user?.name || 'Dentist'"
            class="h-full w-full object-cover"
            @error="handleImageError"
          />
        </div>
      </div>
      
      <!-- Información principal -->
      <div class="mb-2 mt-2">
        <div class="flex justify-between items-start">
          <h3 class="text-lg font-bold text-gray-900 truncate" :title="dentist.user?.name">
            {{ dentist.user?.name }}
          </h3>
          <button
            v-if="showFavoriteButton"
            type="button"
            @click.stop="toggleFavorite"
            :disabled="favoriteLoading"
            class="p-1 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500"
          >
            <svg
              class="w-6 h-6"
              :class="isFavorite ? 'text-red-500 fill-current' : 'text-gray-400'"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
              ></path>
            </svg>
          </button>
        </div>
        
        <p class="text-sm text-gray-600 mb-1" v-if="dentist.specialty">
          {{ dentist.specialty }}
        </p>
        
        <!-- Calificación -->
        <div class="flex items-center mb-2">
          <div class="flex">
            <template v-for="i in 5" :key="i">
              <svg
                class="w-4 h-4"
                :class="i <= Math.round(dentist.rating || 0) ? 'text-yellow-400' : 'text-gray-300'"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </template>
          </div>
          <span class="ml-1 text-sm text-gray-600">
            {{ dentist.rating ? dentist.rating.toFixed(1) : 'No ratings' }}
          </span>
        </div>
      </div>
      
      <!-- Información secundaria -->
      <div class="border-t pt-3 mt-3">
        <!-- Ubicación -->
        <div class="flex items-center text-sm text-gray-600 mb-2">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1114.142 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span class="truncate">{{ dentist.city }}, {{ dentist.state }}</span>
        </div>
        
        <!-- Años de experiencia -->
        <div class="flex items-center text-sm text-gray-600 mb-2" v-if="dentist.experience_years">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ dentist.experience_years }} years of experience</span>
        </div>
        
        <!-- Servicios (mostrar sólo los primeros 3) -->
        <div class="flex flex-wrap gap-1 mt-3" v-if="dentist.services_list && dentist.services_list.length">
          <span
            v-for="(service, index) in dentist.services_list.slice(0, 3)"
            :key="index"
            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-teal-100 text-teal-800"
          >
            {{ service }}
          </span>
          <span
            v-if="dentist.services_list.length > 3"
            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800"
          >
            +{{ dentist.services_list.length - 3 }} more
          </span>
        </div>
      </div>
      
      <!-- Botón de acción -->
      <div class="mt-4" v-if="showActionButton">
        <button
          type="button"
          class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
          @click.stop="$emit('view-profile', dentist)"
        >
          View full profile
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType, ref, computed } from 'vue';
import { DentistProfile } from '../../types/dentist';
import DirectoryService from '../../services/DirectoryService';

export default defineComponent({
  name: 'DentistCard',
  
  props: {
    dentist: {
      type: Object as PropType<DentistProfile>,
      required: true,
    },
    favorited: {
      type: Boolean,
      default: false,
    },
    showFavoriteButton: {
      type: Boolean,
      default: true,
    },
    showActionButton: {
      type: Boolean,
      default: true,
    },
    clickable: {
      type: Boolean,
      default: true,
    },
  },
  
  emits: ['favorite-toggled', 'view-profile', 'click'],
  
  setup(props, { emit }) {
    const isFavorite = ref(props.favorited);
    const favoriteLoading = ref(false);
    
    const toggleFavorite = async (event: Event) => {
      event.preventDefault();
      event.stopPropagation();
      
      if (favoriteLoading.value) return;
      
      favoriteLoading.value = true;
      
      try {
        const response = await DirectoryService.toggleFavorite(props.dentist.id);
        isFavorite.value = response.isFavorite;
        emit('favorite-toggled', { dentistId: props.dentist.id, isFavorite: isFavorite.value });
      } catch (error) {
        console.error('Error toggling favorite:', error);
      } finally {
        favoriteLoading.value = false;
      }
    };
    
    const handleImageError = (event: Event) => {
      const target = event.target as HTMLImageElement;
      target.src = '/images/default-profile.jpg';
    };
    
    return {
      isFavorite,
      favoriteLoading,
      toggleFavorite,
      handleImageError,
    };
  },
});
</script>
