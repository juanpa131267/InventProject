@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Roles</h1>

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

        {{-- Botones y barra de búsqueda --}}
        <div class="d-flex flex-wrap justify-content-between mb-4">
            <a href="{{ route('roles.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear Rol
            </a>

            <input type="text" id="search-rol" class="form-control w-50" placeholder="Buscar por descripción">

            <a href="{{ route('roles.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash"></i> Roles Eliminados
            </a>
        </div>

        {{-- Tabla de roles --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="roles-table">
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
        fetchRoles(); // Cargar roles al inicio
    });

    document.getElementById('search-rol').addEventListener('input', function() {
        fetchRoles(this.value.trim());
    });

    function fetchRoles(search = '', page = 1) {
        let url = `/api/roles?q=${encodeURIComponent(search)}&page=${page}`;
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('roles-table');
            tableBody.innerHTML = '';
            
            if (data.data.length > 0) {
                data.data.forEach(rol => {
                    let row = `<tr>
                        <td>${rol.ID}</td>
                        <td>${rol.DESCRIPCION}</td>
                        <td>
                            <a href="/roles/${rol.ID}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${rol.ID}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron roles.</td></tr>';
            }
            renderPagination(data, 'fetchRoles', 'search-rol');
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este rol?')) {
                deleteRol(id);
            }
        }
    });

    function deleteRol(id) {
        fetch(`/roles/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
            if (response.ok) {
                fetchRoles();
            }
        });
    }
</script>
@endsection
