<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-semibold text-gray-900">My Professional Profile</h1>
      
      <div v-if="loading" class="mt-6 flex justify-center">
        <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else-if="error" class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ error }}</p>
            <p class="mt-2 text-sm text-red-700">
              <router-link :to="{ name: 'dentist-profile-edit' }" class="font-medium underline text-red-700 hover:text-red-600">
                Complete my professional profile
              </router-link>
            </p>
          </div>
        </div>
      </div>

      <div v-else>
        <!-- Incomplete profile banner if applicable -->
        <div v-if="!profileComplete" class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-yellow-700">
                Your professional profile is incomplete. Complete it to be verified.
              </p>
              <p class="mt-2 text-sm text-yellow-700">
                <router-link :to="{ name: 'dentist-profile-edit' }" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                  Complete my profile
                </router-link>
              </p>
            </div>
          </div>
        </div>
        
        <!-- Profile Information -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Professional Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Personal and professional details
              </p>
            </div>
            <router-link :to="{ name: 'dentist-profile-edit' }" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Edit information
            </router-link>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Full name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.name }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Email de contacto</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.email }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Número de cédula profesional</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.registration_number || 'No especificado' }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">University</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.university || 'Not specified' }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Graduation year</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.graduation_year || 'No especificado' }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Ubicación de consultorio</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <span v-if="profile.office_address">{{ profile.office_address }}, {{ profile.city }}, {{ profile.state }}, CP {{ profile.postal_code }}</span>
                  <span v-else>No especificado</span>
                </dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ profile.phone_number || 'Not specified' }}</dd>
              </div>
              <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Services offered</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <ul v-if="profile.services_offered && profile.services_offered.length" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                    <li v-for="(service, index) in profile.services_offered" :key="index" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                      <div class="w-0 flex-1 flex items-center">
                        <span class="ml-2 flex-1 w-0 truncate">{{ service }}</span>
                      </div>
                    </li>
                  </ul>
                  <span v-else>Not specified</span>
                </dd>
              </div>
            </dl>
          </div>
        </div>
        
        <!-- Verification status -->
        <div class="mt-6">
          <verification-status
            :show-documents="true"
            @status-loaded="handleStatusLoaded"
          />
        </div>
        
        <!-- Document management -->
        <div class="mt-6">
          <router-link :to="{ name: 'dentist-profile-documents' }" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Manage verification documents
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { DentistProfile } from '../../../types/dentist';
import dentistProfileService from '../../../services/dentistProfileService';
import VerificationStatus from '../../../components/dentist/VerificationStatus.vue';

export default defineComponent({
  name: 'DentistProfile',
  components: {
    VerificationStatus
  },
  setup() {
    const router = useRouter();
    const profile = ref<DentistProfile>({} as DentistProfile);
    const user = ref<{ name: string; email: string }>({ name: '', email: '' });
    const loading = ref(true);
    const error = ref<string | null>(null);
    const verificationStatus = ref<string>('pending');
    const profileComplete = ref(false);

    const loadProfile = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await dentistProfileService.getProfile();
        profile.value = response.data.profile || {};
        user.value = response.data.user || { name: '', email: '' };
        
        // Check if the profile is complete
        checkProfileCompletion();
      } catch (err: any) {
        console.error('Error loading profile:', err);
        if (err.response && err.response.status === 404) {
          error.value = "No professional profile found. Please complete your information.";
        } else {
          error.value = "An error occurred while loading your information. Please try again later.";
        }
      } finally {
        loading.value = false;
      }
    };

    const checkProfileCompletion = () => {
      const requiredFields = [
        'registration_number', 
        'university', 
        'graduation_year',
        'city',
        'state',
        'postal_code',
        'phone_number'
      ];
      
      profileComplete.value = requiredFields.every(field => 
        profile.value[field as keyof DentistProfile] !== undefined && 
        profile.value[field as keyof DentistProfile] !== null &&
        profile.value[field as keyof DentistProfile] !== ''
      );
    };

    const handleStatusLoaded = (data: { status: string }) => {
      verificationStatus.value = data.status;
    };

    onMounted(loadProfile);

    return {
      profile,
      user,
      loading,
      error,
      verificationStatus,
      profileComplete,
      handleStatusLoaded
    };
  }
});
</script>
