<?php

namespace App\Http\Controllers;

use App\Models\mod_Articulo;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Articulo",
 *     type="object",
 *     title="Articulo",
 *     description="Modelo de Artículo",
 *     required={"ID_INVENTARIOS", "NOMBRE", "MARCA", "DESCRIPCION", "FECHACADUCIDAD", "UNIDAD", "CANTIDAD"},
 *     @OA\Property(property="ID_INVENTARIOS", type="integer", description="ID del inventario asociado"),
 *     @OA\Property(property="NOMBRE", type="string", description="Nombre del artículo"),
 *     @OA\Property(property="MARCA", type="string", description="Marca del artículo"),
 *     @OA\Property(property="DESCRIPCION", type="string", description="Descripción del artículo"),
 *     @OA\Property(property="FECHACADUCIDAD", type="string", format="date", description="Fecha de caducidad del artículo"),
 *     @OA\Property(property="UNIDAD", type="string", description="Unidad de medida del artículo"),
 *     @OA\Property(property="CANTIDAD", type="integer", description="Cantidad disponible del artículo")
 * )
 */
class ArticuloController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articulos",
     *     summary="Obtener todos los artículos",
     *     tags={"Articulo"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de artículos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Articulo"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Articulo::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articulos",
     *     summary="Crear un nuevo artículo",
     *     tags={"Articulo"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Articulo")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Artículo creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_INVENTARIOS' => 'required|integer',
            'NOMBRE'         => 'required|string|max:255',
            'MARCA'          => 'required|string|max:255',
            'DESCRIPCION'    => 'required|string',
            'FECHACADUCIDAD' => 'required|date',
            'UNIDAD'         => 'required|string|max:50',
            'CANTIDAD'       => 'required|integer|min:0'
        ]);

        $articulo = mod_Articulo::create($request->all());
        return response()->json($articulo, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/articulos/{id}",
     *     summary="Obtener un artículo por ID",
     *     tags={"Articulo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del artículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del artículo",
     *         @OA\JsonContent(ref="#/components/schemas/Articulo")
     *     )
     * )
     */
    public function show($id)
    {
        $articulo = mod_Articulo::find($id);
        if (!$articulo) {
            return response()->json(['error' => 'Artículo no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($articulo, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articulos/{id}",
     *     summary="Actualizar un artículo",
     *     tags={"Articulo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del artículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Articulo")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artículo actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $articulo = mod_Articulo::find($id);
        if (!$articulo) {
            return response()->json(['error' => 'Artículo no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'ID_INVENTARIOS' => 'required|integer',
            'NOMBRE'         => 'required|string|max:255',
            'MARCA'          => 'required|string|max:255',
            'DESCRIPCION'    => 'required|string',
            'FECHACADUCIDAD' => 'required|date',
            'UNIDAD'         => 'required|string|max:50',
            'CANTIDAD'       => 'required|integer|min:0'
        ]);

        $articulo->update($request->all());
        return response()->json($articulo, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/articulos/{id}",
     *     summary="Eliminar un artículo",
     *     tags={"Articulo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del artículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Artículo eliminado"
     *     )
     * )
     */

    public function destroy($id)
    {
        $articulo = mod_Articulo::find($id);
        if (!$articulo) {
            return response()->json(['error' => 'Artículo no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $articulo->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/articulos/eliminados",
     *     summary="Obtener todos los artículos eliminados",
     *     tags={"Articulo"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de artículos eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Articulo"))
     *     )
     * )
     */

    public function deleted()
    {
        return response()->json(mod_Articulo::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articulos/restore/{id}",
     *     summary="Restaurar un artículo eliminado",
     *     tags={"Articulo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del artículo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artículo restaurado"
     *     )
     * )
     */

    public function restore($id)
    {
        $articulo = mod_Articulo::withTrashed()->findOrFail($id);
        $articulo->restore();
        return response()->json($articulo, Response::HTTP_OK);
    }
}
