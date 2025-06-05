<?php
// Script para ejecutar migraciones de Laravel en hosting compartido
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Ejecutando migraciones de Laravel</h1>";
echo "<p>Iniciando proceso... " . date('Y-m-d H:i:s') . "</p>";

try {
    // Cargamos el framework Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "<p>Framework cargado correctamente</p>";

    // Ejecutamos las migraciones (similar a php artisan migrate)
    echo "<p>Ejecutando migraciones...</p>";
    $status = $kernel->call('migrate', ['--force' => true]);

    if ($status === 0) {
        echo "<p style='color:green;'>✅ Migraciones ejecutadas con éxito.</p>";
    } else {
        echo "<p style='color:red;'>❌ Hubo un problema al ejecutar las migraciones. Código de salida: $status</p>";
    }

    // Este script no crea usuarios automáticamente.
    // Simplemente muestra un enlace a create-admin.php
    // para que puedas crear manualmente un usuario administrador.
    echo "<p>¿Deseas crear un usuario administrador?</p>";
    echo "<p>Si las migraciones fueron exitosas, <a href='create-admin.php' style='color:blue;'>haz clic aquí para crear un usuario administrador</a>.</p>";

} catch (Exception $e) {
    echo "<p style='color:red;'>❌ ERROR: " . $e->getMessage() . "</p>";
    echo "<p>Traza: " . nl2br($e->getTraceAsString()) . "</p>";
}

echo "<hr>";
echo "<p>Si has completado este paso correctamente, <strong>elimina este archivo por seguridad</strong>.</p>";
