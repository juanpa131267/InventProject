<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mod_Rol;
use Symfony\Component\HttpFoundation\Response;

class RolController extends Controller
{
    // Listar roles con opción de búsqueda por descripción
    public function index(Request $request)
    {
        $search = $request->query('q');

        try {
            $query = mod_Rol::query();
            if (!empty($search)) {
                $query->where('DESCRIPCION', 'like', "%{$search}%");
            }
            $roles = $query->paginate(10);
            return response()->json($roles, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los roles'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar el formulario de creación
    public function create()
    {
        return view('VistasCrud.VistasRol.create');
    }

    // Almacenar un nuevo rol
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DESCRIPCION' => 'required|string|max:255|unique:ROLES,DESCRIPCION',
        ]);

        try {
            mod_Rol::create($validatedData);
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'No se pudo crear el rol.');
        }
    }

    // Mostrar un rol específico en JSON
    public function show($id)
    {
        try {
            $rol = mod_Rol::findOrFail($id);
            return response()->json($rol, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar el formulario de edición
    public function edit($id)
    {
        $rol = mod_Rol::findOrFail($id);
        return view('VistasCrud.VistasRol.edit', compact('rol'));
    }

    // Actualizar un rol existente
    public function update(Request $request, $id)
    {
        $rol = mod_Rol::findOrFail($id);
        
        $validatedData = $request->validate([
            'DESCRIPCION' => "required|string|max:255|unique:ROLES,DESCRIPCION,{$id},ID",
        ]);

        try {
            $rol->update($validatedData);
            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Error al actualizar el rol.');
        }
    }

    // Eliminar un rol (soft delete)
    public function destroy($id)
    {
        $rol = mod_Rol::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $rol->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el rol'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Listar roles eliminados (soft deleted)
    public function deleted()
    {
        $rolesEliminados = mod_Rol::onlyTrashed()->get();
        return view('VistasCrud.VistasRol.deleted', compact('rolesEliminados'));
    }

    // Restaurar un rol eliminado
    public function restore($id)
    {
        $rol = mod_Rol::withTrashed()->findOrFail($id);

        try {
            $rol->restore();
            return redirect()->route('roles.deleted')->with('success', 'Rol restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.deleted')->with('error', 'Error al restaurar el rol.');
        }
    }
}
