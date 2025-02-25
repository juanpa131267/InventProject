@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nuevo Permiso</h1>

    {{-- Mostrar mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario para crear un nuevo permiso --}}
    <form action="{{ route('permisos.store') }}" method="POST">
        @csrf {{-- Token de seguridad para el formulario --}}

        <div class="form-group mb-3">
            <label for="nombre">Nombre del Permiso:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Introduce el nombre del permiso" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Introduce una descripción opcional">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Crear Permiso</button>
            <a href="{{ url('/permisos-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
