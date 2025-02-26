<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="ArticuloxProveedor",
 *     type="object",
 *     title="ArticuloxProveedor",
 *     description="Modelo de Artículo por Proveedor",
 *     required={"ID_ARTICULOS", "ID_PROVEEDORES"},
 *     @OA\Property(property="ID_ARTICULOS", type="integer", description="ID del artículo asociado"),
 *     @OA\Property(property="ID_PROVEEDORES", type="integer", description="ID del proveedor asociado")
 * )
 */

class ArticuloxProveedorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articuloxproveedor",
     *     summary="Obtener todas las asignaciones de artículos a proveedores",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticuloxProveedor"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_ArticuloxProveedor::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articuloxproveedor",
     *     summary="Asignar un artículo a un proveedor",
     *     tags={"ArticuloxProveedor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxProveedor")
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
            'ID_PROVEEDORES' => 'required|integer'
        ]);

        $articuloxproveedor = mod_ArticuloxProveedor::create($request->all());
        return response()->json($articuloxproveedor, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxproveedor/{id}",
     *     summary="Obtener una asignación por ID",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la asignación",
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxProveedor")
     *     )
     * )
     */
    public function show($id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::find($id);
        if (!$articuloxproveedor) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($articuloxproveedor, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxproveedor/{id}",
     *     summary="Actualizar una asignación",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxProveedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::find($id);
        if (!$articuloxproveedor) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_PROVEEDORES' => 'required|integer'
        ]);
        $articuloxproveedor->update($request->all());
        return response()->json($articuloxproveedor, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/articuloxproveedor/{id}",
     *     summary="Eliminar una asignación",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
        $articuloxproveedor = mod_ArticuloxProveedor::find($id);
        if (!$articuloxproveedor) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        $articuloxproveedor->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxproveedor/eliminados",
     *     summary="Obtener todas las asignaciones eliminados",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticuloxProveedor"))
     *     )
     * )
     */

    public function deleted()
    {
        return response()->json(mod_ArticuloxProveedor::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxproveedor/restore/{id}",
     *     summary="Restaurar una asignación eliminada",
     *     tags={"ArticuloxProveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
        $articuloxproveedor = mod_ArticuloxProveedor::withTrashed()->findOrFail($id);
        $articuloxproveedor->restore();
        return response()->json($articuloxproveedor, Response::HTTP_OK);
    }
}
