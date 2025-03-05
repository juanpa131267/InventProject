<?php

namespace App\Http\Controllers;
use App\Models\mod_MovimientosInventario;
use App\Models\mod_Articulo;
use App\Models\mod_Inventario;
use App\Models\mod_Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MovimientoInventarioController extends Controller
{
    // Listar movimientos con búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = mod_MovimientosInventario::query()
            ->whereNull('DELETED_AT')
            ->with(['ARTICULOS', 'INVENTARIOS', 'USUARIOS']);

        if ($search) {
            $query->whereHas('ARTICULOS', function ($q) use ($search) {
                $q->where('NOMBRE', 'LIKE', "%{$search}%");
            });
        }

        $movimientos = $query->paginate(10);
        return response()->json($movimientos);
    }
    


    public function create()
    {
        // Obtener todos los inventarios
        $inventarios = mod_Inventario::all();
    
        // Obtener los artículos de todos los inventarios
        $articulos = mod_Articulo::whereIn('ID_INVENTARIOS', $inventarios->pluck('ID'))->get();
    
        // Obtener los usuarios relacionados con los inventarios
        $usuarios = mod_Usuario::whereIn('ID', $inventarios->pluck('ID_USUARIOS'))->get();
    
        // Debugging: Muestra en el log los datos que se envían a la vista
        Log::info('Datos enviados a la vista create:', [
            'inventarios' => $inventarios,
            'articulos' => $articulos,
            'usuarios' => $usuarios
        ]);
    
        return view('VistasCrud.VistasMovimientoInventario.create', compact('inventarios', 'articulos', 'usuarios'));
    }
    
    

    // Almacenar un nuevo movimiento sin autenticación
    public function store(Request $request)
    {
        Log::info("Datos recibidos en el servidor:", $request->all()); // Ver los datos recibidos en el log
    
        // Validar los datos
        $validatedData = $request->validate([
            'ID_ARTICULOS'   => 'required|exists:ARTICULOS,ID',
            'ID_INVENTARIOS' => 'required|exists:INVENTARIOS,ID',
            'ID_USUARIOS'    => 'required|exists:USUARIOS,ID',
            'TIPO'           => 'required|in:entrada,salida',
            'CANTIDAD'       => 'required|integer|min:1',
            'FECHA'          => 'required|date',
            'HORA'           => 'required',
            'OBSERVACIONES'  => 'nullable|string|max:255',
        ]);
    
        Log::info("Datos validados:", $validatedData);
    
        try {
            $fechaCompleta = $validatedData['FECHA'] . ' ' . $validatedData['HORA'] . ':00';
    
            $movimiento = mod_MovimientosInventario::create([
                'ID_ARTICULOS'   => $validatedData['ID_ARTICULOS'],
                'ID_INVENTARIOS' => $validatedData['ID_INVENTARIOS'],
                'ID_USUARIOS'    => $validatedData['ID_USUARIOS'],
                'TIPO'           => $validatedData['TIPO'],
                'CANTIDAD'       => $validatedData['CANTIDAD'],
                'FECHA'          => $fechaCompleta,
                'OBSERVACIONES'  => $validatedData['OBSERVACIONES'],
            ]);
    
            Log::info("Movimiento creado:", $movimiento->toArray());
    
            return redirect()->route('movimientosinventarios.index')->with('success', 'Movimiento actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al registrar el movimiento:", ['error' => $e->getMessage()]);
            return redirect()->route('movimientosinventarios.index')->with('error', 'Error al actualizar el movimiento.');
        }
    }









    


    // Mostrar un movimiento específico
    public function show($id)
    {
        try {
            $movimiento = mod_MovimientosInventario::with(['ARTICULOS', 'INVENTARIOS', 'USUARIOS'])->findOrFail($id);
            return response()->json($movimiento, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Movimiento no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar vista de edición
    public function edit($id)
    {
        $movimiento = mod_MovimientosInventario::findOrFail($id);
        $articulos = mod_Articulo::all();
        $inventarios = mod_Inventario::all();
        $usuarios = mod_Usuario::all();

        return view('VistasCrud.VistasMovimientoInventario.edit', compact('movimiento', 'articulos', 'inventarios', 'usuarios'));
    }

    // Actualizar un movimiento existente
    public function update(Request $request, $id)
    {
        $movimiento = mod_MovimientosInventario::findOrFail($id);

        $validatedData = $request->validate([
            'ID_ARTICULOS' => 'required|integer|exists:ARTICULOS,ID',
            'ID_INVENTARIOS' => 'required|integer|exists:INVENTARIOS,ID',
            'TIPO' => 'required|string',
            'CANTIDAD' => 'required|integer|min:1',
            'FECHA' => 'required|date',
            'HORA' => 'required', 
            'ID_USUARIOS' => 'required|integer|exists:USUARIOS,ID',
            'OBSERVACIONES' => 'nullable|string'
        ]);

        try {
            $fechaCompleta = $validatedData['FECHA'] . ' ' . $validatedData['HORA'] . ':00';

            $movimiento->update([
                'ID_ARTICULOS' => $validatedData['ID_ARTICULOS'],
                'ID_INVENTARIOS' => $validatedData['ID_INVENTARIOS'],
                'TIPO' => $validatedData['TIPO'],
                'CANTIDAD' => $validatedData['CANTIDAD'],
                'FECHA' => $fechaCompleta,
                'ID_USUARIOS' => $validatedData['ID_USUARIOS'],
                'OBSERVACIONES' => $validatedData['OBSERVACIONES'],
            ]);

            return redirect()->route('movimientosinventarios.index')->with('success', 'Movimiento actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('movimientosinventarios.index')->with('error', 'Error al actualizar el movimiento.');
        }
    }






    // Eliminar lógicamente un movimiento
    public function destroy($id)
    {
        $movimiento = mod_MovimientosInventario::find($id);

        if (!$movimiento) {
            return response()->json(['error' => 'Movimiento no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $movimiento->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el movimiento'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar los movimientos eliminados
    public function deleted()
    {
        $movimientosEliminados = mod_MovimientosInventario::onlyTrashed()->get();
        return view('VistasCrud.VistasMovimientoInventario.deleted', compact('movimientosEliminados'));
    }

    // Restaurar un movimiento eliminado
    public function restore($id)
    {
        $movimiento = mod_MovimientosInventario::withTrashed()->findOrFail($id);

        try {
            $movimiento->restore();
            return redirect()->route('movimientosinventarios.deleted')->with('success', 'Movimiento restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('movimientosinventarios.deleted')->with('error', 'Error al restaurar el movimiento.');
        }
    }
}
