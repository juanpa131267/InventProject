<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_RolxPersona;
use App\Models\mod_Rol;
use App\Models\mod_Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="RolxPersona",
 *     type="object",
 *     title="RolxPersona",
 *     description="Modelo de Rol por Persona",
 *     required={"ID_ROLES", "ID_PERSONAS"},
 *     @OA\Property(property="ID_ROLES", type="integer", description="ID del rol asociado"),
 *     @OA\Property(property="ID_PERSONAS", type="integer", description="ID de la persona asociada")
 * )
 */

 class swRolxPersonaController extends swController
 {
     /**
      * @OA\Get(
      *     path="/api/rolxpersona",
      *     summary="Obtener todas las asignaciones de roles a personas",
      *     tags={"RolxPersona"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de roles asignados a personas",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RolxPersona"))
      *     )
      * )
      */
     public function index()
     {
         return response()->json(mod_RolxPersona::all(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Post(
      *     path="/api/rolxpersona",
      *     summary="Asignar un nuevo rol a una persona",
      *     tags={"RolxPersona"},
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/RolxPersona")
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Rol asignado exitosamente"
      *     )
      * )
      */
     public function store(Request $request)
     {
         $request->validate([
             'ID_ROLES' => 'required|integer',
             'ID_PERSONAS' => 'required|integer'
         ]);
 
         $rolxpersona = mod_RolxPersona::create($request->all());
         return response()->json($rolxpersona, Response::HTTP_CREATED);
     }
 
     /**
      * @OA\Get(
      *     path="/api/rolxpersona/{id}",
      *     summary="Obtener una asignación de rol por ID",
      *     tags={"RolxPersona"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la asignación",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Información de la asignación de rol",
      *         @OA\JsonContent(ref="#/components/schemas/RolxPersona")
      *     )
      * )
      */
     public function show($id)
     {
         $rolxpersona = mod_RolxPersona::find($id);
         if (!$rolxpersona) {
             return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
         }
         return response()->json($rolxpersona, Response::HTTP_OK);
     }

     /**
      * @OA\Put(
      *     path="/api/rolxpersona/{id}",
      *     summary="Actualizar una asignación de rol",
      *     tags={"RolxPersona"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la asignación",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/RolxPersona")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Asignación actualizada"
      *     )
      * )
      */

     public function update(Request $request, $id)
     {
         $rolxpersona = mod_RolxPersona::find($id);
         if (!$rolxpersona) {
             return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
         }
         $request->validate([
             'ID_ROLES' => 'required|integer',
             'ID_PERSONAS' => 'required|integer'
         ]);
         $rolxpersona->update($request->all());
         return response()->json($rolxpersona, Response::HTTP_OK);
     }
 
     /**
      * @OA\Delete(
      *     path="/api/rolxpersona/{id}",
      *     summary="Eliminar una asignación de rol",
      *     tags={"RolxPersona"},
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
         $rolxpersona = mod_RolxPersona::find($id);
         if (!$rolxpersona) {
             return response()->json(['error' => 'Asignación no encontrada'], Response::HTTP_NOT_FOUND);
         }
         $rolxpersona->delete();
         return response()->json(null, Response::HTTP_NO_CONTENT);
     }
 
     /**
      * @OA\Get(
      *     path="/api/rolxpersona/eliminados",
      *     summary="Obtener asignaciones eliminadas",
      *     tags={"RolxPersona"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de asignaciones eliminadas",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RolxPersona"))
      *     )
      * )
      */
     public function deleted()
     {
         return response()->json(mod_RolxPersona::onlyTrashed()->get(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
      *     path="/api/rolxpersona/restore/{id}",
      *     summary="Restaurar una asignación eliminada",
      *     tags={"RolxPersona"},
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
         $rolxpersona = mod_RolxPersona::withTrashed()->findOrFail($id);
         $rolxpersona->restore();
         return response()->json($rolxpersona, Response::HTTP_OK);
     }
 }
 