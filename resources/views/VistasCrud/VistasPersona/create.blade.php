@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Persona</h1>

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

    <form action="{{ route('personas.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" class="form-control" required>
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
            <input type="text" name="telefono" id="telefono" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="departamento">Departamento (ID)</label>
            <select id="departamento" name="departamento_id" class="form-control">
                <option value="">Seleccione un departamento</option>
                @foreach($departamentos as $departamento)
                    <option value="{{ $departamento->departamento_id }}">{{ $departamento->departamento }} ({{ $departamento->departamento_id }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="municipio">Municipio (ID)</label>
            <select id="municipio" name="municipio_id" class="form-control" disabled>
                <option value="">Seleccione un municipio</option>
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Crear</button>
            <a href="{{ url('/personas-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Actualiza municipios al cambiar departamento
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
                            option.textContent = municipio.municipio + ' (' + municipio.municipio_id + ')';
                            municipioSelect.appendChild(option);
                        });
                    }

                    municipioSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching municipios:', error);
                });
        } else {
            document.getElementById('municipio').innerHTML = '<option value="">Seleccione un municipio</option>';
            document.getElementById('municipio').disabled = true;
        }
    });
</script>

@endsection
