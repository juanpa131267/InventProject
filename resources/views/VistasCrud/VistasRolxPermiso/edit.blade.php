@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Asignación de Permiso</h1>

    <form action="{{ route('rolxpermiso.update', $rolxpermiso->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Mostrar el Rol seleccionado --}} 
        <div class="form-group mb-3">
            <label for="rol_id">Rol</label>
            <input type="text" class="form-control" value="{{ $rolxpermiso->rol->descripcion }} (ID: {{ $rolxpermiso->rol_id }})" readonly />
            <input type="hidden" name="rol_id" value="{{ $rolxpermiso->rol_id }}" />
        </div>

        {{-- Select de Permiso --}} 
        <div class="form-group mb-3">
            <label for="permiso_id">Permiso</label>
            <select name="permiso_id" id="permiso_id" class="form-control" required>
                @foreach($permisos as $permiso)
                    <option value="{{ $permiso->id }}" {{ $rolxpermiso->permiso_id == $permiso->id ? 'selected' : '' }}>
                        {{ $permiso->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('/rolxpermiso-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
