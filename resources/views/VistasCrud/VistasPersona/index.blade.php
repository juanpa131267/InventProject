@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Personas</h1>

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

    {{-- Botones para crear nueva persona y buscar --}}
    <div class="mb-4">
        <a href="{{ route('personas.create') }}" class="btn btn-success">Crear Persona</a>

        {{-- Formulario para buscar persona por cédula usando AJAX --}}
        <input type="text" id="search-cedula" class="form-control d-inline-block w-50" placeholder="Buscar por cédula" aria-label="Buscar por cédula">
        
        {{-- Botón para mostrar usuarios eliminados --}}
        <a href="{{ route('personas.deleted') }}" class="btn btn-warning d-inline-block">Usuarios Eliminados</a>
    </div>

    {{-- Tabla de personas --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Municipio (ID)</th> <!-- Nueva columna para Municipio -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="personas-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    // Cargar todas las personas al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        fetchPersonas();
    });

    // Función para hacer la búsqueda de personas por AJAX
    document.getElementById('search-cedula').addEventListener('input', function() {
        let cedula = this.value; // Obtener el valor del campo de búsqueda
        fetchPersonas(cedula); // Llama la función con el valor de la cédula
    });

    // Función para hacer la petición AJAX
    function fetchPersonas(cedula = '') {
        let url = cedula ? `/api/personas?q=${cedula}` : '/api/personas';
        fetch(url)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('personas-table');
            tableBody.innerHTML = ''; // Limpia la tabla antes de agregar nuevos datos

            if (data.length > 0) {
                data.forEach(persona => {
                    let municipioInfo = persona.municipio ? `${persona.municipio.municipio} (${persona.municipio.municipio_id})` : 'No asignado';
                    
                    let row = `
                        <tr data-id="${persona.id}">
                            <td>${persona.cedula}</td>
                            <td>${persona.nombres}</td>
                            <td>${persona.apellido}</td>
                            <td>${persona.telefono}</td>
                            <td>${persona.correo}</td>
                            <td>${municipioInfo}</td>
                            <td>
                                <a href="/personas/${persona.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${persona.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No se encontraron personas.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al buscar personas:', error);
        });
    }


    // Evento para manejar la eliminación de una persona
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar esta persona?')) {
                deletePersona(id);
            }
        }
    });

    // Función para eliminar una persona
    function deletePersona(id) {
        fetch(`/personas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que el token CSRF esté presente
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchPersonas(); // Refresca la lista de personas
            } else {
                console.error('Error al eliminar persona');
            }
        })
        .catch(error => {
            console.error('Error al eliminar persona:', error);
        });
    }
</script>
@endsection
