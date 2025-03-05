<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_Proveedores;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="Proveedor",
 *     type="object",
 *     title="Proveedor",
 *     description="Modelo de Proveedor",
 *     required={"NOMBRE", "TELEFONO", "CORREO", "DIRECCION"},
 *     @OA\Property(property="NOMBRE", type="string", description="Nombre del proveedor"),
 *     @OA\Property(property="TELEFONO", type="string", description="Teléfono del proveedor"),
 *     @OA\Property(property="CORREO", type="string", description="Correo del proveedor"),
 *     @OA\Property(property="DIRECCION", type="string", description="Dirección del proveedor")
 * )
 */

class swProveedorController extends swController
{
    /**
     * @OA\Get(
     *     path="/api/proveedores",
     *     summary="Obtener todos los proveedores",
     *     tags={"Proveedor"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de proveedores",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Proveedor"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_Proveedores::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/proveedores",
     *     summary="Crear un nuevo proveedor",
     *     tags={"Proveedor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Proveedor")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Proveedor creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE'    => 'required|string|max:255',
            'TELEFONO'  => 'required|string|max:20',
            'CORREO'    => 'required|string|email|max:255',
            'DIRECCION' => 'required|string|max:255'
        ]);

        $proveedor = mod_Proveedores::create($request->all());
        return response()->json($proveedor, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/proveedores/{id}",
     *     summary="Obtener un proveedor por ID",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proveedor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del proveedor",
     *         @OA\JsonContent(ref="#/components/schemas/Proveedor")
     *     )
     * )
     */
    public function show($id)
    {
        $proveedor = mod_Proveedores::find($id);
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($proveedor, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/proveedores/{id}",
     *     summary="Actualizar un proveedor",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proveedor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Proveedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $proveedor = mod_Proveedores::find($id);
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'NOMBRE'    => 'required|string|max:255',
            'TELEFONO'  => 'required|string|max:20',
            'CORREO'    => 'required|string|email|max:255',
            'DIRECCION' => 'required|string|max:255'
        ]);

        $proveedor->update($request->all());
        return response()->json($proveedor, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/proveedores/{id}",
     *     summary="Eliminar un proveedor",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proveedor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Proveedor eliminado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $proveedor = mod_Proveedores::find($id);
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $proveedor->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/proveedores/eliminados",
     *     summary="Obtener todos los proveedores eliminados",
     *     tags={"Proveedor"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de proveedores eliminados",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Proveedor"))
     *     )
     * )
     */

    public function deleted()
    {
        return response()->json(mod_Proveedores::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/proveedores/restore/{id}",
     *     summary="Restaurar un proveedor eliminado",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proveedor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor restaurado"
     *     )
     * )
     */

    public function restore($id)
    {
        $proveedor = mod_Proveedores::withTrashed()->findOrFail($id);
        $proveedor->restore();
        return response()->json($proveedor, Response::HTTP_OK);
    }
}
