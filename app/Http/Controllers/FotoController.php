<?php

namespace App\Http\Controllers;

use App\Models\mod_Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FotoController extends Controller
{
    // Listar todas las fotos
    public function index(Request $request)
    {
        try {
            $fotos = mod_Foto::paginate(15);
            return response()->json($fotos, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener fotos'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('VistasCrud.VistasFoto.create');
    }

    // Almacenar una nueva foto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'URL' => 'required|string|max:255',
            'DESCRIPCION' => 'nullable|string',
        ]);

        try {
            mod_Foto::create($validatedData);
            return redirect()->route('fotos.index')->with('success', 'Foto creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('fotos.index')->with('error', 'No se pudo crear la foto.');
        }
    }

    // Mostrar una foto específica
    public function show($id)
    {
        try {
            $foto = mod_Foto::findOrFail($id);
            return response()->json($foto, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $foto = mod_Foto::findOrFail($id);
        return view('VistasCrud.VistasFoto.edit', compact('foto'));
    }

    // Actualizar una foto
    public function update(Request $request, $id)
    {
        $foto = mod_Foto::findOrFail($id);

        $validatedData = $request->validate([
            'URL' => 'required|string|max:255',
            'DESCRIPCION' => 'nullable|string',
        ]);

        try {
            $foto->update($validatedData);
            return redirect()->route('fotos.index')->with('success', 'Foto actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('fotos.index')->with('error', 'Error al actualizar la foto.');
        }
    }

    // Eliminar una foto (lógica)
    public function destroy($id)
    {
        $foto = mod_Foto::find($id);

        if (!$foto) {
            return response()->json(['error' => 'Foto no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $foto->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar foto'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar fotos eliminadas
    public function deleted()
    {
        $fotosEliminadas = mod_Foto::onlyTrashed()->get();
        return view('VistasCrud.VistasFoto.deleted', compact('fotosEliminadas'));
    }

    // Restaurar una foto eliminada
    public function restore($id)
    {
        $foto = mod_Foto::withTrashed()->findOrFail($id);
        $foto->restore();

        return redirect()->route('fotos.deleted')->with('success', 'Foto restaurada exitosamente.');
    }

    // Eliminación permanente de una foto
    public function forceDelete($id)
    {
        $foto = mod_Foto::withTrashed()->findOrFail($id);

        try {
            $foto->forceDelete();
            return redirect()->route('fotos.deleted')->with('success', 'Foto eliminada permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('fotos.deleted')->with('error', 'Error al eliminar permanentemente la foto.');
        }
    }
}
