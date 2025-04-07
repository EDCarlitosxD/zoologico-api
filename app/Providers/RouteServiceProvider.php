<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * El namespace de tu controlador.
     *
     * Si quieres, puedes usarlo para no escribir namespace en cada ruta.
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Este método se llama automáticamente al iniciar Laravel.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Aquí se registran las rutas.
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Rutas web: tienen sesión, cookies, CSRF, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Rutas API: aquí es donde quitamos el "api" prefix.
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}

