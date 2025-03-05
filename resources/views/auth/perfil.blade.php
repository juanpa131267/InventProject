@extends('layouts.appWel')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    {{-- Encabezado con ícono estilizado --}}
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block mb-2">
            <div class="circle-shape bg-primary text-white p-4 shadow" style="width: 100px; height: 100px; border-radius: 50%;">
                <i class="mai-person fs-2"></i>
            </div>
        </div>
        <h2 class="mb-0 mt-2">Perfil de Usuario</h2>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Tarjeta de perfil --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Información Personal</h5>
        </div>
        <div class="card-body">
            {{-- Información Personal --}}
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    <p class="mb-0"><strong>Nombres:</strong> <span class="text-muted">{{ $usuario->persona->nombres }}</span></p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    <p class="mb-0"><strong>Apellido:</strong> <span class="text-muted">{{ $usuario->persona->apellido }}</span></p>
                </div>
            </div>

            {{-- Correo y Teléfono --}}
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-envelope-at text-success me-2"></i>
                    <p class="mb-0"><strong>Correo:</strong> <span class="text-muted">{{ $usuario->persona->correo }}</span></p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-telephone-fill text-warning me-2"></i>
                    <p class="mb-0"><strong>Teléfono:</strong> <span class="text-muted">{{ $usuario->persona->telefono }}</span></p>
                </div>
            </div>

            {{-- Nombre de usuario y Cédula --}}
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-person-badge text-info me-2"></i>
                    <p class="mb-0"><strong>Usuario:</strong> <span class="text-muted">{{ $usuario->login }}</span></p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-credit-card text-danger me-2"></i>
                    <p class="mb-0"><strong>Cédula:</strong> 
                        <span class="badge" style="background-color: #6c757d; color: #fff;">{{ $usuario->persona->cedula }}</span>
                    </p>
                </div>
            </div>

            {{-- Departamento y Municipio --}}
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-geo-alt text-dark me-2"></i>
                    <p class="mb-0"><strong>Departamento:</strong> 
                        <span class="text-muted">{{ $usuario->persona->municipio->departamento->departamento ?? 'No disponible' }}</span>
                    </p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-geo-fill text-secondary me-2"></i>
                    <p class="mb-0"><strong>Municipio:</strong> 
                        <span class="text-muted">{{ $usuario->persona->municipio->municipio ?? 'No disponible' }}</span>
                    </p>
                </div>
            </div>

            {{-- Rol y Fecha de Registro --}}
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-briefcase text-primary me-2"></i>
                    <p class="mb-0"><strong>Rol:</strong> 
                        <span class="badge" style="background-color: #00D9A5; color: #fff;">
                            {{ $usuario->persona->roles->first()->rol->descripcion ?? 'Sin rol asignado' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <i class="bi bi-calendar-check-fill text-success me-2"></i>
                    <p class="mb-0"><strong>Fecha de Registro:</strong> 
                        <span class="text-muted">{{ $usuario->created_at->format('d-m-Y') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones del perfil --}}
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('perfil.edit') }}" class="btn btn-primary mx-2">
            <i class="bi bi-pencil-square"></i> Editar Perfil
        </a>
        <a href="{{ url('/') }}" class="btn btn-danger mx-2">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</div>
@endsection
