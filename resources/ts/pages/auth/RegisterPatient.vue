<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Patient Registration</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
            Back to registration type
          </router-link>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">Full Name</label>
            <input id="name" name="name" type="text" required
              v-model="userData.name"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              placeholder="Full Name" />
          </div>
          <div>
            <label for="email" class="sr-only">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required
              v-model="userData.email"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              placeholder="Email" />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" required
              v-model="userData.password"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              placeholder="Password" />
          </div>
          <div>
            <label for="password_confirmation" class="sr-only">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
              v-model="userData.password_confirmation"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              placeholder="Confirm Password" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            :disabled="loading">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="!loading" class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
              <svg v-if="loading" class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Registering...' : 'Register as Patient' }}
          </button>
        </div>
        
        <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4">
          <div class="flex">
            <div class="ml-3">
              <p class="text-sm text-red-700">{{ error.message }}</p>
              <ul v-if="error.errors" class="list-disc pl-5 space-y-1">
                <li v-for="(fieldErrors, field) in error.errors" :key="field" class="text-sm text-red-700">
                  <strong>{{ field }}:</strong> {{ fieldErrors[0] }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

export default defineComponent({
  name: 'RegisterPatientPage',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const userData = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    })

    // ✅ Acceso correcto al store (sin .value)
    const loading = computed(() => authStore.loading)
    const error = computed(() => authStore.error)

    const handleRegister = async () => {
      // ✅ Usar tu método register con tipo 'patient'
      const success = await authStore.register(userData, 'patient')
      
      if (success) {
        router.push({ name: 'dashboard' })
      }
    }

    return {
      userData,
      loading,
      error,
      handleRegister
    }
  }
})
</script>

