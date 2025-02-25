<?php

namespace App\Http\Controllers;

use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

    /**
     * @OA\Schema(
     *    schema="Permiso",
     *    type="object",
     *    title="Permiso",
     *    required={"nombre"},
     *    @OA\Property(property="id", type="integer", readOnly=true),
     *    @OA\Property(property="nombre", type="string"),
     *    @OA\Property(property="descripcion", type="string"),
     *    @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
     *    @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
     * )
     */

class PermisoController extends Controller
{

        /** 
         * @OA\Get(
         *     path="/api/permisos",
         *     summary="Obtener todos los permisos",
         *     tags={"Permiso"},
         *     @OA\Response(
         *         response=200,
         *         description="Lista de permisos",
         *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permiso"))
         *      )
         * )
        */

    // Mostrar la lista de permisos
    public function index(Request $request)
    {

        // Obtener el término de búsqueda si está presente
        $search = $request->query('q');
    
        try {
            // Si hay un término de búsqueda, filtrar los permisos por nombre
            $permisos = $search
                ? mod_Permiso::where('nombre', 'like', "%{$search}%")->withoutTrashed()->get()
                : mod_Permiso::withoutTrashed()->get();
    
            // Devolver la vista con los permisos
            return response()->json($permisos, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Manejar cualquier excepción y redirigir con un mensaje de error
            return redirect()->json((['error' => 'Error al obtener permisos']), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación de un permiso
    public function create()
    {
        return view('VistasCrud.VistasPermiso.create');
    }

    /**
     * @OA\Post(
     *     path="/api/permisos",
     *     summary="Crear un nuevo permiso",
     *     tags={"Permiso"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permiso creado exitosamente"
     *     ) 
     * )
     */

    // Almacenar un nuevo permiso
    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:permisos',
            'descripcion' => 'nullable|string|max:255',
        ]);

        try {
            // Crear el permiso
            mod_Permiso::create($validatedData);
            return redirect(url('/permisos-index'))->with('success', 'Permiso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'No se pudo crear el permiso. Intente nuevamente.');
        }
    }

    // Mostrar la vista de edición de un permiso
    public function edit($id)
    {
        try {
            $permiso = mod_Permiso::findOrFail($id);
            return view('VistasCrud.VistasPermiso.edit', compact('permiso'));
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'Permiso no encontrado.');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/permisos/{id}",
     *     summary="Actualizar un permiso",
     *     tags={"Permiso"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID del permiso",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Permiso")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permiso actualizado"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Permiso no encontrado"
     *      ) 
     * )
     */

    // Actualizar un permiso existente
    public function update(Request $request, $id)
    {
        $permiso = mod_Permiso::find($id);

        if (!$permiso) {
            return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Validar los datos
        $validatedData = $request->validate([
            'nombre' => "required|string|max:255|unique:permisos,nombre,$id",
            'descripcion' => 'nullable|string|max:255',
        ]);

        try {
            $permiso->update($validatedData);
            return redirect(url('/permisos-index'))->with('success', 'Permiso actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'Error al actualizar permiso.');
        }
    }

/**
 * @OA\Delete(
 *    path="/api/permisos/{id}",
 *    summary="Eliminar un permiso",
 *    tags={"Permiso"},
 *    @OA\Parameter(
 *       name="id",
 *       in="path",
 *       description="ID del permiso",
 *       required=true,
 *       @OA\Schema(type="integer")
 *    ),
 *    @OA\Response(
 *       response=404,
 *       description="Permiso no encontrado"
 *    )
 * )
 */

    // Eliminar un permiso
    public function destroy($id)
    {
        $permiso = mod_Permiso::find($id);

        if (!$permiso) {
            return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $permiso->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar permiso'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar permisos eliminados
    public function deleted()
    {
        $permisosEliminados = mod_Permiso::onlyTrashed()->get();
        return view('VistasCrud.VistasPermiso.deleted', compact('permisosEliminados'));
    }

    // Restaurar un permiso eliminado
    public function restore($id)
    {
        $permiso = mod_Permiso::withTrashed()->findOrFail($id);
        $permiso->restore();

        return redirect()->route('permisos.deleted')->with('success', 'Permiso restaurado exitosamente.');
    }

    // Eliminar completamente un permiso
    public function forceDelete($id)
    {
        $permiso = mod_Permiso::withTrashed()->findOrFail($id);
        $permiso->forceDelete();

        return redirect()->route('permisos.deleted')->with('success', 'Permiso eliminado completamente.');
    }

    /**
     * @OA\Get(
     *     path="/api/permisos/{id}",
     *     summary="Obtener un permiso",
     *     tags={"Permiso"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del permiso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permiso no encontrado"
     *     )
     * )
     */

    // Mostrar la vista de un permiso
    public function show($id)
    {
        try {
            $permiso = mod_Permiso::findOrFail($id);
            return view('VistasCrud.VistasPermiso.show', compact('permiso'));
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'Permiso no encontrado.');
        }
    }

}
