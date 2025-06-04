<template>
  <div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <h3 class="text-xl font-semibold mb-4">Search Filters</h3>
    
    <!-- Search by name/specialty -->
    <div class="mb-4">
      <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
      <div class="relative">
        <input
          type="text"
          id="search"
          v-model="localFilters.search"
          placeholder="Name or specialty..."
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50 pl-10"
          @input="debounceSearch"
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Specialty -->
    <div class="mb-4" v-if="filterOptions?.specialties?.length">
      <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
      <select
        id="specialty"
        v-model="localFilters.specialty"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50"
        @change="applyFilters"
      >
        <option value="">All specialties</option>
        <option v-for="specialty in filterOptions.specialties" :key="specialty" :value="specialty">
          {{ specialty }}
        </option>
      </select>
    </div>

    <!-- Services -->
    <div class="mb-4" v-if="filterOptions?.services?.length">
      <label class="block text-sm font-medium text-gray-700 mb-1">Services</label>
      <div class="max-h-48 overflow-y-auto p-2 border rounded-md">
        <div v-for="service in filterOptions.services" :key="service" class="flex items-center mb-2">
          <input
            type="checkbox"
            :id="'service-' + service"
            :value="service"
            v-model="localFilters.services"
            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
            @change="applyFilters"
          />
          <label :for="'service-' + service" class="ml-2 block text-sm text-gray-700">
            {{ service }}
          </label>
        </div>
      </div>
    </div>

    <!-- Location -->
    <div class="mb-4" v-if="filterOptions?.locations?.length">
      <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
      <select
        id="location"
        v-model="localFilters.location"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50"
        @change="applyFilters"
      >
        <option value="">All locations</option>
        <option v-for="location in filterOptions.locations" :key="location" :value="location">
          {{ location }}
        </option>
      </select>
    </div>

    <!-- Languages -->
    <div class="mb-4" v-if="filterOptions?.languages?.length">
      <label class="block text-sm font-medium text-gray-700 mb-1">Languages</label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="language in filterOptions.languages"
          :key="language"
          type="button"
          class="px-3 py-1 text-xs rounded-full"
          :class="localFilters.languages?.includes(language) ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700'"
          @click="toggleLanguage(language)"
        >
          {{ language }}
        </button>
      </div>
    </div>

    <!-- Years of experience -->
    <div class="mb-4" v-if="filterOptions?.experience_range">
      <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">
        Minimum experience: {{ localFilters.minExperience || 0 }} years
      </label>
      <input
        type="range"
        id="experience"
        v-model.number="localFilters.minExperience"
        :min="filterOptions.experience_range.min"
        :max="filterOptions.experience_range.max"
        step="1"
        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
        @change="applyFilters"
      />
    </div>

    <!-- Sort by -->
    <div class="mb-4">
      <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-1">Sort by</label>
      <div class="flex items-center space-x-2">
        <select
          id="sortBy"
          v-model="localFilters.sortBy"
          class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-500 focus:ring-opacity-50"
          @change="applyFilters"
        >
          <option value="rating">Rating</option>
          <option value="experience_years">Years of experience</option>
          <option value="created_at">Most recent</option>
        </select>
        
        <button 
          type="button"
          @click="toggleSortDirection"
          class="p-2 rounded bg-gray-100 hover:bg-gray-200"
        >
          <svg v-if="localFilters.sortDir === 'asc'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Action buttons -->
    <div class="flex flex-wrap gap-3 mt-6">
      <button
        type="button"
        @click="applyFilters"
        class="flex-grow px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-md shadow-sm transition-colors"
      >
        Apply Filters
      </button>
      <button
        type="button"
        @click="resetFilters"
        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md shadow-sm transition-colors"
      >
        Reset
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch, toRefs, PropType, computed } from 'vue';
import { DirectoryFilters, FilterOptions } from '../../types/directory';

export default defineComponent({
  name: 'DirectoryFilters',
  
  props: {
    filters: {
      type: Object as PropType<DirectoryFilters>,
      required: true,
    },
    filterOptions: {
      type: Object as PropType<FilterOptions>,
      required: false,
      default: null,
    },
  },
  
  emits: ['update:filters', 'apply-filters'],
  
  setup(props, { emit }) {
    const { filters, filterOptions } = toRefs(props);
    const localFilters = ref<DirectoryFilters>({ ...filters.value });
    const searchTimeout = ref<number | null>(null);
    
    // Reset local filters when props change
    watch(filters, (newFilters) => {
      localFilters.value = { ...newFilters };
    }, { deep: true });
    
    // Methods
    const applyFilters = () => {
      emit('update:filters', { ...localFilters.value });
      emit('apply-filters');
    };
    
    const resetFilters = () => {
      localFilters.value = {
        search: '',
        specialty: '',
        services: [],
        location: '',
        languages: [],
        minExperience: 0,
        sortBy: 'rating',
        sortDir: 'desc',
        perPage: 10,
        page: 1,
      };
      applyFilters();
    };
    
    const debounceSearch = () => {
      if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
      }
      
      searchTimeout.value = window.setTimeout(() => {
        applyFilters();
      }, 500);
    };
    
    const toggleLanguage = (language: string) => {
      if (!localFilters.value.languages) {
        localFilters.value.languages = [];
      }
      
      const index = localFilters.value.languages.indexOf(language);
      
      if (index === -1) {
        localFilters.value.languages.push(language);
      } else {
        localFilters.value.languages.splice(index, 1);
      }
      
      applyFilters();
    };
    
    const toggleSortDirection = () => {
      localFilters.value.sortDir = localFilters.value.sortDir === 'asc' ? 'desc' : 'asc';
      applyFilters();
    };
    
    return {
      localFilters,
      applyFilters,
      resetFilters,
      debounceSearch,
      toggleLanguage,
      toggleSortDirection,
    };
  },
});
</script>
