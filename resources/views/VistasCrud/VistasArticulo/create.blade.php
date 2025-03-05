@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Artículo</h1>

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

        <form action="{{ route('articulos.store') }}" method="POST">
            @csrf

            {{-- Selección de Inventario --}}
            <div class="form-group mb-3">
                <label for="ID_INVENTARIOS" class="font-weight-bold">Inventario</label>
                <select name="ID_INVENTARIOS" id="ID_INVENTARIOS" class="form-control" required>
                    <option value="">Seleccione un inventario</option>
                    @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->ID }}">
                            {{ $inventario->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Campo para Nombre --}}
            <div class="form-group mb-3">
                <label for="NOMBRE" class="font-weight-bold">Nombre del Artículo</label>
                <input type="text" name="NOMBRE" id="NOMBRE" class="form-control" required>
            </div>

            {{-- Campo para Marca --}}
            <div class="form-group mb-3">
                <label for="MARCA" class="font-weight-bold">Marca</label>
                <input type="text" name="MARCA" id="MARCA" class="form-control" required>
            </div>

            {{-- Campo para Descripción --}}
            <div class="form-group mb-3">
                <label for="DESCRIPCION" class="font-weight-bold">Descripción</label>
                <textarea name="DESCRIPCION" id="DESCRIPCION" class="form-control" rows="3" required></textarea>
            </div>

            {{-- Campo para Fecha de Caducidad --}}
            <div class="form-group mb-3">
                <label for="FECHACADUCIDAD" class="font-weight-bold">Fecha de Caducidad</label>
                <input type="date" name="FECHACADUCIDAD" id="FECHACADUCIDAD" class="form-control">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="sin_caducidad">
                    <label class="form-check-label" for="sin_caducidad">Este artículo no tiene fecha de caducidad</label>
                </div>
            </div>

            {{-- Campo para Unidad --}}
            <div class="form-group mb-3">
                <label for="UNIDAD" class="font-weight-bold">Unidad de Medida</label>
                <input type="text" name="UNIDAD" id="UNIDAD" class="form-control" required>
            </div>

            {{-- Campo oculto para Cantidad --}}
            <input type="hidden" name="CANTIDAD" id="CANTIDAD" value="0">

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Crear
                </button>
                <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('sin_caducidad').addEventListener('change', function() {
        let fechaInput = document.getElementById('FECHACADUCIDAD');
        if (this.checked) {
            fechaInput.value = ''; 
            fechaInput.disabled = true;
        } else {
            fechaInput.disabled = false;
        }
    });
</script>
@endsection
