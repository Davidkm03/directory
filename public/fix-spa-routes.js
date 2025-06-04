/**
 * Script de corrección para rutas SPA en hosting compartido
 * 
 * Este script debe incluirse en app.blade.php para corregir problemas de navegación
 * comunes en entornos de hosting compartido con SPAs de Vue.js
 */
document.addEventListener('DOMContentLoaded', function() {
  // Corregir enlaces históricos
  document.body.addEventListener('click', function(e) {
    // Solo procesar clics en enlaces que no sean externos
    if (e.target && e.target.tagName === 'A') {
      const href = e.target.getAttribute('href');
      
      // Si el enlace es interno (no tiene http ni comienza con // ni #) 
      if (href && !href.match(/^(https?:|\/\/|#|mailto:|tel:)/)) {
        e.preventDefault();
        
        // Obtener la URL base
        const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
        
        // Construir la ruta correcta
        const path = href.startsWith('/') ? href : `/${href}`;
        const fullPath = baseUrl + path.replace(/^\//, '');
        
        // Navegar usando history API
        window.history.pushState({}, '', fullPath);
        
        // Forzar un evento popstate para que Vue Router responda
        window.dispatchEvent(new PopStateEvent('popstate', { state: {} }));
        
        console.log('SPA route fixed: ', fullPath);
      }
    }
  });

  // Log para verificar que el script está funcionando
  console.log('SPA route fix script loaded successfully');
});
