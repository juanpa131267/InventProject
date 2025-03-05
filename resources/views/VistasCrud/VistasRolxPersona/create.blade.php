@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Asignar Rol a Persona</h1>

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

        <form action="{{ route('rolxpersonas.store') }}" method="POST" class="mt-3">
            @csrf

            {{-- Selección de la Persona --}}
            <div class="form-group mb-3">
                <label for="ID_PERSONAS" class="font-weight-bold">Persona</label>
                <select name="ID_PERSONAS" id="ID_PERSONAS" class="form-control selectpicker" data-live-search="true" required>
                    <option value="">Seleccione una persona</option>
                    @foreach($personasSinRol as $persona)
                        <option value="{{ $persona->ID }}">
                            {{ $persona->NOMBRES }} {{ $persona->APELLIDO }} - {{ $persona->CEDULA }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Selección del Rol --}}
            <div class="form-group mb-3">
                <label for="ID_ROLES" class="font-weight-bold">Rol</label>
                <select name="ID_ROLES" id="ID_ROLES" class="form-control" required>
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->ID }}">{{ $rol->DESCRIPCION }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-user-check"></i> Asignar Rol
                </button>
                <a href="{{ route('rolxpersonas.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
