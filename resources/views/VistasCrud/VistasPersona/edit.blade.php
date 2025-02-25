@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Persona</h1>

    {{-- Mostrar mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('personas.update', $persona->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" class="form-control" value="{{ $persona->cedula }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" value="{{ $persona->nombres }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $persona->apellido }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $persona->telefono }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ $persona->correo }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="departamento">Departamento</label>
            <select id="departamento" name="departamento_id" class="form-control">
                <option value="">Seleccione un departamento</option>
                @foreach($departamentos as $departamento)
                    <option value="{{ $departamento->departamento_id }}" 
                        {{ $persona->municipio->departamento_id == $departamento->departamento_id ? 'selected' : '' }}>
                        {{ $departamento->departamento }} ({{ $departamento->departamento_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="municipio">Municipio</label>
            <select id="municipio" name="municipio_id" class="form-control">
                <option value="">Seleccione un municipio</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio->municipio_id }}" 
                        {{ $persona->municipio_id == $municipio->municipio_id ? 'selected' : '' }}>
                        {{ $municipio->municipio }} ({{ $municipio->municipio_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('/personas-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Si se cambia el departamento, actualizar los municipios correspondientes
    document.getElementById('departamento').addEventListener('change', function() {
        let departamentoId = this.value;

        if (departamentoId) {
            fetch(`/api/departamentos/${departamentoId}/municipios`)
                .then(response => response.json())
                .then(data => {
                    let municipioSelect = document.getElementById('municipio');
                    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';

                    if (data.length > 0) {
                        data.forEach(municipio => {
                            let option = document.createElement('option');
                            option.value = municipio.municipio_id;
                            option.textContent = municipio.municipio;
                            municipioSelect.appendChild(option);
                        });
                    }

                    municipioSelect.disabled = false;

                    // Si el municipio de la persona editada no está en la lista, lo seleccionamos
                    let selectedMunicipioId = {{ json_encode($persona->municipio_id) }};
                    if (selectedMunicipioId) {
                        municipioSelect.value = selectedMunicipioId; // Establece el municipio seleccionado
                    }
                })
                .catch(error => {
                    console.error('Error fetching municipios:', error);
                });
        } else {
            document.getElementById('municipio').innerHTML = '<option value="">Seleccione un municipio</option>';
            document.getElementById('municipio').disabled = true;
        }
    });

    //Al crgar la página mostrar el departamento y municipio de la persona
    document.addEventListener('DOMContentLoaded', function() {
    let departamentoId = document.getElementById('departamento').value;
    let municipioSelect = document.getElementById('municipio');
    let selectedMunicipioId = {{ json_encode($persona->municipio_id) }};

    if (departamentoId) {
        fetch(`/api/departamentos/${departamentoId}/municipios`)
            .then(response => response.json())
            .then(data => {
                municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';

                data.forEach(municipio => {
                    let option = document.createElement('option');
                    option.value = municipio.municipio_id;
                    option.textContent = municipio.municipio + ` (${municipio.municipio_id})`;
                    municipioSelect.appendChild(option);
                });

                // Establecer el municipio seleccionado
                municipioSelect.value = selectedMunicipioId;
            })
            .catch(error => {
                console.error('Error fetching municipios:', error);
            });
    }
});
</script>

@endsection
