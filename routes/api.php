<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ArticuloxCategoriaController;
use App\Http\Controllers\ArticuloxFotoController;
use App\Http\Controllers\ArticuloxProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolxPermisoController;
use App\Http\Controllers\RolxPersonaController;
use App\Http\Controllers\UsuarioController;

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

Route::get('/personas', [PersonaController::class, 'index']);
Route::post('/personas', [PersonaController::class, 'store']);
Route::get('/personas/eliminadas', [PersonaController::class, 'deleted']);
Route::get('/personas/{id}', [PersonaController::class, 'show']);
Route::put('/personas/{id}', [PersonaController::class, 'update']);
Route::delete('/personas/{id}', [PersonaController::class, 'destroy']);
Route::put('/personas/restore/{id}', [PersonaController::class, 'restore']);

// Rutas de Usuarios SWAGGER

Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::get('/usuarios/eliminados', [UsuarioController::class, 'deleted']);
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
Route::put('/usuarios/restore/{id}', [UsuarioController::class, 'restore']);

// Rutas de Roles SWAGGER

Route::get('/roles', [RolController::class, 'index']);
Route::post('/roles', [RolController::class, 'store']);
Route::get('/roles/eliminados', [RolController::class, 'deleted']);
Route::get('/roles/{id}', [RolController::class, 'show']);
Route::put('/roles/{id}', [RolController::class, 'update']);
Route::delete('/roles/{id}', [RolController::class, 'destroy']);
Route::put('/roles/restore/{id}', [RolController::class, 'restore']);

// Rutas de Permisos SWAGGER

Route::get('/permisos', [PermisoController::class, 'index']);
Route::post('/permisos', [PermisoController::class, 'store']);
Route::get('/permisos/eliminados', [PermisoController::class, 'deleted']);
Route::get('/permisos/{id}', [PermisoController::class, 'show']);
Route::put('/permisos/{id}', [PermisoController::class, 'update']);
Route::delete('/permisos/{id}', [PermisoController::class, 'destroy']);
Route::put('/permisos/restore/{id}', [PermisoController::class, 'restore']);

// Rutas de RolesxPermisos SWAGGER

Route::get('/rolxpermiso', [RolxPermisoController::class, 'index']);
Route::post('/rolxpermiso', [RolxPermisoController::class, 'store']);
Route::get('/rolxpermiso/eliminados', [RolxPermisoController::class, 'deleted']);
Route::get('/rolxpermiso/{id}', [RolxPermisoController::class, 'show']);
Route::put('/rolxpermiso/{id}', [RolxPermisoController::class, 'update']);
Route::delete('/rolxpermiso/{id}', [RolxPermisoController::class, 'destroy']);
Route::put('/rolxpermiso/restore/{id}', [RolxPermisoController::class, 'restore']);

// Rutas de RolesxPersonas SWAGGER

Route::get('/rolxpersona', [RolxPersonaController::class, 'index']);
Route::post('/rolxpersona', [RolxPersonaController::class, 'store']);
Route::get('/rolxpersona/eliminados', [RolxPersonaController::class, 'deleted']);
Route::get('/rolxpersona/{id}', [RolxPersonaController::class, 'show']);
Route::put('/rolxpersona/{id}', [RolxPersonaController::class, 'update']);
Route::delete('/rolxpersona/{id}', [RolxPersonaController::class, 'destroy']);
Route::put('/rolxpersona/restore/{id}', [RolxPersonaController::class, 'restore']);

// Rutas de Fotos SWAGGER

Route::get('/fotos', [FotoController::class, 'index']);
Route::post('/fotos', [FotoController::class, 'store']);
Route::get('/fotos/eliminadas', [FotoController::class, 'deleted']);
Route::get('/fotos/{id}', [FotoController::class, 'show']);
Route::put('/fotos/{id}', [FotoController::class, 'update']);
Route::delete('/fotos/{id}', [FotoController::class, 'destroy']);
Route::put('/fotos/restore/{id}', [FotoController::class, 'restore']);

// Rutas de Inventario SWAGGER

Route::get('/inventarios', [InventarioController::class, 'index']);
Route::post('/inventarios', [InventarioController::class, 'store']);
Route::get('/inventarios/eliminados', [InventarioController::class, 'deleted']);
Route::get('/inventarios/{id}', [InventarioController::class, 'show']);
Route::put('/inventarios/{id}', [InventarioController::class, 'update']);
Route::delete('/inventarios/{id}', [InventarioController::class, 'destroy']);
Route::put('/inventarios/restore/{id}', [InventarioController::class, 'restore']);

