<?php

namespace App\Http\Controllers;

use App\Models\mod_Persona;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonaController extends Controller
{
    // Mostrar la lista de personas
    public function index(Request $request)
    {
        $search = $request->query('q'); // Capturar el parámetro de búsqueda
    
        try {
            $query = mod_Persona::query(); // Iniciar la consulta
    
            if (!empty($search)) { // Asegurar que la búsqueda no esté vacía
                $query->where('CEDULA', 'like', "%{$search}%");
            }
    
            $personas = $query->paginate(15); // Aplicar paginación correctamente
    
            return response()->json($personas, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener personas'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    // Mostrar la vista de creación 
    public function create()
    {
        return view('VistasCrud.VistasPersona.create');
    }
    

    // Almacenar una nueva persona
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'CEDULA' => 'required|string|max:20|unique:PERSONAS,CEDULA',
            'NOMBRES' => 'required|string|max:255',
            'APELLIDO' => 'required|string|max:255',
            'TELEFONO' => 'required|string|max:15',
            'CORREO' => 'required|email|max:255|unique:PERSONAS,CORREO',
        ]);
    
        try {
            mod_Persona::create($validatedData);
            return redirect(url('/personas-index'))->with('success', 'Persona creada exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/personas-index'))->with('error', 'No se pudo crear la persona. Intente nuevamente.');
        }
    }

    // Mostrar una persona específica
    public function show($id)
    {
        try {
            $persona = mod_Persona::findOrFail($id);
            return response()->json($persona, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Persona no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición de una persona

    public function edit($id)
    {
        $persona = mod_Persona::findOrFail($id);
        return view('VistasCrud.VistasPersona.edit', compact('persona'));
    }
    
    // Validar los datos de la persona 
    private function validatePersona(Request $request, $id = null)
    {
        $uniqueCedulaRule = $id ? "unique:PERSONAS,CEDULA,$id,ID" : 'unique:PERSONAS,CEDULA';
        
        $request->validate([
            'CEDULA' => "required|string|max:255|$uniqueCedulaRule",
            'NOMBRES' => 'required|string|max:255',
            'APELLIDO' => 'required|string|max:255',
            'TELEFONO' => 'required|string|max:15',
            'CORREO' => 'required|email|max:255',
        ]);
    }

    // Actualizar una persona existente
    public function update(Request $request, $id)
    {
        $persona = mod_Persona::findOrFail($id);
        
    
        $this->validatePersona($request, $id);
    
        try {
            $persona->update([
                'NOMBRES' => $request->NOMBRES,
                'APELLIDO' => $request->APELLIDO,
                'TELEFONO' => $request->TELEFONO,
                'CORREO' => $request->CORREO,
            ]);
    
            return redirect(url('/personas-index'))->with('success', 'Persona actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect(url('/personas-index'))->with('error', 'Error al actualizar persona');
        }
    }
    
    // Eliminar una persona (lógica)
    public function destroy($id)
    {
        $persona = mod_Persona::find($id);
    
        if (!$persona) {
            return response()->json(['error' => 'Persona no encontrada'], Response::HTTP_NOT_FOUND);
        }
    
        try {
            $persona->delete(); // Eliminar lógicamente
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar persona'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar personas eliminadas
    public function deleted()
    {
        $personasEliminadas = mod_Persona::onlyTrashed()->get();
        return view('VistasCrud.VistasPersona.deleted', compact('personasEliminadas'));
    }

    // Restaurar una persona eliminada
    public function restore($id)
    {
        $persona = mod_Persona::withTrashed()->findOrFail($id);
        $persona->restore();

        return redirect()->route('personas.deleted')->with('success', 'Persona restaurada exitosamente.');

    }


}