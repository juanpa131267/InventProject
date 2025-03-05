@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Usuarios Eliminados</h1>

        {{-- Notificaciones de éxito o error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Login</th>
                        <th>Persona</th>
                        <th>Fecha de Eliminación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if($usuariosEliminados->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay usuarios eliminados.</td>
                        </tr>
                    @else
                        @foreach($usuariosEliminados as $usuario)
                            <tr>
                                <td>{{ $usuario->ID }}</td>
                                <td>{{ $usuario->LOGIN }}</td>
                                <td>
                                    @if($usuario->PERSONAS)
                                        {{ $usuario->PERSONAS->NOMBRES }} {{ $usuario->PERSONAS->APELLIDO }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $usuario->DELETED_AT }}</td>
                                <td>
                                    {{-- Botón para restaurar --}}
                                    <form action="{{ route('usuarios.restore', $usuario->ID) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Botón de cancelar que redirige al index de usuarios --}}
        <div class="text-center mt-4">
            <a href="{{ url('/usuarios-index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
