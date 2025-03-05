<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_Categoria;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Categoria",
 *     type="object",
 *     title="Categoria",
 *     description="Modelo de Categoria",
 *     required={"NOMBRE"},
 *     @OA\Property(property="NOMBRE", type="string", description="Nombre de la categoría")
 * )
 */

class swCategoriaController extends swController
{
    /**
     * @OA\Get(
     *     path="/api/categorias",
     *     summary="Obtener todas las categorías",
     *     tags={"Categoria"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Categoria"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Categoria::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/categorias",
     *     summary="Crear una nueva categoría",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Categoria")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:255|unique:CATEGORIAS,NOMBRE'
        ]);

        $categoria = mod_Categoria::create($request->all());
        return response()->json($categoria, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/categorias/{id}",
     *     summary="Obtener una categoría por ID",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la categoría",
     *         @OA\JsonContent(ref="#/components/schemas/Categoria")
     *     )
     * )
     */
    public function show($id)
    {
        $categoria = mod_Categoria::find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($categoria, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/{id}",
     *     summary="Actualizar una categoría",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Categoria")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $categoria = mod_Categoria::find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'NOMBRE' => 'required|string|max:255|unique:CATEGORIAS,NOMBRE,' . $id . ',ID'
        ]);

        $categoria->update($request->all());
        return response()->json($categoria, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/categorias/{id}",
     *     summary="Eliminar una categoría",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Categoría eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $categoria = mod_Categoria::find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $categoria->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/categorias/eliminadas",
     *     summary="Obtener categorías eliminadas",
     *     tags={"Categoria"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías eliminadas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Categoria"))
     *     )
     * )
     */
    public function deleted()
    {
        return response()->json(mod_Categoria::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/restore/{id}",
     *     summary="Restaurar una categoría eliminada",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría restaurada"
     *     )
     * )
     */
    public function restore($id)
    {
        $categoria = mod_Categoria::withTrashed()->findOrFail($id);
        $categoria->restore();
        return response()->json($categoria, Response::HTTP_OK);
    }
}
