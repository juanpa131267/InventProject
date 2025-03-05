<?php

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


Route::get('/', function () {
    return view('welcome');
});

// RUTA ADMINISTRAR CRUD ("home") // 
Route::get('/administrar-tablas', function () {
    return view('VistasCrud.home');
});

    // RUTA INDEX TABLA PERSONAS // 
    Route::resource('personas', PersonaController::class);
    Route::get('/personas-index', function () {
        return view('VistasCrud.VistasPersona.index');
    })->name('personas.index');



    //Ruta para obtener datos de personas (usada en AJAX)
    Route::get('/api/personas', [PersonaController::class, 'index'])->name('personas.api');
    //CRUD de personas (sin duplicaciones)
    Route::resource('personas', PersonaController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/personas-eliminados', [PersonaController::class, 'deleted'])->name('personas.deleted');
        Route::post('/personas/{id}/restore', [PersonaController::class, 'restore'])->name('personas.restore');
        Route::delete('/personas/{id}/force-delete', [PersonaController::class, 'forceDelete'])->name('personas.forceDelete');
        Route::get('/personas/buscar', [PersonaController::class, 'index'])->name('personas.buscar');



    // RUTA INDEX TABLA USUARIOS //
    Route::resource('usuarios', UsuarioController::class);
    Route::get('/usuarios-index', function () {
        return view('VistasCrud.VistasUsuario.index');
    })->name('usuarios.index');

    //Ruta para obtener datos de usuarios (usada en AJAX)
    Route::get('/api/usuarios', [UsuarioController::class, 'index'])->name('usuarios.api');
    //CRUD de usuarios (sin duplicaciones)
    Route::resource('usuarios', UsuarioController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/usuarios-eliminados', [UsuarioController::class, 'deleted'])->name('usuarios.deleted');
        Route::post('/usuarios/{id}/restore', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        Route::delete('/usuarios/{id}/force-delete', [UsuarioController::class, 'forceDelete'])->name('usuarios.forceDelete');
        Route::get('/usuarios/buscar', [UsuarioController::class, 'index'])->name('usuarios.buscar');
        


    // RUTA INDEX TABLA ROLES //
    Route::resource('roles', RolController::class);
    Route::get('/roles-index', function () {
        return view('VistasCrud.VistasRol.index');
    })->name('roles.index');

    //Ruta para obtener datos de roles (usada en AJAX)
    Route::get('/api/roles', [RolController::class, 'index'])->name('roles.api');
    //CRUD de roles (sin duplicaciones)
    Route::resource('roles', RolController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/roles-eliminados', [RolController::class, 'deleted'])->name('roles.deleted');
        Route::post('/roles/{id}/restore', [RolController::class, 'restore'])->name('roles.restore');
        Route::delete('/roles/{id}/force-delete', [RolController::class, 'forceDelete'])->name('roles.forceDelete');
        Route::get('/roles/buscar', [RolController::class, 'index'])->name('roles.buscar');



    // RUTA PARA LA VISTA PRINCIPAL DEL CRUD DE ROLXPERSONA
    Route::get('/rolxpersonas-index', function () {
        return view('VistasCrud.VistasRolxPersona.index');
    })->name('rolxpersonas.index');

    // RUTA PARA EL CRUD DE ROLXPERSONA (EXCLUYENDO INDEX PORQUE SE MANEJA POR AJAX)
    Route::resource('rolxpersonas', RolxPersonaController::class)->except(['index']);

    // RUTA PARA LA API QUE SE USA EN AJAX
    Route::get('/api/rolxpersonas', [RolxPersonaController::class, 'index'])->name('rolxpersonas.api');

        // MANEJO DE REGISTROS ELIMINADOS Y RESTAURACIÓN
        Route::get('/rolxpersonas-eliminados', [RolxPersonaController::class, 'deleted'])->name('rolxpersonas.deleted');
        Route::post('/rolxpersonas/{id}/restore', [RolxPersonaController::class, 'restore'])->name('rolxpersonas.restore');
        Route::delete('/rolxpersonas/{id}/force-delete', [RolxPersonaController::class, 'forceDelete'])->name('rolxpersonas.forceDelete');

        // BÚSQUEDA DE ROLXPERSONA
        Route::get('/rolxpersonas/buscar', [RolxPersonaController::class, 'index'])->name('rolxpersonas.buscar');



    // RUTA INDEX TABLA PERMISOS //
    Route::resource('permisos', PermisoController::class);
    Route::get('/permisos-index', function () {
        return view('VistasCrud.VistasPermiso.index');
    })->name('permisos.index');

    //Ruta para obtener datos de permisos (usada en AJAX)
    Route::get('/api/permisos', [PermisoController::class, 'index'])->name('permisos.api');
    //CRUD de permisos (sin duplicaciones)
    Route::resource('permisos', PermisoController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/permisos-eliminados', [PermisoController::class, 'deleted'])->name('permisos.deleted');
        Route::post('/permisos/{id}/restore', [PermisoController::class, 'restore'])->name('permisos.restore');
        Route::delete('/permisos/{id}/force-delete', [PermisoController::class, 'forceDelete'])->name('permisos.forceDelete');
        Route::get('/permisos/buscar', [PermisoController::class, 'index'])->name('permisos.buscar');

    

    // RUTA INDEX TABLA ROLESXPERMISOS //
    Route::get('/rolxpermisos-index', function () {
        return view('VistasCrud.VistasRolxPermiso.index');
    })->name('rolxpermisos.index');

    //CRUD de rolesxpermisos (sin duplicaciones)
    Route::resource('rolxpermisos', RolxPermisoController::class)->except(['index']);

    //Ruta para obtener datos de rolesxpermisos (usada en AJAX)
    Route::get('/api/rolxpermisos', [RolxPermisoController::class, 'index'])->name('rolxpermisos.api');

        //Manejo de eliminados/restauración
        Route::get('/rolxpermisos-eliminados', [RolxPermisoController::class, 'deleted'])->name('rolxpermisos.deleted');
        Route::post('/rolxpermisos/{id}/restore', [RolxPermisoController::class, 'restore'])->name('rolxpermisos.restore');
        Route::delete('/rolxpermisos/{id}/force-delete', [RolxPermisoController::class, 'forceDelete'])->name('rolxpermisos.forceDelete');
        Route::get('/rolxpermisos/buscar', [RolxPermisoController::class, 'index'])->name('rolxpermisos.buscar');

        // Ruta para mostrar la vista de administración de permisos
        Route::get('/rolxpermisos/{id}/manage', [RolxPermisoController::class, 'manage'])->name('rolxpermisos.manage');

        // Ruta para actualizar los permisos de un rol
        Route::put('/rolxpermisos/{id}/update', [RolxPermisoController::class, 'update'])->name('rolxpermisos.update');
        Route::get('/rolxpermisos-index', [RolxPermisoController::class, 'index'])->name('rolxpermisos.index');


        
    // RUTA INDEX TABLA FOTOS //
    Route::resource('fotos', FotoController::class);
    Route::get('/fotos-index', function () {
        return view('VistasCrud.VistasFoto.index');
    })->name('fotos.index');

    //Ruta para obtener datos de fotos (usada en AJAX)
    Route::get('/api/fotos', [FotoController::class, 'index'])->name('fotos.api');
    //CRUD de fotos (sin duplicaciones)
    Route::resource('fotos', FotoController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/fotos-eliminadas', [FotoController::class, 'deleted'])->name('fotos.deleted');
        Route::post('/fotos/{id}/restore', [FotoController::class, 'restore'])->name('fotos.restore');
        Route::delete('/fotos/{id}/force-delete', [FotoController::class, 'forceDelete'])->name('fotos.forceDelete');
        Route::get('/fotos/buscar', [FotoController::class, 'index'])->name('fotos.buscar');

        
    
    // RUTA INDEX TABLA INVENTARIOS //
    Route::resource('inventarios', InventarioController::class);
    Route::get('/inventarios-index', function () {
        return view('VistasCrud.VistasInventario.index');
    })->name('inventarios.index');

    //Ruta para obtener datos de inventarios (usada en AJAX)
    Route::get('/api/inventarios', [InventarioController::class, 'index'])->name('inventarios.api');
    //CRUD de inventarios (sin duplicaciones)
    Route::resource('inventarios', InventarioController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/inventarios-eliminados', [InventarioController::class, 'deleted'])->name('inventarios.deleted');
        Route::post('/inventarios/{id}/restore', [InventarioController::class, 'restore'])->name('inventarios.restore');
        Route::delete('/inventarios/{id}/force-delete', [InventarioController::class, 'forceDelete'])->name('inventarios.forceDelete');
        Route::get('/inventarios/buscar', [InventarioController::class, 'index'])->name('inventarios.buscar');

    

    // RUTA INDEX TABLA ARTICULOS //
    Route::resource('articulos', ArticuloController::class);
    Route::get('/articulos-index', function () {
        return view('VistasCrud.VistasArticulo.index');
    })->name('articulos.index');

    //Ruta para obtener datos de articulos (usada en AJAX)
    Route::get('/api/articulos', [ArticuloController::class, 'index'])->name('articulos.api');
    //CRUD de articulos (sin duplicaciones)
    Route::resource('articulos', ArticuloController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/articulos-eliminados', [ArticuloController::class, 'deleted'])->name('articulos.deleted');
        Route::post('/articulos/{id}/restore', [ArticuloController::class, 'restore'])->name('articulos.restore');
        Route::delete('/articulos/{id}/force-delete', [ArticuloController::class, 'forceDelete'])->name('articulos.forceDelete');
        Route::get('/articulos/buscar', [ArticuloController::class, 'index'])->name('articulos.buscar');

    

    // RUTA INDEX TABLA ARTICULOSXFOTOS //
    Route::resource('articuloxfotos', ArticuloxFotoController::class);
    Route::get('/articuloxfotos-index', function () {
        return view('VistasCrud.VistasArticuloxFoto.index');
    })->name('articuloxfotos.index');

    //Ruta para obtener datos de articulosxfotos (usada en AJAX)
    Route::get('/api/articuloxfotos', [ArticuloxFotoController::class, 'index'])->name('articuloxfotos.api');
    //CRUD de articulosxfotos (sin duplicaciones)
    Route::resource('articuloxfotos', ArticuloxFotoController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/articuloxfotos-eliminados', [ArticuloxFotoController::class, 'deleted'])->name('articuloxfotos.deleted');
        Route::post('/articuloxfotos/{id}/restore', [ArticuloxFotoController::class, 'restore'])->name('articuloxfotos.restore');
        Route::delete('/articuloxfotos/{id}/force-delete', [ArticuloxFotoController::class, 'forceDelete'])->name('articuloxfotos.forceDelete');
        Route::get('/articuloxfotos/buscar', [ArticuloxFotoController::class, 'index'])->name('articuloxfotos.buscar');

    

    // RUTA INDEX TABLA CATGORIAS //
    Route::resource('categorias', CategoriaController::class);
    Route::get('/categorias-index', function () {
        return view('VistasCrud.VistasCategoria.index');
    })->name('categorias.index');

    //Ruta para obtener datos de categorias (usada en AJAX)
    Route::get('/api/categorias', [CategoriaController::class, 'index'])->name('categorias.api');
    //CRUD de categorias (sin duplicaciones)
    Route::resource('categorias', CategoriaController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/categorias-eliminadas', [CategoriaController::class, 'deleted'])->name('categorias.deleted');
        Route::post('/categorias/{id}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');
        Route::delete('/categorias/{id}/force-delete', [CategoriaController::class, 'forceDelete'])->name('categorias.forceDelete');
        Route::get('/categorias/buscar', [CategoriaController::class, 'index'])->name('categorias.buscar');
    


    // RUTA INDEX TABLA ARTICULOSXCATEGORIAS //
    Route::resource('articuloxcategorias', ArticuloxCategoriaController::class);
    Route::get('/articuloxcategorias-index', function () {
        return view('VistasCrud.VistasArticuloxCategoria.index');
    })->name('articuloxcategorias.index');

    //Ruta para obtener datos de articulosxcategorias (usada en AJAX)
    Route::get('/api/articuloxcategorias', [ArticuloxCategoriaController::class, 'index'])->name('articuloxcategorias.api');
    //CRUD de articulosxcategorias (sin duplicaciones)
    Route::resource('articuloxcategorias', ArticuloxCategoriaController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/articuloxcategorias-eliminados', [ArticuloxCategoriaController::class, 'deleted'])->name('articuloxcategorias.deleted');
        Route::post('/articuloxcategorias/{id}/restore', [ArticuloxCategoriaController::class, 'restore'])->name('articuloxcategorias.restore');
        Route::delete('/articuloxcategorias/{id}/force-delete', [ArticuloxCategoriaController::class, 'forceDelete'])->name('articuloxcategorias.forceDelete');
        Route::get('/articuloxcategorias/buscar', [ArticuloxCategoriaController::class, 'index'])->name('articuloxcategorias.buscar');

    

    // RUTA INDEX TABLA PROVEEDORES //
    Route::resource('proveedores', ProveedorController::class);
    Route::get('/proveedores-index', function () {
        return view('VistasCrud.VistasProveedor.index');
    })->name('proveedores.index');

    //Ruta para obtener datos de proveedores (usada en AJAX)
    Route::get('/api/proveedores', [ProveedorController::class, 'index'])->name('proveedores.api');
    //CRUD de proveedores (sin duplicaciones)
    Route::resource('proveedores', ProveedorController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/proveedores-eliminados', [ProveedorController::class, 'deleted'])->name('proveedores.deleted');
        Route::post('/proveedores/{id}/restore', [ProveedorController::class, 'restore'])->name('proveedores.restore');
        Route::delete('/proveedores/{id}/force-delete', [ProveedorController::class, 'forceDelete'])->name('proveedores.forceDelete');
        Route::get('/proveedores/buscar', [ProveedorController::class, 'index'])->name('proveedores.buscar');

    

    // RUTA INDEX TABLA ARTICULOSXPROVEEDORES //
    Route::resource('articuloxproveedores', ArticuloxProveedorController::class);
    Route::get('/articuloxproveedores-index', function () {
        return view('VistasCrud.VistasArticuloxProveedor.index');
    })->name('articuloxproveedores.index');

    //Ruta para obtener datos de articulosxproveedores (usada en AJAX)
    Route::get('/api/articuloxproveedores', [ArticuloxProveedorController::class, 'index'])->name('articuloxproveedores.api');
    //CRUD de articulosxproveedores (sin duplicaciones)
    Route::resource('articuloxproveedores', ArticuloxProveedorController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/articuloxproveedores-eliminados', [ArticuloxProveedorController::class, 'deleted'])->name('articuloxproveedores.deleted');
        Route::post('/articuloxproveedores/{id}/restore', [ArticuloxProveedorController::class, 'restore'])->name('articuloxproveedores.restore');
        Route::delete('/articuloxproveedores/{id}/force-delete', [ArticuloxProveedorController::class, 'forceDelete'])->name('articuloxproveedores.forceDelete');
        Route::get('/articuloxproveedores/buscar', [ArticuloxProveedorController::class, 'index'])->name('articuloxproveedores.buscar');

    

    // RUTA INDEX TABLA MOVIMIENTOSINVENTARIOS //
    Route::resource('movimientosinventarios', MovimientoInventarioController::class);
    Route::get('/movimientosinventarios-index', function () {
        return view('VistasCrud.VistasMovimientoInventario.index');
    })->name('movimientosinventarios.index');

    //Ruta para obtener datos de movimientosinventarios (usada en AJAX)
    Route::get('/api/movimientosinventarios', [MovimientoInventarioController::class, 'index'])->name('movimientosinventarios.api');
    //CRUD de movimientosinventarios (sin duplicaciones)
    Route::resource('movimientosinventarios', MovimientoInventarioController::class)->except(['index']);

        //Manejo de eliminados/restauración
        Route::get('/movimientosinventarios-eliminados', [MovimientoInventarioController::class, 'deleted'])->name('movimientosinventarios.deleted');
        Route::post('/movimientosinventarios/{id}/restore', [MovimientoInventarioController::class, 'restore'])->name('movimientosinventarios.restore');
        Route::delete('/movimientosinventarios/{id}/force-delete', [MovimientoInventarioController::class, 'forceDelete'])->name('movimientosinventarios.forceDelete');
        Route::get('/movimientosinventarios/buscar', [MovimientoInventarioController::class, 'index'])->name('movimientosinventarios.buscar');