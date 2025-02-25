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
                <th>Rol</th>
                <th>Permiso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($asignacionesEliminadas->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">No hay asignaciones eliminadas.</td>
                </tr>
            @else
                @foreach($asignacionesEliminadas as $asignacion)
                <tr>
                    <td>{{ $asignacion->id }}</td>
                    <td>{{ $asignacion->rol->descripcion }}</td>
                    <td>{{ $asignacion->permiso->nombre }}</td>
                    <td>
                        <form action="{{ route('rolxpermiso.restore', $asignacion->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Restaurar</button>
                        </form>
                        
                        <form action="{{ route('rolxpermiso.forceDelete', $asignacion->id) }}" method="POST" style="display:inline-block;">
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

    {{-- Botón de cancelar que redirige al index de permisos --}}
    <div class="mt-3">
        <a href="{{ url('/rolxpermiso-index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
