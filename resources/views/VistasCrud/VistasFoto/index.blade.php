@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Fotos</h1>

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
            <a href="{{ route('fotos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Agregar Foto
            </a>
            <input type="text" id="search-foto" class="form-control w-50" placeholder="Buscar por descripción">
            <a href="{{ route('fotos.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash"></i> Fotos Eliminadas
            </a>
        </div>

        {{-- Tabla de fotos --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="fotos-table">
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
        fetchFotos(); // Cargar todas las fotos al iniciar
    });

    document.getElementById('search-foto').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchFotos(searchTerm);
    });

    function fetchFotos(search = '', page = 1) {
        let url = `/api/fotos?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('fotos-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(foto => {
                    let row = `<tr>
                        <td>${foto.ID}</td>
                        <td>${foto.URL}</td>
                        <td><img src="${foto.URL}" alt="Foto" class="img-thumbnail" style="width: 100px;"></td>
                        <td>${foto.DESCRIPCION}</td>
                        <td>
                            <a href="/fotos/${foto.ID}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${foto.ID}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron fotos con esa descripción.</td></tr>';
            }
            renderPagination(data, 'fetchFotos', 'search-foto');
        })
        .catch(error => console.error('Error al obtener fotos:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar esta foto?')) {
                deleteFoto(id);
            }
        }
    });

    function deleteFoto(id) {
        fetch(`/fotos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchFotos();
            } else {
                console.error('Error al eliminar la foto');
            }
        })
        .catch(error => console.error('Error al eliminar la foto:', error));
    }
</script>
@endsection
