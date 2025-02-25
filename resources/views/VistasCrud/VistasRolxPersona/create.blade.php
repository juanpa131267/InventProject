@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar Rol a Persona</h1>

    <form action="{{ route('rolxpersona.store') }}" method="POST">
        @csrf

        {{-- Select de Persona --}}
        <div class="form-group mb-3">
            <label for="persona_id">Persona</label>
            <select name="persona_id" id="persona_id" class="form-control" required>
                @foreach($personasSinRol as $persona)
                    <option value="{{ $persona->id }}">{{ $persona->nombres }} {{ $persona->apellido }} ({{ $persona->id }}) </option>
                @endforeach
            </select>
        </div>

        {{-- Select de Rol --}}
        <div class="form-group mb-3">
            <label for="rol_id">Rol</label>
            <select name="rol_id" id="rol_id" class="form-control" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">{{ $rol->descripcion }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Asignar Rol</button>
            <a href="{{ url('/rolxpersona-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
