import axios from 'axios';
import { DirectoryFilters, FilterOptions } from '../types/directory';
import { DentistProfile } from '../types/dentist';

export default {
    /**
     * Obtener listado de dentistas con filtros
     */
    async getDentists(filters: DirectoryFilters = {}): Promise<any> {
        try {
            const response = await axios.get('/api/directory', { params: filters });
            return response.data;
        } catch (error) {
            console.error('Error fetching dentists:', error);
            throw error;
        }
    },

    /**
     * Obtener opciones para filtros
     */
    async getFilterOptions(): Promise<FilterOptions> {
        try {
            const response = await axios.get('/api/directory/filter-options');
            return response.data;
        } catch (error) {
            console.error('Error fetching filter options:', error);
            throw error;
        }
    },

    /**
     * Obtener detalles de un dentista
     */
    async getDentistProfile(id: number): Promise<DentistProfile> {
        try {
            const response = await axios.get(`/api/directory/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error fetching dentist ${id}:`, error);
            throw error;
        }
    },
    
    // Alias para mantener compatibilidad
    async getDentistDetails(id: number): Promise<DentistProfile> {
        return this.getDentistProfile(id);
    },

    /**
     * Marcar/desmarcar dentista como favorito
     */
    async toggleFavorite(id: number): Promise<{ isFavorite: boolean, message: string }> {
        try {
            const response = await axios.post(`/api/patient/favorites/${id}`);
            return response.data;
        } catch (error) {
            console.error(`Error toggling favorite for dentist ${id}:`, error);
            throw error;
        }
    },

    /**
     * Obtener lista de dentistas favoritos
     */
    async getFavorites(): Promise<DentistProfile[]> {
        try {
            const response = await axios.get('/api/patient/favorites');
            return response.data;
        } catch (error) {
            console.error('Error fetching favorites:', error);
            throw error;
        }
    }
};
