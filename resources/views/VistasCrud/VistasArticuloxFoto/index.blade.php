@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Artículos por Foto</h1>

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
            <a href="{{ route('articuloxfotos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Asignar Foto a Artículo
            </a>
            <input type="text" id="search-articuloxfoto" class="form-control w-50" placeholder="Buscar por artículo...">
            <a href="{{ route('articuloxfotos.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Relaciones Eliminadas
            </a>
        </div>

        {{-- Tabla de relaciones Artículo-Foto --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Artículo</th>
                        <th>URL</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="articuloxfotos-table">
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
        fetchArticuloxFoto();
    });

    document.getElementById('search-articuloxfoto').addEventListener('input', function() {
        fetchArticuloxFoto(this.value.trim());
    });

    function fetchArticuloxFoto(search = '', page = 1) {
        let url = `/api/articuloxfotos?q=${encodeURIComponent(search)}&page=${page}`;
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('articuloxfotos-table');
            tableBody.innerHTML = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    let articuloNombre = item.a_r_t_i_c_u_l_o_s ? item.a_r_t_i_c_u_l_o_s.NOMBRE : 'Sin artículo';
                    let fotoURL = item.f_o_t_o_s ? `<img src="${item.f_o_t_o_s.URL}" alt="Foto" width="50">` : 'Sin foto';
                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.ID ?? 'N/A'}</td>
                            <td>${articuloNombre}</td>
                            <td>${item.f_o_t_o_s.URL}</td>
                            <td>${fotoURL}</td>
                            <td>
                                <a href="/articuloxfotos/${item.ID}/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.ID}"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron asignaciones.</td></tr>';
            }
            if (typeof renderPagination === 'function') {
                renderPagination(data, 'fetchArticuloxFoto', 'search-articuloxfoto');
            }
        }).catch(error => console.error('Error al obtener relaciones:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            if (confirm('¿Está seguro de eliminar esta relación?')) {
                deleteArticuloxFoto(event.target.getAttribute('data-id'));
            }
        }
    });

    function deleteArticuloxFoto(id) {
        fetch(`/articuloxfotos/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.ok ? fetchArticuloxFoto() : console.error('Error al eliminar'))
        .catch(error => console.error('Error al eliminar relación:', error));
    }
</script>
@endsection
