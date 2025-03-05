<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxCategoria;
use App\Models\mod_Articulo;
use App\Models\mod_Categoria;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticuloxCategoriaController extends Controller
{
    // Listar relaciones Artículo-Categoría con opción de búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = mod_ArticuloxCategoria::query()
            ->whereNull('DELETED_AT')
            ->whereHas('ARTICULOS', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->whereHas('CATEGORIAS', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->with([
                'ARTICULOS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRE', 'MARCA');
                },
                'CATEGORIAS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRE');
                }
            ]);

        if ($search) {
            $query->whereHas('ARTICULOS', function ($q) use ($search) {
                $q->where('NOMBRE', 'LIKE', "%{$search}%")
                  ->orWhere('MARCA', 'LIKE', "%{$search}%");
            })->orWhereHas('CATEGORIAS', function ($q) use ($search) {
                $q->where('NOMBRE', 'LIKE', "%{$search}%");
            });
        }

        $articulosxcategorias = $query->paginate(10);
        return response()->json($articulosxcategorias);
    }

    public function create()
    {
        $categorias = mod_Categoria::all();
        $articulosSinCategoria = mod_Articulo::whereNotIn('ID', function ($query) {
            $query->select('ID_ARTICULOS')->from('ARTICULOXCATEGORIA');
        })->get();

        return view('VistasCrud.VistasArticuloxCategoria.create', compact('articulosSinCategoria', 'categorias'));
    }

    // Almacenar una nueva relación Artículo-Categoría
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_ARTICULOS'  => 'required|integer|exists:ARTICULOS,ID',
            'ID_CATEGORIAS' => 'required|integer|exists:CATEGORIAS,ID',
        ]);

        try {
            mod_ArticuloxCategoria::create($validatedData);
            return redirect()->route('articuloxcategorias.index')->with('success', 'Categoría asignada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxcategorias.index')->with('error', 'Error al asignar la categoría.');
        }
    }

    // Mostrar una relación específica
    public function show($id)
    {
        try {
            $articuloxcategoria = mod_ArticuloxCategoria::with(['ARTICULOS', 'CATEGORIAS'])->findOrFail($id);
            return response()->json($articuloxcategoria, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::findOrFail($id);
        $articulos = mod_Articulo::all();
        $categorias = mod_Categoria::all();

        return view('VistasCrud.VistasArticuloxCategoria.edit', compact('articuloxcategoria', 'articulos', 'categorias'));
    }

    // Actualizar una relación Artículo-Categoría existente
    public function update(Request $request, $id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::findOrFail($id);

        $validatedData = $request->validate([
            'ID_ARTICULOS'  => 'required|integer|exists:ARTICULOS,ID',
            'ID_CATEGORIAS' => 'required|integer|exists:CATEGORIAS,ID',
        ]);

        try {
            $articuloxcategoria->update($validatedData);
            return redirect(url('/articuloxcategorias-index'))->with('success', 'Relación artículo-categoría actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/articuloxcategorias-index'))->with('error', 'Error al actualizar la relación.');
        }
    }

    // Eliminar lógicamente una relación
    public function destroy($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::find($id);

        if (!$articuloxcategoria) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $articuloxcategoria->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la relación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar las relaciones eliminadas
    public function deleted()
    {
        $articulosxcategoriasEliminados = mod_ArticuloxCategoria::onlyTrashed()->get();
        return view('VistasCrud.VistasArticuloxCategoria.deleted', compact('articulosxcategoriasEliminados'));
    }

    // Restaurar una relación eliminada
    public function restore($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::withTrashed()->findOrFail($id);

        try {
            $articuloxcategoria->restore();
            return redirect()->route('articuloxcategorias.deleted')->with('success', 'Relación restaurada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxcategorias.deleted')->with('error', 'Error al restaurar la relación.');
        }
    }

    // Eliminar permanentemente una relación
    public function forceDelete($id)
    {
        $articuloxcategoria = mod_ArticuloxCategoria::withTrashed()->findOrFail($id);

        try {
            $articuloxcategoria->forceDelete();
            return redirect()->route('articuloxcategorias.deleted')->with('success', 'Relación eliminada permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxcategorias.deleted')->with('error', 'Error al eliminar permanentemente la relación.');
        }
    }
}
