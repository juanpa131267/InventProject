@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Crear Rol</h1>

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

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            {{-- Campo para la descripción del rol --}}
            <div class="form-group mb-3">
                <label for="DESCRIPCION" class="font-weight-bold">Descripción del Rol</label>
                <input type="text" name="DESCRIPCION" id="DESCRIPCION" class="form-control" placeholder="Ingrese el nombre del rol" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Crear
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
