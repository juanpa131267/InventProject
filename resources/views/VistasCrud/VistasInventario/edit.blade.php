@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Inventario</h2>
        
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

        <form id="inventarioForm" action="{{ route('inventarios.update', $inventario->ID) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Inventario</label>
                <input type="text" class="form-control" id="nombre" name="NOMBRE" 
                       value="{{ old('NOMBRE', $inventario->NOMBRE) }}" required>
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario Responsable</label>
                <select class="form-control" id="usuario" name="ID_USUARIOS" required>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->ID }}" {{ $inventario->ID_USUARIOS == $usuario->ID ? 'selected' : '' }}>
                            {{ $usuario->LOGIN }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto Asociada</label>
                <select class="form-control" id="foto" name="ID_FOTOS">
                    <option value="">Sin foto</option>
                    @foreach($fotos as $foto)
                        <option value="{{ $foto->ID }}" {{ $inventario->ID_FOTOS == $foto->ID ? 'selected' : '' }}>
                            {{ $foto->URL }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('inventarios.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection