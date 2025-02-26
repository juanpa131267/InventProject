<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxCategoria;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="ArticuloxCategoria",
 *     type="object",
 *     title="ArticuloxCategoria",
 *     description="Modelo de Articulo por Categoria",
 *     required={"ID_ARTICULOS", "ID_CATEGORIAS"},
 *     @OA\Property(property="ID_ARTICULOS", type="integer", description="ID del articulo asociado"),
 *     @OA\Property(property="ID_CATEGORIAS", type="integer", description="ID de la categoria asociada")
 * )
 */

class ArticuloxCategoriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articuloxcategoria",
     *     summary="Obtener todas las asignaciones de artículos a categorías",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticuloxCategoria"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_ArticuloxCategoria::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articuloxcategoria",
     *     summary="Asignar un nuevo artículo a una categoría",
     *     tags={"ArticuloxCategoria"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxCategoria")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asignación creada exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_CATEGORIAS' => 'required|integer'
        ]);

        $articuloxcategoria = mod_ArticuloxCategoria::create($request->all());
        return response()->json($articuloxcategoria, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxcategoria/{id}",
     *     summary="Obtener una asignación de artículo por ID",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la asignación",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la asignación",
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxCategoria")
     *     )
     * )
     */
    public function show($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::find($id);
        if (!$articuloxcategoria) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($articuloxcategoria, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxcategoria/{id}",
     *     summary="Actualizar una asignación de artículo",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la asignación",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxCategoria")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::find($id);
        if (!$articuloxcategoria) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_CATEGORIAS' => 'required|integer'
        ]);
        $articuloxcategoria->update($request->all());
        return response()->json($articuloxcategoria, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/articuloxcategoria/{id}",
     *     summary="Eliminar una asignación de artículo",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la asignación",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Asignación eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::find($id);
        if (!$articuloxcategoria) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        $articuloxcategoria->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxcategoria/eliminados",
     *     summary="Obtener todas las asignaciones de artículos a categorías eliminados",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticuloxCategoria"))
     *     )
     * )
     */

    public function deleted()
    {
        return response()->json(mod_ArticuloxCategoria::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxcategoria/restore/{id}",
     *     summary="Restaurar una asignación de artículo eliminada",
     *     tags={"ArticuloxCategoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la asignación",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación restaurada"
     *     )
     * )
     */

    public function restore($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::withTrashed()->findOrFail($id);
        $articuloxcategoria->restore();
        return response()->json($articuloxcategoria, Response::HTTP_OK);
    }
}
