<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use Auth;
use Illuminate\Support\Facades\DB;

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
        View::composer('layouts.admin', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                
                // Obtener los menús desde menupermiso
                $menus = DB::table('menupermiso')
                    ->select('menus.*')
                    ->join('menus', 'menupermiso.codmenu', '=', 'menus.codmenu')
                    ->where('menupermiso.codusuario', $userId)
                    ->where('menus.inactivo', false)
                    ->orderBy('menus.position', 'asc')
                    ->get();

                
                // Pasar los menús a todas las vistas
                $view->with('globalMenus', $menus);
            }
        });
    }
}
