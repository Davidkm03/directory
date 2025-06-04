export interface DentistDocument {
  id?: number;
  dentist_profile_id: number;
  document_type: string;
  file_path: string;
  original_filename: string;
  mime_type: string;
  file_size: number;
  status: 'pending' | 'approved' | 'rejected';
  admin_notes?: string;
  document_url?: string; // URL pública del documento
  created_at?: string;
  updated_at?: string;
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

export interface DentistProfile {
  id?: number;
  user_id: number;
  user?: User; // Relación con el usuario
  registration_number: string;
  university: string;
  graduation_year: number;
  professional_experience?: string;
  experience_years?: number; // Años de experiencia
  specialty?: string; // Especialidad dental
  services_offered?: string[];
  services?: string[]; // Alias para services_offered
  services_list?: string[]; // Versión procesada para el directorio
  languages?: string[];
  office_address?: string;
  office_hours?: Record<string, string>; // Horarios de atención
  city: string;
  state: string;
  postal_code: string;
  phone_number: string;
  website_url?: string;
  social_media?: Record<string, string>;
  accepts_insurance: boolean;
  insurance_networks?: string[];
  insurance_accepted?: string; // Información sobre seguros aceptados
  consultation_fee?: number; // Costo de consulta
  rating?: number; // Calificación promedio
  latitude?: number; // Coordenada para el mapa
  longitude?: number; // Coordenada para el mapa
  profile_image?: string; // URL de la imagen de perfil
  cover_image?: string; // URL de la imagen de portada
  verification_status: 'pending' | 'approved' | 'rejected';
  verification_notes?: string; // Notas visibles para el dentista
  admin_notes?: string; // Notas internas solo para administradores
  verified_at?: string;
  verified_by?: number;
  created_at?: string;
  updated_at?: string;
  documents?: DentistDocument[];
  // Campo computado para obtener la dirección completa
  full_address?: string;
}
