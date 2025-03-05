@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Relación de Artículo con Foto</h2>
        
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

        <form id="articuloxfotoForm" action="{{ route('articuloxfotos.update', $articuloxfoto->ID) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Información del Artículo (No editable) -->
            <div class="mb-3">
                <label for="articulo" class="form-label fw-bold">Artículo Asociado</label>
                <input type="text" class="form-control" id="articulo" 
                       value="{{ $articuloxfoto->ARTICULOS->NOMBRE }}" 
                       disabled>
                <input type="hidden" name="ID_ARTICULOS" value="{{ $articuloxfoto->ID_ARTICULOS }}">
            </div>

            <!-- Selección de Foto -->
            <div class="mb-3">
                <label for="ID_FOTOS" class="form-label fw-bold">Foto Asignada</label>
                <select class="form-select" id="ID_FOTOS" name="ID_FOTOS" required>
                    @foreach($fotos as $foto)
                        <option value="{{ $foto->ID }}" 
                            {{ $foto->ID == $articuloxfoto->ID_FOTOS ? 'selected' : '' }}>
                            {{ $foto->URL }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="{{ url('/articuloxfotos-index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
