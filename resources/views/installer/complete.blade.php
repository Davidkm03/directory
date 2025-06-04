<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador - Instalación Completada - Comunidad Odontológica</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-blue-800 tracking-tight">Comunidad Odontológica</h1>
                <p class="mt-3 text-xl text-gray-600">Instalación Completada</p>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-semibold text-gray-800">¡Felicidades!</h2>
                            <p class="mt-1 text-gray-600">La plataforma Comunidad Odontológica se ha instalado correctamente.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Por razones de seguridad, debes eliminar la carpeta "installer" o deshabilitar el acceso al instalador.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900">Próximos pasos</h3>

                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-base font-medium text-gray-800">Iniciar sesión como administrador</h4>
                                <p class="mt-1 text-sm text-gray-600">Utiliza las credenciales que ingresaste durante la instalación para acceder al panel de administración.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-base font-medium text-gray-800">Personalizar la plataforma</h4>
                                <p class="mt-1 text-sm text-gray-600">Configura los ajustes específicos para tu comunidad odontológica desde el panel de administración.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-base font-medium text-gray-800">Invitar a usuarios</h4>
                                <p class="mt-1 text-sm text-gray-600">Comienza a crecer tu comunidad invitando a colegas profesionales de la odontología.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                    <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ir a la página principal
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Comunidad Odontológica. Todos los derechos reservados.
            </div>
        </div>
    </div>
</body>
</html>
