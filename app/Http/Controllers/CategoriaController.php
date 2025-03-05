<?php

namespace App\Http\Controllers;

use App\Models\mod_Categoria;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriaController extends Controller
{
    // Mostrar la lista de categorías
    public function index(Request $request)
    {
        $search = $request->query('q');
        
        try {
            $query = mod_Categoria::query();
            if (!empty($search)) {
                $query->where('NOMBRE', 'like', "%{$search}%");
            }
            
            $categorias = $query->paginate(15);
            return response()->json($categorias, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener categorías'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar la vista de creación
    public function create()
    {
        return view('VistasCrud.VistasCategoria.create');
    }

    // Almacenar una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:255|unique:CATEGORIAS,NOMBRE',
        ]);

        try {
            mod_Categoria::create($request->all());
            return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')->with('error', 'No se pudo crear la categoría.');
        }
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        try {
            $categoria = mod_Categoria::findOrFail($id);
            return response()->json($categoria, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Categoría no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        $categoria = mod_Categoria::findOrFail($id);
        return view('VistasCrud.VistasCategoria.edit', compact('categoria'));
    }

    // Actualizar una categoría
    public function update(Request $request, $id)
    {
        $categoria = mod_Categoria::findOrFail($id);
        
        $request->validate([
            'NOMBRE' => "required|string|max:255|unique:CATEGORIAS,NOMBRE,$id,ID",
        ]);

        try {
            $categoria->update($request->only('NOMBRE'));
            return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')->with('error', 'Error al actualizar la categoría.');
        }
    }

    // Eliminar una categoría (lógica)
    public function destroy($id)
    {
        $categoria = mod_Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $categoria->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la categoría'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar categorías eliminadas
    public function deleted()
    {
        $categoriasEliminadas = mod_Categoria::onlyTrashed()->get();
        return view('VistasCrud.VistasCategoria.deleted', compact('categoriasEliminadas'));
    }

    // Restaurar una categoría eliminada
    public function restore($id)
    {
        $categoria = mod_Categoria::withTrashed()->findOrFail($id);
        $categoria->restore();
        return redirect()->route('categorias.deleted')->with('success', 'Categoría restaurada exitosamente.');
    }

    // Eliminar permanentemente una categoría
    public function forceDelete($id)
    {
        $categoria = mod_Categoria::withTrashed()->findOrFail($id);
        
        try {
            $categoria->forceDelete();
            return redirect()->route('categorias.deleted')->with('success', 'Categoría eliminada permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('categorias.deleted')->with('error', 'Error al eliminar la categoría permanentemente.');
        }
    }
}
