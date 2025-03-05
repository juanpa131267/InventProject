<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

    /**
     * @OA\Schema(
    *     schema="Permiso",
    *     type="object",
    *     title="Permiso",
    *     description="Modelo de Permiso",
    *     required={"NOMBRE", "DESCRIPCION", "ESTADO"},
    *     @OA\Property(property="NOMBRE", type="string", description="Nombre del permiso"),
    *     @OA\Property(property="DESCRIPCION", type="string", description="Descripción del permiso"),
    *     @OA\Property(property="ESTADO", type="string", description="Estado del permiso")
     * )
     */

     class swPermisoController extends swController
     {
         /**
          * @OA\Get(
          *     path="/api/permisos",
          *     summary="Obtener todos los permisos",
          *     tags={"Permiso"},
          *     @OA\Response(
          *         response=200,
          *         description="Lista de permisos",
          *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permiso"))
          *     )
          * )
          */
         public function index()
         {
             return response()->json(mod_Permiso::all(), Response::HTTP_OK);
         }
     
         /**
          * @OA\Post(
          *     path="/api/permisos",
          *     summary="Crear un nuevo permiso",
          *     tags={"Permiso"},
          *     @OA\RequestBody(
          *         required=true,
          *         @OA\JsonContent(ref="#/components/schemas/Permiso")
          *     ),
          *     @OA\Response(
          *         response=201,
          *         description="Permiso creado exitosamente"
          *     )
          * )
          */
         public function store(Request $request)
         {
             $request->validate([
                 'NOMBRE'      => 'required|string|max:255',
                 'DESCRIPCION' => 'required|string|max:255',
                 'ESTADO'      => 'required|string|max:50'
             ]);
     
             $permiso = mod_Permiso::create($request->all());
             return response()->json($permiso, Response::HTTP_CREATED);
         }
     
         /**
          * @OA\Get(
          *     path="/api/permisos/{id}",
          *     summary="Obtener un permiso por ID",
          *     tags={"Permiso"},
          *     @OA\Parameter(
          *         name="id",
          *         in="path",
          *         description="ID del permiso",
          *         required=true,
          *         @OA\Schema(type="integer")
          *     ),
          *     @OA\Response(
          *         response=200,
          *         description="Información del permiso",
          *         @OA\JsonContent(ref="#/components/schemas/Permiso")
          *     )
          * )
          */
         public function show($id)
         {
             $permiso = mod_Permiso::find($id);
             if (!$permiso) {
                 return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
             }
             return response()->json($permiso, Response::HTTP_OK);
         }
     
         /**
          * @OA\Put(
          *     path="/api/permisos/{id}",
          *     summary="Actualizar un permiso",
          *     tags={"Permiso"},
          *     @OA\Parameter(
          *         name="id",
          *         in="path",
          *         description="ID del permiso",
          *         required=true,
          *         @OA\Schema(type="integer")
          *     ),
          *     @OA\RequestBody(
          *         required=true,
          *         @OA\JsonContent(ref="#/components/schemas/Permiso")
          *     ),
          *     @OA\Response(
          *         response=200,
          *         description="Permiso actualizado"
          *     )
          * )
          */
         public function update(Request $request, $id)
         {
             $permiso = mod_Permiso::find($id);
             if (!$permiso) {
                 return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
             }
     
             $request->validate([
                 'NOMBRE'      => 'required|string|max:255',
                 'DESCRIPCION' => 'required|string|max:255',
                 'ESTADO'      => 'required|string|max:50'
             ]);
     
             $permiso->update($request->all());
             return response()->json($permiso, Response::HTTP_OK);
         }
     
         /**
          * @OA\Delete(
          *     path="/api/permisos/{id}",
          *     summary="Eliminar un permiso",
          *     tags={"Permiso"},
          *     @OA\Parameter(
          *         name="id",
          *         in="path",
          *         description="ID del permiso",
          *         required=true,
          *         @OA\Schema(type="integer")
          *     ),
          *     @OA\Response(
          *         response=204,
          *         description="Permiso eliminado"
          *     )
          * )
          */
         public function destroy($id)
         {
             $permiso = mod_Permiso::find($id);
             if (!$permiso) {
                 return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
             }
     
             $permiso->delete();
             return response()->json(null, Response::HTTP_NO_CONTENT);
         }
     
         /**
          * @OA\Get(
          *     path="/api/permisos/eliminados",
          *     summary="Obtener permisos eliminados",
          *     tags={"Permiso"},
          *     @OA\Response(
          *         response=200,
          *         description="Lista de permisos eliminados",
          *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permiso"))
          *     )
          * )
          */
         public function deleted()
         {
             return response()->json(mod_Permiso::onlyTrashed()->get(), Response::HTTP_OK);
         }
     
         /**
          * @OA\Put(
          *     path="/api/permisos/restore/{id}",
          *     summary="Restaurar un permiso eliminado",
          *     tags={"Permiso"},
          *     @OA\Parameter(
          *         name="id",
          *         in="path",
          *         description="ID del permiso",
          *         required=true,
          *         @OA\Schema(type="integer")
          *     ),
          *     @OA\Response(
          *         response=200,
          *         description="Permiso restaurado"
          *     )
          * )
          */
         public function restore($id)
         {
             $permiso = mod_Permiso::withTrashed()->findOrFail($id);
             $permiso->restore();
             return response()->json($permiso, Response::HTTP_OK);
         }
     }
     