@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Permisos Eliminados</h1>

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

    {{-- Tabla de permisos eliminados --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($permisosEliminados->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No hay permisos eliminados.</td>
                </tr>
            @else
                @foreach($permisosEliminados as $permiso)
                    <tr>
                        <td>{{ $permiso->id }}</td>
                        <td>{{ $permiso->nombre }}</td>
                        <td>{{ $permiso->descripcion }}</td>
                        <td>{{ $permiso->deleted_at }}</td>
                        <td>
                            {{-- Botón para restaurar --}}
                            <form action="{{ route('permisos.restore', $permiso->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                            </form>

                            {{-- Botón para eliminar completamente --}}
                            <form action="{{ route('permisos.forceDelete', $permiso->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este permiso permanentemente?')">Eliminar Completamente</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Botón de cancelar que redirige al index de permisos --}}
    <div class="mt-4">
        <a href="{{ url('/permisos-index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
