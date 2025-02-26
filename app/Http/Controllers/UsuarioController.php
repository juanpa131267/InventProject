<?php

namespace App\Http\Controllers;

use App\Models\mod_Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *    title="API de Interacción con Base de Datos FireBird",
 *    version="1.0",
 *    description="API para la gestión"
 * )
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     title="Usuario",
 *     description="Modelo de Usuario",
 *     required={"ID_PERSONAS", "LOGIN", "PASSWORD"},
 *     @OA\Property(property="ID_PERSONAS", type="integer", description="ID de la persona asociada"),
 *     @OA\Property(property="LOGIN", type="string", description="Login del usuario"),
 *     @OA\Property(property="PASSWORD", type="string", description="Contraseña del usuario")
 * )
 */

 class UsuarioController extends Controller
 {
     /**
      * @OA\Get(
      *     path="/api/usuarios",
      *     summary="Obtener todos los usuarios",
      *     tags={"Usuario"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de usuarios",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
      *     )
      * )
      */
     public function index()
     {
         return response()->json(mod_Usuario::all(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Post(
      *     path="/api/usuarios",
      *     summary="Crear un nuevo usuario",
      *     tags={"Usuario"},
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/Usuario")
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Usuario creado exitosamente"
      *     )
      * )
      */
     public function store(Request $request)
     {
         $request->validate([
             'ID_PERSONAS' => 'required|integer',
             'LOGIN'      => 'required|string|max:255|unique:USUARIOS,LOGIN',
             'PASSWORD'   => 'required|string|max:255'
         ]);
 
         $usuario = mod_Usuario::create($request->all());
         return response()->json($usuario, Response::HTTP_CREATED);
     }
 
     /**
      * @OA\Get(
      *     path="/api/usuarios/{id}",
      *     summary="Obtener un usuario por ID",
      *     tags={"Usuario"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID del usuario",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Información del usuario",
      *         @OA\JsonContent(ref="#/components/schemas/Usuario")
      *     )
      * )
      */
     public function show($id)
     {
         $usuario = mod_Usuario::find($id);
         if (!$usuario) {
             return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
         }
         return response()->json($usuario, Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
      *     path="/api/usuarios/{id}",
      *     summary="Actualizar un usuario",
      *     tags={"Usuario"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID del usuario",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(ref="#/components/schemas/Usuario")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Usuario actualizado"
      *     )
      * )
      */
     public function update(Request $request, $id)
     {
         $usuario = mod_Usuario::find($id);
         if (!$usuario) {
             return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
         }
 
         $request->validate([
             'ID_PERSONAS' => 'required|integer',
             'LOGIN'      => 'required|string|max:255|unique:USUARIOS,LOGIN,' . $id . ',ID',
             'PASSWORD'   => 'required|string|max:255'
         ]);
 
         $usuario->update($request->all());
         return response()->json($usuario, Response::HTTP_OK);
     }
 
     /**
      * @OA\Delete(
      *     path="/api/usuarios/{id}",
      *     summary="Eliminar un usuario",
      *     tags={"Usuario"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID del usuario",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=204,
      *         description="Usuario eliminado"
      *     )
      * )
      */
     public function destroy($id)
     {
         $usuario = mod_Usuario::find($id);
         if (!$usuario) {
             return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
         }
 
         $usuario->delete();
         return response()->json(null, Response::HTTP_NO_CONTENT);
     }
 
     /**
      * @OA\Get(
      *     path="/api/usuarios/eliminados",
      *     summary="Obtener usuarios eliminados",
      *     tags={"Usuario"},
      *     @OA\Response(
      *         response=200,
      *         description="Lista de usuarios eliminados",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
      *     )
      * )
      */
     public function deleted()
     {
         return response()->json(mod_Usuario::onlyTrashed()->get(), Response::HTTP_OK);
     }
 
     /**
      * @OA\Put(
      *     path="/api/usuarios/restore/{id}",
      *     summary="Restaurar un usuario eliminado",
      *     tags={"Usuario"},
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID del usuario",
      *         required=true,
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Usuario restaurado"
      *     )
      * )
      */
     public function restore($id)
     {
         $usuario = mod_Usuario::withTrashed()->findOrFail($id);
         $usuario->restore();
         return response()->json($usuario, Response::HTTP_OK);
     }
 }