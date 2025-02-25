@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Roles Eliminados</h1>

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

    {{-- Tabla de roles eliminados --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($rolesEliminados->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">No hay roles eliminados.</td>
                </tr>
            @else
                @foreach($rolesEliminados as $rol)
                    <tr>
                        <td>{{ $rol->id }}</td>
                        <td>{{ $rol->descripcion }}</td>
                        <td>{{ $rol->deleted_at }}</td>
                        <td>
                            {{-- Botón para restaurar --}}
                            <form action="{{ route('roles.restore', $rol->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                            </form>

                            {{-- Botón para eliminar completamente --}}
                            <form action="{{ route('roles.forceDelete', $rol->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este rol permanentemente?')">Eliminar Completamente</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Botón de cancelar que redirige al index de roles --}}
    <div class="mt-4">
        <a href="{{url('/roles-index')}}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
@endsection
