@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignaciones Eliminadas</h1>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Persona</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($asignacionesEliminadas->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No hay asignaciones eliminadas.</td>
                </tr>
            @else
                @foreach($asignacionesEliminadas as $asignacion)
                <tr>
                    <td>{{ $asignacion->id }}</td>
                    <td>{{ $asignacion->persona->nombres }} {{ $asignacion->persona->apellido }}</td>
                    <td>{{ $asignacion->rol->nombre }}</td>
                    <td>
                        <form action="{{ route('rolxpersona.restore', $asignacion->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Restaurar</button>
                        </form>
                        
                        <form action="{{ route('rolxpersona.forceDelete', $asignacion->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta asignación de forma permanente?')">Eliminar Completamente</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Botón de cancelar que redirige al index de usuarios --}}
    <div class="mt-3">
        <a href="{{ url('/rolxpersona-index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
