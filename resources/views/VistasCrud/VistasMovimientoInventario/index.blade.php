@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Movimientos de Inventario</h1>

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
            <a href="{{ route('movimientosinventarios.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Movimiento
            </a>
            <input type="text" id="search-articuloxfoto" class="form-control w-50" placeholder="Buscar por artículo...">
            <a href="{{ route('movimientosinventarios.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Movimientos Eliminados
            </a>
        </div>

        {{-- Tabla de movimientos --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Artículo</th>
                        <th>Inventario</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Observación</th>
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
        let url = `/api/movimientosinventarios?q=${encodeURIComponent(search)}&page=${page}`;
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('articuloxfotos-table');
            tableBody.innerHTML = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    let articuloNombre = item.a_r_t_i_c_u_l_o_s ? item.a_r_t_i_c_u_l_o_s.NOMBRE : 'Sin artículo';
                    let inventarioNombre = item.i_n_v_e_n_t_a_r_i_o_s ? item.i_n_v_e_n_t_a_r_i_o_s.NOMBRE : 'Sin inventario';
                    let usuarioNombre = item.u_s_u_a_r_i_o_s ? item.u_s_u_a_r_i_o_s.LOGIN : 'Sin usuario';

                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.ID ?? 'N/A'}</td>
                            <td>${articuloNombre}</td>
                            <td>${inventarioNombre}</td>
                            <td>${usuarioNombre}</td>
                            <td>${item.TIPO}</td>
                            <td>${item.CANTIDAD}</td>
                            <td>${item.FECHA}</td>
                            <td>${item.OBSERVACIONES}</td>
                            <td>
                                <a href="/movimientosinventarios/${item.ID}/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.ID}"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No se encontraron movimientos.</td></tr>';
            }
            if (typeof renderPagination === 'function') {
                renderPagination(data, 'fetchArticuloxFoto', 'search-articuloxfoto');
            }
        }).catch(error => console.error('Error al obtener movimientos:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            if (confirm('¿Está seguro de eliminar este movimiento?')) {
                deleteArticuloxFoto(event.target.getAttribute('data-id'));
            }
        }
    });

    function deleteArticuloxFoto(id) {
        fetch(`/movimientosinventarios/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.ok ? fetchArticuloxFoto() : console.error('Error al eliminar'))
        .catch(error => console.error('Error al eliminar movimiento:', error));
    }
</script>
@endsection
