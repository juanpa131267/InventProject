@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Gestión de Artículos</h1>

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

        {{-- Botones para crear artículo y búsqueda --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('articulos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Agregar Artículo
            </a>
            
            <input type="text" id="search-articulo" class="form-control w-50" placeholder="Buscar por nombre o marca">
            
            <a href="{{ route('articulos.deleted') }}" class="btn btn-warning">
                <i class="fas fa-trash-alt"></i> Artículos Eliminados
            </a>
        </div>

        {{-- Tabla de artículos --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Inventario</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Descripción</th>
                        <th>Fecha de Caducidad</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="articulos-table">
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
        fetchArticulos();
    });

    document.getElementById('search-articulo').addEventListener('input', function() {
        let searchTerm = this.value.trim();
        fetchArticulos(searchTerm);
    });

    function fetchArticulos(search = '', page = 1) {
        let url = `/api/articulos?q=${encodeURIComponent(search)}&page=${page}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            let tableBody = document.getElementById('articulos-table');
            tableBody.innerHTML = '';

            if (data.data.length > 0) {
                data.data.forEach(articulo => {
                    let inventarioNombre = articulo.i_n_v_e_n_t_a_r_i_o_s.NOMBRE;
                    let row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${articulo.ID}</td>
                        <td>${inventarioNombre}</td>
                        <td>${articulo.NOMBRE}</td>
                        <td>${articulo.MARCA}</td>
                        <td>${articulo.DESCRIPCION}</td>
                        <td>${articulo.FECHACADUCIDAD ? articulo.FECHACADUCIDAD : 'Artículo sin fecha de caducidad'}</td>
                        <td>${articulo.UNIDAD}</td>
                        <td>${articulo.CANTIDAD}</td>
                        <td>
                            <a href="/articulos/${articulo.ID}/edit" class="btn btn-sm btn-primary mx-50">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn mx-50" data-id="${articulo.ID}">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>

                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center">No se encontraron artículos.</td></tr>';
            }

            renderPagination(data, 'fetchArticulos', 'search-articulo');
        })
        .catch(error => {
            console.error('Error al obtener artículos:', error);
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const id = event.target.getAttribute('data-id');
            if (confirm('¿Está seguro de eliminar este artículo?')) {
                deleteArticulo(id);
            }
        }
    });

    function deleteArticulo(id) {
        fetch(`/articulos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
            if (response.ok) {
                fetchArticulos();
            } else {
                console.error('Error al eliminar artículo');
            }
        })
        .catch(error => {
            console.error('Error al eliminar artículo:', error);
        });
    }
</script>
@endsection
