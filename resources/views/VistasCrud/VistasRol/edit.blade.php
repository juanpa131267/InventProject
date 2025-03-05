@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Rol</h2>
        
        {{-- Mostrar mensajes de éxito o error --}}
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

        {{-- Formulario de edición --}}
        <form id="rolForm" action="{{ route('roles.update', $rol->ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bold">Descripción del Rol</label>
                <input type="text" class="form-control border-primary" id="descripcion" name="DESCRIPCION" 
                       value="{{ old('DESCRIPCION', $rol->DESCRIPCION) }}" required>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-success me-2">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
