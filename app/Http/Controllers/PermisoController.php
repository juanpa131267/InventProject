<?php

namespace App\Http\Controllers;

use App\Models\mod_Permiso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermisoController extends Controller
{
    // Mostrar la lista de permisos con opción de búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q'); 

        try {
            $query = mod_Permiso::query();

            if (!empty($search)) {
                $query->where('NOMBRE', 'like', "%{$search}%");
            }

            $permisos = $query->paginate(15);

            return response()->json($permisos, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener permisos'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación
    public function create()
    {
        return view('VistasCrud.VistasPermiso.create');
    }

    // Almacenar un nuevo permiso
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:255|unique:PERMISOS,NOMBRE',
            'DESCRIPCION' => 'nullable|string',
            'ESTADO' => 'required|string|max:50',
        ]);

        try {
            mod_Permiso::create($validatedData);
            return redirect(url('/permisos-index'))->with('success', 'Permiso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'No se pudo crear el permiso. Intente nuevamente.');
        }
    }

    // Mostrar un permiso específico
    public function show($id)
    {
        try {
            $permiso = mod_Permiso::findOrFail($id);
            return response()->json($permiso, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición de un permiso
    public function edit($id)
    {
        $permiso = mod_Permiso::findOrFail($id);
        return view('VistasCrud.VistasPermiso.edit', compact('permiso'));
    }

    // Actualizar un permiso existente
    public function update(Request $request, $id)
    {
        $permiso = mod_Permiso::findOrFail($id);

        $request->validate([
            'NOMBRE' => "required|string|max:255|unique:PERMISOS,NOMBRE,$id,ID",
            'DESCRIPCION' => 'nullable|string',
            'ESTADO' => 'required|string|max:50',
        ]);

        try {
            $permiso->update($request->only(['NOMBRE', 'DESCRIPCION', 'ESTADO']));
            return redirect(url('/permisos-index'))->with('success', 'Permiso actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/permisos-index'))->with('error', 'Error al actualizar el permiso.');
        }
    }

    // Eliminar un permiso (lógica)
    public function destroy($id)
    {
        $permiso = mod_Permiso::find($id);

        if (!$permiso) {
            return response()->json(['error' => 'Permiso no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $permiso->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el permiso'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar permisos eliminados
    public function deleted()
    {
        $permisosEliminados = mod_Permiso::onlyTrashed()->get();
        return view('VistasCrud.VistasPermiso.deleted', compact('permisosEliminados'));
    }

    // Restaurar un permiso eliminado
    public function restore($id)
    {
        $permiso = mod_Permiso::withTrashed()->findOrFail($id);
        $permiso->restore();

        return redirect()->route('permisos.deleted')->with('success', 'Permiso restaurado exitosamente.');
    }

    // Eliminación definitiva de un permiso
    public function forceDelete($id)
    {
        $permiso = mod_Permiso::withTrashed()->findOrFail($id);

        try {
            $permiso->forceDelete();
            return redirect()->route('permisos.deleted')->with('success', 'Permiso eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('permisos.deleted')->with('error', 'Error al eliminar el permiso.');
        }
    }
}
