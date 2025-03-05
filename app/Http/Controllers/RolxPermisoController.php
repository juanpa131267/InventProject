<?php

namespace App\Http\Controllers;

use App\Models\mod_RolxPermiso;
use App\Models\mod_Rol;
use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolxPermisoController extends Controller
{
    // Listar relaciones Rol-Permiso con opción de búsqueda
    public function index(Request $request)
    {
        $roles = mod_Rol::all(); // Obtener todos los roles
        return view('VistasCrud.VistasRolxPermiso.index', compact('roles'));
    }

    public function manage($id)
    {
        $rol = mod_Rol::findOrFail($id); // Obtener el rol específico
        $permisos = mod_Permiso::all(); // Obtener todos los permisos disponibles

        // Obtener los permisos asignados al rol
        $permisosAsignados = $rol->PERMISOS->pluck('ID')->toArray();

        return view('VistasCrud.VistasRolxPermiso.edit', compact('rol', 'permisos', 'permisosAsignados'));
    }
        
    // Mostrar la vista de creación
    public function create()
    {
        $roles = mod_Rol::all();
        $permisos = mod_Permiso::all();
        
        return view('VistasCrud.VistasRolxPermiso.create', compact('roles', 'permisos'));
    }

    // Almacenar una nueva relación Rol-Permiso
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_ROL' => 'required|integer|exists:ROLES,ID',
            'ID_PERMISO' => 'required|integer|exists:PERMISOS,ID',
        ]);

        try {
            mod_RolxPermiso::create($validatedData);
            return redirect()->route('rolxpermisos.index')->with('success', 'Permiso asignado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('rolxpermisos.index')->with('error', 'Error al asignar el permiso.');
        }
    }

    // Mostrar una relación específica
    public function show($id)
    {
        $rolxpermiso = mod_RolxPermiso::with(['ROL', 'PERMISO'])->findOrFail($id);
        return response()->json($rolxpermiso);
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        $rolxpermiso = mod_RolxPermiso::findOrFail($id);
        $roles = mod_Rol::all();
        $permisos = mod_Permiso::all();
        
        return view('VistasCrud.VistasRolxPermiso.edit', compact('rolxpermiso', 'roles', 'permisos'));
    }

    // Actualizar una relación existente
    public function update(Request $request, $id)
    {
        $rol = mod_Rol::findOrFail($id);
    
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:PERMISOS,ID',
        ]);
    
        // Sincronizar los permisos asignados al rol
        $rol->PERMISOS()->sync($validatedData['permisos'] ?? []);
    
        return redirect()->route('rolxpermisos.index')->with('success', 'Permisos actualizados correctamente.');
    }

    // Eliminar lógicamente una relación (Soft Delete)
    public function destroy($id)
    {
        $rolxpermiso = mod_RolxPermiso::findOrFail($id);

        try {
            $rolxpermiso->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la relación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
