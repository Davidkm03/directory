import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Import layout component
const MainLayout = () => import('../layouts/MainLayout.vue')

// COMPONENTES BÁSICOS
const Home = () => import('../pages/Home.vue')
const Login = () => import('../pages/auth/Login.vue')

// Función para crear componentes temporales
const createTempComponent = (title: string) => ({
  template: `
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
      <div class="text-center p-8 bg-white rounded-lg shadow max-w-lg">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">${title}</h2>
        <p class="text-gray-600 mb-4">
          Esta página está en desarrollo y estará disponible pronto.
        </p>
        <router-link to="/" class="text-blue-600 hover:text-blue-800">
          ← Regresar al inicio
        </router-link>
      </div>
    </div>
  `
})

// COMPONENTES TEMPORALES PARA PRUEBAS
const Contact = createTempComponent('Contacto')
const Community = createTempComponent('Comunidad')

// COMPONENTES QUE PUEDEN FALLAR - Los manejamos con fallback
const safeImport = (importPath: string, fallbackName: string) => {
  return () => import(importPath).catch(() => {
    console.warn(`Component ${importPath} failed to load, using fallback`)
    return {
      template: `
        <div class="min-h-screen bg-gray-50 flex items-center justify-center">
          <div class="text-center p-8 bg-white rounded-lg shadow">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">${fallbackName}</h2>
            <p class="text-gray-600 mb-4">Esta página no está disponible temporalmente.</p>
            <router-link to="/" class="text-blue-600 hover:text-blue-800">
              ← Regresar al inicio
            </router-link>
          </div>
        </div>
      `
    }
  })
}

// IMPORTACIONES SEGURAS CON FALLBACK
const RegisterType = safeImport('../pages/auth/RegisterType.vue', 'Registro')
const RegisterPatient = safeImport('../pages/auth/RegisterPatient.vue', 'Registro de Pacientes')
const RegisterDentist = safeImport('../pages/auth/RegisterDentist.vue', 'Registro de Dentistas')
const Dashboard = safeImport('../pages/Dashboard/Index.vue', 'Panel de Control')
const DirectoryIndex = safeImport('../pages/Directory/Index.vue', 'Directorio')
const DirectoryDetail = safeImport('../pages/Directory/DetailView.vue', 'Perfil de Dentista')

// COMPONENTES ADMIN (COMENTADOS POR AHORA)
// const Admin = safeImport('../pages/Admin/Index.vue', 'Admin Panel')
// const AdminVerification = safeImport('../pages/Admin/Verification/Index.vue', 'Verification')

// RUTAS PRINCIPALES CORREGIDAS
const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    component: MainLayout,
    children: [
      // Página principal
      {
        path: '',
        name: 'home',
        component: Home
      },
      // Directorio público
      {
        path: 'directory',
        name: 'directory',
        component: DirectoryIndex
      },
      {
        path: 'directory/:id',
        name: 'directory-detail',
        component: DirectoryDetail,
        props: true
      },
      // Páginas de contacto y comunidad (temporales)
      {
        path: 'contact',
        name: 'contact',
        component: Contact
      },
      {
        path: 'community',
        name: 'community',
        component: Community
      },
      // Dashboard se mueve dentro del layout
      {
        path: 'dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
      }
    ]
  },
  // Rutas de autenticación como rutas independientes
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterType
  },
  {
    path: '/register/patient',
    name: 'register-patient',
    component: RegisterPatient
  },
  {
    path: '/register/dentist',
    name: 'register-dentist',
    component: RegisterDentist
  },
  // Ruta fallback para URLs no encontradas
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: createTempComponent('Página no encontrada'),
    meta: { title: 'Página no encontrada' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  },
  // Configuraciones adicionales que mejoran la navegación
  linkActiveClass: 'active',
  linkExactActiveClass: 'active-exact'
})

// Middleware de navegación optimizado
router.beforeEach(async (to, from, next) => {
  // Si la ruta requiere autenticación
  if (to.matched.some(record => record.meta.requiresAuth)) {
    const token = localStorage.getItem('auth_token')
    
    if (!token) {
      next({ 
        name: 'login',
        query: { redirect: to.fullPath }
      })
      return
    }
    
    // Verificación básica de autenticación sin validación compleja de roles
    const authStore = useAuthStore()
    
    // Verificamos si tenemos la información del usuario
    if (!authStore.user) {
      try {
        // Intentamos obtener los datos del usuario
        await authStore.getCurrentUser()
      } catch (error) {
        console.error('Error al obtener datos del usuario:', error)
        next({ name: 'login' })
        return
      }
    }
    
    next()
  } else {
    // Rutas públicas
    next()
  }
})

export default router