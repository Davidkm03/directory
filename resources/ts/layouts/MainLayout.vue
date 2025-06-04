<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation bar -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <!-- Logo -->
              <router-link to="/" class="text-xl font-bold text-blue-800">
                Dental Community
              </router-link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <!-- Desktop navigation links -->
              <router-link 
                to="/" 
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-blue-500 text-gray-900': $route.name === 'home' }"
              >
                Home
              </router-link>
              <router-link 
                to="/directory" 
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-blue-500 text-gray-900': $route.path.startsWith('/directory') }"
              >
                Dentist Directory
              </router-link>
              <router-link 
                to="/contact" 
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Contact
              </router-link>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <!-- Access buttons -->
            <template v-if="!isAuthenticated">
              <router-link 
                to="/login" 
                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                @click="handleNavigation('/login')"
              >
                Login
              </router-link>
              <router-link 
                to="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium"
                @click="handleNavigation('/register')"
              >
                Register
              </router-link>
            </template>
            <!-- User menu -->
            <div v-else class="ml-3 relative dropdown-container">
              <div>
                <button 
                  @click="toggleDropdown" 
                  type="button"
                  class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <span class="sr-only">Open user menu</span>
                  <div class="h-8 w-8 rounded-full bg-blue-200 flex items-center justify-center text-blue-600">
                    {{ userInitials }}
                  </div>
                </button>
              </div>
              <!-- Dropdown menu -->
              <div 
                v-if="isDropdownOpen" 
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50"
              >
                <router-link 
                  to="/dashboard" 
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  @click="handleNavigation('/dashboard'); toggleDropdown()"
                >
                  My Profile
                </router-link>
                <router-link 
                  to="/dashboard/settings" 
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  @click="handleNavigation('/dashboard/settings'); toggleDropdown()"
                >
                  Settings
                </router-link>
                <button 
                  @click="logout(); toggleDropdown()" 
                  type="button"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Logout
                </button>
              </div>
            </div>
          </div>
          
          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button 
              @click="toggleMobileMenu" 
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
            >
              <span class="sr-only">Abrir menú principal</span>
              <!-- Icono de hamburguesa -->
              <svg 
                v-if="!isMobileMenuOpen" 
                class="block h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <!-- Icono X -->
              <svg 
                v-else 
                class="block h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Mobile menu -->
      <div v-if="isMobileMenuOpen" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <router-link 
            to="/" 
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            :class="{ 'border-blue-500 bg-blue-50 text-blue-700': $route.name === 'home', 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800': $route.name !== 'home' }"
            @click="handleNavigation('/'); toggleMobileMenu()"
          >
            Home
          </router-link>
          <router-link 
            to="/directory" 
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            :class="{ 'border-blue-500 bg-blue-50 text-blue-700': $route.path.startsWith('/directory'), 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800': !$route.path.startsWith('/directory') }"
            @click="handleNavigation('/directory'); toggleMobileMenu()"
          >
            Dentist Directory
          </router-link>
          <router-link 
            to="/contact" 
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800"
            @click="handleNavigation('/contact'); toggleMobileMenu()"
          >
            Contact
          </router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
          <div v-if="isAuthenticated" class="flex items-center px-4">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-600">
                {{ userInitials }}
              </div>
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-gray-800">{{ userName }}</div>
              <div class="text-sm font-medium text-gray-500">{{ userEmail }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <template v-if="isAuthenticated">
              <router-link 
                to="/dashboard" 
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                @click="handleNavigation('/dashboard'); toggleMobileMenu()"
              >
                My Profile
              </router-link>
              <router-link 
                to="/dashboard/settings" 
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                @click="handleNavigation('/dashboard/settings'); toggleMobileMenu()"
              >
                Settings
              </router-link>
              <button 
                @click="logout(); toggleMobileMenu()" 
                class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
              >
                Logout
              </button>
            </template>
            <template v-else>
              <router-link 
                to="/login" 
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                @click="handleNavigation('/login'); toggleMobileMenu()"
              >
                Login
              </router-link>
              <router-link 
                to="/register" 
                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                @click="handleNavigation('/register'); toggleMobileMenu()"
              >
                Register
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Main content -->
    <main>
      <slot></slot>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white mt-12 border-t border-gray-200">
      <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
        <nav class="-mx-5 -my-2 flex flex-wrap justify-center" aria-label="Footer">
          <div class="px-5 py-2">
            <a href="#" class="text-base text-gray-500 hover:text-gray-900">About</a>
          </div>
          <div class="px-5 py-2">
            <a href="#" class="text-base text-gray-500 hover:text-gray-900">Blog</a>
          </div>
          <div class="px-5 py-2">
            <a href="#" class="text-base text-gray-500 hover:text-gray-900">Contact</a>
          </div>
          <div class="px-5 py-2">
            <a href="#" class="text-base text-gray-500 hover:text-gray-900">Terms and conditions</a>
          </div>
          <div class="px-5 py-2">
            <a href="#" class="text-base text-gray-500 hover:text-gray-900">Privacy policy</a>
          </div>
        </nav>
        <p class="mt-8 text-center text-base text-gray-500">
          &copy; {{ new Date().getFullYear() }} Dental Community. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default defineComponent({
  name: 'MainLayout',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore() // THIS LINE WAS MISSING
    
    // User dropdown menu state
    const isDropdownOpen = ref(false)
    const isMobileMenuOpen = ref(false)
    
    // Check if user is authenticated
    const isAuthenticated = computed(() => {
      return authStore.user !== null && authStore.user !== undefined && authStore.user.id !== undefined
    })
    
    // User data
    const userName = computed(() => {
      return authStore.user?.name || 'User'
    })
    
    const userEmail = computed(() => {
      return authStore.user?.email || ''
    })
    
    // Calculate user initials
    const userInitials = computed(() => {
      if (!authStore.user?.name) return '?'
      
      const parts = authStore.user.name.split(' ')
      if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
      return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
    })
    
    // Toggle dropdown menu state
    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value
    }
    
    // Toggle mobile menu
    const toggleMobileMenu = () => {
      isMobileMenuOpen.value = !isMobileMenuOpen.value
    }
    
    // Function to ensure correct navigation in production environments
    const handleNavigation = (path: string) => {
      try {
        console.log(`Navigating to: ${path}`)
        router.push(path)
      } catch (error) {
        console.error('Navigation error:', error)
        window.location.href = path
      }
    }
    
    // Logout
    const logout = async () => {
      try {
        console.log('Logging out...')
        await axios.post('/logout', {}, {
          headers: { 
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
          }
        })
      } catch (error) {
        console.error('Logout error', error)
      } finally {
        // Remove token and authentication state
        localStorage.removeItem('auth_token')
        // Clear user state
        authStore.$reset()
        router.push({ name: 'login' })
      }
    }
    
    // Detect clicks outside menus to close them
    const handleOutsideClick = (event: Event) => {
      const target = event.target as Element
      if (isDropdownOpen.value && !target.closest('.dropdown-container')) {
        isDropdownOpen.value = false
      }
    }
    
    // Add listener for clicks outside menu
    onMounted(() => {
      document.addEventListener('click', handleOutsideClick)
    })
    
    onUnmounted(() => {
      document.removeEventListener('click', handleOutsideClick)
    })
    
    return {
      isAuthenticated,
      isDropdownOpen,
      isMobileMenuOpen,
      userName,
      userEmail,
      userInitials,
      toggleDropdown,
      toggleMobileMenu,
      handleNavigation, // WAS MISSING IN RETURN
      logout
    }
  }
})
</script>