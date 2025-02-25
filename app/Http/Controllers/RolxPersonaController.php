<?php

namespace App\Http\Controllers;

use App\Models\mod_RolxPersona;
use App\Models\mod_Rol;
use App\Models\mod_Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="RolxPersona",
 *     type="object",
 *     title="RolxPersona",
 *     required={"rol_id", "persona_id"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="rol_id", type="integer"),
 *     @OA\Property(property="persona_id", type="integer")
 * )
 */

class RolxPersonaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/rolxpersona",
     *     summary="Obtener todas las relaciones entre roles y personas",
     *     tags={"RolxPersona"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles por persona"
     *     )
     * )
     */

    // Mostrar la lista de roles asignados a personas
    public function index(Request $request)
    {
        $search = $request->query('q'); // Buscador por persona
    
        try {
            // Buscar registros según el nombre de la persona
            $rolxpersona = mod_RolxPersona::with(['persona', 'rol'])
                ->when($search, function($query, $search) {
                    return $query->whereHas('persona', function($q) use ($search) {
                        $q->where('nombres', 'like', "%{$search}%"); // Filtrar solo por nombre de persona
                    });
                })
                ->get();
    
            // Filtrar los registros que tienen relación con persona y rol
            $rolxpersona = $rolxpersona->filter(function($item) {
                return $item->persona && $item->rol; // Asegurarse de que ambas relaciones existan
            });
    
            // Comprobar si hay registros
            return response()->json($rolxpersona->values()->all(), Response::HTTP_OK); // Retornar el array directamente
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los roles asignados: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación
    public function create()
    {
        $roles = mod_Rol::all();
        $personasSinRol = mod_Persona::whereDoesntHave('roles')->get(); // Aquí se usa el modelo correctamente
        
        return view('VistasCrud.VistasRolxPersona.create', compact('personasSinRol', 'roles'));
    }

    /**
     * @OA\Post(
     *     path="/api/rolxpersona",
     *     summary="Crear una nueva relación entre rol y persona",
     *     tags={"RolxPersona"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rol_id", "persona_id"},
     *             @OA\Property(property="rol_id", type="integer", example=1),
     *             @OA\Property(property="persona_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Relación creada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */

    // Almacenar un nuevo rol asignado a una persona
    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'persona_id' => 'required|exists:personas,id',
        ]);

        // Verificar si la persona ya tiene un rol asignado
        $personaConRol = mod_RolxPersona::where('persona_id', $request->persona_id)->exists();
        
        if ($personaConRol) {
            return redirect()->back()->with('error', 'Esta persona ya tiene un rol asignado.');
        }

        try {
            // Crear la asignación rol-persona
            mod_RolxPersona::create($validatedData);
            return redirect(url('/rolxpersona-index'))->with('success', 'Rol asignado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/rolxpersona-index'))->with('error', 'No se pudo asignar el rol. Intente nuevamente.');
        }
    }
    

    // Mostrar la vista de edición
    public function edit($id)
    {
        try {
            $rolxpersona = mod_RolxPersona::findOrFail($id);
            $personas = \App\Models\mod_Persona::all(); // Obtener personas
            $roles = \App\Models\mod_Rol::all(); // Obtener roles
    
            return view('VistasCrud.VistasRolxPersona.edit', compact('rolxpersona', 'personas', 'roles'));
        } catch (\Exception $e) {
            return redirect(url('/rolxpersona-index'))->with('error', 'Asignación no encontrada');
        }
    }

    /**
     * @OA\Put(
     *     path="/api/rolxpersona/{id}",
     *     summary="Actualizar una relación entre rol y persona",
     *     tags={"RolxPersona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la relación rolxpersona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rol_id", type="integer", example=1),
     *             @OA\Property(property="persona_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación actualizada con éxito"
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
        $rolxpersona = mod_RolxPersona::find($id);
        
        if (!$rolxpersona) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'persona_id' => 'required|exists:personas,id',
        ]);

        try {
            $rolxpersona->update($validatedData);
            return redirect(url('/rolxpersona-index'))->with('success', 'Asignación actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect(url('/rolxpersona-index'))->with('error', 'Error al actualizar la asignación');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/rolxpersona/{id}",
     *     summary="Eliminar una relación entre rol y persona",
     *     tags={"RolxPersona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la relación rolxpersona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Relación eliminada con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Relación no encontrada"
     *     )
     * )
     */

    // Eliminar una asignación lógicamente
    public function destroy($id)
    {
        $rolxpersona = mod_RolxPersona::find($id);
    
        if (!$rolxpersona) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    
        try {
            $rolxpersona->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar asignación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar asignaciones eliminadas
    public function deleted()
    {
        $asignacionesEliminadas = mod_RolxPersona::onlyTrashed()->get();
        return view('VistasCrud.VistasRolxPersona.deleted', compact('asignacionesEliminadas'));
    }

    // Restaurar una asignación eliminada
    public function restore($id)
    {
        $rolxpersona = mod_RolxPersona::withTrashed()->findOrFail($id);
        $rolxpersona->restore();

        return redirect()->route('rolxpersona.deleted')->with('success', 'Asignación restaurada exitosamente.');
    }

    // Eliminar completamente una asignación
    public function forceDelete($id)
    {
        $rolxpersona = mod_RolxPersona::withTrashed()->findOrFail($id);
        $rolxpersona->forceDelete();

        return redirect()->route('rolxpersona.deleted')->with('success', 'Asignación eliminada permanentemente.');
    }

    /**
     * @OA\Get(
     *     path="/api/rolxpersona/{id}",
     *     summary="Obtener una relación específica entre rol y persona",
     *     tags={"RolxPersona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la relación rolxpersona",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la relación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Relación no encontrada"
     *     )
     * )
     */

    //mostar la vista de asignaciones
    public function show($id)
    {
        try {
            $rolxpersona = mod_RolxPersona::findOrFail($id);
            return response()->json($rolxpersona, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }
    
    // Validar los datos de una asignación
    private function validateRolxPersona(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'persona_id' => 'required|exists:personas,id',
        ]);
    }
}