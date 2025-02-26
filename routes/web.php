<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;
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


Route::get('/', function () {
    return view('welcome');
});

// RUTA ADMINISTRAR CRUD ("home") // 
Route::get('/administrar-tablas', function () {
    return view('VistasCrud.home');
});

    // RUTA INDEX TABLA PERSONAS // 
    Route::get('/personas-index', [PersonaController::class, 'index'])->name('personas.index');
    Route::get('/personas-index', function () {
        return view('VistasCrud.VistasPersona.index');
    });
        // RUTAS PARA MANEJAR PERSONAS
        Route::resource('personas', PersonaController::class)->except(['index']);
        Route::resource('personas', PersonaController::class);

        Route::get('/personas-eliminados', [PersonaController::class, 'deleted'])->name('personas.deleted');
        Route::post('/personas/{id}/restore', [PersonaController::class, 'restore'])->name('personas.restore');
        Route::delete('/personas/{id}/force-delete', [PersonaController::class, 'forceDelete'])->name('personas.forceDelete');


    // RUTA INDEX TABLA USUARIOS // 
    Route::get('/usuarios-index', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios-index', function () {
        return view('VistasCrud.VistasUsuario.index');
    });
        // RUTAS PARA MANEJAR USUARIOS
        Route::resource('usuarios', UsuarioController::class)->except(['index']);
        Route::resource('usuarios', UsuarioController::class);

        Route::get('/usuarios-eliminados', [UsuarioController::class, 'deleted'])->name('usuarios.deleted');
        Route::post('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        Route::delete('/usuarios/{id}/force-delete', [UsuarioController::class, 'forceDelete'])->name('usuarios.forceDelete');


    // RUTA INDEX TABLA ROLES //
    Route::get('/roles-index', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles-index', function () {
        return view('VistasCrud.VistasRol.index');
    });
        // RUTAS PARA MANEJAR ROLES
        Route::resource('roles', RolController::class)->except(['index']);
        Route::resource('roles', RolController::class);

        Route::get('/roles-eliminados', [RolController::class, 'deleted'])->name('roles.deleted');
        Route::post('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore');
        Route::delete('/roles/{id}/force-delete', [RolController::class, 'forceDelete'])->name('roles.forceDelete');


    // RUTA INDEX TABLA ROLESXPERSONAS //
    Route::get('/rolxpersona-index', [RolxPersonaController::class, 'index'])->name('rolxpersona.index');
    Route::get('/rolxpersona-index', function () {
        return view('VistasCrud.VistasRolxPersona.index');
    });
        // RUTAS PARA MANEJAR ROLESXPERSONAS
        Route::resource('rolxpersona', RolxPersonaController::class)->except(['index']);
        Route::resource('rolxpersona', RolxPersonaController::class);

        Route::get('/rolxpersona-eliminados', [RolxPersonaController::class, 'deleted'])->name('rolxpersona.deleted');
        Route::post('/rolxpersona/{id}/restore', [RolxPersonaController::class, 'restore'])->name('rolxpersona.restore');
        Route::delete('/rolxpersona/{id}/force-delete', [RolxPersonaController::class, 'forceDelete'])->name('rolxpersona.forceDelete');

    // RUTA INDEX TABLA PERMISOS //
    Route::get('/permisos-index', [PermisoController::class, 'index'])->name('permisos.index');
    Route::get('/permisos-index', function () {
        return view('VistasCrud.VistasPermiso.index');
    });
        // RUTAS PARA MANEJAR PERMISOS
        Route::resource('permisos', PermisoController::class)->except(['index']);
        Route::resource('permisos', PermisoController::class);

        Route::get('/permisos-eliminados', [PermisoController::class, 'deleted'])->name('permisos.deleted');
        Route::post('/permisos/{id}/restore', [PermisoController::class, 'restore'])->name('permisos.restore');
        Route::get('/rolxpermiso/permisos-disponibles/{rol_id}', [RolxPermisoController::class, 'obtenerPermisosDisponibles']);
        Route::delete('/permisos/{id}/force-delete', [PermisoController::class, 'forceDelete'])->name('permisos.forceDelete');

    // RUTA INDEX TABLA ROLESXPERMISOS //
    Route::get('/rolxpermiso-index', [RolxPermisoController::class, 'index'])->name('rolxpermiso.index');
    Route::get('/rolxpermiso-index', function () {
        return view('VistasCrud.VistasRolxPermiso.index');
    });
        // RUTAS PARA MANEJAR ROLESXPERMISOS
        Route::resource('rolxpermiso', RolxPermisoController::class)->except(['index']);
        Route::resource('rolxpermiso', RolxPermisoController::class);

        Route::get('/rolxpermiso-eliminados', [RolxPermisoController::class, 'deleted'])->name('rolxpermiso.deleted');
        Route::post('/rolxpermiso/{id}/restore', [RolxPermisoController::class, 'restore'])->name('rolxpermiso.restore');
        Route::delete('/rolxpermiso/{id}/force-delete', [RolxPermisoController::class, 'forceDelete'])->name('rolxpermiso.forceDelete');
