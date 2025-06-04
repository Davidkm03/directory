<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use PDO;
use PDOException;

class InstallerController extends Controller
{
    /**
     * Mostrar la página inicial del instalador
     */
    public function index()
    {
        // Si ya está instalado, redirigir al home
        if ($this->isInstalled()) {
            return redirect('/');
        }

        // Verificar los requisitos del sistema
        $requirements = $this->checkRequirements();
        $canContinue = !in_array(false, $requirements);

        return view('installer.welcome', compact('requirements', 'canContinue'));
    }

    /**
     * Mostrar el formulario de configuración
     */
    public function showConfig()
    {
        // Si ya está instalado, redirigir al home
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('installer.config');
    }

    /**
     * Procesar la configuración
     */
    public function processConfig(Request $request)
    {
        // Si ya está instalado, redirigir al home
        if ($this->isInstalled()) {
            return redirect('/');
        }

        // Validar los campos del formulario
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|string|url',
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        try {
            // Verificar conexión a la base de datos
            $this->testDatabaseConnection(
                $validated['db_host'],
                $validated['db_port'],
                $validated['db_name'],
                $validated['db_user'],
                $validated['db_password']
            );

            // Guardar configuración en .env
            $this->updateEnvFile($validated);

            // Ejecutar migraciones y seeders
            $this->runMigrations();

            // Crear usuario administrador
            $this->createAdminUser(
                $validated['admin_name'],
                $validated['admin_email'],
                $validated['admin_password']
            );

            // Marcar como instalado
            $this->setAsInstalled();

            return redirect()->route('installer.complete');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Installation error: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar página de instalación completa
     */
    public function complete()
    {
        if (!$this->isInstalled()) {
            return redirect()->route('installer.index');
        }

        return view('installer.complete');
    }

    /**
     * Verificar si el sistema ya está instalado
     */
    private function isInstalled()
    {
        return file_exists(storage_path('installed'));
    }

    /**
     * Verificar los requisitos del sistema
     */
    private function checkRequirements()
    {
        return [
            'php_version' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl' => extension_loaded('openssl'),
            'mbstring' => extension_loaded('mbstring'),
            'fileinfo' => extension_loaded('fileinfo'),
            'json' => extension_loaded('json'),
            'storage_writable' => is_writable(storage_path()),
            'cache_writable' => is_writable(base_path('bootstrap/cache')),
            'env_writable' => is_writable(base_path('.env.example')) && is_writable(base_path())
        ];
    }

    /**
     * Probar la conexión a la base de datos
     */
    private function testDatabaseConnection($host, $port, $database, $username, $password)
    {
        try {
            $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
            $connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Intentar crear la base de datos si no existe
            $connection->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            return true;
        } catch (PDOException $e) {
            throw new Exception('Could not connect to the database: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar el archivo .env con la configuración
     */
    private function updateEnvFile($config)
    {
        // Copiar .env.example a .env si no existe
        if (!file_exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
        }

        // Actualizar variables en .env
        $this->setEnv('APP_NAME', '"' . $config['app_name'] . '"');
        $this->setEnv('APP_URL', $config['app_url']);
        $this->setEnv('DB_HOST', $config['db_host']);
        $this->setEnv('DB_PORT', $config['db_port']);
        $this->setEnv('DB_DATABASE', $config['db_name']);
        $this->setEnv('DB_USERNAME', $config['db_user']);
        $this->setEnv('DB_PASSWORD', $config['db_password']);

        // Generar clave de la aplicación
        Artisan::call('key:generate', ['--force' => true]);
        
        return true;
    }

    /**
     * Establecer variable en .env
     */
    private function setEnv($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Si la clave existe, reemplazarla
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace('/' . $key . '=.*/', $key . '=' . $value, $content);
            } else {
                // Si la clave no existe, añadirla al final
                $content .= "\n" . $key . '=' . $value;
            }
            
            file_put_contents($path, $content);
        }
    }

    /**
     * Ejecutar migraciones y seeders
     */
    private function runMigrations()
    {
        try {
            // Ejecutar migraciones
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Ejecutar seeders si existen
            Artisan::call('db:seed', ['--force' => true]);
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Error running migrations: ' . $e->getMessage());
        }
    }

    /**
     * Crear usuario administrador
     */
    private function createAdminUser($name, $email, $password)
    {
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            
            // Aquí puedes asignar roles de administrador si tienes un sistema de roles
            
            return $user;
        } catch (Exception $e) {
            throw new Exception('Error creating admin user: ' . $e->getMessage());
        }
    }

    /**
     * Marcar como instalado
     */
    private function setAsInstalled()
    {
        // Crear archivo que indica que la aplicación está instalada
        File::put(storage_path('installed'), 'Installation completed on ' . date('Y-m-d H:i:s'));
        
        // Optimizar la aplicación
        Artisan::call('optimize:clear');
        
        return true;
    }
}
