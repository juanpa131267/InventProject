<?php

namespace App\Http\Controllers;

use App\Models\mod_Persona;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="API de Personas",
 *    version="1.0.0",
 *    description="API de Personas",
 * )
 */

/**
 * @OA\Schema(
 *     schema="Persona",
 *     type="object",
 *     title="Persona",
 *     description="Modelo de Persona",
 *     required={"CEDULA", "NOMBRES", "APELLIDO", "TELEFONO", "CORREO"},
 *     @OA\Property(property="CEDULA", type="string", description="Cédula de la persona"),
 *     @OA\Property(property="NOMBRES", type="string", description="Nombres de la persona"),
 *     @OA\Property(property="APELLIDO", type="string", description="Apellido de la persona"),
 *     @OA\Property(property="TELEFONO", type="string", description="Teléfono de la persona"),
 *     @OA\Property(property="CORREO", type="string", format="email", description="Correo de la persona")
 * )
 */

class PersonaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/personas",
     *     summary="Obtener todas las personas",
     *     tags={"Persona"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Persona"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Persona::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/personas",
     *     summary="Crear una nueva persona",
     *     tags={"Persona"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Persona creada exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'CEDULA' => 'required|string|max:255|unique:PERSONAS,CEDULA',
            'NOMBRES' => 'required|string|max:255',
            'APELLIDO' => 'required|string|max:255',
            'TELEFONO' => 'required|string|max:15',
            'CORREO' => 'required|email|max:255'
        ]);

        $persona = mod_Persona::create($request->all());
        return response()->json($persona, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}",
     *     summary="Obtener una persona por ID",
     *     tags={"Persona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la persona",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     )
     * )
     */
    public function show($id)
    {
        $persona = mod_Persona::find($id);
        if (!$persona) {
            return response()->json(['error' => 'Persona no encontrada'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($persona, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/personas/{id}",
     *     summary="Actualizar una persona",
     *     tags={"Persona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $persona = mod_Persona::find($id);
        if (!$persona) {
            return response()->json(['error' => 'Persona no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'CEDULA' => 'required|string|max:255|unique:PERSONAS,CEDULA,' . $id . ',ID',
            'NOMBRES' => 'required|string|max:255',
            'APELLIDO' => 'required|string|max:255',
            'TELEFONO' => 'required|string|max:15',
            'CORREO' => 'required|email|max:255'
        ]);

        $persona->update($request->all());
        return response()->json($persona, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/personas/{id}",
     *     summary="Eliminar una persona",
     *     tags={"Persona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Persona eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $persona = mod_Persona::find($id);
        if (!$persona) {
            return response()->json(['error' => 'Persona no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $persona->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/personas/eliminadas",
     *     summary="Obtener personas eliminadas",
     *     tags={"Persona"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas eliminadas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Persona"))
     *     )
     * )
     */
    public function deleted()
    {
        return response()->json(mod_Persona::onlyTrashed()->get(), 200);
    }

    /**
     * @OA\Put(
     *     path="/api/personas/restore/{id}",
     *     summary="Restaurar una persona eliminada",
     *     tags={"Persona"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona restaurada"
     *     )
     * )
     */
    public function restore($id)
    {
        $persona = mod_Persona::withTrashed()->findOrFail($id);
        $persona->restore();
        return response()->json($persona, 200);
    }
}
