@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0 text-center">Administrar Tablas</h1>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ url('/personas-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Personas
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/usuarios-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Usuarios
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/roles-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Roles
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/rolxpersona-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Roles por Persona
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/permisos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Permisos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/rolxpermiso-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Roles por Permisos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/inmuebles-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Inmuebles
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/ubicaciones-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Ubicaciones
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/comentarios-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Comentarios
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/espacios_publicacion-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Espacios de Publicación
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/favoritos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Favoritos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/imagenes_inmueble-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Imágenes de Inmueble
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
