<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User};
use Auth;

class UsersController extends Controller
{
    public function editarPerfil($id){
        $user = User::find($id);

        if($id != Auth::id()){
            return redirect()->back()->with('error', 'No tienes permisos para acceder a esta ruta!');
        }

        return view('users.update-profile', [
            'user' => $user
        ]);
    }

    public function updateProfile($id, Request $request){
        $user = User::find($id);
        $user->telefonocel = $request->telefonocel;
        $user->save();

        return redirect()->back()->with('message', 'Usuario modificado con éxito!');
    }

    public function updatePassword($id, Request $request){
        if(!$request->password){
            return redirect()->back()->with('error', 'Escribe una contraseña válida.');
        }

        if($request->password != $request->input('confirm-password')){
            return redirect()->back()->with('error', 'La contraseña de verificación no coincide.');
        }

        $user = User::find($id);
        if($request->password){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->back()->with('message', 'Constraseña Actualizada con Éxito');
    } 
}
