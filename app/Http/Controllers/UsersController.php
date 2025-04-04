<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Menu, Consultor, MenuPermiso};
use Illuminate\Support\Facades\DB;
use Auth;

class UsersController extends Controller
{
    public function index(){
        $users = User::all();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(){
        $menus = Menu::where('inactivo', false)->orderBy('position', 'asc')->get();

        return view('users.create', [
            'menus' => $menus
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'codtipodoc' => 'required|in:1,2',
            'documento'  => 'required|min:6|max:9|unique:users,documento',
            'nombre'     => 'required|string|max:255',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'required|string|in:Directiva,Gerencia,Analista',
        ]);
    
        // Crear el usuario
        $user = new User();
        $user->codtipodoc = $validatedData['codtipodoc'];
        $user->documento = $validatedData['documento'];
        $user->name = $validatedData['nombre'];
        $user->telefonocel = $validatedData['telefono'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        $user->fecha = now();
        $user->master = $request->has('master'); // Verifica si el checkbox está marcado
        $user->inactivo = false;
        $user->password = bcrypt('12345678');
        $user->save();
    
        // Actualizar `codusuario` con el ID generado
        $user->codusuario = $user->id;
        $user->save();
    
        // Guardar si el usuario es consultor
        if ($request->has('consultor')) {
            $consultor = new Consultor();
            $consultor->codconsultor = $user->id;
            $consultor->codusuario = $user->id;
            $consultor->nombre = $user->name;
            $consultor->inactivo = false;
            $consultor->save();
        }
    
        // Guardar permisos seleccionados
        if ($request->has('codmenu')) {
            foreach ($request->codmenu as $codmenu) {
                $registra = $request->registra[$codmenu] ?? 0; 
                $vertodo   = $request->vertodo[$codmenu] ?? 0;
            
                $menupermiso = new MenuPermiso();
                $menupermiso->codusuario = $user->id;
                $menupermiso->codmenu = $codmenu;
                $menupermiso->registra = $registra;
                $menupermiso->vertodo = $vertodo;
                $menupermiso->save();
            }
        }
    
        return redirect('users')->with('message', 'Usuario creado con éxito!');
    }
    

    public function edit($id){
        $user = User::find($id);
        $menus = Menu::where('inactivo', false)->orderBy('position', 'asc')->get();

        // Obtener los IDs de los menús que el usuario tiene activados en 'menupermiso'
        $userMenuIds = DB::table('menupermiso')
        ->where('codusuario', $user->codusuario)
        ->pluck('codmenu') // Suponiendo que la clave foránea se llama 'menu_id'
        ->toArray(); // Convertir a array para facilitar la verificación

         // Obtener los menús donde 'registra' está activo
        $userRegistraIds = DB::table('menupermiso')
        ->where('codusuario', $user->codusuario)
        ->where('registra', 1)
        ->pluck('codmenu')
        ->toArray();

        // Obtener los menús donde 'vertodo' está activo
        $userVerTodoIds = DB::table('menupermiso')
            ->where('codusuario', $user->codusuario)
            ->where('vertodo', 1)
            ->pluck('codmenu')
            ->toArray();

        return view('users.edit', [
            'user' => $user,
            'menus' => $menus,
            'userMenuIds' => $userMenuIds, 
            'userRegistraIds' => $userRegistraIds,
            'userVerTodoIds' => $userVerTodoIds
        ]);
    }

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

    public function subirFoto(Request $request)
    {
        if ($request->has('captured_photo') && !empty($request->captured_photo)) {
            $base64Image = $request->captured_photo;

            $user = auth()->user();
            $user->photo = $base64Image;
            $user->save();

            return back()->with('success', 'Foto de perfil actualizada correctamente.');
        }


        return back()->with('error', 'No se recibió ninguna imagen.');
    }

    public function setMenu(Request $request)
    {
        $codusuario = $request->codusuario;
        $codmenu = $request->codmenu;
        $type = $request->type; // Puede ser "menu", "registra" o "vertodo"
        $check = $request->check;
    
        // Si el tipo es "menu", se activa/desactiva todo
        if ($type === 'menu') {
            if ($check) {
                DB::table('menupermiso')->updateOrInsert([
                    'codusuario' => $codusuario,
                    'codmenu' => $codmenu
                ], [
                    'registra' => 0, // Default
                    'vertodo' => 0, // Default
                ]);
            } else {
                DB::table('menupermiso')
                    ->where('codusuario', $codusuario)
                    ->where('codmenu', $codmenu)
                    ->delete();
            }
        } else {
            // Para "registra" y "vertodo", actualizamos solo ese campo
            DB::table('menupermiso')
                ->where('codusuario', $codusuario)
                ->where('codmenu', $codmenu)
                ->update([$type => $check]);
        }
    
        return response()->json(['success' => true, 'message' => 'Permiso actualizado correctamente']);
    }
    
    public function setUserConfig(Request $request) {
        $user = User::find($request->codusuario);
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        if ($request->field == 'consultor') {
            $consultor = Consultor::where('codusuario', $request->codusuario)->first();
    
            if ($consultor) {
                $consultor->inactivo = !$request->value;
                $consultor->save();
            } else {
                return response()->json(['error' => 'Consultor no encontrado'], 404);
            }
        } else {
            if($request->field == 'master'){
                $user->master = $request->value;
                $user->save();
            }else{
                $user->inactivo = !$request->value;
                $user->save();
            }

        }

        return response()->json(['success' => 'Configuración actualizada']);
    }

    public function setRole(Request $request){

        $user = User::find($request->userId);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('message', 'Rol Modificado con éxito!');
    }
    
}
