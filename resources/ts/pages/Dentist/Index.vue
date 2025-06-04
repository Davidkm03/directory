<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Portal del Odontólogo</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Área exclusiva para profesionales de la odontología
            </p>
          </div>
          
          <div class="px-4 py-5 sm:p-6">
            <!-- Perfil del odontólogo -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
              <div class="px-4 py-5 sm:px-6 flex items-center">
                <div class="flex-shrink-0 h-20 w-20">
                  <div v-if="user?.profile_photo_path" class="h-20 w-20 rounded-full overflow-hidden">
                    <img :src="'/storage/' + user.profile_photo_path" alt="Foto de perfil" class="h-full w-full object-cover" />
                  </div>
                  <div v-else class="h-20 w-20 rounded-full bg-green-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ userInitials }}
                  </div>
                </div>
                <div class="ml-6">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Dr(a). {{ user?.name }}
                  </h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ user?.specialty }}
                  </p>
                  <p class="mt-1 text-xs text-gray-400">
                    Lic. {{ user?.license_number }}
                  </p>
                </div>
              </div>
              <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                  <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                      Biografía
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ user?.biography || 'No has agregado una biografía todavía.' }}
                    </dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                      Correo electrónico
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ user?.email }}
                    </dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                      Miembro desde
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ formatDate(user?.created_at) }}
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Herramientas del odontólogo -->
            <div>
              <h2 class="text-lg font-medium text-gray-900 mb-3">Gestiona tu práctica</h2>
              
              <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Tarjeta de pacientes -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                          Mis Pacientes
                        </dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">
                            <!-- Datos de ejemplo, aquí iría la conexión con backend -->
                            24
                          </div>
                        </dd>
                      </div>
                    </div>
                    <div class="mt-4">
                      <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ver todos
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Tarjeta de citas -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                          Citas pendientes
                        </dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">
                            <!-- Datos de ejemplo, aquí iría la conexión con backend -->
                            5
                          </div>
                        </dd>
                      </div>
                    </div>
                    <div class="mt-4">
                      <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Ver agenda
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Tarjeta de casos clínicos -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                          Casos clínicos
                        </dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">
                            <!-- Datos de ejemplo, aquí iría la conexión con backend -->
                            3
                          </div>
                        </dd>
                      </div>
                    </div>
                    <div class="mt-4">
                      <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Ver casos
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Acciones rápidas -->
              <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Acciones rápidas</h3>
                <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                  <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <div class="flex-shrink-0">
                      <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <a href="#" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">
                          Agregar nuevo paciente
                        </p>
                      </a>
                    </div>
                  </div>

                  <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <div class="flex-shrink-0">
                      <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <a href="#" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">
                          Programar cita
                        </p>
                      </a>
                    </div>
                  </div>

                  <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <div class="flex-shrink-0">
                      <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <a href="#" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">
                          Editar perfil profesional
                        </p>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

export default defineComponent({
  name: 'DentistPage',
  setup() {
    const authStore = useAuthStore();
    
    const formatDate = (dateString?: string) => {
      if (!dateString) return '';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      }).format(date);
    };

    return {
      user: authStore.user,
      userInitials: authStore.userInitials,
      formatDate
    };
  },
});
</script>
