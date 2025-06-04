<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prueba de CSS</title>
    <!-- Usar estilos inline para asegurarnos de que funciona -->
    <style>
        .test-box {
            padding: 1rem;
            margin: 1rem;
            background-color: blue;
            color: white;
            border-radius: 0.5rem;
        }
    </style>
    <!-- Cargar directamente el CSS compilado con URL absoluta -->
    <link href="http://127.0.0.1:8001/build/assets/app-D8IWtaEv.css" rel="stylesheet">
</head>
<body>
    <h1>Diagnóstico de CSS</h1>
    
    <!-- Caja con estilos inline - esto debería verse bien siempre -->
    <div class="test-box">
        Este recuadro usa estilos inline y debería verse azul con texto blanco.
    </div>
    
    <!-- Caja con clases de Tailwind - solo funciona si Tailwind carga -->
    <div class="p-4 m-4 bg-blue-500 text-white rounded-lg">
        Si ves este texto en un recuadro azul con texto blanco, Tailwind funciona.
    </div>
    
    <hr>
    <div>
        <h3>Información de depuración:</h3>
        <p>URL del CSS: <code>{{ asset('build/assets/app-D8IWtaEv.css') }}</code></p>
        <p>URL de la aplicación: <code>{{ config('app.url') }}</code></p>
        <p>Ruta absoluta al archivo CSS: <code>{{ public_path('build/assets/app-D8IWtaEv.css') }}</code></p>
        <p>¿El archivo existe? <code>{{ file_exists(public_path('build/assets/app-D8IWtaEv.css')) ? 'Sí' : 'No' }}</code></p>
    </div>
</body>
</html>
