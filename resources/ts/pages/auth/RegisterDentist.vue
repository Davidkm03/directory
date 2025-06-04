
<!-- ============================================= -->
<!-- RegisterDentist.vue - CORREGIDO para tu store -->
<!-- ============================================= -->
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Dentist Registration</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
            Back to type selection
          </router-link>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <!-- Basic Information -->
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
            <input id="name" name="name" type="text" required
              v-model="userData.name"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Full name" />
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required
              v-model="userData.email"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Email" />
          </div>
          
          <!-- Dentist-specific fields -->
          <div>
            <label for="specialty" class="block text-sm font-medium text-gray-700">Specialty</label>
            <input id="specialty" name="specialty" type="text" required
              v-model="userData.specialty"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Specialty (e.g. Orthodontics, Endodontics)" />
          </div>
          
          <div>
            <label for="license_number" class="block text-sm font-medium text-gray-700">Professional license number</label>
            <input id="license_number" name="license_number" type="text" required
              v-model="userData.license_number"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Professional license number" />
          </div>
          
          <div>
            <label for="biography" class="block text-sm font-medium text-gray-700">Professional biography</label>
            <textarea id="biography" name="biography" rows="4"
              v-model="userData.biography"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Brief professional biography"></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Profile photo</label>
            <div class="mt-1 flex items-center">
              <div v-if="photoPreview" class="flex-shrink-0 h-20 w-20 bg-gray-100 rounded-full overflow-hidden">
                <img :src="photoPreview" alt="Preview" class="h-full w-full object-cover">
              </div>
              <div v-else class="flex-shrink-0 h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <input 
                type="file" 
                ref="fileInput"
                @change="updatePhotoPreview"
                class="hidden"
                accept="image/*"
              >
              <button 
                type="button"
                @click="$refs.fileInput.click()"
                class="ml-4 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Select image
              </button>
            </div>
          </div>
          
          <!-- Passwords -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" required
              v-model="userData.password"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Password (minimum 8 characters)" />
          </div>
          
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
              v-model="userData.password_confirmation"
              class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Confirm password" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            :disabled="loading">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="!loading" class="h-5 w-5 text-green-500 group-hover:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <svg v-if="loading" class="animate-spin h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Registering...' : 'Register as Dentist' }}
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
import { defineComponent, reactive, computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

interface DentistRegistrationData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  specialty: string;
  license_number: string;
  biography?: string;
  profile_photo?: File | null;
}

export default defineComponent({
  name: 'RegisterDentistPage',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    const fileInput = ref<HTMLInputElement | null>(null)
    const photoPreview = ref<string | null>(null)
    
    const userData = reactive<DentistRegistrationData>({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      specialty: '',
      license_number: '',
      biography: '',
      profile_photo: null
    })

    // ✅ Acceso correcto al store (sin .value)
    const loading = computed(() => authStore.loading)
    const error = computed(() => authStore.error)

    const updatePhotoPreview = (event: Event) => {
      const target = event.target as HTMLInputElement
      if (target.files && target.files.length > 0) {
        const file = target.files[0]
        userData.profile_photo = file
        
        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
          photoPreview.value = e.target?.result as string
        }
        reader.readAsDataURL(file)
      }
    }

    const handleRegister = async () => {
      // ✅ Usar tu método register con tipo 'dentist'
      const success = await authStore.register(userData, 'dentist')
      
      if (success) {
        router.push({ name: 'dashboard' })
      }
    }

    return {
      userData,
      loading,
      error,
      photoPreview,
      fileInput,
      updatePhotoPreview,
      handleRegister
    }
  }
})
</script>