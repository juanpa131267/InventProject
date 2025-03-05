@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Relación de Artículo con Proveedor</h2>
        
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

        <form id="articuloxproveedorForm" action="{{ route('articuloxproveedores.update', $articuloxproveedor->ID) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Información del Artículo (No editable) -->
            <div class="mb-3">
                <label for="articulo" class="form-label fw-bold">Artículo Asociado</label>
                <input type="text" class="form-control" id="articulo" 
                       value="{{ $articuloxproveedor->ARTICULOS->NOMBRE }}" 
                       disabled>
                <input type="hidden" name="ID_ARTICULOS" value="{{ $articuloxproveedor->ID_ARTICULOS }}">
            </div>

            <!-- Selección de Proveedor -->
            <div class="mb-3">
                <label for="ID_PROVEEDORES" class="form-label fw-bold">Proveedor Asignado</label>
                <select class="form-select" id="ID_PROVEEDORES" name="ID_PROVEEDORES" required>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->ID }}" 
                            {{ $proveedor->ID == $articuloxproveedor->ID_PROVEEDORES ? 'selected' : '' }}>
                            {{ $proveedor->NOMBRE }} - {{ $proveedor->CORREO }} - {{ $proveedor->TELEFONO }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="{{ url('/articuloxproveedores-index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
