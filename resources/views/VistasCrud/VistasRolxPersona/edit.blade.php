@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Asignación de Rol</h2>
        
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

        <form id="rolxpersonaForm" action="{{ route('rolxpersonas.update', $rolxpersona->ID) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Información de la Persona (No editable) -->
            <div class="mb-3">
                <label for="persona" class="form-label fw-bold">Persona Asociada</label>
                <input type="text" class="form-control" id="persona" 
                       value="{{ $rolxpersona->PERSONAS->NOMBRES }} {{ $rolxpersona->PERSONAS->APELLIDO }}" 
                       disabled>
                <input type="hidden" name="ID_PERSONAS" value="{{ $rolxpersona->ID_PERSONAS }}">
            </div>

            <!-- Selección de Rol -->
            <div class="mb-3">
                <label for="ID_ROLES" class="form-label fw-bold">Rol Asignado</label>
                <select class="form-select" id="ID_ROLES" name="ID_ROLES" required>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->ID }}" 
                            {{ $rol->ID == $rolxpersona->ID_ROLES ? 'selected' : '' }}>
                            {{ $rol->DESCRIPCION }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="{{ url('/rolxpersonas-index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
