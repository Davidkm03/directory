<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin', 'throttle:10,1']);
    }

    // Clear all caches
    public function clearCache(Request $request)
    {
        
        $output = [];
        
        // Clear configuration cache
        Artisan::call('config:clear');
        $output[] = 'Configuration: ' . trim(Artisan::output());
        
        // Clear routes cache
        Artisan::call('route:clear');
        $output[] = 'Routes: ' . trim(Artisan::output());
        
        // Clear view cache
        Artisan::call('view:clear');
        $output[] = 'Views: ' . trim(Artisan::output());
        
        // Clear application cache
        Artisan::call('cache:clear');
        $output[] = 'Application: ' . trim(Artisan::output());
        
        // Clear compiled cache
        Artisan::call('optimize:clear');
        $output[] = 'Optimization: ' . trim(Artisan::output());

        return response()->json([
            'success' => true,
            'message' => 'All caches have been cleared',
            'details' => $output
        ]);
    }

    // Regenerate cache for production
    public function optimizeCache(Request $request)
    {
        
        $output = [];
        
        // Generate configuration cache
        Artisan::call('config:cache');
        $output[] = 'Configuration: ' . trim(Artisan::output());
        
        // Generate routes cache
        Artisan::call('route:cache');
        $output[] = 'Routes: ' . trim(Artisan::output());
        
        // Optimize autoloader
        Artisan::call('optimize');
        $output[] = 'Optimization: ' . trim(Artisan::output());

        return response()->json([
            'success' => true,
            'message' => 'Cache optimized for production',
            'details' => $output
        ]);
    }
    
    // Rebuild frontend assets
    public function rebuildAssets(Request $request)
    {
        
        $output = [];
        
        // Clear JavaScript cache in /public
        if (file_exists(public_path('js'))) {
            $jsFiles = glob(public_path('js') . '/*');
            foreach($jsFiles as $file) {
                if(is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                    @unlink($file);
                    $output[] = 'Deleted: ' . basename($file);
                }
            }
        }
        
        // Clear CSS cache in /public
        if (file_exists(public_path('css'))) {
            $cssFiles = glob(public_path('css') . '/*');
            foreach($cssFiles as $file) {
                if(is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                    @unlink($file);
                    $output[] = 'Deleted: ' . basename($file);
                }
            }
        }
        
        // Run npm commands if in development environment
        if (app()->environment('local')) {
            // This won't work on shared hosting, only for development
            if (function_exists('exec')) {
                // Run npm and rebuild assets
                $rootPath = base_path();
                exec('cd ' . $rootPath . ' && npm run build 2>&1', $cmdOutput, $returnCode);
                $output[] = 'Build process: ' . ($returnCode === 0 ? 'Success' : 'Failed');
                $output[] = implode("\n", array_slice($cmdOutput, 0, 20)); // Show first 20 lines
            } else {
                $output[] = 'Cannot execute npm commands - exec function disabled';
            }
        } else {
            $output[] = 'Production environment - manual rebuild required';
            $output[] = 'Please run "npm run build" on your development machine and upload the compiled files';
        }

        return response()->json([
            'success' => true,
            'message' => 'Frontend assets cache cleared',
            'rebuildRequired' => app()->environment('production'),
            'details' => $output
        ]);
    }
}
