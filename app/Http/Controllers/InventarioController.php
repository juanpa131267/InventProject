<?php

namespace App\Http\Controllers;

use App\Models\mod_Inventario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="Inventario",
 *     type="object",
 *     title="Inventario",
 *     description="Modelo de Inventario",
 *     required={"NOMBRE", "ID_FOTOS", "ID_USUARIOS"},
 *     @OA\Property(property="NOMBRE", type="string", description="Nombre del inventario"),
 *     @OA\Property(property="ID_FOTOS", type="integer", description="ID de la foto asociada"),
 *     @OA\Property(property="ID_USUARIOS", type="integer", description="ID del usuario propietario")
 * )
 */

class InventarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/inventarios",
     *     summary="Obtener todos los inventarios",
     *     tags={"Inventario"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de inventarios",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Inventario"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Inventario::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/inventarios",
     *     summary="Crear un nuevo inventario",
     *     tags={"Inventario"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inventario creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'ID_FOTOS' => 'required|integer',
            'ID_USUARIOS' => 'required|integer'
        ]);

        $inventario = mod_Inventario::create($request->all());
        return response()->json($inventario, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/inventarios/{id}",
     *     summary="Obtener un inventario por ID",
     *     tags={"Inventario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del inventario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del inventario",
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     )
     * )
     */
    public function show($id)
    {
        $inventario = mod_Inventario::find($id);
        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($inventario, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/inventarios/{id}",
     *     summary="Actualizar un inventario",
     *     tags={"Inventario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del inventario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Inventario")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $inventario = mod_Inventario::find($id);
        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'ID_FOTOS' => 'required|integer',
            'ID_USUARIOS' => 'required|integer'
        ]);

        $inventario->update($request->all());
        return response()->json($inventario, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/inventarios/{id}",
     *     summary="Eliminar un inventario",
     *     tags={"Inventario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del inventario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Inventario eliminado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $inventario = mod_Inventario::find($id);
        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $inventario->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/inventarios/eliminados",
     *     summary="Obtener inventarios eliminados",
     *     tags={"Inventario"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de inventarios eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Inventario"))
     *     )
     * )
     */
    public function deleted()
    {
        return response()->json(mod_Inventario::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/inventarios/restore/{id}",
     *     summary="Restaurar un inventario eliminado",
     *     tags={"Inventario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del inventario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario restaurado"
     *     )
     * )
     */

    public function restore($id)
    {
        $inventario = mod_Inventario::onlyTrashed()->find($id);
        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $inventario->restore();
        return response()->json($inventario, Response::HTTP_OK);
    }
}
