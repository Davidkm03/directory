<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="py-6">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">
                  Welcome back, {{ user?.name || 'User' }}!
                </p>
              </div>
              <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 font-medium">
                  {{ userInitials }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Profile Status
                    </dt>
                    <dd class="text-lg font-medium text-gray-900">
                      {{ profileStatus }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Member Since
                    </dt>
                    <dd class="text-lg font-medium text-gray-900">
                      {{ memberSince }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                      Account Type
                    </dt>
                    <dd class="text-lg font-medium text-gray-900 capitalize">
                      {{ user?.role || 'User' }}
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Role-specific content -->
        <div v-if="user?.role === 'dentist'" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Dentist Profile Card -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Professional Profile
              </h3>
              <div class="space-y-3">
                <div v-if="user.specialty">
                  <span class="text-sm font-medium text-gray-500">Specialty:</span>
                  <span class="ml-2 text-sm text-gray-900">{{ user.specialty }}</span>
                </div>
                <div v-if="user.license_number">
                  <span class="text-sm font-medium text-gray-500">License:</span>
                  <span class="ml-2 text-sm text-gray-900">{{ user.license_number }}</span>
                </div>
                <div class="pt-3">
                  <router-link 
                    to="/dentist/profile/edit"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Edit Profile
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Verification Status -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Verification Status
              </h3>
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg v-if="user.email_verified_at" class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <svg v-else class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-900">
                    {{ user.email_verified_at ? 'Verified' : 'Pending Verification' }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ user.email_verified_at ? 'Your account is verified' : 'Please verify your email address' }}
                  </p>
                </div>
              </div>
              <div class="mt-4" v-if="!user.email_verified_at">
                <button 
                  @click="resendVerification"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Resend Verification Email
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Quick Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <!-- Browse Directory -->
              <router-link 
                to="/directory"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Browse Directory
              </router-link>

              <!-- Edit Profile -->
              <router-link 
                :to="profileEditRoute"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profile
              </router-link>

              <!-- Settings -->
              <router-link 
                to="/dashboard/settings"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </router-link>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8 bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Recent Activity
            </h3>
            <div class="text-center py-6">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No activity yet</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by exploring the community.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'

export default defineComponent({
  name: 'DashboardPage',
  components: {
    MainLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    // ✅ Computed properties usando datos reales del usuario
    const user = computed(() => authStore.user)
    
    const userInitials = computed(() => {
      if (!user.value?.name) return '?'
      return user.value.name
        .split(' ')
        .map(name => name[0])
        .join('')
        .toUpperCase()
        .substring(0, 2)
    })

    const profileStatus = computed(() => {
      if (!user.value) return 'Loading...'
      return user.value.email_verified_at ? 'Active' : 'Pending'
    })

    const memberSince = computed(() => {
      if (!user.value?.created_at) return 'Unknown'
      return new Date(user.value.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short'
      })
    })

    const profileEditRoute = computed(() => {
      switch (user.value?.role) {
        case 'dentist':
          return '/dentist/profile/edit'
        case 'patient':
          return '/patient/profile/edit'
        case 'admin':
          return '/admin/profile/edit'
        default:
          return '/dashboard/settings'
      }
    })

    // ✅ Obtener datos del usuario si no están disponibles
    onMounted(async () => {
      if (!user.value) {
        try {
          await authStore.getCurrentUser()
        } catch (error) {
          console.error('Error getting user data:', error)
          // Si hay error, redirigir al login
          router.push({ name: 'login' })
        }
      }
    })

    const resendVerification = async () => {
      try {
        // Aquí irían las llamadas para reenviar verificación
        console.log('Resending verification email...')
        // await authStore.resendVerification()
      } catch (error) {
        console.error('Error resending verification:', error)
      }
    }

    return {
      user,
      userInitials,
      profileStatus,
      memberSince,
      profileEditRoute,
      resendVerification
    }
  }
})
</script>