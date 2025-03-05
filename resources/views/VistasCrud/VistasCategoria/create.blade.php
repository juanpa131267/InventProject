@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Categoría</h1>

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

        <form action="{{ route('categorias.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="form-group mb-3">
                <label for="NOMBRE" class="form-label">Nombre de la Categoría</label>
                <input type="text" name="NOMBRE" id="NOMBRE" class="form-control" required>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-success me-2">
                    <i class="fas fa-plus"></i> Crear
                </button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
