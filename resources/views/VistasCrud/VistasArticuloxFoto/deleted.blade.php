@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Relaciones Artículo - Foto Eliminadas</h1>

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

        {{-- Tabla de registros eliminados --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Artículo</th>
                        <th>Foto</th>
                        <th>Fecha de Eliminación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if($articuloxfotosEliminados->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay registros eliminados.</td>
                        </tr>
                    @else
                        @foreach($articuloxfotosEliminados as $registro)
                            <tr>
                                <td>{{ $registro->ID }}</td>
                                <td>
                                    @if($registro->ARTICULOS)
                                        {{ $registro->ARTICULOS->NOMBRE }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($registro->FOTOS)
                                        <img src="{{ asset($registro->FOTOS->URL) }}" alt="Foto" class="img-thumbnail" width="100">
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $registro->DELETED_AT }}</td>
                                <td>
                                    {{-- Botón para restaurar --}}
                                    <form action="{{ route('articuloxfotos.restore', $registro->ID) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
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

        {{-- Botón de cancelar que redirige al index de ArticuloxFoto --}}
        <div class="mt-3 text-center">
            <a href="{{ route('articuloxfotos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
