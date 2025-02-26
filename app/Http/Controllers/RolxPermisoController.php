<?php

namespace App\Http\Controllers;

use App\Models\mod_RolxPermiso;
use App\Models\mod_Rol;
use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="RolxPermiso",
 *     type="object",
 *     title="RolxPermiso",
 *     description="Modelo de la relación entre roles y permisos",
 *     required={"ID_ROLES", "ID_PERMISOS"},
 *     @OA\Property(property="ID_ROLES", type="integer", description="ID del rol"),
 *     @OA\Property(property="ID_PERMISOS", type="integer", description="ID del permiso")
 * )
 */

 class RolxPermisoController extends Controller
 {
     /**
      * @OA\Get(
      *     path="/api/rolxpermiso",
      *     summary="Obtener todas las relaciones entre roles y permisos",
      *     tags={"RolxPermiso"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de relaciones",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RolxPermiso"))
      *     )
      * )
      */
     public function index()
     {
         return response()->json(mod_RolxPermiso::all(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Post(
      *     path="/api/rolxpermiso",
      *     summary="Crear una nueva relación entre rol y permiso",
      *     tags={"RolxPermiso"},
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/RolxPermiso")
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Relación creada exitosamente"
      *     )
      * )
      */
     public function store(Request $request)
     {
         $request->validate([
             'ID_ROLES' => 'required|integer',
             'ID_PERMISOS' => 'required|integer'
         ]);
 
         $rolxpermiso = mod_RolxPermiso::create($request->all());
         return response()->json($rolxpermiso, Response::HTTP_CREATED);
     }
 
     /**
      * @OA\Get(
      *     path="/api/rolxpermiso/{id}",
      *     summary="Obtener una relación por ID",
      *     tags={"RolxPermiso"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la relación",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Información de la relación",
      *         @OA\JsonContent(ref="#/components/schemas/RolxPermiso")
      *     )
      * )
      */
     public function show($id)
     {
         $rolxpermiso = mod_RolxPermiso::find($id);
         if (!$rolxpermiso) {
             return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
         }
         return response()->json($rolxpermiso, Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
        *     path="/api/rolxpermiso/{id}",
        *     summary="Actualizar una relación",
        *     tags={"RolxPermiso"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID de la relación",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\JsonContent(ref="#/components/schemas/RolxPermiso")
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Relación actualizada"
        *     )
        * )
        */

    public function update(Request $request, $id)
    {
        $rolxpermiso = mod_RolxPermiso::find($id);
        if (!$rolxpermiso) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'ID_ROLES' => 'required|integer',
            'ID_PERMISOS' => 'required|integer'
        ]);

        $rolxpermiso->update($request->all());
        return response()->json($rolxpermiso, Response::HTTP_OK);
    }

     /**
      * @OA\Delete(
      *     path="/api/rolxpermiso/{id}",
      *     summary="Eliminar una relación",
      *     tags={"RolxPermiso"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la relación",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=204,
      *         description="Relación eliminada"
      *     )
      * )
      */

     public function destroy($id)
     {
         $rolxpermiso = mod_RolxPermiso::find($id);
         if (!$rolxpermiso) {
             return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
         }
         
         $rolxpermiso->delete();
         return response()->json(null, Response::HTTP_NO_CONTENT);
     }
 
     /**
      * @OA\Get(
      *     path="/api/rolxpermiso/eliminados",
      *     summary="Obtener relaciones eliminadas",
      *     tags={"RolxPermiso"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de relaciones eliminadas",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RolxPermiso"))
      *     )
      * )
      */
     public function deleted()
     {
         return response()->json(mod_RolxPermiso::onlyTrashed()->get(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
      *     path="/api/rolxpermiso/restore/{id}",
      *     summary="Restaurar una relación eliminada",
      *     tags={"RolxPermiso"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la relación",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Relación restaurada"
      *     )
      * )
      */
     public function restore($id)
     {
         $rolxpermiso = mod_RolxPermiso::withTrashed()->findOrFail($id);
         $rolxpermiso->restore();
         return response()->json($rolxpermiso, Response::HTTP_OK);
     }
 }
 