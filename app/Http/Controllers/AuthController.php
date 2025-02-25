<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mod_Usuario;
use App\Models\mod_Persona;
use App\Models\mod_Departamentos;
use App\Models\mod_Municipios;
use App\Models\mod_Rol;
use App\Models\mod_RolxPersona;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $usuario = mod_Usuario::where('login', $request->input('login'))->first();

        if ($usuario && Hash::check($request->input('password'), $usuario->password)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            return redirect()->intended('/')->with('status', 'Acceso a usuario');
        }

        return back()->withErrors(['login' => 'El usuario o la contraseña son incorrectos']);
    }

    public function showRegistrationForm()
    {
        $departamentos = mod_Departamentos::all(); // Asegúrate de que este modelo exista y esté correctamente configurado
        return view('auth.register', compact('departamentos'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'login' => 'required|unique:usuarios',
            'password' => 'required|confirmed',
            'nombres' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255|unique:personas,correo',
            'cedula' => 'required|string|max:255|unique:personas,cedula',
            'municipio_id' => 'required|exists:municipios,municipio_id',
        ]);
    
        // Crear la nueva persona
        $persona = mod_Persona::create($request->only(['nombres', 'apellido', 'correo', 'telefono', 'cedula', 'municipio_id']));
    
        // Crear el nuevo usuario
        $usuario = mod_Usuario::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'persona_id' => $persona->id,
        ]);
    
        // Asignar un rol por defecto al usuario creado en la tabla rolxpersona
        $rol = mod_Rol::where('descripcion', 'Usuario')->first(); // Asegúrate de que el campo sea correcto
        if ($rol) {
            mod_RolxPersona::create([
                'rol_id' => $rol->id,
                'persona_id' => $persona->id,
            ]);
        }
    
        Auth::login($usuario);
        return redirect('/')->with('status', 'Registro exitoso. Bienvenido!');
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
