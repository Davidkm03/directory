import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import '../css/app.css'

// Crear aplicación Vue con Pinia para gestión de estado
const app = createApp(App)
const pinia = createPinia()

// Montar aplicación con router y pinia
app.use(router)
app.use(pinia)
app.mount('#app')
