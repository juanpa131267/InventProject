@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Artículos por Categoría</h1>

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
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('articuloxcategorias.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Asignar Categoría a Artículo
            </a>
            <input type="text" id="search-articuloxcategoria" class="form-control w-50" placeholder="Buscar por artículo o categoría...">
            <a href="{{ route('articuloxcategorias.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Relaciones Eliminadas
            </a>
        </div>

        {{-- Tabla de relaciones Artículo-Categoría --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="articuloxcategorias-table">
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
        fetchArticuloxCategoria();
    });

    document.getElementById('search-articuloxcategoria').addEventListener('input', function() {
        fetchArticuloxCategoria(this.value.trim());
    });

    function fetchArticuloxCategoria(search = '', page = 1) {
        let url = `/api/articuloxcategorias?q=${encodeURIComponent(search)}&page=${page}`;
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('articuloxcategorias-table');
            tableBody.innerHTML = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    let articuloNombre = item.a_r_t_i_c_u_l_o_s ? item.a_r_t_i_c_u_l_o_s.NOMBRE : 'Sin artículo';
                    let categoriaNombre = item.c_a_t_e_g_o_r_i_a_s ? item.c_a_t_e_g_o_r_i_a_s.NOMBRE : 'Sin categoría';
                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.ID ?? 'N/A'}</td>
                            <td>${articuloNombre}</td>
                            <td>${categoriaNombre}</td>
                            <td>
                                <a href="/articuloxcategorias/${item.ID}/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.ID}"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron asignaciones.</td></tr>';
            }
            if (typeof renderPagination === 'function') {
                renderPagination(data, 'fetchArticuloxCategoria', 'search-articuloxcategoria');
            }
        }).catch(error => console.error('Error al obtener relaciones:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            if (confirm('¿Está seguro de eliminar esta relación?')) {
                deleteArticuloxCategoria(event.target.getAttribute('data-id'));
            }
        }
    });

    function deleteArticuloxCategoria(id) {
        fetch(`/articuloxcategorias/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.ok ? fetchArticuloxCategoria() : console.error('Error al eliminar'))
        .catch(error => console.error('Error al eliminar relación:', error));
    }
</script>
@endsection
