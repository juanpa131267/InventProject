@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Usuario</h1>

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

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            {{-- Selección de la Persona asociada al Usuario --}}
            <div class="form-group mb-3">
                <label for="ID_PERSONAS" class="font-weight-bold">Persona</label>
                <select name="ID_PERSONAS" id="ID_PERSONAS" class="form-control" required>
                    <option value="">Seleccione una persona</option>
                    @foreach($personas as $persona)
                        <option value="{{ $persona->ID }}">
                            {{ $persona->NOMBRES }} {{ $persona->APELLIDO }} - {{ $persona->CEDULA }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Campo para el Login del Usuario --}}
            <div class="form-group mb-3">
                <label for="LOGIN" class="font-weight-bold">Login</label>
                <input type="text" name="LOGIN" id="LOGIN" class="form-control" required>
            </div>

            {{-- Campo para la Contraseña --}}
            <div class="form-group mb-3">
                <label for="PASSWORD" class="font-weight-bold">Contraseña</label>
                <div class="input-group">
                    <input type="password" name="PASSWORD" id="PASSWORD" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">Mostrar</button>
                    </div>
                </div>
            </div>

            {{-- Campo para Confirmar la Contraseña --}}
            <div class="form-group mb-3">
                <label for="password_confirmation" class="font-weight-bold">Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">Mostrar</button>
                    </div>
                </div>
                <small id="passwordError" class="text-danger" style="display: none;">Las contraseñas no coinciden.</small>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                    <i class="fas fa-user-plus"></i> Crear
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('PASSWORD');
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('passwordError');
        const submitBtn = document.getElementById('submitBtn');

        function validatePasswords() {
            if (password.value !== confirmPassword.value) {
                passwordError.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                passwordError.style.display = 'none';
                submitBtn.disabled = false;
            }
        }

        password.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);

        document.getElementById('togglePassword').addEventListener('click', function() {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
            this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const type = confirmPassword.type === 'password' ? 'text' : 'password';
            confirmPassword.type = type;
            this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
        });
    });
</script>
@endsection