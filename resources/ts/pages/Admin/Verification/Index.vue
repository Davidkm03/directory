<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-semibold text-gray-900">Verificación de Perfiles Dentales</h1>
          <p class="mt-1 text-sm text-gray-500">
            Administra las solicitudes de verificación de perfiles profesionales
          </p>
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

      <div v-else class="mt-6">
        <!-- Filtros -->
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6">
          <div class="md:flex md:items-center">
            <div class="flex-1">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Filtros</h3>
            </div>
            <div class="mt-4 flex md:mt-0">
              <div class="flex items-center">
                <select
                  v-model="filter"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                  <option value="all">Todos los perfiles</option>
                  <option value="pending">Pendientes</option>
                  <option value="approved">Aprobados</option>
                  <option value="rejected">Rechazados</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Lista de perfiles -->
        <div v-if="filteredProfiles.length === 0" class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
            No se encontraron perfiles que coincidan con los criterios de búsqueda.
          </div>
        </div>

        <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="profile in filteredProfiles" :key="profile.id" class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <img
                      v-if="profile.user.avatar_url"
                      :src="profile.user.avatar_url"
                      alt=""
                      class="h-12 w-12 rounded-full"
                    />
                    <div v-else class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                      <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ profile.user.name }}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ profile.user.email }}
                    </div>
                  </div>
                </div>
                <div class="flex items-center">
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
                  <router-link 
                    :to="{ name: 'admin-dentist-verification-detail', params: { id: profile.id } }" 
                    class="ml-4 px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200"
                  >
                    Ver detalle
                  </router-link>
                </div>
              </div>
              <div class="mt-2 sm:flex sm:justify-between">
                <div class="sm:flex">
                  <div class="flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Cédula: {{ profile.registration_number }}
                  </div>
                  <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                    {{ profile.university }} ({{ profile.graduation_year }})
                  </div>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                  <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                  </svg>
                  <p>
                    Actualizado: {{ formatDate(profile.updated_at) }}
                  </p>
                </div>
              </div>
            </li>
          </ul>
          
          <!-- Paginación -->
          <div v-if="totalPages > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between items-center">
              <button 
                @click="previousPage" 
                :disabled="currentPage === 1"
                :class="[
                  currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white'
                ]"
              >
                Anterior
              </button>
              <span class="text-sm text-gray-700">
                Página {{ currentPage }} de {{ totalPages }}
              </span>
              <button 
                @click="nextPage" 
                :disabled="currentPage === totalPages"
                :class="[
                  currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white'
                ]"
              >
                Siguiente
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted, watch } from 'vue';
import { DentistProfile } from '../../../types/dentist';
import axios from 'axios';

interface ProfileWithUser extends DentistProfile {
  user: {
    name: string;
    email: string;
    avatar_url?: string;
  };
}

export default defineComponent({
  name: 'AdminDentistVerificationIndex',
  setup() {
    const loading = ref(true);
    const error = ref<string | null>(null);
    const profiles = ref<ProfileWithUser[]>([]);
    const filter = ref('all');
    const currentPage = ref(1);
    const perPage = ref(10);
    const totalProfiles = ref(0);

    const totalPages = computed(() => Math.ceil(totalProfiles.value / perPage.value));

    const filteredProfiles = computed(() => {
      if (filter.value === 'all') {
        return profiles.value;
      }
      return profiles.value.filter(profile => profile.verification_status === filter.value);
    });

    const loadProfiles = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await axios.get('/api/admin/dentist-profiles', {
          params: {
            page: currentPage.value,
            per_page: perPage.value,
            status: filter.value !== 'all' ? filter.value : undefined
          }
        });
        profiles.value = response.data.data;
        totalProfiles.value = response.data.meta.total;
      } catch (err: any) {
        console.error('Error al cargar perfiles:', err);
        error.value = "Error al cargar la lista de perfiles. Por favor, intenta nuevamente.";
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

    const formatDate = (dateString: string): string => {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
      }).format(date);
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
      }
    };

    const previousPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
      }
    };

    // Cargar perfiles cuando cambie el filtro o la página
    watch([filter, currentPage], () => {
      loadProfiles();
    });

    onMounted(loadProfiles);

    return {
      loading,
      error,
      profiles,
      filter,
      currentPage,
      totalPages,
      filteredProfiles,
      getStatusLabel,
      formatDate,
      nextPage,
      previousPage
    };
  }
});
</script>
