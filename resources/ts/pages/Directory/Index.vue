<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="py-6">
            <h1 class="text-3xl font-bold text-gray-900">Dentist Directory</h1>
            <p class="mt-2 text-gray-600">Find qualified dental professionals in your area</p>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and View Controls -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 space-y-4 lg:space-y-0">
          <!-- Search Bar -->
          <div class="flex-1 max-w-lg">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Search by name, specialty, or location..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>

          <!-- View Mode Selector -->
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">View:</span>
            <button
              @click="viewMode = 'grid'"
              :class="viewMode === 'grid' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'"
              class="p-2 rounded-md hover:bg-blue-50 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
            <button
              @click="viewMode = 'list'"
              :class="viewMode === 'list' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'"
              class="p-2 rounded-md hover:bg-blue-50 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Specialty Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
              <select
                v-model="filters.specialty"
                @change="applyFilters"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">All Specialties</option>
                <option value="general">General Dentistry</option>
                <option value="orthodontics">Orthodontics</option>
                <option value="endodontics">Endodontics</option>
                <option value="periodontics">Periodontics</option>
                <option value="oral-surgery">Oral Surgery</option>
                <option value="pediatric">Pediatric Dentistry</option>
                <option value="cosmetic">Cosmetic Dentistry</option>
              </select>
            </div>

            <!-- Location Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
              <input
                v-model="filters.location"
                @input="applyFilters"
                type="text"
                placeholder="City, State"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- Experience Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Min. Experience</label>
              <select
                v-model="filters.experience"
                @change="applyFilters"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Any</option>
                <option value="1">1+ Years</option>
                <option value="5">5+ Years</option>
                <option value="10">10+ Years</option>
                <option value="15">15+ Years</option>
              </select>
            </div>

            <!-- Sort Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
              <select
                v-model="filters.sortBy"
                @change="applyFilters"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="rating">Highest Rated</option>
                <option value="experience">Most Experienced</option>
                <option value="name">Name (A-Z)</option>
                <option value="location">Location</option>
              </select>
            </div>
          </div>

          <!-- Clear Filters -->
          <div class="mt-4 flex justify-end">
            <button
              @click="clearFilters"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Clear Filters
            </button>
          </div>
        </div>

        <!-- Results Count -->
        <div class="mb-6">
          <p class="text-gray-600">
            {{ loading ? 'Loading...' : `Showing ${filteredDentists.length} of ${totalDentists} dentists` }}
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="loading && !dentists.length" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
          <svg class="mx-auto h-12 w-12 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="text-lg font-medium text-red-800 mb-2">Error Loading Directory</h3>
          <p class="text-red-600 mb-4">{{ error }}</p>
          <button
            @click="loadDentists"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
          >
            Try Again
          </button>
        </div>

        <!-- No Results -->
        <div v-else-if="!filteredDentists.length && !loading" class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Dentists Found</h3>
          <p class="text-gray-600 mb-4">Try adjusting your search criteria or filters.</p>
          <button
            @click="clearFilters"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Clear All Filters
          </button>
        </div>

        <!-- Grid View -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="dentist in paginatedDentists"
            :key="dentist.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
          >
            <!-- Profile Image -->
            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
              <img
                :src="dentist.profileImage || getDefaultAvatar(dentist.name)"
                :alt="dentist.name"
                class="w-full h-48 object-cover"
              />
            </div>

            <!-- Content -->
            <div class="p-6">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900">{{ dentist.name }}</h3>
                <button
                  v-if="isAuthenticated"
                  @click="toggleFavorite(dentist.id)"
                  class="p-1 rounded-full hover:bg-gray-100"
                >
                  <svg
                    class="w-5 h-5"
                    :class="dentist.isFavorite ? 'text-red-500 fill-current' : 'text-gray-400'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
              </div>

              <p class="text-sm text-blue-600 font-medium mb-2">{{ dentist.specialty }}</p>

              <!-- Rating -->
              <div class="flex items-center mb-3">
                <div class="flex">
                  <template v-for="i in 5" :key="i">
                    <svg
                      class="w-4 h-4"
                      :class="i <= dentist.rating ? 'text-yellow-400' : 'text-gray-300'"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </template>
                </div>
                <span class="ml-2 text-sm text-gray-600">{{ dentist.rating.toFixed(1) }} ({{ dentist.reviewCount }} reviews)</span>
              </div>

              <!-- Location -->
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ dentist.location }}
              </div>

              <!-- Experience -->
              <div class="flex items-center text-sm text-gray-600 mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ dentist.experience }} years experience
              </div>

              <!-- View Profile Button -->
              <button
                @click="viewProfile(dentist)"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
              >
                View Profile
              </button>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
          <div
            v-for="dentist in paginatedDentists"
            :key="dentist.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
          >
            <div class="p-6 flex flex-col md:flex-row">
              <!-- Profile Image -->
              <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                <img
                  :src="dentist.profileImage || getDefaultAvatar(dentist.name)"
                  :alt="dentist.name"
                  class="w-24 h-24 rounded-full object-cover mx-auto md:mx-0"
                />
              </div>

              <!-- Content -->
              <div class="flex-grow">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ dentist.name }}</h3>
                    <p class="text-blue-600 font-medium mb-2">{{ dentist.specialty }}</p>

                    <!-- Rating -->
                    <div class="flex items-center mb-3">
                      <div class="flex">
                        <template v-for="i in 5" :key="i">
                          <svg
                            class="w-4 h-4"
                            :class="i <= dentist.rating ? 'text-yellow-400' : 'text-gray-300'"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                        </template>
                      </div>
                      <span class="ml-2 text-sm text-gray-600">{{ dentist.rating.toFixed(1) }} ({{ dentist.reviewCount }} reviews)</span>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ dentist.location }}
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ dentist.experience }} years experience
                      </div>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center space-x-2">
                    <button
                      v-if="isAuthenticated"
                      @click="toggleFavorite(dentist.id)"
                      class="p-2 rounded-full hover:bg-gray-100"
                    >
                      <svg
                        class="w-5 h-5"
                        :class="dentist.isFavorite ? 'text-red-500 fill-current' : 'text-gray-400'"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                    </button>
                    <button
                      @click="viewProfile(dentist)"
                      class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                      View Profile
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="mt-8 flex justify-center">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>

            <template v-for="page in getPageNumbers()" :key="page">
              <button
                v-if="page !== '...'"
                @click="changePage(page)"
                :class="page === currentPage 
                  ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              >
                {{ page }}
              </button>
              <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                ...
              </span>
            </template>

            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'

