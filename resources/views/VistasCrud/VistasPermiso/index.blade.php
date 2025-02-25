@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Permisos</h1>

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

    {{-- Botones para crear nuevo permiso y buscar --}}
    <div class="mb-4">
        <a href="{{ route('permisos.create') }}" class="btn btn-success">Crear Permiso</a>

        {{-- Formulario para buscar permiso usando AJAX --}}
        <input type="text" id="search-nombre" class="form-control d-inline-block w-50" placeholder="Buscar por nombre" aria-label="Buscar por nombre">
        
        {{-- Botón para mostrar permisos eliminados --}}
        <a href="{{ route('permisos.deleted') }}" class="btn btn-warning d-inline-block">Permisos Eliminados</a>
    </div>

    {{-- Tabla de permisos --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Permiso</th> 
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="permisos-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    // Cargar todos los permisos al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        fetchPermisos();
    });

    // Función para hacer la búsqueda de permisos por AJAX
    document.getElementById('search-nombre').addEventListener('input', function() {
        let nombre = this.value; // Obtener el valor del campo de búsqueda
        fetchPermisos(nombre); // Llama la función con el valor del nombre
    });

    // Función para hacer la petición AJAX
    function fetchPermisos(nombre = '') {
        let url = nombre ? `/api/permisos?q=${nombre}` : '/api/permisos';

        fetch(url)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('permisos-table');
            tableBody.innerHTML = ''; // Limpia la tabla antes de agregar nuevos datos

            if (data.length > 0) {
                data.forEach(permiso => {
                    let row = `
                        <tr data-id="${permiso.id}">
                            <td>${permiso.id}</td> {{-- Mostrar la ID del permiso --}}
                            <td>${permiso.nombre}</td>
                            <td>${permiso.descripcion}</td>
                            <td>
                                <a href="/permisos/${permiso.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${permiso.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron permisos.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al buscar permisos:', error);
        });
    }

    // Evento para manejar la eliminación de un permiso
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este permiso?')) {
                deletePermiso(id);
            }
        }
    });

    // Función para eliminar un permiso
    function deletePermiso(id) {
        fetch(`/permisos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que el token CSRF esté presente
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchPermisos(); // Refresca la lista de permisos
            } else {
                console.error('Error al eliminar permiso');
            }
        })
        .catch(error => {
            console.error('Error al eliminar permiso:', error);
        });
    }
</script>
@endsection
