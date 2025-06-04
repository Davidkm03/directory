import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'dentist' | 'patient';
  specialty?: string;
  license_number?: string;
  biography?: string;
  profile_photo_path?: string;
  email_verified_at?: string;
  created_at?: string;
  updated_at?: string;
}

interface LoginCredentials {
  email: string;
  password: string;
  remember?: boolean;
}

interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

interface RegisterDentistData extends RegisterData {
  specialty: string;
  license_number: string;
  biography?: string;
  profile_photo?: File | null;
}

interface AuthError {
  message: string;
  errors?: Record<string, string[]>;
}

export const useAuthStore = defineStore('auth', () => {
  // Estado
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('auth_token'));
  const loading = ref<boolean>(false);
  const error = ref<AuthError | null>(null);

  // Getters
  const isAuthenticated = computed(() => !!token.value);
  const userInitials = computed(() => {
    if (!user.value?.name) return '?';
    return user.value.name
      .split(' ')
      .map(name => name[0])
      .join('')
      .toUpperCase()
      .substring(0, 2);
  });

  // Acciones
  async function getCurrentUser() {
    if (!token.value) return null;
    
    try {
      loading.value = true;
      error.value = null;
      
      const response = await fetch('/api/user', {
        headers: {
          'Authorization': `Bearer ${token.value}`,
          'Accept': 'application/json',
        }
      });
      
      if (!response.ok) {
        throw new Error('No se pudo obtener la información del usuario');
      }
      
      user.value = await response.json();
      return user.value;
    } catch (err) {
      error.value = {
        message: err instanceof Error ? err.message : 'Error desconocido'
      };
      return null;
    } finally {
      loading.value = false;
    }
  }

  async function login(credentials: LoginCredentials) {
    try {
      loading.value = true;
      error.value = null;
      
      const csrf = await getCsrfToken();
      
      const response = await fetch('/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify(credentials),
        credentials: 'same-origin'
      });
      
      const data = await response.json();
      
      if (!response.ok) {
        error.value = {
          message: data.message || 'Error de autenticación',
          errors: data.errors
        };
        return false;
      }
      
      // Guardar token y usuario
      token.value = data.token;
      localStorage.setItem('auth_token', data.token);
      user.value = data.user;
      
      return true;
    } catch (err) {
      error.value = {
        message: err instanceof Error ? err.message : 'Error de conexión'
      };
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function register(data: RegisterData, type: 'patient' | 'dentist' = 'patient') {
    try {
      loading.value = true;
      error.value = null;
      
      const csrf = await getCsrfToken();
      
      let url = `/api/register/${type}`;
      let headers: Record<string, string> = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf,
      };
      
      let body: FormData | string;
      
      // Si es dentista y tiene foto, usar FormData
      if (type === 'dentist' && 'profile_photo' in data && data.profile_photo) {
        const formData = new FormData();
        Object.entries(data).forEach(([key, value]) => {
          if (key === 'profile_photo' && value instanceof File) {
            formData.append(key, value);
          } else if (value !== null && value !== undefined) {
            formData.append(key, String(value));
          }
        });
        body = formData;
      } else {
        headers['Content-Type'] = 'application/json';
        body = JSON.stringify(data);
      }
      
      const response = await fetch(url, {
        method: 'POST',
        headers,
        body,
        credentials: 'same-origin'
      });
      
      const responseData = await response.json();
      
      if (!response.ok) {
        error.value = {
          message: responseData.message || 'Error al registrarse',
          errors: responseData.errors
        };
        return false;
      }
      
      // Guardar token y usuario
      token.value = responseData.token;
      localStorage.setItem('auth_token', responseData.token);
      user.value = responseData.user;
      
      return true;
    } catch (err) {
      error.value = {
        message: err instanceof Error ? err.message : 'Error de conexión'
      };
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    try {
      loading.value = true;
      error.value = null;
      
      if (!token.value) {
        clearAuth();
        return true;
      }
      
      const csrf = await getCsrfToken();
      
      await fetch('/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token.value}`,
          'X-CSRF-TOKEN': csrf,
        },
        credentials: 'same-origin'
      });
      
      // Limpiar datos de autenticación
      clearAuth();
      return true;
    } catch (err) {
      error.value = {
        message: err instanceof Error ? err.message : 'Error al cerrar sesión'
      };
      // De todos modos, limpiar datos locales
      clearAuth();
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function getCsrfToken(): Promise<string> {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
      return metaTag.getAttribute('content') || '';
    }
    
    // Si no hay meta tag, intentar hacer una petición para obtener el token
    await fetch('/sanctum/csrf-cookie');
    const refreshedMetaTag = document.querySelector('meta[name="csrf-token"]');
    return refreshedMetaTag?.getAttribute('content') || '';
  }

  function clearAuth() {
    user.value = null;
    token.value = null;
    localStorage.removeItem('auth_token');
  }

  // Inicializar usuario si hay token
  if (token.value) {
    getCurrentUser().catch(() => {
      clearAuth();
    });
  }

  return {
    // Estado
    user,
    token,
    loading,
    error,
    
    // Getters
    isAuthenticated,
    userInitials,
    
    // Acciones
    login,
    register,
    logout,
    getCurrentUser,
    clearAuth
  }
});
