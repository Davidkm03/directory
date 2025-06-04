import { DentistProfile } from './dentist';

export interface DirectoryFilters {
    search?: string;
    specialty?: string;
    services?: string[];
    location?: string;
    languages?: string[];
    minExperience?: number;
    insurance?: string[];
    sortBy?: string;
    sortDir?: 'asc' | 'desc';
    latitude?: number;
    longitude?: number;
    radius?: number;
    perPage?: number;
    page?: number;
}

export interface FilterOptions {
    specialties: string[];
    services: string[];
    locations: string[];
    languages: string[];
    experience_range: {
        min: number;
        max: number;
    };
}

export interface MapPosition {
    lat: number;
    lng: number;
}

export interface MapMarker extends MapPosition {
    id: number;
    name: string;
    specialty?: string;
    office_address?: string;
    city?: string;
    state?: string;
    postal_code?: string;
    profile_image?: string;
    rating?: number;
    experience_years?: number;
    services?: string[];
    // Opcional: si necesitamos pasar más propiedades del dentista
    [key: string]: any;
}

export interface DirectoryState {
    profiles: DentistProfile[];
    loading: boolean;
    error: string | null;
    filters: DirectoryFilters;
    filterOptions: FilterOptions | null;
    totalResults: number;
    currentPage: number;
    lastPage: number;
    viewMode: 'grid' | 'list' | 'map';
    selectedProfile: DentistProfile | null;
    mapCenter: MapPosition;
    mapZoom: number;
    markers: MapMarker[];
    favorites: number[];
    favoritesLoading: boolean;
}
