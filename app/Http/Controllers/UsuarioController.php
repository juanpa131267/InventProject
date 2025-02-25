<?php

namespace App\Http\Controllers;

use App\Models\mod_Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *    title="API de Usuarios",
 *    version="1.0",
 *    description="API para la gestión de usuarios"
 * )
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     title="Usuario",
 *     required={"login", "password", "persona_id"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="login", type="string"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="persona_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class UsuarioController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/usuarios",
     *     summary="Obtener la lista de usuarios",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
     *     )
     * )
     */

    // Mostrar la lista de usuarios
    public function index(Request $request)
    {
        $search = $request->query('q'); // Buscador por login
        
        try {
            $usuarios = $search 
                ? mod_Usuario::where('login', 'like', "%{$search}%")->withoutTrashed()->get() 
                : mod_Usuario::withoutTrashed()->get();

            return response()->json($usuarios, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener usuarios'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación
    public function create()
    {
        // Obtener las personas que no tienen usuarios asignados
        $personas = \App\Models\mod_Persona::whereDoesntHave('usuario')->get(); // Ahora debe funcionar
    
        // Pasar la lista de personas a la vista
        return view('VistasCrud.VistasUsuario.create', compact('personas'));
    }

    /**
     * @OA\Post(
     *     path="/api/usuarios",
     *     summary="Crear un nuevo usuario",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password","persona_id"},
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="persona_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
     *     )
     * )
     */

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'login' => 'required|string|max:50|unique:usuarios',
            'password' => 'required|string|min:8',
            'persona_id' => 'required|exists:personas,id',
        ]);
    
        try {
            // Crear el usuario utilizando el modelo mod_Usuario
            $validatedData['password'] = Hash::make($validatedData['password']); // Hash de la contraseña
            mod_Usuario::create($validatedData);
    
            // Redirigir al índice con un mensaje de éxito
            return redirect(url('/usuarios-index'))->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, redirigir con un mensaje de error
            return redirect(url('/usuarios-index'))->with('error', 'No se pudo crear el usuario. Intente nuevamente.');
        }
    }

    // Mostrar la vista de edición de un usuario
    public function edit($id)
    {
        try {
            $usuario = mod_Usuario::findOrFail($id);
            $personas = \App\Models\mod_Persona::all(); // Obtener la lista de personas para el select
    
            return view('VistasCrud.VistasUsuario.edit', compact('usuario', 'personas'));
        } catch (\Exception $e) {
            return redirect(url('/usuarios-index'))->with('error', 'Usuario no encontrado');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/usuarios/{id}",
     *     summary="Actualizar un usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password","persona_id"},
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="persona_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $usuario = mod_Usuario::find($id);
        
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $this->validateUsuario($request, $id);

        try {
            $usuario->update($request->all());
            return redirect(url('/usuarios-index'))->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect(url('/usuarios-index'))->with('error', 'Error al actualizar usuario');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/usuarios/{id}",
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */

    // Eliminar un usuario (lógica)
    public function destroy($id)
    {
        $usuario = mod_Usuario::find($id);
    
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
    
        try {
            $usuario->delete(); // Eliminar lógicamente
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar usuario'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de usuarios eliminados
    public function deleted()
    {
        $usuariosEliminados = mod_Usuario::onlyTrashed()->get();
        return view('VistasCrud.VistasUsuario.deleted', compact('usuariosEliminados'));
    }

    // Restaurar usuario eliminado
    public function restore($id)
    {
        $usuario = mod_Usuario::withTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.deleted')->with('success', 'Usuario restaurado exitosamente.');
    }

    // Eliminar completamente a un usuario
    public function forceDelete($id)
    {
        $usuario = mod_Usuario::withTrashed()->findOrFail($id);
        $usuario->forceDelete();

        return redirect()->route('usuarios.deleted')->with('success', 'Usuario eliminado completamente.');
    }

    /**
     * @OA\Get(
     *     path="/api/usuarios/{id}",
     *     summary="Obtener un usuario específico",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */

    // Mostrar un usuario específico
    public function show($id)
    {
        try {
            $usuario = mod_Usuario::findOrFail($id);
            return view('VistasCrud.VistasUsuario.show', compact('usuario'));
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
    }

    // Validación común para creación y actualización
    private function validateUsuario(Request $request, $id = null)
    {
        $uniqueLoginRule = $id ? "unique:usuarios,login,$id" : 'unique:usuarios,login';
        
        $request->validate([
            'login' => "required|string|max:50|$uniqueLoginRule",
            'password' => 'nullable|string|min:8',
            'persona_id' => 'required|exists:personas,id',
        ]);
    }
}
