<?php
// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Script para crear un usuario administrador en un entorno de hosting compartido
// IMPORTANTE: ELIMINAR ESTE ARCHIVO INMEDIATAMENTE DESPUÉS DE USARLO

echo "<h1>Creación de usuario administrador</h1>";
echo "<p>Iniciando proceso...</p>";

try {
    // Cargamos el framework Laravel
    echo "<p>Cargando framework Laravel...</p>";
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\DB;

    // Verificar conexión a la base de datos
    echo "<p>Verificando conexión a la base de datos...</p>";
    
    try {
        // Verificar conexión a la base de datos
        DB::connection()->getPdo();
        echo "<p style='color:green;'>✅ Conexión exitosa a la base de datos: " . DB::connection()->getDatabaseName() . "</p>";
        
        // Mostrar la configuración de la BD (sin contraseña)
        echo "<p>Configuración de la BD: Host=" . config('database.connections.mysql.host') . 
             ", Puerto=" . config('database.connections.mysql.port') . 
             ", Base de datos=" . config('database.connections.mysql.database') . 
             ", Usuario=" . config('database.connections.mysql.username') . "</p>";
        
        echo "<p>Verificando si el usuario ya existe...</p>";
        $existingUser = User::where('email', 'davidkm0393@gmail.com')->first();

        if ($existingUser) {
            echo "<p>El usuario ya existe. Actualizando rol y contraseña...</p>";
            // Si ya existe, actualizamos el rol y la contraseña
            $existingUser->update([
                'password' => Hash::make('Dios1234'),
                'role' => 'admin',
            ]);
            echo "<p style='color:green;'>✅ Usuario administrador actualizado con éxito.</p>";
        } else {
            echo "<p>El usuario no existe. Creando nuevo usuario administrador...</p>";
            // Si no existe, lo creamos
            $user = User::create([
                'name' => 'Administrador',
                'email' => 'davidkm0393@gmail.com',
                'password' => Hash::make('Dios1234'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            if ($user) {
                echo "<p style='color:green;'>✅ Usuario administrador creado con éxito.</p>";
            } else {
                echo "<p style='color:red;'>❌ Error al crear el usuario administrador. Verifica la estructura de tu base de datos.</p>";
            }
        }
    } catch (\Exception $e) {
        echo "<p style='color:red;'>❌ ERROR DE CONEXIÓN A LA BASE DE DATOS: " . $e->getMessage() . "</p>";
        echo "<p>Por favor verifica las credenciales en tu archivo .env</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color:red;'>❌ ERROR GENERAL: " . $e->getMessage() . "</p>";
    echo "<p>Traza: " . $e->getTraceAsString() . "</p>";
}

// Instrucción de seguridad
echo "<br><br><strong style='color:red;'>IMPORTANTE: ELIMINA ESTE ARCHIVO INMEDIATAMENTE POR SEGURIDAD</strong>";
echo "<p>Fecha y hora de ejecución: " . date('Y-m-d H:i:s') . "</p>";
