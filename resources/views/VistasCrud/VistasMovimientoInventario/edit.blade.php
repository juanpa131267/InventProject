@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Editar Movimiento de Inventario</h2>
        
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

        <form id="movimientoInventarioForm" action="{{ route('movimientosinventarios.update', $movimiento->ID) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Selección de Inventario -->
            <div class="form-group mb-3">
                <label for="ID_INVENTARIOS" class="font-weight-bold">Inventario</label>
                <select name="ID_INVENTARIOS" id="ID_INVENTARIOS" class="form-control" required>
                    <option value="">Seleccione un inventario</option>
                    @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->ID }}" 
                                data-usuario="{{ $inventario->ID_USUARIOS }}" 
                                data-login="{{ $inventario->USUARIOS->LOGIN }}"
                                {{ $inventario->ID == $movimiento->ID_INVENTARIOS ? 'selected' : '' }}>
                            {{ $inventario->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo para mostrar el usuario (solo lectura) -->
            <div class="form-group mb-3" id="usuario-container">
                <label for="USUARIO" class="font-weight-bold">Usuario</label>
                <input type="text" id="USUARIO" class="form-control" readonly 
                       value="{{ $movimiento->USUARIOS->LOGIN }}">
            </div>

            <!-- Campo oculto para ID_USUARIOS -->
            <input type="hidden" name="ID_USUARIOS" id="ID_USUARIOS_HIDDEN" value="{{ $movimiento->ID_USUARIOS }}">

            <!-- Selección de Artículo -->
            <div class="form-group mb-3">
                <label for="ID_ARTICULOS" class="font-weight-bold">Artículo</label>
                <select name="ID_ARTICULOS" id="ID_ARTICULOS" class="form-control" required>
                    <option value="">Seleccione un artículo</option>
                    @foreach($articulos as $articulo)
                        <option value="{{ $articulo->ID }}" 
                                data-inventario="{{ $articulo->ID_INVENTARIOS }}"
                                {{ $articulo->ID == $movimiento->ID_ARTICULOS ? 'selected' : '' }}>
                            {{ $articulo->NOMBRE }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de Movimiento -->
            <div class="form-group mb-3">
                <label for="TIPO" class="font-weight-bold">Tipo de Movimiento</label>
                <select name="TIPO" id="TIPO" class="form-control" required>
                    <option value="entrada" {{ $movimiento->TIPO == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="salida" {{ $movimiento->TIPO == 'salida' ? 'selected' : '' }}>Salida</option>
                </select>
            </div>

            <!-- Cantidad -->
            <div class="form-group mb-3">
                <label for="CANTIDAD" class="font-weight-bold">Cantidad</label>
                <input type="number" name="CANTIDAD" id="CANTIDAD" class="form-control" 
                       value="{{ $movimiento->CANTIDAD }}" min="1" required>
            </div>

            <!-- Fecha y Hora -->
            <div class="form-group mb-3 row">
                <div class="col-md-6">
                    <label for="FECHA" class="font-weight-bold">Fecha</label>
                    <input type="date" name="FECHA" id="FECHA" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($movimiento->FECHA)->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="HORA" class="font-weight-bold">Hora</label>
                    <input type="time" name="HORA" id="HORA" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($movimiento->FECHA)->format('H:i') }}" required>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="form-group mb-3">
                <label for="OBSERVACIONES" class="font-weight-bold">Observaciones</label>
                <textarea name="OBSERVACIONES" id="OBSERVACIONES" class="form-control" rows="3">{{ $movimiento->OBSERVACIONES }}</textarea>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
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
        const formulario = document.getElementById('movimientoInventarioForm');

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

        // Evento al enviar el formulario
        formulario.addEventListener('submit', function (e) {
            // Habilitar temporalmente los campos bloqueados para enviar sus valores
            if (articuloSelect.disabled) {
                articuloSelect.disabled = false;
            }
        });

        // Inicializar campos al cargar la página
        actualizarCampos();
        if (tipoSelect.value === "salida") {
            articuloSelect.disabled = true;
            usuarioContainer.style.display = "none";
        }
    });
</script>
@endsection