@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Inventarios</h1>

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
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('inventarios.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Crear Inventario
            </a>
            <input type="text" id="search-inventario" class="form-control w-50" placeholder="Buscar por nombre...">
            <a href="{{ route('inventarios.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Inventarios Eliminados
            </a>
        </div>

        {{-- Tabla de inventarios --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="inventarios-table">
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
        fetchInventarios();
    });

    document.getElementById('search-inventario').addEventListener('input', function() {
        fetchInventarios(this.value.trim());
    });

    function fetchInventarios(search = '', page = 1) {
        let url = `/api/inventarios?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('inventarios-table');
            tableBody.innerHTML = '';

            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    let usuario = item.u_s_u_a_r_i_o_s ? `${item.u_s_u_a_r_i_o_s.LOGIN}` : 'Sin usuario';
                    
                    let fotoUrl = item.f_o_t_o_s ? item.f_o_t_o_s.URL : null;
                    let foto = fotoUrl ? `<img src="${fotoUrl}" alt="Foto" class="img-thumbnail" width="50">` : 'Sin foto';

                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.ID}</td>
                            <td>${item.NOMBRE}</td>
                            <td>${usuario}</td>
                            <td>${foto}</td>
                            <td>
                                <a href="/inventarios/${item.ID}/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.ID}"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron inventarios.</td></tr>';
            }

            if (typeof renderPagination === 'function') {
                renderPagination(data, 'fetchInventarios', 'search-inventario');
            }
        }).catch(error => console.error('Error al obtener inventarios:', error));
    }


    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            if (confirm('¿Está seguro de eliminar este inventario?')) {
                deleteInventario(event.target.getAttribute('data-id'));
            }
        }
    });

    function deleteInventario(id) {
        fetch(`/inventarios/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.ok ? fetchInventarios() : console.error('Error al eliminar'))
        .catch(error => console.error('Error al eliminar inventario:', error));
    }
</script>
@endsection
