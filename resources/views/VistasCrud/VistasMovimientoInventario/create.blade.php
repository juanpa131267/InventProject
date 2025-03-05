@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Registrar Movimiento de Inventario</h1>

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

        <form action="{{ route('movimientosinventarios.store') }}" method="POST" class="mt-3">
            @csrf

            <!-- Campo para seleccionar el inventario -->
            <div class="form-group mb-3">
                <label for="ID_INVENTARIOS" class="font-weight-bold">Inventario</label>
                <select name="ID_INVENTARIOS" id="ID_INVENTARIOS" class="form-control" required>
                    <option value="">Seleccione un inventario</option>
                    @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->ID }}" 
                                data-usuario="{{ $inventario->ID_USUARIOS }}" 
                                data-login="{{ $inventario->USUARIOS->LOGIN }}">
                            {{ $inventario->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo para mostrar el usuario (solo lectura) -->
            <div class="form-group mb-3" id="usuario-container">
                <label for="USUARIO" class="font-weight-bold">Usuario</label>
                <input type="text" id="USUARIO" class="form-control" readonly>
            </div>

            <!-- Campo oculto para ID_USUARIOS -->
            <input type="hidden" name="ID_USUARIOS" id="ID_USUARIOS_HIDDEN">

            <!-- Campo para seleccionar el artículo -->
            <div class="form-group mb-3">
                <label for="ID_ARTICULOS" class="font-weight-bold">Artículo</label>
                <select name="ID_ARTICULOS" id="ID_ARTICULOS" class="form-control" required>
                    <option value="">Seleccione un artículo</option>
                    @foreach($articulos as $articulo)
                        <option value="{{ $articulo->ID }}" data-inventario="{{ $articulo->ID_INVENTARIOS }}">
                            {{ $articulo->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo para seleccionar el tipo de movimiento -->
            <div class="form-group mb-3">
                <label for="TIPO" class="font-weight-bold">Tipo de Movimiento</label>
                <select name="TIPO" id="TIPO" class="form-control" required>
                    <option value="">Seleccione el tipo de movimiento</option>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <!-- Campo para la cantidad -->
            <div class="form-group mb-3">
                <label for="CANTIDAD" class="font-weight-bold">Cantidad</label>
                <input type="number" name="CANTIDAD" id="CANTIDAD" class="form-control" min="1" required>
            </div>

            <!-- Campo para la fecha -->
            <div class="form-group mb-3">
                <label for="FECHA" class="font-weight-bold">Fecha</label>
                <input type="date" name="FECHA" id="FECHA" class="form-control" required>
            </div>

            <!-- Campo para la hora -->
            <div class="form-group mb-3">
                <label for="HORA" class="font-weight-bold">Hora</label>
                <input type="time" name="HORA" id="HORA" class="form-control" required>
            </div>

            <!-- Campo para las observaciones -->
            <div class="form-group mb-3">
                <label for="OBSERVACIONES" class="font-weight-bold">Observaciones</label>
                <textarea name="OBSERVACIONES" id="OBSERVACIONES" class="form-control" rows="3"></textarea>
            </div>

            <!-- Botones de acción -->
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Registrar Movimiento
                </button>
                <a href="{{ route('movimientosinventarios.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inventarioSelect = document.getElementById('ID_INVENTARIOS');
        const usuarioHidden = document.getElementById('ID_USUARIOS_HIDDEN');
        const usuarioInput = document.getElementById('USUARIO');
        const usuarioContainer = document.getElementById('usuario-container');
        const articuloSelect = document.getElementById('ID_ARTICULOS');
        const tipoSelect = document.getElementById('TIPO');

        // Deshabilitar artículo al inicio
        articuloSelect.disabled = true;

        // Función para actualizar el usuario y el artículo
        const actualizarCampos = () => {
            const inventarioId = inventarioSelect.value;
            const usuarioId = inventarioSelect.options[inventarioSelect.selectedIndex].getAttribute('data-usuario');
            const usuarioLogin = inventarioSelect.options[inventarioSelect.selectedIndex].getAttribute('data-login');

            // Asignar el usuario seleccionado al campo oculto y mostrarlo en el campo de solo lectura
            usuarioHidden.value = usuarioId || "";
            usuarioInput.value = usuarioLogin || ""; // Mostrar el LOGIN del usuario

            // Filtrar artículos según el inventario seleccionado
            let hasValidArticle = false;
            [...articuloSelect.options].forEach(option => {
                if (option.value === "" || option.getAttribute('data-inventario') === inventarioId) {
                    option.style.display = 'block';
                    hasValidArticle = true;
                } else {
                    option.style.display = 'none';
                }
            });

            articuloSelect.value = "";
            articuloSelect.disabled = !hasValidArticle;
        };

        // Evento al cambiar el inventario
        inventarioSelect.addEventListener('change', actualizarCampos);

        // Evento al cambiar el tipo de movimiento
        tipoSelect.addEventListener('change', function () {
            if (this.value === "salida") {
                // Bloquear el artículo y ocultar el usuario
                articuloSelect.disabled = true;
                usuarioContainer.style.display = "none";
            } else {
                // Habilitar el artículo y mostrar el usuario
                articuloSelect.disabled = false;
                usuarioContainer.style.display = "block";
                actualizarCampos(); // Actualizar campos nuevamente
            }
        });
    });
</script>
@endsection