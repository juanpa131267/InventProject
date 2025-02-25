@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Asignación de Rol</h1>

    <form action="{{ route('rolxpersona.update', $rolxpersona->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Mostrar la Persona seleccionada --}} 
        <div class="form-group mb-3">
            <label for="persona_id">Persona</label>
            <input type="text" class="form-control" value="{{ $rolxpersona->persona->nombres }} {{ $rolxpersona->persona->apellido }} (ID: {{ $rolxpersona->persona_id }})" readonly />
            <input type="hidden" name="persona_id" value="{{ $rolxpersona->persona_id }}" />
        </div>

        {{-- Select de Rol --}} 
        <div class="form-group mb-3">
            <label for="rol_id">Rol</label>
            <select name="rol_id" id="rol_id" class="form-control" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" {{ $rolxpersona->rol_id == $rol->id ? 'selected' : '' }}>
                        {{ $rol->descripcion }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('/rolxpersona-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

