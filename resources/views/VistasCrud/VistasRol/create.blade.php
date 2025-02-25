@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nuevo Rol</h1>

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

    {{-- Formulario para crear un nuevo rol --}}
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="descripcion">Descripción del Rol:</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion') }}" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar Rol</button>
            <a href="{{url('/roles-index')}}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
