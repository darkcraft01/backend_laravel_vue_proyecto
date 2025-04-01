<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function funListar(){
        // Listar usuarios
        $usuarios = DB::select("SELECT * from users");
        return $usuarios;

    }

    public function funGuardar(Request $request){
        // Guardar usuario

        $nombre = $request->name;
        $correo = $request->email;
        $password = $request->password;

        $usuario = new User();
        $usuario->name = $nombre;
        $usuario->email = $correo;
        $usuario->password = bcrypt($password);
        $usuario->save();

        return ["mensaje" => "Usuario Registrado"];

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
