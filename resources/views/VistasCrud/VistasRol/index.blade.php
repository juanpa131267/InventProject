@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Roles</h1>

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

    {{-- Botones para crear nuevo rol y buscar --}}
    <div class="mb-4">
        <a href="{{ route('roles.create') }}" class="btn btn-success">Crear Rol</a>

        {{-- Formulario para buscar rol usando AJAX --}}
        <input type="text" id="search-descripcion" class="form-control d-inline-block w-50" placeholder="Buscar por descripción" aria-label="Buscar por descripción">
        
        {{-- Botón para mostrar roles eliminados --}}
        <a href="{{ route('roles.deleted') }}" class="btn btn-warning d-inline-block">Roles Eliminados</a>
    </div>

    {{-- Tabla de roles --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Rol</th> 
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="roles-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    // Cargar todos los roles al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        fetchRoles();
    });

    // Función para hacer la búsqueda de roles por AJAX
    document.getElementById('search-descripcion').addEventListener('input', function() {
        let descripcion = this.value; // Obtener el valor del campo de búsqueda
        fetchRoles(descripcion); // Llama la función con el valor de la descripción
    });

    // Función para hacer la petición AJAX
    function fetchRoles(descripcion = '') {
        let url = descripcion ? `/api/roles?q=${descripcion}` : '/api/roles';

        fetch(url)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('roles-table');
            tableBody.innerHTML = ''; // Limpia la tabla antes de agregar nuevos datos

            if (data.length > 0) {
                data.forEach(rol => {
                    let row = `
                        <tr data-id="${rol.id}">
                            <td>${rol.id}</td> {{-- Mostrar la ID del rol --}}
                            <td>${rol.descripcion}</td>
                            <td>
                                <a href="/roles/${rol.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${rol.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron roles.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al buscar roles:', error);
        });
    }

    // Evento para manejar la eliminación de un rol
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este rol?')) {
                deleteRol(id);
            }
        }
    });

    // Función para eliminar un rol
    function deleteRol(id) {
        fetch(`/roles/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que el token CSRF esté presente
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchRoles(); // Refresca la lista de roles
            } else {
                console.error('Error al eliminar rol');
            }
        })
        .catch(error => {
            console.error('Error al eliminar rol:', error);
        });
    }
</script>
@endsection
