<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxProveedor;
use App\Models\mod_Articulo;
use App\Models\mod_Proveedores;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticuloxProveedorController extends Controller
{
    // Listar relaciones Articulo-Proveedor con opción de búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = mod_ArticuloxProveedor::query()
            ->whereNull('DELETED_AT')
            ->whereHas('ARTICULOS', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->whereHas('PROVEEDORES', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->with([
                'ARTICULOS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRE', 'MARCA');
                },
                'PROVEEDORES' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRE', 'TELEFONO', 'CORREO');
                }
            ]);

        if ($search) {
            $query->whereHas('ARTICULOS', function ($q) use ($search) {
                $q->where('NOMBRE', 'LIKE', "%{$search}%")
                  ->orWhere('MARCA', 'LIKE', "%{$search}%");
            });
        }

        $articulosxproveedores = $query->paginate(10);
        return response()->json($articulosxproveedores);
    }

    public function create()
    {
        $proveedores = mod_Proveedores::all();
        $articulosSinProveedor = mod_Articulo::whereNotIn('ID', function ($query) {
            $query->select('ID_ARTICULOS')->from('ARTICULOXPROVEEDOR');
        })->get();
    
        return view('VistasCrud.VistasArticuloxProveedor.create', compact('articulosSinProveedor', 'proveedores'));
    }
    
    // Almacenar una nueva relación Artículo-Proveedor
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_ARTICULOS' => 'required|integer|exists:ARTICULOS,ID',
            'ID_PROVEEDORES' => 'required|integer|exists:PROVEEDORES,ID',
        ]);
    
        try {
            mod_ArticuloxProveedor::create($validatedData);
            return redirect()->route('articuloxproveedores.index')->with('success', 'Proveedor asignado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxproveedores.index')->with('error', 'Error al asignar el proveedor.');
        }
    }
    


    // Mostrar una relación específica
    public function show($id)
    {
        try {
            $articuloxproveedor = mod_ArticuloxProveedor::with(['ARTICULOS', 'PROVEEDORES'])->findOrFail($id);
            return response()->json($articuloxproveedor, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::findOrFail($id);
        $articulos = mod_Articulo::all();
        $proveedores = mod_Proveedores::all();

        return view('VistasCrud.VistasArticuloxProveedor.edit', compact('articuloxproveedor', 'articulos', 'proveedores'));
    }

    // Actualizar una relación artículo-proveedor existente
    public function update(Request $request, $id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::findOrFail($id);

        $validatedData = $request->validate([
            'ID_ARTICULOS'   => 'required|integer|exists:ARTICULOS,ID',
            'ID_PROVEEDORES' => 'required|integer|exists:PROVEEDORES,ID',
        ]);

        try {
            $articuloxproveedor->update($validatedData);
            return redirect(url('/articuloxproveedores-index'))->with('success', 'Relación artículo-proveedor actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/articuloxproveedores-index'))->with('error', 'Error al actualizar la relación artículo-proveedor.');
        }
    }


    // Eliminar lógicamente una relación
    public function destroy($id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::find($id);

        if (!$articuloxproveedor) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $articuloxproveedor->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la relación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar las relaciones eliminadas
    public function deleted()
    {
        $articulosxproveedoresEliminados = mod_ArticuloxProveedor::onlyTrashed()->get();
        return view('VistasCrud.VistasArticuloxProveedor.deleted', compact('articulosxproveedoresEliminados'));
    }

    // Restaurar una relación eliminada
    public function restore($id)
    {
        $articuloxproveedor = mod_ArticuloxProveedor::withTrashed()->findOrFail($id);

        try {
            $articuloxproveedor->restore();
            return redirect()->route('articuloxproveedores.deleted')->with('success', 'Relación restaurada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxproveedores.deleted')->with('error', 'Error al restaurar la relación.');
        }
    }
}
