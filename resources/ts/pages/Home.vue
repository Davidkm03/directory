<template>
  <MainLayout>
    <div class="bg-gradient-to-b from-blue-100 to-white min-h-screen">
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
          <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
            Welcome to the Dental Community
          </h1>
          <p class="mt-5 max-w-2xl mx-auto text-xl text-gray-500">
            The professional platform to connect with colleagues, share knowledge and improve your dental practice.
          </p>
          
          <!-- Call to Action Buttons -->
          <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <router-link 
              to="/register" 
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
              Join Now
            </router-link>
            <router-link 
              to="/directory" 
              class="inline-flex items-center px-6 py-3 border border-blue-600 text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
              Browse Directory
            </router-link>
          </div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
          <!-- Professional Community -->
          <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
            <div class="text-blue-500 text-4xl mb-4 flex justify-center">
              <!-- SVG Icon for Users -->
              <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-center">Professional Community</h3>
            <p class="text-gray-600 text-center">
              Connect with dentists worldwide, share experiences and expand your professional network.
            </p>
          </div>

          <!-- Educational Resources -->
          <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
            <div class="text-blue-500 text-4xl mb-4 flex justify-center">
              <!-- SVG Icon for Education -->
              <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-center">Educational Resources</h3>
            <p class="text-gray-600 text-center">
              Access training materials, clinical cases and the latest innovations in the field of dentistry.
            </p>
          </div>

          <!-- Specialized Forums -->
          <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
            <div class="text-blue-500 text-4xl mb-4 flex justify-center">
              <!-- SVG Icon for Chat -->
              <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-center">Specialized Forums</h3>
            <p class="text-gray-600 text-center">
              Participate in discussions about specialties, techniques and solutions to clinical challenges.
            </p>
          </div>
        </div>

        <!-- Statistics Section -->
        <div class="mt-20 bg-white rounded-lg shadow-lg p-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Join Our Growing Community</h2>
            <p class="mt-4 text-lg text-gray-500">Connect with dental professionals worldwide</p>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
              <div class="text-3xl font-bold text-blue-600">1,000+</div>
              <div class="text-sm text-gray-500 mt-1">Registered Dentists</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-blue-600">50+</div>
              <div class="text-sm text-gray-500 mt-1">Specialties Covered</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-blue-600">24/7</div>
              <div class="text-sm text-gray-500 mt-1">Community Support</div>
            </div>
          </div>
        </div>

        <!-- Newsletter Section -->
        <div class="mt-20 bg-blue-600 rounded-lg shadow-lg p-8 text-center">
          <h2 class="text-3xl font-extrabold text-white mb-4">Stay Updated</h2>
          <p class="text-blue-100 mb-6">Get the latest news and updates from the dental community</p>
          <div class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
            <input 
              type="email" 
              placeholder="Enter your email" 
              class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <button class="px-6 py-2 bg-white text-blue-600 font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-blue-600 transition-colors duration-200">
              Subscribe
            </button>
          </div>
        </div>
      </main>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import MainLayout from '@/layouts/MainLayout.vue'

export default defineComponent({
  name: 'HomePage',
  components: {
    MainLayout
  }
})
</script>