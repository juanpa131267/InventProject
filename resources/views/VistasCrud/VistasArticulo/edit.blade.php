@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Artículo</h2>
        
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

        <form id="articuloForm" action="{{ route('articulos.update', $articulo->ID) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="ID_INVENTARIOS" class="form-label">Inventario</label>
                <select name="ID_INVENTARIOS" id="ID_INVENTARIOS" class="form-control" required>
                    <option value="">Seleccione un inventario</option>
                    @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->ID }}" 
                            {{ $articulo->ID_INVENTARIOS == $inventario->ID ? 'selected' : '' }}>
                            {{ $inventario->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="NOMBRE" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="NOMBRE" name="NOMBRE" 
                       value="{{ old('NOMBRE', $articulo->NOMBRE) }}" required>
            </div>

            <div class="mb-3">
                <label for="MARCA" class="form-label">Marca</label>
                <input type="text" class="form-control" id="MARCA" name="MARCA" 
                       value="{{ old('MARCA', $articulo->MARCA) }}">
            </div>

            <div class="mb-3">
                <label for="DESCRIPCION" class="form-label">Descripción</label>
                <textarea class="form-control" id="DESCRIPCION" name="DESCRIPCION" rows="3">{{ old('DESCRIPCION', $articulo->DESCRIPCION) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="FECHACADUCIDAD" class="form-label">Fecha de Caducidad</label>
                <input type="date" class="form-control" id="FECHACADUCIDAD" name="FECHACADUCIDAD" 
                       value="{{ old('FECHACADUCIDAD', $articulo->FECHACADUCIDAD) }}">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="sin_caducidad">
                    <label class="form-check-label" for="sin_caducidad">Este artículo no tiene fecha de caducidad</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="UNIDAD" class="form-label">Unidad</label>
                <input type="text" class="form-control" id="UNIDAD" name="UNIDAD" 
                       value="{{ old('UNIDAD', $articulo->UNIDAD) }}" required>
            </div>

            <div class="mb-3">
                <label for="CANTIDAD" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="CANTIDAD" name="CANTIDAD" 
                    value="{{ old('CANTIDAD', $articulo->CANTIDAD) }}" required min="0" readonly>
            </div>


            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ url('/articulos-index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('articuloForm').addEventListener('submit', function() {
        document.getElementById('CANTIDAD').disabled = false;
    });
</script>

@endsection
