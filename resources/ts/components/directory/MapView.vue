<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="h-[500px] w-full relative" ref="mapContainer">
      <!-- Overlay de carga -->
      <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-teal-500 border-t-transparent"></div>
      </div>
      
      <!-- Contenedor del mapa -->
      <div id="map" class="h-full w-full"></div>
      
      <!-- Información del dentista seleccionado -->
      <div 
        v-if="selectedDentist" 
        class="absolute bottom-4 left-4 right-4 bg-white rounded-lg shadow-lg p-4 z-10 max-w-md mx-auto"
      >
        <button 
          @click="clearSelectedDentist" 
          class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        
        <div class="flex items-start">
          <div class="flex-shrink-0 mr-3">
            <div class="h-12 w-12 rounded-full overflow-hidden">
              <img
                :src="selectedDentist.profile_image || '/images/default-profile.jpg'"
                :alt="selectedDentist.user?.name || 'Dentist'"
                class="h-full w-full object-cover"
              />
            </div>
          </div>
          <div>
            <h4 class="font-semibold text-gray-900">{{ selectedDentist.user?.name }}</h4>
            <p class="text-sm text-gray-600" v-if="selectedDentist.specialty">{{ selectedDentist.specialty }}</p>
            <div class="flex items-center mt-1 text-sm">
              <div class="flex">
                <template v-for="i in 5" :key="i">
                  <svg
                    class="w-3 h-3"
                    :class="i <= Math.round(selectedDentist.rating || 0) ? 'text-yellow-400' : 'text-gray-300'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </template>
              </div>
              <span class="ml-1">{{ selectedDentist.rating ? selectedDentist.rating.toFixed(1) : 'No ratings' }}</span>
            </div>
          </div>
        </div>
        
        <div class="mt-3">
          <p class="text-sm text-gray-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1114.142 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ selectedDentist.office_address }}, {{ selectedDentist.city }}, {{ selectedDentist.state }}
          </p>
        </div>
        
        <div class="mt-3 flex justify-end">
          <button
            @click="$emit('view-profile', selectedDentist)"
            class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500"
          >
            View profile
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch, PropType, onUnmounted, nextTick } from 'vue';
import { DentistProfile } from '../../types/dentist';
import { MapMarker } from '../../types/directory';

declare global {
  interface Window {
    google: any;
    initGoogleMap: () => void;
  }
}

export default defineComponent({
  name: 'MapView',
  
  props: {
    markers: {
      type: Array as PropType<MapMarker[]>,
      required: true,
    },
    center: {
      type: Object as PropType<{ lat: number; lng: number }>,
      default: () => ({ lat: 19.4326, lng: -99.1332 }), // Ciudad de México por defecto
    },
    zoom: {
      type: Number,
      default: 13,
    },
    loading: {
      type: Boolean,
      default: false,
    }
  },
  
  emits: ['marker-click', 'map-clicked', 'view-profile', 'map-moved', 'bounds-changed'],
  
  setup(props, { emit }) {
    const mapContainer = ref<HTMLElement | null>(null);
    const map = ref<any>(null);
    const googleMarkers = ref<any[]>([]);
    const selectedDentist = ref<DentistProfile | null>(null);
    
    // Inicializar el mapa
    const initMap = () => {
      if (!mapContainer.value || !window.google) return;
      
      map.value = new window.google.maps.Map(document.getElementById('map'), {
        center: props.center,
        zoom: props.zoom,
        styles: [
          // Estilos personalizados para el mapa (opcional)
        ],
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
        zoomControl: true,
      });
      
      // Eventos del mapa
      map.value.addListener('click', () => {
        selectedDentist.value = null;
        emit('map-clicked');
      });
      
      map.value.addListener('dragend', () => {
        const center = map.value.getCenter();
        emit('map-moved', { lat: center.lat(), lng: center.lng() });
      });
      
      map.value.addListener('zoom_changed', () => {
        // Obtener los límites actuales del mapa
        const bounds = map.value.getBounds();
        if (bounds) {
          const ne = bounds.getNorthEast();
          const sw = bounds.getSouthWest();
          
          emit('bounds-changed', {
            ne: { lat: ne.lat(), lng: ne.lng() },
            sw: { lat: sw.lat(), lng: sw.lng() },
          });
        }
      });
      
      // Crear marcadores iniciales
      createMarkers();
    };
    
    // Crear marcadores en el mapa
    const createMarkers = () => {
      // Limpiar marcadores existentes
      googleMarkers.value.forEach(marker => marker.setMap(null));
      googleMarkers.value = [];
      
      if (!map.value) return;
      
      // Crear nuevos marcadores
      props.markers.forEach(markerData => {
        const marker = new window.google.maps.Marker({
          position: { lat: markerData.lat, lng: markerData.lng },
          map: map.value,
          title: markerData.name,
          animation: window.google.maps.Animation.DROP,
          icon: {
            url: '/images/dentist-marker.png', // Puede reemplazarse con un ícono personalizado
            scaledSize: new window.google.maps.Size(35, 35),
          },
        });
        
        // Añadir evento de clic al marcador
        marker.addListener('click', () => {
          emit('marker-click', markerData.id);
          
          // Buscar el dentista correspondiente y mostrarlo
          const dentist = props.markers.find(m => m.id === markerData.id) as DentistProfile;
          if (dentist) {
            selectedDentist.value = dentist;
          }
        });
        
        googleMarkers.value.push(marker);
      });
    };
    
    // Montar componente
    onMounted(() => {
      // Si la API de Google Maps ya está cargada
      if (window.google && window.google.maps) {
        nextTick(() => initMap());
      } else {
        // Si aún no está cargada, configurar una función que será llamada cuando se cargue
        window.initGoogleMap = initMap;
        
        // Si no hay script de Google Maps, añadirlo
        if (!document.getElementById('google-maps-script')) {
          const apiKey = process.env.VUE_APP_GOOGLE_MAPS_API_KEY || '';
          const script = document.createElement('script');
          script.id = 'google-maps-script';
          script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initGoogleMap`;
          script.async = true;
          script.defer = true;
          document.head.appendChild(script);
        }
      }
    });
    
    // Observar cambios en los marcadores
    watch(() => props.markers, () => {
      nextTick(() => {
        if (map.value) createMarkers();
      });
    }, { deep: true });
    
    // Observar cambios en el centro del mapa
    watch(() => props.center, (newCenter) => {
      if (map.value) {
        map.value.setCenter(newCenter);
      }
    }, { deep: true });
    
    // Observar cambios en el zoom
    watch(() => props.zoom, (newZoom) => {
      if (map.value) {
        map.value.setZoom(newZoom);
      }
    });
    
    // Limpiar al desmontar
    onUnmounted(() => {
      if (googleMarkers.value.length) {
        googleMarkers.value.forEach(marker => marker.setMap(null));
      }
      
      // Eliminar la función global
      if (window.initGoogleMap) {
        delete window.initGoogleMap;
      }
    });
    
    // Limpiar dentista seleccionado
    const clearSelectedDentist = () => {
      selectedDentist.value = null;
    };
    
    return {
      mapContainer,
      selectedDentist,
      clearSelectedDentist,
    };
  },
});
</script>

<style scoped>
/* Estilos específicos para el mapa, si es necesario */
</style>
