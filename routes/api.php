<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;
use App\Http\Controllers\SwaggerControllers\swArticuloController;
use App\Http\Controllers\SwaggerControllers\swArticuloxCategoriaController;
use App\Http\Controllers\SwaggerControllers\swArticuloxFotoController;
use App\Http\Controllers\SwaggerControllers\swArticuloxProveedorController;
use App\Http\Controllers\SwaggerControllers\swCategoriaController;
use App\Http\Controllers\SwaggerControllers\swFotoController;
use App\Http\Controllers\SwaggerControllers\swInventarioController;
use App\Http\Controllers\SwaggerControllers\swMovimientoInventarioController;
use App\Http\Controllers\SwaggerControllers\swPermisoController;
use App\Http\Controllers\SwaggerControllers\swPersonaController;
use App\Http\Controllers\SwaggerControllers\swProveedorController;
use App\Http\Controllers\SwaggerControllers\swRolController;
use App\Http\Controllers\SwaggerControllers\swRolxPermisoController;
use App\Http\Controllers\SwaggerControllers\swRolxPersonaController;
use App\Http\Controllers\SwaggerControllers\swUsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de Personas SWAGGER

Route::get('/personas', [swPersonaController::class, 'index']);
Route::post('/personas', [swPersonaController::class, 'store']);
Route::get('/personas/eliminadas', [swPersonaController::class, 'deleted']);
Route::get('/personas/{id}', [swPersonaController::class, 'show']);
Route::put('/personas/{id}', [swPersonaController::class, 'update']);
Route::delete('/personas/{id}', [swPersonaController::class, 'destroy']);
Route::put('/personas/restore/{id}', [swPersonaController::class, 'restore']);

// Rutas de Usuarios SWAGGER

