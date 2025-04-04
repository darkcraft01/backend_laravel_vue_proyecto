<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function funListar(){
        // Listar usuarios
        //$usuarios = DB::select("SELECT * from users");
        $usuarios = User::get();
        return $usuarios;

    }

    public function funGuardar(Request $request){
        // Guardar usuario

        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "require"
        ]);

        try{
            $nombre = $request->name;
            $correo = $request->email;
            $password = $request->password;
    
            $usuario = new User();
            $usuario->name = $nombre;
            $usuario->email = $correo;
            $usuario->password = bcrypt($password);
            $usuario->save();
    
            return response()->json(["mensaje" => "Usuario Registrado"], 200);
        }catch(\Exception $e){
            return response()->json(["mensaje" => "Error del servidor", "error" => $e->getMessage()], 500);
        }

        

    }

    public function funMostrar($id){
        // Mostrar usuario
        $usuario = User::find($id);
        return response()->json($usuario, 200);
    }

    public function funModificar(Request $request, $id){
        // Modificar usuario
        $nombre = $request->name;
        $correo = $request->email;
        $password = $request->password;

        $usuario = User::find($id);
        $usuario->name = $nombre;
        $usuario->email = $correo;
        $usuario->password = bcrypt($password);
        $usuario->update();

        return response()->json(["mensaje" => "Usuario Actualizado"], 201);
    }

    public function funEliminar($id){
        // Eliminar usuario
        $usuario = User::find($id);
        $usuario->delete();

        return response()->json(["mensaje" => "Usuario Eliminado"], 200);
    }
}
