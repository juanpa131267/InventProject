@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Usuario</h2>
        
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

        <form id="usuarioForm" action="{{ route('usuarios.update', $usuario->ID) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="persona" class="form-label">Persona Asociada</label>
                <input type="text" class="form-control" id="persona" 
                       value="{{ $usuario->PERSONAS ? $usuario->PERSONAS->NOMBRES . ' ' . $usuario->PERSONAS->APELLIDO : 'No asignada' }}" 
                       disabled>
                <input type="hidden" name="ID_PERSONAS" value="{{ $usuario->ID_PERSONAS }}">
            </div>

            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" class="form-control" id="login" name="LOGIN" 
                       value="{{ old('LOGIN', $usuario->LOGIN) }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña <small class="text-muted">(Dejar en blanco para no cambiar)</small></label>
                <input type="password" class="form-control" id="password" name="PASSWORD">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ url('/usuarios-index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection