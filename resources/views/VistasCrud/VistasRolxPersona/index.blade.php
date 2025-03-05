@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Roles por Persona</h1>

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
            <a href="{{ route('rolxpersonas.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Asignar Rol
            </a>
            <input type="text" id="search-rolxpersona" class="form-control w-50" placeholder="Buscar por nombre de persona...">
            <a href="{{ route('rolxpersonas.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Asignaciones Eliminadas
            </a>
        </div>

        {{-- Tabla de relaciones Persona-Rol --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Persona</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="rolxpersonas-table">
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
        fetchRolxPersona();
    });

    document.getElementById('search-rolxpersona').addEventListener('input', function() {
        fetchRolxPersona(this.value.trim());
    });

    function fetchRolxPersona(search = '', page = 1) {
        let url = `/api/rolxpersonas?q=${encodeURIComponent(search)}&page=${page}`;
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('rolxpersonas-table');
            tableBody.innerHTML = '';
            if (data.data && data.data.length > 0) {
                data.data.forEach(item => {
                    let personaNombre = item.p_e_r_s_o_n_a_s ? `${item.p_e_r_s_o_n_a_s.NOMBRES} ${item.p_e_r_s_o_n_a_s.APELLIDO}` : 'Sin persona';
                    let rolDescripcion = item.r_o_l_e_s ? item.r_o_l_e_s.DESCRIPCION : 'Sin rol';
                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.ID ?? 'N/A'}</td>
                            <td>${personaNombre}</td>
                            <td>${rolDescripcion}</td>
                            <td>
                                <a href="/rolxpersonas/${item.ID}/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.ID}"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron asignaciones.</td></tr>';
            }
            if (typeof renderPagination === 'function') {
                renderPagination(data, 'fetchRolxPersona', 'search-rolxpersona');
            }
        }).catch(error => console.error('Error al obtener asignaciones:', error));
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            if (confirm('¿Está seguro de eliminar esta asignación de rol?')) {
                deleteRolxPersona(event.target.getAttribute('data-id'));
            }
        }
    });

    function deleteRolxPersona(id) {
        fetch(`/rolxpersonas/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.ok ? fetchRolxPersona() : console.error('Error al eliminar'))
        .catch(error => console.error('Error al eliminar asignación:', error));
    }
</script>
@endsection