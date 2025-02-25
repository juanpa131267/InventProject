@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Rol por Permiso</h1>

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
        <a href="{{ route('rolxpermiso.create') }}" class="btn btn-success">Asignar Permiso</a>
        <input type="text" id="search-login" class="form-control d-inline-block w-50" placeholder="Buscar por rol" aria-label="Buscar por">
        <a href="{{ route('rolxpermiso.deleted') }}" class="btn btn-warning">Asignaciones Eliminadas</a>
    </div>

    {{-- Tabla de asignaciones --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID RolxPermiso</th>
                <th>Rol (ID)</th>
                <th>Permiso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="rolxpermiso-table">
            {{-- Aquí se cargarán los datos dinámicamente con AJAX --}}
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchRolxPermiso();

        // Filtrar asignaciones en base al input de búsqueda
    document.getElementById('search-login').addEventListener('input', function() {
            const search = this.value.trim();
            fetchRolxPermiso(search);
        });
    });

    // Función para obtener las asignaciones de rolxpermiso con búsqueda opcional
    function fetchRolxPermiso(search = '') {
        const url = `/rolxpermiso?q=${encodeURIComponent(search)}`;
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => renderTable(data))
            .catch(error => console.error('Error fetching data:', error));
    }

    // Función para renderizar la tabla
    function renderTable(data) {
        const tableBody = document.getElementById('rolxpermiso-table');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No hay asignaciones disponibles.</td></tr>`;
            return;
        }

        // Renderizar cada fila
        data.forEach(item => {
            let rolDescripcion = item.rol ? `${item.rol.descripcion} (ID: ${item.rol.id})` : 'Sin rol';
            let permisoNombre = item.permiso ? item.permiso.nombre : 'Desconocido';

            let row = `
                <tr data-id="${item.id}">
                    <td>${item.id}</td>
                    <td>${rolDescripcion}</td>
                    <td>${permisoNombre}</td>
                    <td>
                        <a href="/rolxpermiso/${item.id}/edit" class="btn btn-primary btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}">Eliminar</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // Detectar clic en el botón de eliminación
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            confirmDelete(id);
        }
    });

    // Confirmar antes de eliminar
    function confirmDelete(id) {
        if (confirm('¿Está seguro de eliminar esta asignación?')) {
            console.log("ID to delete: ", id); // Agregar esto para depurar y ver si el ID se está capturando
            deleteRolxPermiso(id);
        }
    }

    // Eliminar asignación vía AJAX
    function deleteRolxPermiso(id) {
        console.log("Sending delete request for ID:", id); // Depuración para confirmar el ID
        fetch(`/rolxpermiso/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log("Response status:", response.status); // Depuración para ver el estado de la respuesta
            if (response.status === 204 || response.status === 200) {
                console.log('Deletion successful, removing row from DOM');
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();  // Eliminar la fila visualmente
                }
                showSuccessMessage('Asignación eliminada exitosamente.');
            } else {
                return response.json().then(data => {
                    console.error('Error:', data);
                    alert(data.error || 'Error al eliminar la asignación.');
                });
            }
        })
        .catch(error => console.error('Error deleting assignment:', error));
    }
</script>
@endsection
