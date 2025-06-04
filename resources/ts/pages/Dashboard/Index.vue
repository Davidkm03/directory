<template>
  <MainLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <!-- Main dashboard container -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div v-if="userLoading">
              <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>
            
            <div v-else-if="user">
              <div class="border-b border-gray-200 pb-5 mb-5">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Welcome, {{ user.name }}</h3>
                <p class="mt-1 text-sm text-gray-500">
                  It's a pleasure to have you back in the Dental Community
                </p>
              </div>
              
              <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Statistics and quick access -->
                <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                  <div class="p-5">
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">
                            Colleagues in the community
                          </dt>
                          <dd>
                            <div class="text-lg font-medium text-blue-700">
                              458
                            </div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                  <div class="bg-blue-100 px-5 py-3">
                    <div class="text-sm">
                      <a href="#" class="font-medium text-blue-700 hover:text-blue-900">View all</a>
                    </div>
                  </div>
                </div>

                <div class="bg-green-50 overflow-hidden shadow rounded-lg">
                  <div class="p-5">
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">
                            Educational resources
                          </dt>
                          <dd>
                            <div class="text-lg font-medium text-green-700">
                              126
                            </div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                  <div class="bg-green-100 px-5 py-3">
                    <div class="text-sm">
                      <a href="#" class="font-medium text-green-700 hover:text-green-900">Explore resources</a>
                    </div>
                  </div>
                </div>

                <div class="bg-purple-50 overflow-hidden shadow rounded-lg">
                  <div class="p-5">
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">
                            Active discussions
                          </dt>
                          <dd>
                            <div class="text-lg font-medium text-purple-700">
                              32
                            </div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                  <div class="bg-purple-100 px-5 py-3">
                    <div class="text-sm">
                      <a href="#" class="font-medium text-purple-700 hover:text-purple-900">Participate</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming events</h3>
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                  <ul class="divide-y divide-gray-200">
                    <li>
                      <a href="#" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                          <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-blue-600 truncate">
                              New techniques in dental implants
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                              <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Webinar
                              </p>
                            </div>
                          </div>
                          <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                              <p class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Dr. Carlos Martínez
                              </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                              <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                              </svg>
                              <p>
                                July 10, 2025
                              </p>
                            </div>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                          <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-blue-600 truncate">
                              Annual Pediatric Dentistry Meeting
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                              <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                In-person
                              </p>
                            </div>
                          </div>
                          <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                              <p class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Barcelona, Spain
                              </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                              <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                              </svg>
                              <p>
                                September 24-26, 2025
                              </p>
                            </div>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div v-else>
              <p class="text-red-500">Could not load user information.</p>
              <button 
                @click="loadUserData" 
                class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Retry
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, computed } from 'vue'
import MainLayout from '@/layouts/MainLayout.vue'
import { useAuthStore } from '@/stores/auth'

export default defineComponent({
  name: 'DashboardPage',
  components: {
    MainLayout
  },
  setup() {
    const authStore = useAuthStore()
    const userLoading = ref(true)
    
    const user = computed(() => authStore.user)
    
    const loadUserData = async () => {
      userLoading.value = true
      await authStore.getCurrentUser()
      userLoading.value = false
    }
    
    onMounted(async () => {
      await loadUserData()
    })
    
    return {
      user,
      userLoading,
      loadUserData
    }
  }
})
</script>
