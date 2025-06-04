import axios from 'axios';
import { DentistProfile } from '../types/dentist';

const API_URL = '/api';

export default {
  // Obtener el perfil actual del dentista
  getProfile() {
    return axios.get(`${API_URL}/dentist/profile`);
  },

  // Crear un nuevo perfil de dentista
  createProfile(profileData: Partial<DentistProfile>) {
    return axios.post(`${API_URL}/dentist/profile`, profileData);
  },

  // Actualizar el perfil de un dentista
  updateProfile(profileData: Partial<DentistProfile>) {
    return axios.put(`${API_URL}/dentist/profile`, profileData);
  },

  // Obtener el estado de verificación del perfil
  getVerificationStatus() {
    return axios.get(`${API_URL}/dentist/verification-status`);
  },

  // Subir un documento para verificación
  uploadDocument(formData: FormData) {
    return axios.post(`${API_URL}/dentist/document`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  // Eliminar un documento
  deleteDocument(documentId: number) {
    return axios.delete(`${API_URL}/dentist/document/${documentId}`);
  },

  // Obtener perfil público de un dentista
  getPublicProfile(userId: number) {
    return axios.get(`${API_URL}/dentist/public-profile/${userId}`);
  }
};
