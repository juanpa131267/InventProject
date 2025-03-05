@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Proveedores</h1>

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
            <a href="{{ route('proveedores.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Crear Proveedor
            </a>
            <input type="text" id="search-proveedor" class="form-control w-50" placeholder="Buscar por nombre o correo">
            <a href="{{ route('proveedores.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash"></i> Proveedores Eliminados
            </a>
        </div>

        {{-- Tabla de proveedores --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="proveedores-table">
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
        fetchProveedores(); // Cargar todos los proveedores al iniciar
    });

    document.getElementById('search-proveedor').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchProveedores(searchTerm);
    });

    function fetchProveedores(search = '', page = 1) {
        let url = `/api/proveedores?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('proveedores-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(proveedor => {
                    let row = `<tr>
                        <td>${proveedor.ID}</td>
                        <td>${proveedor.NOMBRE}</td>
                        <td>${proveedor.TELEFONO}</td>
                        <td>${proveedor.CORREO}</td>
                        <td>${proveedor.DIRECCION}</td>
                        <td>
                            <a href="/proveedores/${proveedor.ID}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${proveedor.ID}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No se encontraron proveedores.</td></tr>';
            }
            renderPagination(data, 'fetchProveedores', 'search-proveedor');
        })
        .catch(error => console.error('Error al obtener proveedores:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este proveedor?')) {
                deleteProveedor(id);
            }
        }
    });

    function deleteProveedor(id) {
        fetch(`/proveedores/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchProveedores();
            } else {
                console.error('Error al eliminar proveedor');
            }
        })
        .catch(error => console.error('Error al eliminar proveedor:', error));
    }
</script>
@endsection
