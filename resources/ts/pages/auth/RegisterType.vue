<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Select Registration Type</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Already have an account?
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
            Sign in
          </router-link>
        </p>
      </div>

      <div class="mt-8 space-y-6">
        <div class="flex flex-col space-y-4">
          <router-link to="/register/patient" 
            class="py-4 px-6 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
            I am a Patient
          </router-link>
          
          <router-link to="/register/dentist" 
            class="py-4 px-6 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-center">
            I am a Dentist
          </router-link>
          
          <div class="pt-4">
            <p class="text-center text-sm text-gray-600">
              Choose the option that best fits your profile
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'RegisterTypePage'
})
</script>
