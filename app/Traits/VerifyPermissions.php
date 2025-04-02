<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\Menu;

trait VerifyPermissions
{
    public function hasPermissions($campo)
    {
        // Obtener la ruta actual sin parámetros
        $rutaActual = Request::path();

        // Buscar el menú correspondiente a la ruta
        $menu = Menu::where('ruta', $rutaActual)->first();

        // Si no hay menú registrado para esta ruta, devolver `false`
        if (!$menu) {
            return false;
        }

        // Buscar el permiso del usuario para ese menú
        $permiso = Auth::user()->menupermisos->where('codmenu', $menu->codmenu)->first();

        return $permiso ? $permiso->$campo : false;
    }
}