<?php

namespace App\Http\Controllers;

use App\Models\mod_Inventario;
use App\Models\mod_Foto;
use App\Models\mod_Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
    
        $query = mod_Inventario::query()
            ->whereNull('DELETED_AT') // Excluir inventarios eliminados
            ->with([
                'USUARIOS' => function ($q) {
                    $q->select('ID', 'LOGIN');
                },
                'FOTOS' => function ($q) {
                    $q->select('ID', 'URL'); // Ajusta según la estructura de tu tabla de fotos
                }
            ]);
    
        if ($search) {
            $query->where('NOMBRE', 'LIKE', "%{$search}%");
        }
    
        $inventarios = $query->paginate(10);
    
        return response()->json($inventarios);
    }
    

    // Mostrar formulario de creación
    public function create()
    {
        $usuarios = mod_Usuario::all(); // O el método correcto para obtener los usuarios
        $fotos = mod_Foto::all();
        return view('VistasCrud.VistasInventario.create', compact('usuarios', 'fotos'));
    }
    

    // Almacenar un nuevo inventario
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'ID_FOTOS' => 'nullable|integer|exists:FOTOS,ID',
            'ID_USUARIOS' => 'required|integer|exists:USUARIOS,ID',
        ]);        

        try {
            mod_Inventario::create($validatedData);
            return redirect()->route('inventarios.index')->with('success', 'Inventario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('inventarios.create')->with('error', 'Error: ' . $e->getMessage());
        }        
    }

    // Mostrar un inventario específico
    public function show($id)
    {
        try {
            $inventario = mod_Inventario::findOrFail($id);
            return response()->json($inventario, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    public function edit($id)
    {
        $inventario = mod_Inventario::findOrFail($id);
        $usuarios = mod_Usuario::all(); // Obtener todos los usuarios
        $fotos = mod_Foto::all();
    
        return view('VistasCrud.VistasInventario.edit', compact('inventario', 'usuarios', 'fotos'));
    }
    
    // Actualizar un inventario
    public function update(Request $request, $id)
    {
        $inventario = mod_Inventario::findOrFail($id);

        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:255',
            'ID_FOTOS' => 'nullable|integer|exists:FOTOS,ID',
            'ID_USUARIOS' => 'required|integer|exists:USUARIOS,ID',
        ]);

        try {
            $inventario->update($validatedData);
            return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('inventarios.index')->with('error', 'Error al actualizar el inventario.');
        }
    }

    // Eliminar un inventario (lógica)
    public function destroy($id)
    {
        $inventario = mod_Inventario::find($id);

        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $inventario->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar inventario'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar inventarios eliminados
    public function deleted()
    {
        $inventariosEliminados = mod_Inventario::onlyTrashed()->get();
        return view('VistasCrud.VistasInventario.deleted', compact('inventariosEliminados'));
    }

    // Restaurar un inventario eliminado
    public function restore($id)
    {
        $inventario = mod_Inventario::withTrashed()->findOrFail($id);
        $inventario->restore();

        return redirect()->route('inventarios.deleted')->with('success', 'Inventario restaurado exitosamente.');
    }

    // Eliminación permanente
    public function forceDelete($id)
    {
        $inventario = mod_Inventario::withTrashed()->findOrFail($id);

        try {
            $inventario->forceDelete();
            return redirect()->route('inventarios.deleted')->with('success', 'Inventario eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('inventarios.deleted')->with('error', 'Error al eliminar permanentemente el inventario.');
        }
    }
}