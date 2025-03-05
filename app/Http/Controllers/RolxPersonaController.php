<?php

namespace App\Http\Controllers;

use App\Models\mod_RolxPersona;
use App\Models\mod_Persona;
use App\Models\mod_Rol;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolxPersonaController extends Controller
{
    // Listar relaciones usuario-rol con opción de búsqueda
    public function index(Request $request)
    {
        $search = $request->query('q');
    
        $query = mod_RolxPersona::query()
            ->whereNull('DELETED_AT') // Excluir relaciones eliminadas
            ->whereHas('PERSONAS', function ($q) {
                $q->whereNull('DELETED_AT'); // Excluir personas eliminadas
            })
            ->whereHas('ROLES', function ($q) {
                $q->whereNull('DELETED_AT'); // Excluir roles eliminados
            })
            ->with([
                'PERSONAS' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'NOMBRES', 'APELLIDO');
                },
                'ROLES' => function ($q) {
                    $q->whereNull('DELETED_AT')->select('ID', 'DESCRIPCION');
                }
            ]);
    
        if ($search) {
            $query->whereHas('PERSONAS', function ($q) use ($search) {
                $q->where('NOMBRES', 'LIKE', "%{$search}%")
                  ->orWhere('APELLIDO', 'LIKE', "%{$search}%");
            });
        }
    
        $rolxpersonas = $query->paginate(10);
    
        return response()->json($rolxpersonas);
    }
    
    
    

    // Mostrar la vista de creación
    public function create()
    {
        // Obtener solo las personas que NO tienen un rol asignado
        $personasSinRol = mod_Persona::whereDoesntHave('ROLXPERSONA')->get();
        $roles = mod_Rol::all();
    
        return view('VistasCrud.VistasRolxPersona.create', compact('personasSinRol', 'roles'));
    }
    

    // Almacenar una nueva relación usuario-rol
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_PERSONAS' => 'required|integer|exists:PERSONAS,ID',
            'ID_ROLES'    => 'required|integer|exists:ROLES,ID',
        ]);

        try {
            mod_RolxPersona::create($validatedData);
            return redirect(url('/rolxpersonas-index'))->with('success', 'Rol asignado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/rolxpersonas-index'))->with('error', 'No se pudo asignar el rol. Intente nuevamente.');
        }
    }

    // Mostrar una relación usuario-rol específica
    public function show($id)
    {
        try {
            $rolxpersona = mod_RolxPersona::with(['PERSONAS', 'ROLES'])->findOrFail($id);
            return response()->json($rolxpersona, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición
    public function edit($id)
    {
        $rolxpersona = mod_RolxPersona::findOrFail($id);
        $personas = mod_Persona::all();
        $roles = mod_Rol::all();
    
        return view('VistasCrud.VistasRolxPersona.edit', compact('rolxpersona', 'personas', 'roles'));
    }

    // Actualizar una relación usuario-rol existente
    public function update(Request $request, $id)
    {
        $rolxpersona = mod_RolxPersona::findOrFail($id);

        $validatedData = $request->validate([
            'ID_PERSONAS' => 'required|integer|exists:PERSONAS,ID',
            'ID_ROLES'    => 'required|integer|exists:ROLES,ID',
        ]);

        try {
            $rolxpersona->update($validatedData);
            return redirect(url('/rolxpersonas-index'))->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/rolxpersonas-index'))->with('error', 'Error al actualizar el rol.');
        }
    }

    // Eliminar lógicamente una relación usuario-rol (Soft Delete)
    public function destroy($id)
    {
        $rolxpersona = mod_RolxPersona::find($id);

        if (!$rolxpersona) {
            return response()->json(['error' => 'Relación no encontrada'], Response::HTTP_NOT_FOUND);
        }

        try {
            $rolxpersona->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la relación'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar las relaciones eliminadas
    public function deleted()
    {
        $rolxpersonaEliminados = mod_RolxPersona::onlyTrashed()->get();
        return view('VistasCrud.VistasRolxPersona.deleted', compact('rolxpersonaEliminados'));
    }

    // Restaurar una relación eliminada
    public function restore($id)
    {
        $rolxpersona = mod_RolxPersona::withTrashed()->findOrFail($id);

        try {
            $rolxpersona->restore();
            return redirect()->route('rolxpersonas.deleted')->with('success', 'Relación restaurada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('rolxpersonas.deleted')->with('error', 'Error al restaurar la relación.');
        }
    }
}