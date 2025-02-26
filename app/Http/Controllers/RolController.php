<?php

namespace App\Http\Controllers;

use App\Models\mod_Rol;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="API de Roles",
 *    version="1.0.0",
 *    description="API para gestionar roles"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Rol",
 *     type="object",
 *     title="Rol",
 *     description="Modelo de Rol",
 *     required={"DESCRIPCION"},
 *     @OA\Property(property="DESCRIPCION", type="string", description="Descripción del rol")
 * )
 */
class RolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Obtener todos los roles",
     *     tags={"Rol"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Rol"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Rol::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Crear un nuevo rol",
     *     tags={"Rol"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'DESCRIPCION' => 'required|string|max:255|unique:ROLES,DESCRIPCION'
        ]);

        $rol = mod_Rol::create($request->all());
        return response()->json($rol, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Obtener un rol por ID",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del rol",
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     )
     * )
     */
    public function show($id)
    {
        $rol = mod_Rol::find($id);
        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($rol, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Actualizar un rol",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $rol = mod_Rol::find($id);
        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'DESCRIPCION' => 'required|string|max:255|unique:ROLES,DESCRIPCION,' . $id . ',ID'
        ]);

        $rol->update($request->all());
        return response()->json($rol, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rol eliminado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $rol = mod_Rol::find($id);
        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $rol->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/eliminados",
     *     summary="Obtener roles eliminados",
     *     tags={"Rol"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Rol"))
     *     )
     * )
     */
    public function deleted()
    {
        return response()->json(mod_Rol::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/restore/{id}",
     *     summary="Restaurar un rol eliminado",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol restaurado"
     *     )
     * )
     */
    public function restore($id)
    {
        $rol = mod_Rol::withTrashed()->findOrFail($id);
        $rol->restore();
        return response()->json($rol, Response::HTTP_OK);
    }
}
