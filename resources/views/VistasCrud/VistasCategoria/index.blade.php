@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Categorías</h1>

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
            <a href="{{ route('categorias.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear Categoría
            </a>
            <input type="text" id="search-categoria" class="form-control w-50" placeholder="Buscar por nombre">
            <a href="{{ route('categorias.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash"></i> Categorías Eliminadas
            </a>
        </div>

        {{-- Tabla de categorías --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table">
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
        fetchCategorias(); // Cargar todas las categorías al iniciar
    });

    document.getElementById('search-categoria').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchCategorias(searchTerm);
    });

    function fetchCategorias(search = '', page = 1) {
        let url = `/api/categorias?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('categorias-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(categoria => {
                    let row = `<tr>
                        <td>${categoria.ID}</td>
                        <td>${categoria.NOMBRE}</td>
                        <td>
                            <a href="/categorias/${categoria.ID}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${categoria.ID}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron categorías con ese nombre.</td></tr>';
            }
            renderPagination(data, 'fetchCategorias', 'search-categoria');
        })
        .catch(error => console.error('Error al obtener categorías:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar esta categoría?')) {
                deleteCategoria(id);
            }
        }
    });

    function deleteCategoria(id) {
        fetch(`/categorias/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchCategorias();
            } else {
                console.error('Error al eliminar categoría');
            }
        })
        .catch(error => console.error('Error al eliminar categoría:', error));
    }
</script>
@endsection
