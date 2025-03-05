@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0 text-center">Administrar Tablas de Registro</h1>
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
                        <a href="{{ url('/rolxpersonas-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Roles por Persona
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/permisos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Permisos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/rolxpermisos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Roles por Permisos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/fotos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Fotos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/inventarios-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Inventarios
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/articulos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Articulos
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/articuloxfotos-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Articulo por foto
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/categorias-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Categorias
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/articuloxcategorias-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Articulo por Categoria
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/proveedores-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Proveedores
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/articuloxproveedores-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Articulo por Proveedor
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                        <a href="{{ url('/movimientosinventarios-index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Movimientos del inventario
                            <span class="badge bg-primary rounded-pill">Gestionar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
