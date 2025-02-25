@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Usuario</h1>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="persona_id">Persona (ID)</label>
            <select name="persona_id" id="persona_id" class="form-control" required>
                @foreach($personas as $persona)
                    <option value="{{ $persona->id }}">
                        {{ $persona->nombres }} {{ $persona->apellido }} ({{ $persona->id }})
                    </option>
                @endforeach
            </select>
        </div>


        <div class="form-group mb-3">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" class="form-control" value="{{ old('login') }}" required>
        </div>

        <!-- Campo para contraseña -->
        <div class="form-group mb-3">
            <label for="password">Contraseña</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        Mostrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Campo para confirmar la contraseña -->
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                        Mostrar
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Crear Usuario</button>
            <a href="{{ url('/usuarios-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Mostrar u ocultar contraseña principal
    document.getElementById('togglePassword').addEventListener('click', function() {
        let passwordInput = document.getElementById('password');
        let type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
    });

    // Mostrar u ocultar confirmación de contraseña
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        let confirmPasswordInput = document.getElementById('password_confirmation');
        let type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
    });
</script>
@endsection