// Rutas de Articulos SWAGGER

Route::get('/articulos', [ArticuloController::class, 'index']);
Route::post('/articulos', [ArticuloController::class, 'store']);
Route::get('/articulos/eliminados', [ArticuloController::class, 'deleted']);
Route::get('/articulos/{id}', [ArticuloController::class, 'show']);
Route::put('/articulos/{id}', [ArticuloController::class, 'update']);
Route::delete('/articulos/{id}', [ArticuloController::class, 'destroy']);
Route::put('/articulos/restore/{id}', [ArticuloController::class, 'restore']);

// Rutas de ArticuloxFoto SWAGGER

Route::get('/articuloxfoto', [ArticuloxFotoController::class, 'index']);
Route::post('/articuloxfoto', [ArticuloxFotoController::class, 'store']);
Route::get('/articuloxfoto/eliminados', [ArticuloxFotoController::class, 'deleted']);
Route::get('/articuloxfoto/{id}', [ArticuloxFotoController::class, 'show']);
Route::put('/articuloxfoto/{id}', [ArticuloxFotoController::class, 'update']);
Route::delete('/articuloxfoto/{id}', [ArticuloxFotoController::class, 'destroy']);
Route::put('/articuloxfoto/restore/{id}', [ArticuloxFotoController::class, 'restore']);

// Rutas de Categorias SWAGGER

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::get('/categorias/eliminadas', [CategoriaController::class, 'deleted']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
Route::put('/categorias/restore/{id}', [CategoriaController::class, 'restore']);

// Rutas de ArticuloxCategoria SWAGGER  

Route::get('/articuloxcategoria', [ArticuloxCategoriaController::class, 'index']);
Route::post('/articuloxcategoria', [ArticuloxCategoriaController::class, 'store']);
Route::get('/articuloxcategoria/eliminados', [ArticuloxCategoriaController::class, 'deleted']);
Route::get('/articuloxcategoria/{id}', [ArticuloxCategoriaController::class, 'show']);
Route::put('/articuloxcategoria/{id}', [ArticuloxCategoriaController::class, 'update']);
Route::delete('/articuloxcategoria/{id}', [ArticuloxCategoriaController::class, 'destroy']);
Route::put('/articuloxcategoria/restore/{id}', [ArticuloxCategoriaController::class, 'restore']);

// Rutas de Proveedores SWAGGER

Route::get('/proveedores', [ProveedorController::class, 'index']);
Route::post('/proveedores', [ProveedorController::class, 'store']);
Route::get('/proveedores/eliminados', [ProveedorController::class, 'deleted']);
Route::get('/proveedores/{id}', [ProveedorController::class, 'show']);
Route::put('/proveedores/{id}', [ProveedorController::class, 'update']);
Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy']);
Route::put('/proveedores/restore/{id}', [ProveedorController::class, 'restore']);

// Rutas de ArticuloxProveedor SWAGGER

Route::get('/articuloxproveedor', [ArticuloxProveedorController::class, 'index']);
Route::post('/articuloxproveedor', [ArticuloxProveedorController::class, 'store']);
Route::get('/articuloxproveedor/eliminados', [ArticuloxProveedorController::class, 'deleted']);
Route::get('/articuloxproveedor/{id}', [ArticuloxProveedorController::class, 'show']);
Route::put('/articuloxproveedor/{id}', [ArticuloxProveedorController::class, 'update']);
Route::delete('/articuloxproveedor/{id}', [ArticuloxProveedorController::class, 'destroy']);
Route::put('/articuloxproveedor/restore/{id}', [ArticuloxProveedorController::class, 'restore']);

// Rutas de Movimientos de Inventario SWAGGER

Route::get('/movimientosinventario', [MovimientoInventarioController::class, 'index']);
Route::post('/movimientosinventario', [MovimientoInventarioController::class, 'store']);
Route::get('/movimientosinventario/eliminados', [MovimientoInventarioController::class, 'deleted']);
Route::get('/movimientosinventario/{id}', [MovimientoInventarioController::class, 'show']);
Route::put('/movimientosinventario/{id}', [MovimientoInventarioController::class, 'update']);
Route::delete('/movimientosinventario/{id}', [MovimientoInventarioController::class, 'destroy']);
Route::put('/movimientosinventario/restore/{id}', [MovimientoInventarioController::class, 'restore']);
