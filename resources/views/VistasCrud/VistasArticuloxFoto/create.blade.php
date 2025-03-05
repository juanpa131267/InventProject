@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Asignar Imagen a Artículo</h1>

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

        <form action="{{ route('articuloxfotos.store') }}" method="POST" class="mt-3">
            @csrf

            {{-- Selección del Artículo --}}
            <div class="form-group mb-3">
                <label for="ID_ARTICULOS" class="font-weight-bold">Artículo</label>
                <select name="ID_ARTICULOS" id="ID_ARTICULOS" class="form-control selectpicker" data-live-search="true" required>
                    <option value="">Seleccione un artículo</option>
                    @foreach($articulosSinFoto as $articulo)
                        <option value="{{ $articulo->ID }}">
                            {{ $articulo->NOMBRE }} - {{ $articulo->CODIGO }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Selección de la Foto --}}
            <div class="form-group mb-3">
                <label for="ID_FOTOS" class="font-weight-bold">Foto</label>
                <select name="ID_FOTOS" id="ID_FOTOS" class="form-control" required>
                    <option value="">Seleccione una foto</option>
                    @foreach($fotos as $foto)
                        <option value="{{ $foto->ID }}">Descripción Foto: {{ $foto->DESCRIPCION }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Asignar Imagen
                </button>
                <a href="{{ route('articuloxfotos.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
