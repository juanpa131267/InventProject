@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Personas</h1>

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

        {{-- Botones y búsqueda --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('personas.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Crear Persona
            </a>
            <input type="text" id="search-persona" class="form-control w-50" placeholder="Buscar por cédula">
            <a href="{{ route('personas.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash"></i> Personas Eliminadas
            </a>
        </div>

        {{-- Tabla de personas --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="personas-table">
                    {{-- Los datos se cargarán aquí dinámicamente con AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div id="pagination" class="d-flex justify-content-center"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchPersonas(); // Cargar todas las personas al iniciar
    });

    document.getElementById('search-persona').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchPersonas(searchTerm);
    });

    function fetchPersonas(search = '', page = 1) {
        let url = `/api/personas?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('personas-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(persona => {
                    let row = `<tr>
                        <td>${persona.ID}</td>
                        <td>${persona.CEDULA}</td>
                        <td>${persona.NOMBRES}</td>
                        <td>${persona.APELLIDO}</td>
                        <td>${persona.TELEFONO}</td>
                        <td>${persona.CORREO}</td>
                        <td>
                            <a href="/personas/${persona.ID}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${persona.ID}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No se encontraron personas con esa cédula.</td></tr>';
            }
            renderPagination(data, 'fetchPersonas', 'search-persona');
        })
        .catch(error => console.error('Error al obtener personas:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar esta persona?')) {
                deletePersona(id);
            }
        }
    });

    function deletePersona(id) {
        fetch(`/personas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchPersonas();
            } else {
                console.error('Error al eliminar persona');
            }
        })
        .catch(error => console.error('Error al eliminar persona:', error));
    }
</script>
@endsection
