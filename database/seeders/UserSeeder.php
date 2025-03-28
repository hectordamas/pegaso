<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Obtener los usuarios desde la tabla original `usuario`
        $usuarios = DB::table('usuario')->get();
    
        foreach ($usuarios as $usuario) {
            DB::table('users')->insert([
                'id'          => $usuario->codusuario,  // Se mantiene el mismo ID
                'codusuario'  => $usuario->codusuario,  // Se mantiene el mismo ID
                'codtipodoc'  => $usuario->codtipodoc,
                'documento'   => $usuario->documento,
                'name'        => $usuario->nombre,
                'email'       => $usuario->mail, // Laravel usa 'email' en lugar de 'mail'
                'telefonocel' => $usuario->telefonocel,
                'fecha'       => $usuario->fecha,
                'master'      => $usuario->master,
                'inactivo'    => $usuario->inactivo,
                'photo'       => $usuario->photo,
                'password'    => bcrypt('123456789'), // Convertir SHA-1 a bcrypt
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $user = new User();
        $user->codusuario = 1000;
        $user->codtipodoc = 1;
        $user->documento = '27439674';
        $user->name = "Héctor Damas";
        $user->email = 'hectorgabrieldm@hotmail.com';
        $user->telefonocel = '04241930033';
        $user->fecha = now();
        $user->master = true;
        $user->inactivo = false;
        $user->photo = null;
        $user->password  = bcrypt('123456789'); // Convertir SHA-1 a bcrypt
        $user->role = "Directiva";
        $user->save();

        $user->codusuario = $user->id;
        $user->save();

        echo "Usuarios importados correctamente.\n";
    }
}
