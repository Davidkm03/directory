<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solución para el error "Specified key was too long" en hosting compartidos
        // con configuraciones MySQL más restrictivas (menor que 767 bytes por índice)
        Schema::defaultStringLength(191);
    }
}
