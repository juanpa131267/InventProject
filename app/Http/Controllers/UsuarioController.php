<?php

namespace App\Http\Controllers;

use App\Models\mod_Persona;
use App\Models\mod_Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class UsuarioController extends Controller
{
    // Listar usuarios, con opción de búsqueda por LOGIN
    public function index(Request $request)
    {
        $search = $request->query('q');
    
        try {
            $query = mod_Usuario::with('PERSONAS'); // Cargar relación
        
            if (!empty($search)) {
                $query->where('LOGIN', 'like', "%{$search}%")
                      ->orWhereHas('PERSONAS', function ($q) use ($search) {
                          $q->where('NOMBRES', 'like', "%{$search}%")
                            ->orWhere('APELLIDO', 'like', "%{$search}%");
                      });
            }
    
            $usuarios = $query->paginate(15);
    
            // Registrar en logs (en lugar de detener con dd())
            Log::info('Usuarios obtenidos:', ['usuarios' => $usuarios]);
    
            return response()->json($usuarios, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener usuarios'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    
    // Mostrar la vista de creación de usuario
    public function create()
    {
        $personas = mod_Persona::whereNotIn('ID', function ($query) {
            $query->select('ID_PERSONAS')->from('USUARIOS');
        })->get();
    
        return view('VistasCrud.VistasUsuario.create', compact('personas'));
    }
    

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_PERSONAS' => 'required|integer|exists:PERSONAS,ID',
            'LOGIN'       => 'required|string|max:255|unique:USUARIOS,LOGIN',
            'PASSWORD'    => 'required|string|min:6',
        ]);

        // Aplicar hash a la contraseña antes de almacenar
        $validatedData['PASSWORD'] = bcrypt($validatedData['PASSWORD']);

        try {
            mod_Usuario::create($validatedData);
            return redirect(url('/usuarios-index'))->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/usuarios-index'))->with('error', 'No se pudo crear el usuario. Intente nuevamente.');
        }
    }

    // Mostrar un usuario específico (respuesta JSON)
    public function show($id)
    {
        try {
            $usuario = mod_Usuario::findOrFail($id);
            return response()->json($usuario, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }

    // Mostrar la vista de edición para un usuario
    public function edit($id)
    {
        $usuario = mod_Usuario::findOrFail($id);
        return view('VistasCrud.VistasUsuario.edit', compact('usuario'));
    }

    // Método privado para validar datos del usuario
    private function validateUsuario(Request $request, $id = null)
    {
        $uniqueLoginRule = $id ? "unique:USUARIOS,LOGIN,$id,ID" : 'unique:USUARIOS,LOGIN';

        $rules = [
            'ID_PERSONAS' => 'required|integer|exists:PERSONAS,ID',
            'LOGIN'       => "required|string|max:255|$uniqueLoginRule",
            // En actualización, la contraseña es opcional; en creación, es obligatoria
            'PASSWORD'    => $id ? 'nullable|string|min:6' : 'required|string|min:6',
        ];

        $request->validate($rules);
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $usuario = mod_Usuario::findOrFail($id);
        $this->validateUsuario($request, $id);

        try {
            $data = [
                'ID_PERSONAS' => $request->ID_PERSONAS,
                'LOGIN'       => $request->LOGIN,
            ];
            // Si se envía nueva contraseña, se actualiza (con hash)
            if ($request->filled('PASSWORD')) {
                $data['PASSWORD'] = bcrypt($request->PASSWORD);
            }
            $usuario->update($data);
            return redirect(url('/usuarios-index'))->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect(url('/usuarios-index'))->with('error', 'Error al actualizar el usuario.');
        }
    }

    // Eliminar lógicamente un usuario (soft delete)
    public function destroy($id)
    {
        $usuario = mod_Usuario::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $usuario->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mostrar los usuarios eliminados (soft deleted)
    public function deleted()
    {
        $usuariosEliminados = mod_Usuario::onlyTrashed()->get();
        return view('VistasCrud.VistasUsuario.deleted', compact('usuariosEliminados'));
    }

    // Restaurar un usuario eliminado
    public function restore($id)
    {
        $usuario = mod_Usuario::withTrashed()->findOrFail($id);

        try {
            $usuario->restore();
            return redirect()->route('usuarios.deleted')->with('success', 'Usuario restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.deleted')->with('error', 'Error al restaurar el usuario.');
        }
    }
}
