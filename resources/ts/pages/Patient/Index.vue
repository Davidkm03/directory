<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Portal del Paciente</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Bienvenido a tu área personal de salud dental
            </p>
          </div>
          
          <div class="px-4 py-5 sm:p-6">
            <!-- Perfil del paciente -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
              <div class="px-4 py-5 sm:px-6 flex items-center">
                <div class="flex-shrink-0 h-20 w-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                  {{ userInitials }}
                </div>
                <div class="ml-6">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ user?.name }}
                  </h3>
                  <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ user?.email }}
                  </p>
                  <p class="mt-1 text-xs text-gray-400">
                    Miembro desde {{ formatDate(user?.created_at) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Resumen de estado dental -->
            <div class="mb-8">
              <h2 class="text-lg font-medium text-gray-900 mb-3">Mi salud dental</h2>
              
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                    <!-- Resumen de citas -->
                    <div>
                      <h3 class="text-md font-medium text-gray-900">Próximas citas</h3>
                      
                      <!-- Si no hay citas -->
                      <p class="mt-2 text-sm text-gray-500 italic">
                        No tienes citas programadas actualmente.
                      </p>
                      
                      <!-- Lista de citas (futura implementación) -->
                      <!-- 
                      <div class="mt-2 space-y-3">
                        <div class="border rounded-md p-3 bg-gray-50">
                          <div class="flex justify-between">
                            <div>
                              <p class="font-medium text-gray-800">Revisión dental</p>
                              <p class="text-sm text-gray-500">Dr. Nombre Apellido</p>
                            </div>
                            <div class="text-right">
                              <p class="font-medium text-gray-800">15 de Mayo, 2023</p>
                              <p class="text-sm text-gray-500">10:00 AM</p>
                            </div>
                          </div>
                        </div>
                      </div> 
                      -->
                      
                      <div class="mt-4">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          Solicitar cita
                        </button>
                      </div>
                    </div>
                    
                    <!-- Resumen de tratamientos -->
                    <div>
                      <h3 class="text-md font-medium text-gray-900">Tratamientos activos</h3>
                      
                      <!-- Si no hay tratamientos -->
                      <p class="mt-2 text-sm text-gray-500 italic">
                        No tienes tratamientos activos actualmente.
                      </p>
                      
                      <!-- Lista de tratamientos (futura implementación) -->
                      <!--
                      <div class="mt-2 space-y-3">
                        <div class="border rounded-md p-3 bg-gray-50">
                          <p class="font-medium text-gray-800">Ortodoncia</p>
                          <p class="text-sm text-gray-500">Iniciado el 10 de Enero, 2023</p>
                          <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                          </div>
                          <p class="mt-1 text-xs text-gray-500 text-right">45% completado</p>
                        </div>
                      </div>
                      -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Odontólogos y opciones -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <!-- Odontólogos disponibles -->
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-3">Odontólogos disponibles</h3>
                  
                  <!-- Ejemplo de odontólogo (futura implementación dinámica) -->
                  <div class="border-t border-b border-gray-200 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                        JP
                      </div>
                      <div class="ml-4">
                        <h4 class="text-md font-medium text-gray-900">Dr. Juan Pérez</h4>
                        <p class="text-sm text-gray-500">Ortodoncia</p>
                      </div>
                      <div class="ml-auto">
                        <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          Ver perfil
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <div class="border-b border-gray-200 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        MR
                      </div>
                      <div class="ml-4">
                        <h4 class="text-md font-medium text-gray-900">Dra. María Rodríguez</h4>
                        <p class="text-sm text-gray-500">Odontopediatría</p>
                      </div>
                      <div class="ml-auto">
                        <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          Ver perfil
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <div class="mt-4 text-center">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                      Ver todos los odontólogos
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- Recursos de salud dental -->
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-3">Recursos de salud dental</h3>
                  
                  <ul class="divide-y divide-gray-200">
                    <li class="py-3">
                      <a href="#" class="flex items-center hover:bg-gray-50 -mx-2 px-2 py-1 rounded-md">
                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 text-blue-600 rounded-md flex items-center justify-center">
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                          </svg>
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium text-gray-900">Guía para una higiene dental correcta</p>
                        </div>
                      </a>
                    </li>
                    <li class="py-3">
                      <a href="#" class="flex items-center hover:bg-gray-50 -mx-2 px-2 py-1 rounded-md">
                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 text-blue-600 rounded-md flex items-center justify-center">
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                          </svg>
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium text-gray-900">Video: Cómo cepillarse correctamente</p>
                        </div>
                      </a>
                    </li>
                    <li class="py-3">
                      <a href="#" class="flex items-center hover:bg-gray-50 -mx-2 px-2 py-1 rounded-md">
                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 text-blue-600 rounded-md flex items-center justify-center">
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium text-gray-900">Preguntas frecuentes sobre salud dental</p>
                        </div>
                      </a>
                    </li>
                  </ul>
                  
                  <div class="mt-4 text-center">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                      Ver todos los recursos
                    </button>
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
import { defineComponent } from 'vue';
import { useAuthStore } from '@/stores/auth';

export default defineComponent({
  name: 'PatientPage',
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
