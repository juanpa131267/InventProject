<?php

namespace App\Http\Controllers;

use App\Models\mod_RolxPermiso;
use App\Models\mod_Rol;
use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *    schema="RolxPermiso",
 *    type="object",
 *    title="RolxPermiso",
 *    required={"rol_id", "permiso_id"},
 *    @OA\Property(property="id", type="integer", readOnly=true),
 *    @OA\Property(property="rol_id", type="integer"),
 *    @OA\Property(property="permiso_id", type="integer"),
 *    @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *    @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class RolxPermisoController extends Controller
{

    /**
     * @OA\Get(
     *    path="/api/rolxpermiso",
     *    summary="Listar todas las asignaciones de roles y permisos",
     *    tags={"RolxPermiso"},
     *    @OA\Response(
     *       response=200,
     *       description="Lista de asignaciones de roles y permisos"
     *    )
     * )
     */

    // Mostrar la lista de permisos asignados a roles
    public function index(Request $request)
    {
        $search = $request->query('q'); // Captura el parámetro de búsqueda
    
        try {
            // Buscar registros según el nombre del rol
            $rolxpermiso = mod_RolxPermiso::with(['rol', 'permiso'])
                ->when($search, function($query, $search) {
                    return $query->whereHas('rol', function($q) use ($search) {
                        $q->where('descripcion', 'like', "%{$search}%"); // Filtra por la descripción del rol
                    });
                })
                ->get();
    
            return response()->json($rolxpermiso, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los permisos asignados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    // Mostrar la vista de creación
    public function create()
    {
        $roles = mod_Rol::all();
        $permisosSinRol = mod_Permiso::whereDoesntHave('roles')->get(); // Obtener permisos sin rol
        
        return view('VistasCrud.VistasRolxPermiso.create', compact('permisosSinRol', 'roles'));
    }

/**
 * @OA\Post(
 *     path="/api/rolxpermiso",
 *     summary="Crear un nuevo rol-permiso",
 *     tags={"RolxPermiso"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="rol_id", type="integer"),
 *             @OA\Property(property="permiso_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Relacion creada exitosamente"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     )
 * )
 */

    // Almacenar un nuevo permiso asignado a un rol
    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'permiso_id' => 'required|exists:permisos,id',
        ]);

        // Verificar si el rol ya tiene el permiso asignado
        $rolConPermiso = mod_RolxPermiso::where('rol_id', $request->rol_id)
                                          ->where('permiso_id', $request->permiso_id)
                                          ->exists();
        
        if ($rolConPermiso) {
            return redirect()->back()->with('error', 'Este rol ya tiene el permiso asignado.');
        }

        try {
            // Crear la asignación rol-permiso
            mod_RolxPermiso::create($validatedData);
            return redirect(url('/rolxpermiso-index'))->with('success', 'Permiso asignado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/rolxpermiso-index'))->with('error', 'No se pudo asignar el permiso. Intente nuevamente.');
        }
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        try {
            $rolxpermiso = mod_RolxPermiso::findOrFail($id);
            $roles = mod_Rol::all(); // Obtener roles
            $permisos = mod_Permiso::all(); // Obtener permisos
    
            return view('VistasCrud.VistasRolxPermiso.edit', compact('rolxpermiso', 'roles', 'permisos'));
        } catch (\Exception $e) {
            return redirect(url('/rolxpermiso-index'))->with('error', 'Asignación no encontrada');
        }
    }

/**
 * @OA\Put(
 *     path="/api/rolxpermiso/{id}",
 *     summary="Actualizar un rol-permiso",
 *     tags={"RolxPermiso"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del rol-permiso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="rol_id", type="integer"),
 *             @OA\Property(property="permiso_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Relación actualizado exitosamente"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Relación no encontrada"
 *     )
 * )
 */

    // Actualizar una asignación existente
    public function update(Request $request, $id)
    {
        $rolxpermiso = mod_RolxPermiso::find($id);
        
        if (!$rolxpermiso) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'permiso_id' => 'required|exists:permisos,id',
        ]);

        try {
            $rolxpermiso->update($validatedData);
            return redirect(url('/rolxpermiso-index'))->with('success', 'Asignación actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect(url('/rolxpermiso-index'))->with('error', 'Error al actualizar la asignación');
        }
    }

    /**
     * @OA\Delete(
     *    path="/api/rolxpermiso/{id}",
     *    summary="Eliminar una asignación lógicamente",
     *    tags={"RolxPermiso"},
     *    @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       description="ID de la asignación",
     *       @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Asignación eliminada"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Asignación no encontrada"
     *    )
     * )
     */

    // Eliminar una asignación lógicamente
    public function destroy($id)
    {
        $rolxpermiso = mod_RolxPermiso::find($id);

        if (!$rolxpermiso) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $rolxpermiso->delete();
            return response()->json(['success' => true], Response::HTTP_NO_CONTENT); // Esto confirma que la eliminación fue exitosa
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar asignación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // Mostrar asignaciones eliminadas
    public function deleted()
    {
        $asignacionesEliminadas = mod_RolxPermiso::onlyTrashed()->get();
        return view('VistasCrud.VistasRolxPermiso.deleted', compact('asignacionesEliminadas'));
    }

    // Restaurar una asignación eliminada
    public function restore($id)
    {
        $rolxpermiso = mod_RolxPermiso::withTrashed()->findOrFail($id);
        $rolxpermiso->restore();

        return redirect()->route('rolxpermiso.deleted')->with('success', 'Asignación restaurada exitosamente.');
    }

    // Eliminar completamente una asignación
    public function forceDelete($id)
    {
        $rolxpermiso = mod_RolxPermiso::withTrashed()->findOrFail($id);
        $rolxpermiso->forceDelete();

        return redirect()->route('rolxpermiso.deleted')->with('success', 'Asignación eliminada permanentemente.');
    }

/**
 * @OA\Get(
 *     path="/api/rolxpermiso/{id}",
 *     summary="Obtener un rol-permiso específico",
 *     tags={"RolxPermiso"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del rol-permiso",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Relación encontrada"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Relación no encontrada"
 *     )
 * )
 */

    // Mostrar una asignación específica
    public function show($id)
    {
        try {
            $rolxpermiso = mod_RolxPermiso::findOrFail($id);
            return response()->json($rolxpermiso, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Validar los datos de una asignación
    private function validateRolxPermiso(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'permiso_id' => 'required|exists:permisos,id',
        ]);
    }

    // Obtener permisos disponibles según el rol seleccionado
    public function obtenerPermisosDisponibles($rol_id)
    {
        try {
            // Obtener permisos que NO están asignados al rol seleccionado
            $permisosAsignados = mod_RolxPermiso::where('rol_id', $rol_id)->pluck('permiso_id');
            $permisosDisponibles = mod_Permiso::whereNotIn('id', $permisosAsignados)->get();

            return response()->json($permisosDisponibles, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener permisos disponibles'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
