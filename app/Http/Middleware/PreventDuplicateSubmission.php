<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreventDuplicateSubmission
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->method() === 'POST') {
            // Creamos una clave única con base en los datos
            $key = sha1(auth()->id() . $request->fullUrl() . serialize($request->all()));

            // Si ya existe la clave en cache, es duplicada
            if (Cache::has($key)) {
                return response('', 204); // Silenciosamente ignoramos
            }

            // Deja pasar la solicitud
            $response = $next($request);

            // Si fue exitosa (2xx o 3xx), la marcamos como ya procesada
            if ($response->isSuccessful() || $response->isRedirection()) {
                Cache::put($key, true, now()->addSeconds(10));
            }

            return $response;
        }

        // Si no es POST, pasa normalmente
        return $next($request);
    }
}
