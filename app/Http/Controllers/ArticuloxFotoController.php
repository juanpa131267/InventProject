<?php

namespace App\Http\Controllers;

use App\Models\mod_ArticuloxFoto;
use App\Models\mod_Articulo;
use App\Models\mod_Foto;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticuloxFotoController extends Controller
{
    // Listar relaciones Articulo-Foto con opción de búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = mod_ArticuloxFoto::query()
            ->whereNull('DELETED_AT')
            ->whereHas('ARTICULOS', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->whereHas('FOTOS', function ($q) {
                $q->whereNull('DELETED_AT');
            })
            ->with([
                'ARTICULOS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRE', 'MARCA');
                },
                'FOTOS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'URL', 'DESCRIPCION');
                }
            ]);

        if ($search) {
            $query->whereHas('ARTICULOS', function ($q) use ($search) {
                $q->where('NOMBRE', 'LIKE', "%{$search}%")
                  ->orWhere('MARCA', 'LIKE', "%{$search}%");
            });
        }

        $articulosxfotos = $query->paginate(10);
        return response()->json($articulosxfotos);
    }

    public function create()
    {
        $fotos = mod_Foto::all();
        $articulosSinFoto = mod_Articulo::whereNotIn('ID', function ($query) {
            $query->select('ID_ARTICULOS')->from('ARTICULOXFOTO');
        })->get();

        return view('VistasCrud.VistasArticuloxFoto.create', compact('articulosSinFoto', 'fotos'));
    }

    // Almacenar una nueva relación Artículo-Foto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_ARTICULOS' => 'required|integer|exists:ARTICULOS,ID',
            'ID_FOTOS' => 'required|integer|exists:FOTOS,ID',
        ]);

        try {
            mod_ArticuloxFoto::create($validatedData);
            return redirect()->route('articuloxfotos.index')->with('success', 'Imagen asignada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxfotos.index')->with('error', 'Error al asignar la imagen.');
        }
    }

    // Mostrar una relación específica
    public function show($id)
    {
        try {
            $articuloxfoto = mod_ArticuloxFoto::with(['ARTICULOS', 'FOTOS'])->findOrFail($id);
            return response()->json($articuloxfoto, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }


    // Mostrar la vista de edición
    public function edit($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::findOrFail($id);
        $articulos = mod_Articulo::all();
        $fotos = mod_Foto::all();

        return view('VistasCrud.VistasArticuloxFoto.edit', compact('articuloxfoto', 'articulos', 'fotos'));
    }

    // Actualizar una relación artículo-foto existente
    public function update(Request $request, $id)
    {
        $articuloxfoto = mod_ArticuloxFoto::findOrFail($id);

        $validatedData = $request->validate([
            'ID_ARTICULOS' => 'required|integer|exists:ARTICULOS,ID',
            'ID_FOTOS'     => 'required|integer|exists:FOTOS,ID',
        ]);

        try {
            $articuloxfoto->update($validatedData);
            return redirect(url('/articuloxfotos-index'))->with('success', 'Relación artículo-foto actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/articuloxfotos-index'))->with('error', 'Error al actualizar la relación artículo-foto.');
        }
    }



    // Eliminar lógicamente una relación
    public function destroy($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::find($id);

        if (!$articuloxfoto) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $articuloxfoto->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la relación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar las relaciones eliminadas
    public function deleted()
    {
        $articuloxfotosEliminados = mod_ArticuloxFoto::onlyTrashed()->get();
        return view('VistasCrud.VistasArticuloxFoto.deleted', compact('articuloxfotosEliminados'));
    }

    // Restaurar una relación eliminada
    public function restore($id)
    {
        $articuloxfoto = mod_ArticuloxFoto::withTrashed()->findOrFail($id);

        try {
            $articuloxfoto->restore();
            return redirect()->route('articuloxfotos.deleted')->with('success', 'Relación restaurada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('articuloxfotos.deleted')->with('error', 'Error al restaurar la relación.');
        }
    }
}
