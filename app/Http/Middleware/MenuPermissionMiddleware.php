<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class MenuPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $codmenu)
    {
        $user = auth()->user(); // Obtener el usuario autenticado

        if (!$user) {
            return redirect()->route('login'); // Redirigir si no está autenticado
        }

        // Verificar si el usuario tiene permiso en menupermiso
        $tienePermiso = DB::table('menupermiso')
            ->where('codusuario', $user->codusuario)
            ->where('codmenu', $codmenu)
            ->exists();

        if (!$tienePermiso) {
            return redirect()->back()->with('error', 'No tienes permisos para acceder a esta sección'); // Bloquear acceso
        }

        return $next($request);
    }
}
