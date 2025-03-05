@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Permisos</h1>

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

        {{-- Botones de acción --}}
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('permisos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear Permiso
            </a>
            <input type="text" id="search-permiso" class="form-control w-50" placeholder="Buscar por nombre de permiso">
            <a href="{{ route('permisos.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Permisos Eliminados
            </a>
        </div>

        {{-- Tabla de permisos --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="permisos-table">
                    {{-- Datos cargados dinámicamente con AJAX --}}
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div id="pagination" class="d-flex justify-content-center"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchPermisos();
    });

    document.getElementById('search-permiso').addEventListener('input', function() {
        fetchPermisos(this.value.trim());
    });

    function fetchPermisos(search = '', page = 1) {
        let url = `/api/permisos?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('permisos-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(permiso => {
                    let row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${permiso.ID}</td>
                        <td>${permiso.NOMBRE}</td>
                        <td>${permiso.DESCRIPCION}</td>
                        <td>
                            <a href="/permisos/${permiso.ID}/edit" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${permiso.ID}">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron permisos.</td></tr>';
            }
            renderPagination(data, 'fetchPermisos', 'search-permiso');
        })
        .catch(error => console.error('Error al obtener permisos:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este permiso?')) {
                deletePermiso(id);
            }
        }
    });

    function deletePermiso(id) {
        fetch(`/permisos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchPermisos();
            } else {
                console.error('Error al eliminar permiso');
            }
        })
        .catch(error => console.error('Error al eliminar permiso:', error));
    }
</script>
@endsection