Route::get('/usuarios', [swUsuarioController::class, 'index']);
Route::post('/usuarios', [swUsuarioController::class, 'store']);
Route::get('/usuarios/eliminados', [swUsuarioController::class, 'deleted']);
Route::get('/usuarios/{id}', [swUsuarioController::class, 'show']);
Route::put('/usuarios/{id}', [swUsuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [swUsuarioController::class, 'destroy']);
Route::put('/usuarios/restore/{id}', [swUsuarioController::class, 'restore']);

// Rutas de Roles SWAGGER

Route::get('/roles', [swRolController::class, 'index']);
Route::post('/roles', [swRolController::class, 'store']);
Route::get('/roles/eliminados', [swRolController::class, 'deleted']);
Route::get('/roles/{id}', [swRolController::class, 'show']);
Route::put('/roles/{id}', [swRolController::class, 'update']);
Route::delete('/roles/{id}', [swRolController::class, 'destroy']);
Route::put('/roles/restore/{id}', [swRolController::class, 'restore']);

// Rutas de Permisos SWAGGER

Route::get('/permisos', [swPermisoController::class, 'index']);
Route::post('/permisos', [swPermisoController::class, 'store']);
Route::get('/permisos/eliminados', [swPermisoController::class, 'deleted']);
Route::get('/permisos/{id}', [swPermisoController::class, 'show']);
Route::put('/permisos/{id}', [swPermisoController::class, 'update']);
Route::delete('/permisos/{id}', [swPermisoController::class, 'destroy']);
Route::put('/permisos/restore/{id}', [swPermisoController::class, 'restore']);

// Rutas de RolesxPermisos SWAGGER
Route::get('/rolxpermiso', [swRolxPermisoController::class, 'index']);
Route::post('/rolxpermiso', [swRolxPermisoController::class, 'store']);
Route::get('/rolxpermiso/eliminados', [swRolxPermisoController::class, 'deleted']);
Route::get('/rolxpermiso/{id}', [swRolxPermisoController::class, 'show']);
Route::put('/rolxpermiso/{id}', [swRolxPermisoController::class, 'update']);
Route::delete('/rolxpermiso/{id}', [swRolxPermisoController::class, 'destroy']);
Route::put('/rolxpermiso/restore/{id}', [swRolxPermisoController::class, 'restore']);

// Rutas de RolesxPersonas SWAGGER
Route::get('/rolxpersona', [swRolxPersonaController::class, 'index']);
Route::post('/rolxpersona', [swRolxPersonaController::class, 'store']);
Route::get('/rolxpersona/eliminados', [swRolxPersonaController::class, 'deleted']);
Route::get('/rolxpersona/{id}', [swRolxPersonaController::class, 'show']);
Route::put('/rolxpersona/{id}', [swRolxPersonaController::class, 'update']);
Route::delete('/rolxpersona/{id}', [swRolxPersonaController::class, 'destroy']);
Route::put('/rolxpersona/restore/{id}', [swRolxPersonaController::class, 'restore']);

// Rutas de Fotos SWAGGER

Route::get('/fotos', [swFotoController::class, 'index']);
Route::post('/fotos', [swFotoController::class, 'store']);
Route::get('/fotos/eliminadas', [swFotoController::class, 'deleted']);
Route::get('/fotos/{id}', [swFotoController::class, 'show']);
Route::put('/fotos/{id}', [swFotoController::class, 'update']);
Route::delete('/fotos/{id}', [swFotoController::class, 'destroy']);
Route::put('/fotos/restore/{id}', [swFotoController::class, 'restore']);

// Rutas de Inventario SWAGGER

Route::get('/inventarios', [swInventarioController::class, 'index']);
Route::post('/inventarios', [swInventarioController::class, 'store']);
Route::get('/inventarios/eliminados', [swInventarioController::class, 'deleted']);
Route::get('/inventarios/{id}', [swInventarioController::class, 'show']);
Route::put('/inventarios/{id}', [swInventarioController::class, 'update']);
Route::delete('/inventarios/{id}', [swInventarioController::class, 'destroy']);
Route::put('/inventarios/restore/{id}', [swInventarioController::class, 'restore']);

// Rutas de Articulos SWAGGER

Route::get('/articulos', [swArticuloController::class, 'index']);
Route::post('/articulos', [swArticuloController::class, 'store']);
Route::get('/articulos/eliminados', [swArticuloController::class, 'deleted']);
Route::get('/articulos/{id}', [swArticuloController::class, 'show']);
Route::put('/articulos/{id}', [swArticuloController::class, 'update']);
Route::delete('/articulos/{id}', [swArticuloController::class, 'destroy']);
Route::put('/articulos/restore/{id}', [swArticuloController::class, 'restore']);

// Rutas de ArticuloxFoto SWAGGER

Route::get('/articuloxfoto', [swArticuloxFotoController::class, 'index']);
Route::post('/articuloxfoto', [swArticuloxFotoController::class, 'store']);
Route::get('/articuloxfoto/eliminados', [swArticuloxFotoController::class, 'deleted']);
Route::get('/articuloxfoto/{id}', [swArticuloxFotoController::class, 'show']);
Route::put('/articuloxfoto/{id}', [swArticuloxFotoController::class, 'update']);
Route::delete('/articuloxfoto/{id}', [swArticuloxFotoController::class, 'destroy']);
Route::put('/articuloxfoto/restore/{id}', [swArticuloxFotoController::class, 'restore']);

// Rutas de Categorias SWAGGER

Route::get('/categorias', [swCategoriaController::class, 'index']);
Route::post('/categorias', [swCategoriaController::class, 'store']);
Route::get('/categorias/eliminadas', [swCategoriaController::class, 'deleted']);
Route::get('/categorias/{id}', [swCategoriaController::class, 'show']);
Route::put('/categorias/{id}', [swCategoriaController::class, 'update']);
Route::delete('/categorias/{id}', [swCategoriaController::class, 'destroy']);
Route::put('/categorias/restore/{id}', [swCategoriaController::class, 'restore']);

// Rutas de ArticuloxCategoria SWAGGER  

Route::get('/articuloxcategoria', [swArticuloxCategoriaController::class, 'index']);
Route::post('/articuloxcategoria', [swArticuloxCategoriaController::class, 'store']);
Route::get('/articuloxcategoria/eliminados', [swArticuloxCategoriaController::class, 'deleted']);
Route::get('/articuloxcategoria/{id}', [swArticuloxCategoriaController::class, 'show']);
Route::put('/articuloxcategoria/{id}', [swArticuloxCategoriaController::class, 'update']);
Route::delete('/articuloxcategoria/{id}', [swArticuloxCategoriaController::class, 'destroy']);
Route::put('/articuloxcategoria/restore/{id}', [swArticuloxCategoriaController::class, 'restore']);

// Rutas de Proveedores SWAGGER

Route::get('/proveedores', [swProveedorController::class, 'index']);
Route::post('/proveedores', [swProveedorController::class, 'store']);
Route::get('/proveedores/eliminados', [swProveedorController::class, 'deleted']);
Route::get('/proveedores/{id}', [swProveedorController::class, 'show']);
Route::put('/proveedores/{id}', [swProveedorController::class, 'update']);
Route::delete('/proveedores/{id}', [swProveedorController::class, 'destroy']);
Route::put('/proveedores/restore/{id}', [swProveedorController::class, 'restore']);

// Rutas de ArticuloxProveedor SWAGGER

Route::get('/articuloxproveedor', [swArticuloxProveedorController::class, 'index']);
Route::post('/articuloxproveedor', [swArticuloxProveedorController::class, 'store']);
Route::get('/articuloxproveedor/eliminados', [swArticuloxProveedorController::class, 'deleted']);
Route::get('/articuloxproveedor/{id}', [swArticuloxProveedorController::class, 'show']);
Route::put('/articuloxproveedor/{id}', [swArticuloxProveedorController::class, 'update']);
Route::delete('/articuloxproveedor/{id}', [swArticuloxProveedorController::class, 'destroy']);
Route::put('/articuloxproveedor/restore/{id}', [swArticuloxProveedorController::class, 'restore']);

// Rutas de Movimientos de Inventario SWAGGER

Route::get('/movimientosinventario', [swMovimientoInventarioController::class, 'index']);
Route::post('/movimientosinventario', [swMovimientoInventarioController::class, 'store']);
Route::get('/movimientosinventario/eliminados', [swMovimientoInventarioController::class, 'deleted']);
Route::get('/movimientosinventario/{id}', [swMovimientoInventarioController::class, 'show']);
Route::put('/movimientosinventario/{id}', [swMovimientoInventarioController::class, 'update']);
Route::delete('/movimientosinventario/{id}', [swMovimientoInventarioController::class, 'destroy']);
Route::put('/movimientosinventario/restore/{id}', [swMovimientoInventarioController::class, 'restore']);
