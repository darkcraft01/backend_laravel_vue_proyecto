<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$personas = DB::select("SELECT * FROM personas");

        $personas = Persona::with(['user'])->orderBy('id', 'desc')->get();

        return response()->json($personas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validacion
        $request->validate([
            "nombres" => "required|string|min:3|max:30",
            "apellidos" => "string|min:3|max:30"
        ]);
        
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        
        //SQL Server
        //DB::insert("INSERT INTO personas (nombres, apellidos) values (?,?)", [$nombres,$apellidos]);

        //QUERY builder
        //$personas = DB::table("personas")->insert(["nombres" => $nombres, "apellidos" => $apellidos]);

        //Eloquemt ORM
        $persona = new Persona;
        $persona->nombres = $nombres;
        $persona->apellidos = $apellidos;
        $persona->save();

        return response()->json(["mensaje" => "Persona Registrada"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function funGuardarPersonaUser(Request $request){
        //validar Datos Personales y Usuario
        $request->validate([
            "nombres" => "required|min:2|max:30",
            "apellidos" => "required|min:2|max:30",
            "email" => "required|email|unique:users",
            "password" => "required|min:6|string"
        ]);

        DB::beginTransaction();

        try {
            //guardar User
            $u = new User();
            $u->name = $request->nombres;
            $u->email = $request->email;
            $u->password = Hash::make($request->password);
            $u->save();

            //guardar Persona
            $p = new Persona();
            $p->nombres = $request->nombres;
            $p->apellidos = $request->apellidos;
            $p->user_id = $u->id;
            $p->save();

            DB::commit();
            //respuesta correcta
            return response()->json(["mensaje" => "Datos registrados correctamente"], 201);
        } catch (\Exception $e){
            //respuesta error (revertir)
            DB::rollBack();
            return response()->json(["mensaje" => "Ocurrio un error al registrar los datos", "error" => $e->getMessage()], 400);
        }
    }
    //guardar Persona_User

    public function funAddUserPersona(Request $request, $id){

        //validar Datos Personales y Usuario
        $request->validate([           
            "email" => "required|email|unique:users",
            "password" => "required|min:6|string"
        ]);
        
        DB::beginTransaction();

        try {
            $persona = Persona::find($id);

            //guardar User
            $u = new User();
            $u->name = $persona->nombres;
            $u->email = $request->email;
            $u->password = Hash::make($request->password);
            $u->save();

            //asignamos la cuenta de usuario a la persona
            $persona->user_id = $u->id;

            $persona->update();

            DB::commit();

            return response()->json(["mensaje" => "cuenta asignada a la persona"], 201);
        
        } catch (\Exception $e){
            //respuesta error (revertir)
            DB::rollBack();
            return response()->json(["mensaje" => "Ocurrio un error al asignar cuenta de usuario", "error" => $e->getMessage()], 400);
        }
    }
}
