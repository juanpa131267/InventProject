@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Administrar Permisos para el Rol: <strong>{{ $rol->DESCRIPCION }}</strong></h1>

        <form action="{{ route('rolxpermisos.update', $rol->ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Permiso</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisos as $permiso)
                            <tr>
                                <td>{{ $permiso->ID }}</td>
                                <td>{{ $permiso->NOMBRE }}</td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" name="permisos[]" value="{{ $permiso->ID }}"
                                            id="permiso_{{ $permiso->ID }}" {{ in_array($permiso->ID, $permisosAsignados) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Botones de acción --}}
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('rolxpermisos.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
