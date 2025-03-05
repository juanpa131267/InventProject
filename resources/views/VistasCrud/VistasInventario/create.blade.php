@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Inventario</h1>

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

        <form action="{{ route('inventarios.store') }}" method="POST">
            @csrf

            {{-- Campo para el Nombre del Inventario --}}
            <div class="form-group mb-3">
                <label for="NOMBRE" class="font-weight-bold">Nombre del Inventario</label>
                <input type="text" name="NOMBRE" id="NOMBRE" class="form-control" required>
            </div>

            {{-- Selección del Usuario Responsable --}}
            <div class="form-group mb-3">
                <label for="ID_USUARIOS" class="font-weight-bold">Usuario Responsable</label>
                <select name="ID_USUARIOS" id="ID_USUARIOS" class="form-control" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->ID }}">{{ $usuario->LOGIN }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Selección de la Foto --}}
            <div class="form-group mb-3">
                <label for="ID_FOTOS" class="font-weight-bold">Foto del Inventario (Opcional)</label>
                <select name="ID_FOTOS" id="ID_FOTOS" class="form-control">
                    <option value="">Seleccione una foto</option>
                    @foreach($fotos as $foto)
                        <option value="{{ $foto->ID }}">Imagen "{{ $foto->DESCRIPCION }}"</option>
                    @endforeach
                </select>
            </div>


            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('inventarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
