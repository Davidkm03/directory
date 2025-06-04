<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SetupDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-db {--admin-email=} {--admin-password=} {--admin-name=Administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta migraciones y configura la base de datos con un usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar la conexión a la BD
        try {
            $this->info('Verificando conexión a la base de datos...');
            DB::connection()->getPdo();
            $this->info('✅ Conexión establecida a: ' . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('❌ Error de conexión a la base de datos: ' . $e->getMessage());
            $this->info('Verifica las credenciales en .env: ');
            $this->info('DB_HOST=' . config('database.connections.mysql.host'));
            $this->info('DB_DATABASE=' . config('database.connections.mysql.database'));
            $this->info('DB_USERNAME=' . config('database.connections.mysql.username'));
            return 1;
        }
        
        // Ejecutar migraciones
        $this->info('Ejecutando migraciones...');
        $this->call('migrate', ['--force' => true]);
        
        // Crear usuario administrador
        $email = $this->option('admin-email') ?: env('ADMIN_EMAIL');
        $password = $this->option('admin-password') ?: env('ADMIN_PASSWORD');
        $name = $this->option('admin-name');

        if (!$email || !$password) {
            $this->error('Admin email and password are required. Provide them as options or set ADMIN_EMAIL and ADMIN_PASSWORD in .env');
            return 1;
        }
        
        $this->info('Creando usuario administrador...');
        
        try {
            // Verificar si el usuario ya existe
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'email_verified_at' => now()
                ]);
                $this->info("✅ Usuario administrador actualizado: {$email}");
            } else {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'email_verified_at' => now()
                ]);
                $this->info("✅ Usuario administrador creado: {$email}");
            }
            
            $this->info('✅ Configuración de base de datos completada exitosamente');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error al crear usuario administrador: ' . $e->getMessage());
            return 1;
        }
    }
}
