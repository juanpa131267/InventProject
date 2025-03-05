@extends('layouts.appWel')

@section('content')
<div class="container py-4">
    {{-- Encabezado con ícono estilizado --}}
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block mb-2">
            <div class="circle-shape bg-primary text-white p-4 shadow" style="width: 100px; height: 100px; border-radius: 50%;">
                <i class="mai-pencil fs-2"></i>
            </div>
        </div>
        <h2 class="mb-0 mt-2">Editar Perfil</h2>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Formulario de edición de perfil --}}
    <div class="card mx-auto shadow-lg border-0" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('perfil.update') }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-group mb-3">
                    <label for="login">Nombre de usuario</label>
                    <input type="text" name="login" id="login" class="form-control" value="{{ old('login', $usuario->login) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="cedula">Cédula</label>
                    <input type="text" name="cedula" id="cedula" class="form-control" value="{{ old('cedula', $persona->cedula) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nombres">Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres', $persona->nombres) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido', $persona->apellido) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $persona->telefono) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo', $persona->correo) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="departamento_id">Departamento</label>
                    <select id="departamento_id" name="departamento_id" class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->departamento_id }}" {{ $persona->municipio->departamento_id == $departamento->departamento_id ? 'selected' : '' }}>
                                {{ $departamento->departamento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="municipio_id">Municipio</label>
                    <select id="municipio_id" name="municipio_id" class="form-control" required disabled>
                        <option value="">Seleccione un municipio</option>
                    </select>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary mx-2"><i class="bi bi-save"></i> Guardar Cambios</button>
                    <a href="{{ url('/perfil') }}" class="btn btn-danger mx-2"><i class="bi bi-x-circle"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departamentoSelect = document.getElementById('departamento_id');
        const municipioSelect = document.getElementById('municipio_id');
        const selectedMunicipioId = {{ json_encode($persona->municipio_id) }};

        // Cargar municipios al cambiar el departamento
        departamentoSelect.addEventListener('change', function() {
            const departamentoId = this.value;
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
                        municipioSelect.value = selectedMunicipioId;
                    })
                    .catch(error => {
                        municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                        console.error(error);
                    });
            } else {
                municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                municipioSelect.disabled = true;
            }
        });

        // Cargar municipios si ya hay un departamento seleccionado al iniciar
        if (departamentoSelect.value) {
            departamentoSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