interface Dentist {
  id: number
  name: string
  specialty: string
  location: string
  experience: number
  rating: number
  reviewCount: number
  profileImage?: string
  isFavorite: boolean
  bio?: string
}

interface Filters {
  specialty: string
  location: string
  experience: string
  sortBy: string
}

export default defineComponent({
  name: 'DirectoryIndex',
  components: {
    MainLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    // Reactive data
    const loading = ref(false)
    const error = ref('')
    const searchQuery = ref('')
    const viewMode = ref<'grid' | 'list'>('grid')
    const currentPage = ref(1)
    const itemsPerPage = ref(12)
    
    const filters = ref<Filters>({
      specialty: '',
      location: '',
      experience: '',
      sortBy: 'rating'
    })

    // Mock data - In real app, this would come from API
    const dentists = ref<Dentist[]>([
      {
        id: 1,
        name: 'Dr. Sarah Johnson',
        specialty: 'General Dentistry',
        location: 'New York, NY',
        experience: 8,
        rating: 4.8,
        reviewCount: 142,
        isFavorite: false,
        bio: 'Experienced general dentist focused on preventive care and patient comfort.'
      },
      {
        id: 2,
        name: 'Dr. Michael Chen',
        specialty: 'Orthodontics',
        location: 'Los Angeles, CA',
        experience: 12,
        rating: 4.9,
        reviewCount: 238,
        isFavorite: false,
        bio: 'Specialized in modern orthodontic treatments including Invisalign.'
      },
      {
        id: 3,
        name: 'Dr. Emily Rodriguez',
        specialty: 'Pediatric Dentistry',
        location: 'Chicago, IL',
        experience: 6,
        rating: 4.7,
        reviewCount: 89,
        isFavorite: true,
        bio: 'Dedicated to making dental visits fun and comfortable for children.'
      },
      {
        id: 4,
        name: 'Dr. David Kim',
        specialty: 'Oral Surgery',
        location: 'Houston, TX',
        experience: 15,
        rating: 4.6,
        reviewCount: 167,
        isFavorite: false,
        bio: 'Expert in complex oral surgeries and dental implants.'
      },
      {
        id: 5,
        name: 'Dr. Lisa Thompson',
        specialty: 'Cosmetic Dentistry',
        location: 'Miami, FL',
        experience: 10,
        rating: 4.9,
        reviewCount: 195,
        isFavorite: false,
        bio: 'Specializing in smile makeovers and aesthetic treatments.'
      },
      {
        id: 6,
        name: 'Dr. Robert Wilson',
        specialty: 'Endodontics',
        location: 'Seattle, WA',
        experience: 14,
        rating: 4.5,
        reviewCount: 123,
        isFavorite: false,
        bio: 'Root canal specialist with advanced pain management techniques.'
      }
    ])

    // Computed properties
    const isAuthenticated = computed(() => authStore.isAuthenticated)
    
    const filteredDentists = computed(() => {
      let filtered = [...dentists.value]

      // Search filter
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(dentist =>
          dentist.name.toLowerCase().includes(query) ||
          dentist.specialty.toLowerCase().includes(query) ||
          dentist.location.toLowerCase().includes(query)
        )
      }

      // Specialty filter
      if (filters.value.specialty) {
        filtered = filtered.filter(dentist =>
          dentist.specialty.toLowerCase().includes(filters.value.specialty.toLowerCase())
        )
      }

      // Location filter
      if (filters.value.location) {
        filtered = filtered.filter(dentist =>
          dentist.location.toLowerCase().includes(filters.value.location.toLowerCase())
        )
      }

      // Experience filter
      if (filters.value.experience) {
        const minExp = parseInt(filters.value.experience)
        filtered = filtered.filter(dentist => dentist.experience >= minExp)
      }

      // Sort
      switch (filters.value.sortBy) {
        case 'rating':
          filtered.sort((a, b) => b.rating - a.rating)
          break
        case 'experience':
          filtered.sort((a, b) => b.experience - a.experience)
          break
        case 'name':
          filtered.sort((a, b) => a.name.localeCompare(b.name))
          break
        case 'location':
          filtered.sort((a, b) => a.location.localeCompare(b.location))
          break
      }

      return filtered
    })

    const totalDentists = computed(() => dentists.value.length)
    const totalPages = computed(() => Math.ceil(filteredDentists.value.length / itemsPerPage.value))
    
    const paginatedDentists = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value
      const end = start + itemsPerPage.value
      return filteredDentists.value.slice(start, end)
    })

    // Methods
    const loadDentists = async () => {
      loading.value = true
      error.value = ''
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        // In real app, would fetch from API here
        // const response = await fetch('/api/dentists')
        // dentists.value = await response.json()
        
      } catch (err) {
        error.value = 'Failed to load dentists. Please try again.'
        console.error('Error loading dentists:', err)
      } finally {
        loading.value = false
      }
    }

    const handleSearch = () => {
      currentPage.value = 1
    }

    const applyFilters = () => {
      currentPage.value = 1
    }

    const clearFilters = () => {
      searchQuery.value = ''
      filters.value = {
        specialty: '',
        location: '',
        experience: '',
        sortBy: 'rating'
      }
      currentPage.value = 1
    }

    const toggleFavorite = (dentistId: number) => {
      if (!isAuthenticated.value) {
        router.push({ name: 'login', query: { redirect: router.currentRoute.value.fullPath } })
        return
      }

      const dentist = dentists.value.find(d => d.id === dentistId)
      if (dentist) {
        dentist.isFavorite = !dentist.isFavorite
        // In real app, would also update on server
      }
    }

    const viewProfile = (dentist: Dentist) => {
      router.push({ name: 'directory-detail', params: { id: dentist.id.toString() } })
    }

    const changePage = (page: number) => {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        window.scrollTo({ top: 0, behavior: 'smooth' })
      }
    }

    const getPageNumbers = () => {
      const pages: (number | string)[] = []
      const delta = 2

      for (let i = 1; i <= totalPages.value; i++) {
        if (
          i === 1 ||
          i === totalPages.value ||
          (i >= currentPage.value - delta && i <= currentPage.value + delta)
        ) {
          pages.push(i)
        } else if (i === currentPage.value - delta - 1 || i === currentPage.value + delta + 1) {
          pages.push('...')
        }
      }

      return pages
    }

    const getDefaultAvatar = (name: string) => {
      // Generate a placeholder avatar URL
      const initials = name.split(' ').map(n => n[0]).join('')
      return `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&background=3B82F6&color=fff&size=200`
    }

    // Watch for filter changes
    watch([searchQuery, filters], () => {
      currentPage.value = 1
    }, { deep: true })

    // Load data on mount
    onMounted(() => {
      loadDentists()
    })

    return {
      // Data
      loading,
      error,
      searchQuery,
      viewMode,
      currentPage,
      filters,
      dentists,
      
      // Computed
      isAuthenticated,
      filteredDentists,
      totalDentists,
      totalPages,
      paginatedDentists,
      
      // Methods
      loadDentists,
      handleSearch,
      applyFilters,
      clearFilters,
      toggleFavorite,
      viewProfile,
      changePage,
      getPageNumbers,
      getDefaultAvatar
    }
  }
})
</script>