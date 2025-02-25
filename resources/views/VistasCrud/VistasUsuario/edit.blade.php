@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuario</h1>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="persona_id">Persona</label>
            <!-- Mostrar la información de la persona en un input de solo lectura -->
            <input type="text" class="form-control" value="{{ $usuario->persona->nombres }} {{ $usuario->persona->apellido }} (ID: {{ $usuario->persona_id }})" readonly />
            <!-- Guardar el ID de la persona en un input oculto -->
            <input type="hidden" name="persona_id" value="{{ $usuario->persona_id }}" />
        </div>

        <div class="form-group mb-3">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" class="form-control" value="{{ $usuario->login }}" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('/usuarios-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
