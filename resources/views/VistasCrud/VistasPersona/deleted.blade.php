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
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($personasEliminadas->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">No hay usuarios eliminados.</td>
                </tr>
            @else
                @foreach($personasEliminadas as $persona)
                    <tr>
                        <td>{{ $persona->cedula }}</td>
                        <td>{{ $persona->nombres }}</td>
                        <td>{{ $persona->apellido }}</td>
                        <td>{{ $persona->telefono }}</td>
                        <td>{{ $persona->correo }}</td>
                        <td>{{ $persona->deleted_at }}</td>
                        <td>
                            {{-- Botón para restaurar --}}
                            <form action="{{ route('personas.restore', $persona->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                            </form>

                            {{-- Botón para eliminar completamente --}}
                            <form action="{{ route('personas.forceDelete', $persona->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta persona de forma permanente?')">Eliminar Completamente</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Botón de cancelar que redirige al index de personas --}}
    <div class="mt-3">
        <a href="{{ url('/personas-index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
