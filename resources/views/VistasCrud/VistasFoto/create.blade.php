@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Agregar Foto</h1>

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

        <form action="{{ route('fotos.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="form-group mb-3">
                <label for="URL" class="form-label">URL de la Foto</label>
                <input type="text" name="URL" id="URL" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="DESCRIPCION" class="form-label">Descripción</label>
                <textarea name="DESCRIPCION" id="DESCRIPCION" class="form-control" rows="3" required></textarea>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-success me-2">
                    <i class="fas fa-plus"></i> Agregar Foto
                </button>
                <a href="{{ route('fotos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
