<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Back Button -->
      <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <button 
            @click="$router.back()" 
            class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Directory
          </button>
        </div>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow p-8 text-center">
          <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="text-xl font-medium text-gray-900 mb-2">Profile Not Found</h3>
          <p class="text-gray-600 mb-4">{{ error }}</p>
          <button 
            @click="loadDentistProfile()" 
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Try Again
          </button>
        </div>
      </div>
      
      <!-- Dentist Profile -->
      <div v-else-if="dentist" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <!-- Cover Photo -->
          <div class="h-48 bg-gradient-to-r from-blue-500 to-teal-500 relative">
            <img
              v-if="dentist.coverImage"
              :src="dentist.coverImage"
              alt="Cover"
              class="w-full h-full object-cover"
            />
            
            <!-- Favorite Button -->
            <button
              v-if="isAuthenticated"
              @click="toggleFavorite"
              :disabled="favoriteLoading"
              class="absolute top-4 right-4 p-2 rounded-full bg-white shadow-md hover:bg-gray-100 transition-colors"
            >
              <svg
                class="w-6 h-6"
                :class="isFavorite ? 'text-red-500 fill-current' : 'text-gray-400'"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
            
            <!-- Profile Picture -->
            <div class="absolute -bottom-16 left-8">
              <div class="h-32 w-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white">
                <img
                  :src="dentist.profileImage || getDefaultAvatar(dentist.name)"
                  :alt="dentist.name"
                  class="h-full w-full object-cover"
                />
              </div>
            </div>
          </div>
          
          <!-- Basic Info -->
          <div class="pt-20 px-8 pb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
              <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">{{ dentist.name }}</h1>
                <p class="text-lg text-blue-600 font-medium mt-1">{{ dentist.specialty }}</p>
                
                <!-- Rating -->
                <div class="flex items-center mt-3">
                  <div class="flex">
                    <template v-for="i in 5" :key="i">
                      <svg
                        class="w-5 h-5"
                        :class="i <= dentist.rating ? 'text-yellow-400' : 'text-gray-300'"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </template>
                  </div>
                  <span class="ml-2 text-gray-600">
                    {{ dentist.rating.toFixed(1) }} out of 5 ({{ dentist.reviewCount }} reviews)
                  </span>
                </div>
              </div>
              
              <!-- Verification Badge -->
              <div v-if="dentist.verified" class="px-4 py-2 bg-green-100 text-green-800 rounded-full flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Verified
              </div>
            </div>
          </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-6">
            <!-- About -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">About</h2>
              <p class="text-gray-700 leading-relaxed">{{ dentist.bio }}</p>
            </div>

            <!-- Services -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Services Offered</h2>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="service in dentist.services" 
                  :key="service" 
                  class="px-3 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
                >
                  {{ service }}
                </span>
              </div>
            </div>

            <!-- Insurance -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Insurance Accepted</h2>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="insurance in dentist.insurance" 
                  :key="insurance" 
                  class="px-3 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium"
                >
                  {{ insurance }}
                </span>
              </div>
            </div>

            <!-- Languages -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Languages Spoken</h2>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="language in dentist.languages" 
                  :key="language" 
                  class="px-3 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium"
                >
                  {{ language }}
                </span>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
              
              <div class="space-y-4">
                <!-- Address -->
                <div class="flex items-start">
                  <svg class="w-5 h-5 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <div>
                    <p class="text-gray-700">{{ dentist.address }}</p>
                    <p class="text-gray-700">{{ dentist.location }}</p>
                  </div>
                </div>
                
                <!-- Phone -->
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <a :href="`tel:${dentist.phone}`" class="text-blue-600 hover:underline">{{ dentist.phone }}</a>
                </div>
                
                <!-- Website -->
                <div v-if="dentist.website" class="flex items-center">
                  <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                  </svg>
                  <a :href="dentist.website" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Visit Website</a>
                </div>
              </div>
              
              <!-- Contact Button -->
              <div class="mt-6">
                <button class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                  Contact Office
                </button>
              </div>
            </div>

            <!-- Professional Details -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Professional Details</h2>
              
              <div class="space-y-4">
                <!-- Education -->
                <div>
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path d="M12 14l9-5-9-5-9 5 9 5z" />
                      <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    <span class="font-medium text-gray-700">Education</span>
                  </div>
                  <p class="text-gray-600 ml-7">{{ dentist.education }}</p>
                </div>
                
                <!-- Experience -->
                <div>
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium text-gray-700">Experience</span>
                  </div>
                  <p class="text-gray-600 ml-7">{{ dentist.experience }} years</p>
                </div>
                
                <!-- License -->
                <div>
                  <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="font-medium text-gray-700">License</span>
                  </div>
                  <p class="text-gray-600 ml-7">{{ dentist.license }}</p>
                </div>
              </div>
            </div>

            <!-- Office Hours -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Office Hours</h2>
              
              <div class="space-y-2">
                <div v-for="(hours, day) in dentist.officeHours" :key="day" class="flex justify-between items-center">
                  <span class="font-medium text-gray-700 capitalize">{{ day }}</span>
                  <span class="text-gray-600">{{ hours }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Map Section -->
        <div class="mt-8">
          <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Location</h2>
            <div class="h-64 bg-gray-200 rounded-lg flex items-center justify-center">
              <div class="text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-gray-500">Interactive map would be displayed here</p>
                <p class="text-sm text-gray-400 mt-1">{{ dentist.address }}, {{ dentist.location }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'

interface Dentist {
  id: number
  name: string
  specialty: string
  location: string
  address: string
  phone: string
  website?: string
  email?: string
  bio: string
  rating: number
  reviewCount: number
  experience: number
  education: string
  license: string
  verified: boolean
  profileImage?: string
  coverImage?: string
  services: string[]
  insurance: string[]
  languages: string[]
  officeHours: Record<string, string>
  isFavorite?: boolean
}

export default defineComponent({
  name: 'DentistDetailView',
  components: {
    MainLayout
  },
  setup() {
    const route = useRoute()
    const router = useRouter()
    const authStore = useAuthStore()
    
    const dentist = ref<Dentist | null>(null)
    const loading = ref(true)
    const error = ref('')
    const isFavorite = ref(false)
    const favoriteLoading = ref(false)

    // Mock data - In real app, this would come from API
    const mockDentists: Record<string, Dentist> = {
      '1': {
        id: 1,
        name: 'Dr. Sarah Johnson',
        specialty: 'General Dentistry',
        location: 'New York, NY',
        address: '123 Main Street, Suite 100',
        phone: '+1 (555) 123-4567',
        website: 'https://drjohnson.dental',
        email: 'info@drjohnson.dental',
        bio: 'Dr. Sarah Johnson is a dedicated general dentist with over 8 years of experience providing comprehensive dental care. She is committed to creating a comfortable environment for her patients while delivering the highest quality dental treatments. Dr. Johnson stays current with the latest dental technologies and techniques to ensure her patients receive the best possible care.',
        rating: 4.8,
        reviewCount: 142,
        experience: 8,
        education: 'DDS, Columbia University College of Dental Medicine (2016)',
        license: 'NY-DDS-12345',
        verified: true,
        services: [
          'Preventive Care',
          'Teeth Cleaning',
          'Fillings',
          'Crowns & Bridges',
          'Root Canal',
          'Teeth Whitening',
          'Dental Implants'
        ],
        insurance: [
          'Delta Dental',
          'BlueCross BlueShield',
          'Aetna',
          'Cigna',
          'MetLife'
        ],
        languages: ['English', 'Spanish'],
        officeHours: {
          monday: '8:00 AM - 6:00 PM',
          tuesday: '8:00 AM - 6:00 PM',
          wednesday: '8:00 AM - 6:00 PM',
          thursday: '8:00 AM - 6:00 PM',
          friday: '8:00 AM - 4:00 PM',
          saturday: '9:00 AM - 2:00 PM',
          sunday: 'Closed'
        }
      },
      '2': {
        id: 2,
        name: 'Dr. Michael Chen',
        specialty: 'Orthodontics',
        location: 'Los Angeles, CA',
        address: '456 Oak Avenue, Suite 200',
        phone: '+1 (555) 987-6543',
        website: 'https://drchenortho.com',
        bio: 'Dr. Michael Chen is a board-certified orthodontist specializing in modern orthodontic treatments including traditional braces and Invisalign. With 12 years of experience, he has helped thousands of patients achieve beautiful, healthy smiles.',
        rating: 4.9,
        reviewCount: 238,
        experience: 12,
        education: 'DDS, UCLA School of Dentistry; MS Orthodontics, USC',
        license: 'CA-ORTHO-67890',
        verified: true,
        services: [
          'Traditional Braces',
          'Invisalign',
          'Clear Braces',
          'Retainers',
          'Early Treatment',
          'Adult Orthodontics'
        ],
        insurance: [
          'Anthem',
          'Kaiser Permanente',
          'Blue Shield',
          'UnitedHealthcare'
        ],
        languages: ['English', 'Mandarin', 'Cantonese'],
        officeHours: {
          monday: '9:00 AM - 5:00 PM',
          tuesday: '9:00 AM - 5:00 PM',
          wednesday: '9:00 AM - 5:00 PM',
          thursday: '9:00 AM - 5:00 PM',
          friday: '9:00 AM - 3:00 PM',
          saturday: 'By appointment',
          sunday: 'Closed'
        }
      }
    }

    const isAuthenticated = computed(() => authStore.isAuthenticated)

    const loadDentistProfile = async () => {
      loading.value = true
      error.value = ''
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        const dentistId = route.params.id as string
        const foundDentist = mockDentists[dentistId]
        
        if (!foundDentist) {
          throw new Error('Dentist not found')
        }
        
        dentist.value = foundDentist
        isFavorite.value = foundDentist.isFavorite || false
        
      } catch (err) {
        error.value = 'Could not load dentist profile. Please try again.'
        console.error('Error loading dentist profile:', err)
      } finally {
        loading.value = false
      }
    }

    const toggleFavorite = async () => {
      if (!isAuthenticated.value) {
        router.push({ name: 'login', query: { redirect: route.fullPath } })
        return
      }

      if (!dentist.value) return

      favoriteLoading.value = true
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 500))
        isFavorite.value = !isFavorite.value
        
      } catch (err) {
        console.error('Error toggling favorite:', err)
      } finally {
        favoriteLoading.value = false
      }
    }

    const getDefaultAvatar = (name: string) => {
      const initials = name.split(' ').map(n => n[0]).join('')
      return `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&background=3B82F6&color=fff&size=200`
    }

    onMounted(() => {
      loadDentistProfile()
    })

    return {
      dentist,
      loading,
      error,
      isAuthenticated,
      isFavorite,
      favoriteLoading,
      loadDentistProfile,
      toggleFavorite,
      getDefaultAvatar
    }
  }
})
</script>