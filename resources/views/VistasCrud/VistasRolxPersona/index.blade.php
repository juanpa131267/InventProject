@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Rol por Persona</h1>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('rolxpersona.create') }}" class="btn btn-success">Asignar Rol</a>
        <input type="text" id="search-login" class="form-control d-inline-block w-50" placeholder="Buscar por persona" aria-label="Buscar por">
        <a href="{{ route('rolxpersona.deleted') }}" class="btn btn-warning">Asignaciones Eliminadas</a>
    </div>

    {{-- Tabla de asignaciones --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID RolxPersona</th>
                <th>Persona (ID)</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="rolxpersona-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchRolxPersona();
    });

    document.getElementById('search-login').addEventListener('input', function() {
        let search = this.value;
        fetchRolxPersona(search);
    });

    function fetchRolxPersona(search = '') {
        let url = search ? `/api/rolxpersona?q=${search}` : '/api/rolxpersona';

        fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('No se encontraron datos');
            }
            return response.json();
        })
        .then(data => {
            let tableBody = document.getElementById('rolxpersona-table');
            tableBody.innerHTML = '';

            data.forEach(item => {
                let personaNombre = item.persona ? `${item.persona.nombres} ${item.persona.apellido}` : 'Desconocido';
                let rolDescripcion = item.rol ? item.rol.descripcion : 'Sin rol';

                let row = `
                    <tr data-id="${item.id}">
                        <td>${item.id}</td>
                        <td>${personaNombre} (${item.persona ? item.persona.id : 'Sin ID'})</td>
                        <td>${rolDescripcion}</td>
                        <td>
                            <a href="/rolxpersona/${item.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}">Eliminar</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error al buscar asignaciones:', error);
            document.getElementById('rolxpersona-table').innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron asignaciones.</td></tr>';
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            let id = event.target.getAttribute('data-id');
            if (confirm('¿Estás seguro de eliminar esta asignación?')) {
                fetch(`/rolxpersona/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        fetchRolxPersona(); // Refresh the data after deletion
                    } else {
                        alert('Error al eliminar la asignación');
                    }
                });
            }
        }
    });
</script>
@endsection
