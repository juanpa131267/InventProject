<?php

namespace App\Http\Controllers;

use App\Models\mod_Rol;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="Rol",
 *     type="object",
 *     title="Rol",
 *     required={"descripcion"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="descripcion", type="string", maxLength=255),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class RolController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Listar todos los roles",
     *     tags={"Rol"},  
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles"
     *     )
     * )
     */

    // Mostrar la lista de roles
    public function index(Request $request)
    {
        $search = $request->query('q');

        try {
            $roles = $search
                ? mod_Rol::where('descripcion', 'like', "%{$search}%")->withoutTrashed()->get()
                : mod_Rol::withoutTrashed()->get();

            return response()->json($roles, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener roles'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación de un rol
    public function create()
    {
        return view('VistasCrud.VistasRol.create');
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Crear un nuevo rol",
     *     tags={"Rol"},  
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descripcion"},
     *             @OA\Property(property="descripcion", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado"
     *     )
     * )
     */

    // Almacenar un nuevo rol
    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'descripcion' => 'required|string|max:255|unique:roles',
        ]);

        try {
            // Crear el rol
            mod_Rol::create($validatedData);
            return redirect(url('/roles-index'))->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/roles-index'))->with('error', 'No se pudo crear el rol. Intente nuevamente.');
        }
    }

    // Mostrar la vista de edición de un rol
    public function edit($id)
    {
        try {
            $rol = mod_Rol::findOrFail($id);
            return view('VistasCrud.VistasRol.edit', compact('rol'));
        } catch (\Exception $e) {
            return redirect(url('/roles-index'))->with('error', 'Rol no encontrado.');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Actualizar un rol existente",
     *     tags={"Rol"},  
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descripcion"},
     *             @OA\Property(property="descripcion", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */

    // Actualizar un rol existente
    public function update(Request $request, $id)
    {
        $rol = mod_Rol::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Validar los datos
        $validatedData = $request->validate([
            'descripcion' => "required|string|max:255|unique:roles,descripcion,$id",
        ]);

        try {
            $rol->update($validatedData);
            return redirect(url('/roles-index'))->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/roles-index'))->with('error', 'Error al actualizar rol.');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Rol"}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */

    // Eliminar un rol
    public function destroy($id)
    {
        $rol = mod_Rol::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $rol->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar rol'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar roles eliminados
    public function deleted()
    {
        $rolesEliminados = mod_Rol::onlyTrashed()->get();
        return view('VistasCrud.VistasRol.deleted', compact('rolesEliminados'));
    }

    // Restaurar un rol eliminado
    public function restore($id)
    {
        $rol = mod_Rol::withTrashed()->findOrFail($id);
        $rol->restore();

        return redirect()->route('roles.deleted')->with('success', 'Rol restaurado exitosamente.');
    }

    // Eliminar completamente un rol
    public function forceDelete($id)
    {
        $rol = mod_Rol::withTrashed()->findOrFail($id);
        $rol->forceDelete();

        return redirect()->route('roles.deleted')->with('success', 'Rol eliminado completamente.');
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Obtener un rol específico",
     *     tags={"Rol"}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */

    // Mostrar la vista de un rol
    public function show($id)
    {
        try {
            $rol = mod_Rol::findOrFail($id);
            return view('VistasCrud.VistasRol.show', compact('rol'));
        } catch (\Exception $e) {
            return redirect(url('roles.index'))->with('error', 'Rol no encontrado.');
        }
    }

    // Validar la descripción de un rol
    public function validateDescription(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255|unique:roles',
        ]);
    }
}