@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Rol</h1>

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

    {{-- Formulario para editar el rol existente --}}
    <form action="{{ route('roles.update', $rol->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="descripcion">Descripción del Rol:</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion', $rol->descripcion) }}" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Rol</button>
            <a href="{{url('/roles-index')}}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
