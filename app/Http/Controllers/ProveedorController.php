<?php

namespace App\Http\Controllers;

use App\Models\mod_Proveedores;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProveedorController extends Controller
{
    // Mostrar lista de proveedores con paginación y búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');

        try {
            $query = mod_Proveedores::query();
            if (!empty($search)) {
                $query->where('NOMBRE', 'like', "%{$search}%")
                      ->orWhere('CORREO', 'like', "%{$search}%");
            }
            $proveedores = $query->paginate(15);
            return response()->json($proveedores, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener proveedores'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar vista de creación de proveedores
    public function create()
    {
        return view('VistasCrud.VistasProveedor.create');
    }

    // Almacenar un nuevo proveedor
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'TELEFONO' => 'nullable|string|max:20',
            'CORREO' => 'nullable|email|max:255|unique:PROVEEDORES,CORREO',
            'DIRECCION' => 'nullable|string',
        ]);

        try {
            mod_Proveedores::create($validatedData);
            return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')->with('error', 'No se pudo crear el proveedor. Intente nuevamente.');
        }
    }

    // Mostrar un proveedor específico
    public function show($id)
    {
        try {
            $proveedor = mod_Proveedores::findOrFail($id);
            return response()->json($proveedor, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Proveedor no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar vista de edición de un proveedor
    public function edit($id)
    {
        $proveedor = mod_Proveedores::findOrFail($id);
        return view('VistasCrud.VistasProveedor.edit', compact('proveedor'));
    }

    // Actualizar un proveedor existente
    public function update(Request $request, $id)
    {
        $proveedor = mod_Proveedores::findOrFail($id);

        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'TELEFONO' => 'nullable|string|max:20',
            'CORREO' => "nullable|email|max:255|unique:PROVEEDORES,CORREO,$id,ID",
            'DIRECCION' => 'nullable|string',
        ]);

        try {
            $proveedor->update($validatedData);
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')->with('error', 'Error al actualizar proveedor.');
        }
    }

    // Eliminar un proveedor (lógica)
    public function destroy($id)
    {
        $proveedor = mod_Proveedores::find($id);

        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $proveedor->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar proveedor'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar proveedores eliminados
    public function deleted()
    {
        $proveedoresEliminados = mod_Proveedores::onlyTrashed()->get();
        return view('VistasCrud.VistasProveedor.deleted', compact('proveedoresEliminados'));
    }

    // Restaurar un proveedor eliminado
    public function restore($id)
    {
        $proveedor = mod_Proveedores::withTrashed()->findOrFail($id);
        $proveedor->restore();

        return redirect()->route('proveedores.deleted')->with('success', 'Proveedor restaurado exitosamente.');
    }

    // Eliminación forzada (borrado permanente)
    public function forceDelete($id)
    {
        $proveedor = mod_Proveedores::withTrashed()->findOrFail($id);

        try {
            $proveedor->forceDelete();
            return redirect()->route('proveedores.deleted')->with('success', 'Proveedor eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('proveedores.deleted')->with('error', 'Error al eliminar proveedor permanentemente.');
        }
    }
}
