<?php

// Este archivo permite ejecutar comandos Artisan desde el navegador
// ¡IMPORTANTE! Elimina este archivo después de usarlo

// Establecer tiempo máximo de ejecución a 5 minutos
set_time_limit(300);

// Comprobación de seguridad básica - cambia esto a tu valor secreto
$secret_token = 'eKzPKHshLSTV5tgd';
$requested_token = $_GET['token'] ?? '';

if ($requested_token !== $secret_token) {
    echo "Acceso denegado. Token incorrecto.";
    exit;
}

// Cargar Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Comando a ejecutar
$command = $_GET['command'] ?? '';
$allowedCommands = [
    'migrate', 
    'db:seed', 
    'cache:clear', 
    'config:clear', 
    'view:clear',
    'app:setup-db'
];

// Verificar si el comando está permitido
if (!in_array($command, $allowedCommands) && strpos($command, 'app:') !== 0) {
    echo "Comando no permitido por seguridad.";
    exit;
}

// Parámetros adicionales
$params = [];
if (isset($_GET['force']) && $_GET['force'] === '1') {
    $params['--force'] = true;
}

if (isset($_GET['email'])) {
    $params['--admin-email'] = $_GET['email'];
}

if (isset($_GET['password'])) {
    $params['--admin-password'] = $_GET['password'];
}

if (isset($_GET['name'])) {
    $params['--admin-name'] = $_GET['name'];
}

// Mostrar detalles
echo "<html><head><title>Ejecución de comandos Artisan</title>";
echo "<style>
body { font-family: sans-serif; line-height: 1.6; margin: 40px; color: #333; max-width: 800px; }
h1, h2 { color: #4a5568; }
pre { background: #f0f0f0; padding: 12px; border-radius: 4px; overflow: auto; }
.success { color: green; }
.error { color: red; }
.warning { color: darkorange; background: #fff4e5; padding: 10px; border-left: 4px solid darkorange; margin: 20px 0; }
</style></head><body>";

echo "<h1>Ejecución de Artisan: <code>$command</code></h1>";

echo "<div class='warning'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo <code>artisan.web.php</code> inmediatamente después de usarlo.</div>";

// Información de entorno
echo "<h2>🔍 Información del entorno</h2>";
echo "<ul>";
echo "<li><strong>Versión de PHP:</strong> " . phpversion() . "</li>";
echo "<li><strong>Ambiente Laravel:</strong> " . app()->environment() . "</li>";
echo "<li><strong>Conexión DB:</strong> " . config('database.default') . "</li>";
echo "<li><strong>Host DB:</strong> " . config('database.connections.mysql.host') . "</li>";
echo "<li><strong>Base de datos:</strong> " . config('database.connections.mysql.database') . "</li>";
echo "</ul>";

// Ejecutar el comando
echo "<h2>⚙️ Ejecutando comando...</h2>";
echo "<pre>";
ob_start();
$exitCode = $kernel->call($command, $params);
$output = ob_get_clean();
echo htmlspecialchars($output);
echo "</pre>";

if ($exitCode === 0) {
    echo "<p class='success'>✅ ¡Comando completado con éxito! Código de salida: $exitCode</p>";
} else {
    echo "<p class='error'>❌ Error al ejecutar el comando. Código de salida: $exitCode</p>";
}

// Enlaces para otras acciones
echo "<h2>🔄 Acciones disponibles</h2>";
echo "<ul>";
echo "<li><a href='?token=$secret_token&command=migrate&force=1'>Ejecutar migraciones (--force)</a></li>";
echo "<li><a href='?token=$secret_token&command=app:setup-db'>Configurar base de datos y crear admin</a></li>";
echo "<li><a href='?token=$secret_token&command=cache:clear'>Limpiar caché</a></li>";
echo "<li><a href='?token=$secret_token&command=config:clear'>Limpiar caché de configuración</a></li>";
echo "<li><a href='?token=$secret_token&command=view:clear'>Limpiar caché de vistas</a></li>";
echo "</ul>";

echo "<p>Este archivo debe ser <strong>eliminado inmediatamente</strong> después de su uso por razones de seguridad.</p>";
echo "</body></html>";
