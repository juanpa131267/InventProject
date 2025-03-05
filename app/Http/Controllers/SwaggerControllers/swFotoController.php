<?php

namespace App\Http\Controllers\SwaggerControllers;

use App\Models\mod_Foto;
use carbon\carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="API de Fotos",
 *    version="1.0",
 *    description="API para la gestión de fotos"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Foto",
 *     type="object",
 *     title="Foto",
 *     description="Modelo de Foto",
 *     required={"URL", "DESCRIPCION", "FECHA_SUBIDA"},
 *     @OA\Property(property="URL", type="string", description="URL de la foto"),
 *     @OA\Property(property="DESCRIPCION", type="string", description="Descripción de la foto"),
 * )
 */

 class swFotoController extends swController
 {
     /**
      * @OA\Get(
      *     path="/api/fotos",
      *     summary="Obtener todas las fotos",
      *     tags={"Foto"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de fotos",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Foto"))
      *     )
      * )
      */
     public function index()
     {
         return response()->json(mod_Foto::all(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Post(
      *     path="/api/fotos",
      *     summary="Crear una nueva foto",
      *     tags={"Foto"},
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/Foto")
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Foto creada exitosamente"
      *     )
      * )
      */
      public function store(Request $request)
      {
          $validatedData = $request->validate([
              'URL' => 'required|string|max:255',
              'DESCRIPCION' => 'nullable|string',
          ]);
  
          $foto = mod_Foto::create($validatedData);
          return response()->json($foto, 201);
      }
 
     /**
      * @OA\Get(
      *     path="/api/fotos/{id}",
      *     summary="Obtener una foto por ID",
      *     tags={"Foto"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la foto",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Información de la foto",
      *         @OA\JsonContent(ref="#/components/schemas/Foto")
      *     )
      * )
      */
     public function show($id)
     {
         $foto = mod_Foto::find($id);
         if (!$foto) {
             return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
         }
         return response()->json($foto, Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
      *     path="/api/fotos/{id}",
      *     summary="Actualizar una foto",
      *     tags={"Foto"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la foto",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/Foto")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Foto actualizada"
      *     )
      * )
      */
     public function update(Request $request, $id)
     {
         $foto = mod_Foto::find($id);
         if (!$foto) {
             return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
         }
 
         $request->validate([
             'URL' => 'required|string|max:255',
             'DESCRIPCION' => 'nullable|string',
         ]);
 
         $foto->update($request->all());
         return response()->json($foto, Response::HTTP_OK);
     }
 
     /**
      * @OA\Delete(
      *     path="/api/fotos/{id}",
      *     summary="Eliminar una foto",
      *     tags={"Foto"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID de la foto",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=204,
      *         description="Foto eliminada"
      *     )
      * )
      */
     public function destroy($id)
     {
         $foto = mod_Foto::find($id);
         if (!$foto) {
             return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
         }
 
         $foto->delete();
         return response()->json(null, Response::HTTP_NO_CONTENT);
     }
    

    /**
     * @OA\Get(
     *      path="/api/fotos/eliminadas",
     *      summary="Obtener todas las fotos eliminadas",
     *      tags={"Foto"},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de fotos eliminadas",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Foto"))
     *      )
     * )
     */

    public function deleted()
    {
        return response()->json(mod_Foto::onlyTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *    path="/api/fotos/restore/{id}",
     *    summary="Restaurar una foto eliminada",
     *    tags={"Foto"},
     *    @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID de la foto",
     *      required=true,
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="Foto restaurada"
     *    )
     * )
     */

    public function restore($id)
    {
        $foto = mod_Foto::withTrashed()->find($id);
        if (!$foto) {
            return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $foto->restore();
        return response()->json($foto, Response::HTTP_OK);
    }
 }
 