@extends('layouts.appWel')

@section('title', 'Registro - Rentalo')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block mb-2">
            <div class="circle-shape bg-primary text-white p-4 shadow" style="width: 100px; height: 100px; border-radius: 50%;">
                <i class="mai-document fs-2"></i>
            </div>
        </div>
        <h2 class="mb-0 mt-2">Registro de Usuario</h2>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Mostrar mensajes de error de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de registro --}}
    <div class="card mx-auto shadow-lg border-0" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="cedula">Cédula</label>
                    <input type="number" name="cedula" id="cedula" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nombres">Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="telefono">Teléfono</label>
                    <input type="number" name="telefono" id="telefono" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="departamento_id">Departamento</label>
                    <select id="departamento_id" name="departamento_id" class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->departamento_id }}">{{ $departamento->departamento }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="municipio_id">Municipio</label>
                    <select id="municipio_id" name="municipio_id" class="form-control" required disabled>
                        <option value="">Seleccione un municipio</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="login">Nombre de Usuario</label>
                    <input type="text" name="login" id="login" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
                            <i class="mai-eye fs-2"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password_confirmation">
                            <i class="mai-eye fs-2"></i>
                        </button>
                    </div>
                </div>

                {{-- Términos y Condiciones --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Términos y Condiciones</span>
                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Haz clic para más detalles"></i>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Para poder continuar con el registro de usuario, es necesario que aceptes nuestros términos y condiciones.</p>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="termsAndConditions" required>
                            <label class="form-check-label" for="termsAndConditions">
                                Acepto los <a href="{{ url('/terminosycondiciones') }}" target="_blank" class="text-primary">términos y condiciones</a> de uso.
                            </label>
                        </div>
                        <p class="text-muted small">Al aceptar, confirmas que has leído y comprendido nuestras políticas de uso.</p>
                    </div>
                </div>


                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary mx-2"><i class="bi bi-person-plus-fill"></i> Registrarse</button>
                    <a href="{{ url('/') }}" class="btn btn-danger mx-2"><i class="bi bi-x-circle"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Actualizar municipios al seleccionar un departamento
    const departamentoSelect = document.getElementById('departamento_id');
    const municipioSelect = document.getElementById('municipio_id');

    departamentoSelect.addEventListener('change', () => {
        const departamentoId = departamentoSelect.value;
        municipioSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';

        if (departamentoId) {
            fetch(`/api/departamentos/${departamentoId}/municipios`)
                .then(response => response.json())
                .then(data => {
                    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                    data.forEach(municipio => {
                        municipioSelect.innerHTML += `<option value="${municipio.municipio_id}">${municipio.municipio}</option>`;
                    });
                    municipioSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error al cargar municipios:', error);
                    municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        }
    });

    // Mostrar y ocultar contraseñas
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.getAttribute('data-target'));
            const type = target.type === 'password' ? 'text' : 'password';
            target.type = type;
            button.innerHTML = type === 'password' ? '<i class="mai-eye"></i>' : '<i class="mai-eye"></i>';
        });
    });

    // Habilitar el botón de guardar cuando la casilla de términos y condiciones esté marcada
    document.getElementById('termsAndConditions').addEventListener('change', function() {
    document.getElementById('submitBtn').disabled = !this.checked;
    });
</script>

@endsection
