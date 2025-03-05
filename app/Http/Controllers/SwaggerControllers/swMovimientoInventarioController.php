<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_MovimientosInventario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="MovimientoInventario",
 *     type="object",
 *     title="MovimientoInventario",
 *     description="Modelo de Movimiento de Inventario",
 *     required={"ID_ARTICULOS", "ID_INVENTARIOS", "TIPO", "CANTIDAD", "FECHA", "ID_USUARIOS"},
 *     @OA\Property(property="ID_ARTICULOS", type="integer", description="ID del artículo asociado"),
 *     @OA\Property(property="ID_INVENTARIOS", type="integer", description="ID del inventario asociado"),
 *     @OA\Property(property="TIPO", type="string", description="Tipo de movimiento (entrada/salida)"),
 *     @OA\Property(property="CANTIDAD", type="integer", description="Cantidad de artículos movidos"),
 *     @OA\Property(property="FECHA", type="string", format="date", description="Fecha del movimiento"),
 *     @OA\Property(property="ID_USUARIOS", type="integer", description="ID del usuario que realizó el movimiento"),
 *     @OA\Property(property="OBSERVACIONES", type="string", description="Observaciones adicionales del movimiento")
 * )
 */
class swMovimientoInventarioController extends swController
{
    /**
     * @OA\Get(
     *     path="/api/movimientosinventario",
     *     summary="Obtener todos los movimientos de inventario",
     *     tags={"MovimientoInventario"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de movimientos de inventario",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/MovimientoInventario"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(mod_MovimientosInventario::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/movimientosinventario",
     *     summary="Crear un nuevo movimiento de inventario",
     *     tags={"MovimientoInventario"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MovimientoInventario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movimiento de inventario creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_INVENTARIOS' => 'required|integer',
            'TIPO' => 'required|string|max:255',
            'CANTIDAD' => 'required|integer',
            'FECHA' => 'required|date',
            'ID_USUARIOS' => 'required|integer',
            'OBSERVACIONES' => 'nullable|string'
        ]);

        $movimiento = mod_MovimientosInventario::create($request->all());
        return response()->json($movimiento, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/movimientosinventario/{id}",
     *     summary="Obtener un movimiento de inventario por ID",
     *     tags={"MovimientoInventario"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información del movimiento de inventario", @OA\JsonContent(ref="#/components/schemas/MovimientoInventario"))
     * )
     */
    public function show($id)
    {
        $movimiento = mod_MovimientosInventario::find($id);
        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento de inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($movimiento, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/movimientosinventario/{id}",
     *     summary="Actualizar un movimiento de inventario por ID",
     *     tags={"MovimientoInventario"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/MovimientoInventario")),
     *     @OA\Response(response=200, description="Movimiento de inventario actualizado exitosamente", @OA\JsonContent(ref="#/components/schemas/MovimientoInventario"))
     * )
     */

    public function update(Request $request, $id)
    {
        $movimiento = mod_MovimientosInventario::find($id);
        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento de inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'ID_ARTICULOS' => 'required|integer',
            'ID_INVENTARIOS' => 'required|integer',
            'TIPO' => 'required|string|max:255',
            'CANTIDAD' => 'required|integer',
            'FECHA' => 'required|date',
            'ID_USUARIOS' => 'required|integer',
            'OBSERVACIONES' => 'nullable|string'
        ]);

        $movimiento->update($request->all());
        return response()->json($movimiento, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/movimientosinventario/{id}",
     *     summary="Eliminar un movimiento de inventario por ID",
     *     tags={"MovimientoInventario"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Movimiento de inventario eliminado exitosamente")
     * )
     */

    public function destroy($id)
    {
        $movimiento = mod_MovimientosInventario::find($id);
        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento de inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }
        $movimiento->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/movimientosinventario/eliminados",
     *     summary="Obtener todos los movimientos de inventario eliminados",
     *     tags={"MovimientoInventario"},
     *     @OA\Response(response=200, description="Lista de movimientos de inventario eliminados", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/MovimientoInventario")))
     * )
     */

    public function deleted()
    {
        return response()->json(mod_MovimientosInventario::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/movimientosinventario/restore/{id}",
     *     summary="Restaurar un movimiento de inventario eliminado por ID",
     *     tags={"MovimientoInventario"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Movimiento de inventario restaurado exitosamente", @OA\JsonContent(ref="#/components/schemas/MovimientoInventario"))
     * )
     */

    public function restore($id)
    {
        $movimiento = mod_MovimientosInventario::withTrashed()->findOrFail($id);
        $movimiento->restore();
        return response()->json($movimiento, Response::HTTP_OK);
    }
}
