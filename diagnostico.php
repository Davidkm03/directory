<?php
// Script simple para diagnosticar la conexión en hosting compartido
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico del entorno</h1>";

// Información de PHP
echo "<h2>Información de PHP</h2>";
echo "<p>Versión de PHP: " . phpversion() . "</p>";
echo "<p>Versión detallada: " . PHP_VERSION . "</p>";
echo "<p>PHP SAPI: " . php_sapi_name() . "</p>";

// Información del sistema
echo "<p>Sistema operativo: " . PHP_OS . "</p>";

// Límites de PHP
echo "<h3>Límites de PHP:</h3>";
echo "<ul>";
echo "<li>memory_limit: " . ini_get('memory_limit') . "</li>";
echo "<li>max_execution_time: " . ini_get('max_execution_time') . " segundos</li>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "</ul>";

// Verificar extensiones necesarias
$requiredExtensions = ['pdo_mysql', 'mbstring', 'xml', 'curl', 'json', 'fileinfo', 'openssl'];
echo "<h3>Extensiones requeridas:</h3>";
echo "<ul>";
foreach ($requiredExtensions as $extension) {
    echo "<li>$extension: " . (extension_loaded($extension) ? "<span style='color:green'>✓ Instalada</span>" : "<span style='color:red'>✗ No instalada</span>") . "</li>";
}
echo "</ul>";

// 1. Información PHP
echo "<h2>Información PHP</h2>";
echo "<p>Versión PHP: " . phpversion() . "</p>";
echo "<p>Extensiones cargadas: " . implode(", ", get_loaded_extensions()) . "</p>";

// 2. Verificar si puede leer archivos
echo "<h2>Acceso a archivos</h2>";
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    echo "<p>✅ El archivo .env existe</p>";
    
    // Leer el archivo .env para verificar las credenciales
    // (Solo mostramos DB_HOST y DB_DATABASE por seguridad)
    $env_content = file_get_contents($env_file);
    preg_match('/DB_HOST=(.*)/', $env_content, $host_matches);
    preg_match('/DB_DATABASE=(.*)/', $env_content, $db_matches);
    
    echo "<p>DB_HOST=" . (isset($host_matches[1]) ? $host_matches[1] : 'No encontrado') . "</p>";
    echo "<p>DB_DATABASE=" . (isset($db_matches[1]) ? $db_matches[1] : 'No encontrado') . "</p>";
} else {
    echo "<p>❌ El archivo .env no existe o no es accesible</p>";
}

// 3. Probar conexión directa a MySQL
echo "<h2>Prueba de conexión directa</h2>";
try {
    // Intentar leer db config de .env
    function getEnv($key, $default = null) {
        if (file_exists(__DIR__ . '/.env')) {
            $env_content = file_get_contents(__DIR__ . '/.env');
            preg_match('/' . $key . '=(.*)/', $env_content, $matches);
            return isset($matches[1]) ? trim($matches[1]) : $default;
        }
        return $default;
    }
    
    $db_host = getEnv('DB_HOST', 'localhost');
    $db_name = getEnv('DB_DATABASE', 'zkutpiez_dental');
    $db_user = getEnv('DB_USERNAME', 'zkutpiez_dental');
    $db_pass = getEnv('DB_PASSWORD', '');
    
    echo "<p>Intentando conectar a $db_host como $db_user...</p>";
    
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($mysqli->connect_error) {
        echo "<p>❌ Error de conexión: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p>✅ Conexión exitosa a MySQL</p>";
        
        // Ver si hay tablas
        $result = $mysqli->query("SHOW TABLES");
        if ($result) {
            if ($result->num_rows > 0) {
                echo "<p>Tablas existentes:</p><ul>";
                while ($row = $result->fetch_array()) {
                    echo "<li>" . $row[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No hay tablas en la base de datos</p>";
            }
        }
        
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p>Fecha y hora: " . date('Y-m-d H:i:s') . "</p>";
?>
