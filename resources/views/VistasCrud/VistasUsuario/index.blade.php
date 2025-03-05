@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Usuarios</h1>

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

        {{-- Botones para crear usuario y búsqueda --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('usuarios.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Crear Usuario
            </a>
            
            <input type="text" id="search-usuario" class="form-control w-50" placeholder="Buscar por login">
            
            <a href="{{ route('usuarios.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Usuarios Eliminados
            </a>
        </div>

        {{-- Tabla de usuarios --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Login</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuarios-table">
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
        fetchUsuarios();
    });

    document.getElementById('search-usuario').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchUsuarios(searchTerm);
    });

    function fetchUsuarios(search = '', page = 1) {
    let url = `/api/usuarios?q=${encodeURIComponent(search)}&page=${page}`;

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error del servidor: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        let tableBody = document.getElementById('usuarios-table');
        tableBody.innerHTML = '';

        if (!data || !data.data) {
            console.error('Error: Respuesta inesperada del servidor', data);
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar los usuarios</td></tr>';
            return;
        }

        if (data.data.length > 0) {
            data.data.forEach(usuario => {
                let personaNombre = usuario.p_e_r_s_o_n_a_s.NOMBRES + ' ' + usuario.p_e_r_s_o_n_a_s.APELLIDO;
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.ID}</td>
                    <td>${personaNombre}</td>
                    <td>${usuario.LOGIN}</td>
                    <td>
                        <a href="/usuarios/${usuario.ID}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${usuario.ID}">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron usuarios.</td></tr>';
        }

        renderPagination(data, 'fetchUsuarios', 'search-usuario');
    })
    .catch(error => {
        console.error('Error al obtener usuarios:', error);
        document.getElementById('usuarios-table').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar los datos</td></tr>';
    });
}


    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este usuario?')) {
                deleteUsuario(id);
            }
        }
    });

    function deleteUsuario(id) {
        fetch(`/usuarios/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.ok ? fetchUsuarios() : console.error('Error al eliminar usuario'))
        .catch(error => console.error('Error al eliminar usuario:', error));
    }
</script>
@endsection
