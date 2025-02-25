@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar Permiso a Rol</h1>

    <form action="{{ route('rolxpermiso.store') }}" method="POST">
        @csrf

        {{-- Select de Rol --}}
        <div class="form-group mb-3">
            <label for="rol_id">Rol</label>
            <select name="rol_id" id="rol_id" class="form-control" required>
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">{{ $rol->descripcion }}</option>
                @endforeach
            </select>
        </div>

        {{-- Select de Permiso --}}
        <div class="form-group mb-3">
            <label for="permiso_id">Permiso</label>
            <select name="permiso_id" id="permiso_id" class="form-control" required>
                <option value="">Seleccione un permiso</option>
                {{-- Los permisos se llenarán dinámicamente con AJAX --}}
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Asignar Permiso</button>
            <a href="{{ url('/rolxpermiso-index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rol_id').on('change', function() {
            const rolId = $(this).val();
            const permisoSelect = $('#permiso_id');
            
            permisoSelect.empty().append('<option value="">Cargando permisos...</option>');

            if (rolId) {
                $.ajax({
                    url: `/rolxpermiso/permisos-disponibles/${rolId}`,
                    method: 'GET',
                    success: function(data) {
                        permisoSelect.empty();
                        if (data.length > 0) {
                            permisoSelect.append('<option value="">Seleccione un permiso</option>');
                            data.forEach(permiso => {
                                permisoSelect.append(
                                    `<option value="${permiso.id}">${permiso.nombre} - ${permiso.descripcion}</option>`
                                );
                            });
                        } else {
                            permisoSelect.append('<option value="">No hay permisos disponibles</option>');
                        }
                    },
                    error: function() {
                        permisoSelect.empty().append('<option value="">Error al cargar permisos</option>');
                    }
                });
            } else {
                permisoSelect.empty().append('<option value="">Seleccione un permiso</option>');
            }
        });
    });
</script>
@endsection
