@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Persona</h1>

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

        <form action="{{ route('personas.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="form-group mb-3">
                <label for="CEDULA" class="form-label">Cédula</label>
                <input type="text" name="CEDULA" id="CEDULA" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="NOMBRES" class="form-label">Nombres</label>
                <input type="text" name="NOMBRES" id="NOMBRES" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="APELLIDO" class="form-label">Apellido</label>
                <input type="text" name="APELLIDO" id="APELLIDO" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="TELEFONO" class="form-label">Teléfono</label>
                <input type="text" name="TELEFONO" id="TELEFONO" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="CORREO" class="form-label">Correo</label>
                <input type="email" name="CORREO" id="CORREO" class="form-control" required>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-success me-2">
                    <i class="fas fa-user-plus"></i> Crear
                </button>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
