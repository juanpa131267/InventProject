<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxFoto;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="ArticuloxFoto",
 *     type="object",
 *     title="ArticuloxFoto",
 *     description="Modelo de Artículo por Foto",
 *     required={"ID_ARTICULOS", "ID_FOTOS"},
 *     @OA\Property(property="ID_ARTICULOS", type="integer", description="ID del artículo asociado"),
 *     @OA\Property(property="ID_FOTOS", type="integer", description="ID de la foto asociada")
 * )
 */
class ArticuloxFotoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articuloxfoto",
     *     summary="Obtener todas las asignaciones de artículos a fotos",
     *     tags={"ArticuloxFoto"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticuloxFoto"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_ArticuloxFoto::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articuloxfoto",
     *     summary="Asignar una foto a un artículo",
     *     tags={"ArticuloxFoto"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticuloxFoto")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asignación creada"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_FOTOS' => 'required|integer'
        ]);
        
        $articuloxfoto = mod_ArticuloxFoto::create($request->all());
        return response()->json($articuloxfoto, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxfoto/{id}",
     *     summary="Obtener una asignación por ID",
     *     tags={"ArticuloxFoto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Asignación encontrada", @OA\JsonContent(ref="#/components/schemas/ArticuloxFoto"))
     * )
     */
    public function show($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::find($id);
        if (!$articuloxfoto) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($articuloxfoto, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxfoto/{id}",
     *     summary="Actualizar una asignación",
     *     tags={"ArticuloxFoto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/ArticuloxFoto")),
     *     @OA\Response(response=200, description="Asignación actualizada")
     * )
     */
    public function update(Request $request, $id)
    {
        $articuloxfoto = mod_ArticuloxFoto::find($id);
        if (!$articuloxfoto) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_FOTOS' => 'required|integer'
        ]);
        
        $articuloxfoto->update($request->all());
        return response()->json($articuloxfoto, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/articuloxfoto/{id}",
     *     summary="Eliminar una asignación",
     *     tags={"ArticuloxFoto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Asignación eliminada")
     * )
     */
    public function destroy($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::find($id);
        if (!$articuloxfoto) {
            return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        $articuloxfoto->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/articuloxfoto/eliminados",
     *     summary="Obtener asignaciones eliminadas",
     *     tags={"ArticuloxFoto"},
     *     @OA\Response(response=200, description="Lista de asignaciones eliminadas")
     * )
     */
    public function deleted()
    {
        return response()->json(mod_ArticuloxFoto::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articuloxfoto/restore/{id}",
     *     summary="Restaurar una asignación eliminada",
     *     tags={"ArticuloxFoto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Asignación restaurada")
     * )
     */
    public function restore($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::withTrashed()->findOrFail($id);
        $articuloxfoto->restore();
        return response()->json($articuloxfoto, Response::HTTP_OK);
    }
}
