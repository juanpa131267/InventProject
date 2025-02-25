@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuarios Eliminados</h1>

    {{-- Notificaciones de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabla de usuarios eliminados --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Persona ID</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($usuariosEliminados->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No hay usuarios eliminados.</td>
                </tr>
            @else
                @foreach($usuariosEliminados as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->login }}</td>
                        <td>{{ $usuario->persona_id }}</td>
                        <td>{{ $usuario->deleted_at }}</td>
                        <td>
                            {{-- Botón para restaurar --}}
                            <form action="{{ route('usuarios.restore', $usuario->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                            </form>

                            {{-- Botón para eliminar completamente --}}
                            <form action="{{ route('usuarios.forceDelete', $usuario->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este usuario de forma permanente?')">Eliminar Completamente</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Botón de cancelar que redirige al index de usuarios --}}
    <div class="mt-3">
        <a href="{{ url('/usuarios-index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
