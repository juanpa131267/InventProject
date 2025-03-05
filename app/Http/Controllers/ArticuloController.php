<?php

namespace App\Http\Controllers;

use App\Models\mod_Articulo;
use App\Models\mod_Inventario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ArticuloController extends Controller
{
    // Listar artículos con opción de búsqueda por nombre o marca
    public function index(Request $request)
    {
        $search = $request->query('q');
        try {
            $query = mod_Articulo::with('INVENTARIOS');
            if (!empty($search)) {
                $query->where('NOMBRE', 'like', "%{$search}%")
                      ->orWhere('MARCA', 'like', "%{$search}%");
            }
            $articulos = $query->paginate(15);

    

            return response()->json($articulos, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener artículos'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar formulario de creación
    public function create()
    {
        $inventarios = mod_Inventario::all();
        return view('VistasCrud.VistasArticulo.create', compact('inventarios'));
    }

    // Almacenar un nuevo artículo

    public function store(Request $request)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'ID_INVENTARIOS' => 'required|integer|exists:INVENTARIOS,ID',
            'NOMBRE' => 'required|string|max:255',
            'MARCA' => 'nullable|string|max:255',
            'DESCRIPCION' => 'nullable|string',
            'FECHACADUCIDAD' => 'nullable|date',
            'UNIDAD' => 'required|string|max:10',
            'CANTIDAD' => 'required|integer|min:0'
        ]);

        $validatedData['FECHACADUCIDAD'] = $request->FECHACADUCIDAD ?: null;
    
        try {
            // Intentar crear el artículo
            mod_Articulo::create($validatedData);
            return redirect(url('/articulos-index'))->with('success', 'Artículo creado exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error en storage/logs/laravel.log
            Log::error('Error al crear artículo:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $validatedData
            ]);
    
            return redirect(url('/articulos-index'))->with('error', 'Error al crear el artículo.');
        }
    }

    // Mostrar detalles de un artículo en JSON
    public function show($id)
    {
        try {
            $articulo = mod_Articulo::findOrFail($id);
            return response()->json($articulo, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Artículo no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $articulo = mod_Articulo::findOrFail($id);
        $inventarios = mod_Inventario::all();
        return view('VistasCrud.VistasArticulo.edit', compact('articulo', 'inventarios'));
    }

    // Actualizar artículo existente
    public function update(Request $request, $id)
    {
        $articulo = mod_Articulo::findOrFail($id);
        
        $validatedData = $request->validate([
            'ID_INVENTARIOS' => 'required|integer|exists:INVENTARIOS,ID',
            'NOMBRE' => 'required|string|max:255',
            'MARCA' => 'nullable|string|max:255',
            'DESCRIPCION' => 'nullable|string',
            'FECHACADUCIDAD' => 'nullable|date',
            'UNIDAD' => 'required|string|max:10',
        ]);
        
        $validatedData['FECHACADUCIDAD'] = $request->FECHACADUCIDAD ?: null;
        


        try {
            $articulo->update($validatedData);
            return redirect(url('/articulos-index'))->with('success', 'Artículo actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/articulos-index'))->with('error', 'Error al actualizar el artículo.');
        }
    }

    // Eliminar un artículo (soft delete)
    public function destroy($id)
    {
        $articulo = mod_Articulo::find($id);

        if (!$articulo) {
            return response()->json(['error' => 'Artículo no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $articulo->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el artículo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar artículos eliminados
    public function deleted()
    {
        $articulosEliminados = mod_Articulo::onlyTrashed()->get();
        return view('VistasCrud.VistasArticulo.deleted', compact('articulosEliminados'));
    }

    // Restaurar un artículo eliminado
    public function restore($id)
    {
        $articulo = mod_Articulo::withTrashed()->findOrFail($id);

        try {
            $articulo->restore();
            return redirect()->route('articulos.deleted')->with('success', 'Artículo restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('articulos.deleted')->with('error', 'Error al restaurar el artículo.');
        }
    }

    // Eliminación permanente de un artículo
    public function forceDelete($id)
    {
        $articulo = mod_Articulo::withTrashed()->findOrFail($id);

        try {
            $articulo->forceDelete();
            return redirect()->route('articulos.deleted')->with('success', 'Artículo eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('articulos.deleted')->with('error', 'Error al eliminar el artículo permanentemente.');
        }
    }
}
