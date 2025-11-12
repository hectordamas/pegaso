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
            
                $menus = DB::table('menupermiso')
                    ->select('menus.*')
                    ->join('menus', 'menupermiso.codmenu', '=', 'menus.codmenu')
                    ->where('menupermiso.codusuario', $userId)
                    ->where('menus.inactivo', false)
                    ->orderBy('menus.position', 'asc')
                    ->get();
            
                // Agrupamos los menús
                $groupedMenus = [
                    'inicio'      => [],
                    'cxc'         => [],
                    'comisiones'  => [],
                    'config'      => [],
                    'otros'       => [],
                ];
            
                foreach ($menus as $menu) {
                    if (in_array($menu->codmenu, [3, 14])) {
                        $groupedMenus['cxc'][] = $menu;
                    } elseif (in_array($menu->codmenu, [143, 144, 145, 146])) {
                        $groupedMenus['comisiones'][] = $menu;
                    } elseif (in_array($menu->codmenu, [9, 10, 11])) {
                        $groupedMenus['config'][] = $menu;
                    } else {
                        $groupedMenus['otros'][] = $menu;
                    }
                }
            
                $view->with('groupedMenus', $groupedMenus);
            }
        });
    }


}
