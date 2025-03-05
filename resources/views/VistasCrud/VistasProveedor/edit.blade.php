@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Proveedor</h2>

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

        <form id="proveedorForm" action="{{ route('proveedores.update', $proveedor->ID) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre del Proveedor</label>
                <input type="text" class="form-control" id="nombre" name="NOMBRE" value="{{ old('NOMBRE', $proveedor->NOMBRE) }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="TELEFONO" value="{{ old('TELEFONO', $proveedor->TELEFONO) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="CORREO" value="{{ old('CORREO', $proveedor->CORREO) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label fw-bold">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="DIRECCION" value="{{ old('DIRECCION', $proveedor->DIRECCION) }}" required>
            </div>
            
            <div class="mt-4 d-flex justify-content-center gap-2">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="{{ url('/proveedores-index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
