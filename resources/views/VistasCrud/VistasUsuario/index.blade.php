@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Usuarios</h1>

    {{-- Mostrar mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Botones para crear nuevo usuario y buscar --}}
    <div class="mb-4">
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">Crear Usuario</a>
        {{-- Formulario para buscar usuario por login usando AJAX --}}
        <input type="text" id="search-login" class="form-control d-inline-block w-50" placeholder="Buscar por login" aria-label="Buscar por login">
        
        {{-- Botón para mostrar usuarios eliminados --}}
        <a href="{{ route('usuarios.deleted') }}" class="btn btn-warning d-inline-block">Usuarios Eliminados</a>
    </div>

    {{-- Tabla de usuarios --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Usuario</th> 
                <th>Login</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="usuarios-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    // Cargar todos los usuarios al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        fetchUsuarios();
    });

    // Función para hacer la búsqueda de usuarios por AJAX
    document.getElementById('search-login').addEventListener('input', function() {
        let login = this.value; // Obtener el valor del campo de búsqueda
        fetchUsuarios(login); // Llama la función con el valor del login
    });

    // Función para hacer la petición AJAX
    function fetchUsuarios(login = '') {
        let url = login ? `/api/usuarios?q=${login}` : '/api/usuarios';

        fetch(url)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('usuarios-table');
            tableBody.innerHTML = ''; // Limpia la tabla antes de agregar nuevos datos

            if (data.length > 0) {
                data.forEach(usuario => {
                    let row = `
                        <tr data-id="${usuario.id}">
                            <td>${usuario.id}</td> {{-- Mostrar la ID del usuario --}}
                            <td>${usuario.login}</td>
                            <td>
                                <a href="/usuarios/${usuario.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${usuario.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron usuarios.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al buscar usuarios:', error);
        });
    }

    // Evento para manejar la eliminación de un usuario
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este usuario?')) {
                deleteUsuario(id);
            }
        }
    });

    // Función para eliminar un usuario
    function deleteUsuario(id) {
        fetch(`/usuarios/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que el token CSRF esté presente
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                fetchUsuarios(); // Refresca la lista de usuarios
            } else {
                console.error('Error al eliminar usuario');
            }
        })
        .catch(error => {
            console.error('Error al eliminar usuario:', error);
        });
    }
</script>
@endsection
