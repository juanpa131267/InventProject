@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Permiso</h1>

    {{-- Mostrar errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario para editar el permiso existente --}}
    <form action="{{ route('permisos.update', $permiso->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nombre">Nombre del Permiso:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $permiso->nombre) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Introduce una descripción opcional">{{ old('descripcion', $permiso->descripcion) }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Permiso</button>
            <a href="{{ url('/permisos-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
