@extends('layouts.appWel')

@section('title', 'Recuperar Publicaciones Eliminadas')

@section('content')
    <!-- Banner de la página -->
    <div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptWindow.jpg') }});">
        <div class="banner-section">
            <div class="container text-center wow fadeInUp">
                <h1 class="font-weight-normal">Recuperar Publicaciones Eliminadas</h1>
            </div>
        </div>
    </div>

    <!-- Contenedor principal -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-4 text-center">
            <div class="d-flex justify-content-center align-items-center">
                <input type="text" id="search-inmueble" class="form-control w-50" placeholder="Buscar por descripción" aria-label="Buscar por">
            </div>
        </div>

        <div class="row" id="inmueble-mosaic">
            @foreach($inmuebles as $inmueble)
                <div class="col-md-4 mb-4" id="inmueble-{{ $inmueble->id }}">
                    <div class="card shadow-sm rounded-lg">
                        <!-- Aquí va el carousel de imágenes o una imagen por defecto -->
                        <div class="card-header p-0">
                            @if($inmueble->imagenes->isNotEmpty())
                                <div id="carousel-{{ $inmueble->id }}" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($inmueble->imagenes as $index => $imagen)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ $imagen->url_imagen }}" class="d-block w-100" alt="Imagen del inmueble" style="height: 200px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('assetsWelcome/img/default-image.jpg') }}" class="d-block w-100" alt="Imagen predeterminada" style="height: 200px; object-fit: cover;">
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $inmueble->descripcion }}</h5>
                            <p class="card-text">
                                <strong>Dirección:</strong> 
                                {{ $inmueble->ubicacion->direccion ?? 'No disponible' }}<br>
                                <strong>Alquiler (Mensual):</strong> {{ number_format($inmueble->precio, 0, ',', '.') }} COP<br>
                                <strong>Propietario:</strong> {{ $inmueble->rolxpersona->persona->nombres ?? 'N/A' }} {{ $inmueble->rolxpersona->persona->apellido ?? 'N/A' }}
                            </p>
                            <p><strong>Estado de Publicación:</strong> Eliminado</p>

                            <!-- Botón para restaurar el inmueble -->
                            <form action="{{ route('inmuebles.restore', $inmueble->id) }}" method="POST" style="display:inline;" onsubmit="restoreInmueble(event, {{ $inmueble->id }})">
                                @csrf
                                @method('PUT') <!-- Este método PUT es el que se usa para restaurar -->
                                <button type="submit" class="btn btn-primary w-100">
                                    Restaurar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12 text-center py-3 wow fadeInUp" data-wow-delay="400ms">
        <a href="{{ url('/administrar-publicaciones') }}" class="btn btn-primary">Regresar</a>
    </div>

    <script>
        document.getElementById('search-inmueble').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            let cards = document.querySelectorAll('.col-md-4');

            cards.forEach(card => {
                let description = card.querySelector('.card-title').textContent.toLowerCase();
                if (description.indexOf(search) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function restoreInmueble(event, id) {
            event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

            // Mostrar una confirmación antes de proceder
            if (confirm("¿Estás seguro de que deseas restaurar este inmueble?")) {
                const form = event.target; // El formulario que se está enviando

                // Hacer la solicitud AJAX usando fetch
                fetch(form.action, {
                    method: form.method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(form)
                })
                .then(response => {
                    // Asegurarse de que la respuesta sea JSON
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json(); // Asumiendo que el controlador devuelve JSON
                })
                .then(data => {
                    // Mostrar mensaje de éxito o error
                    if (data.success) {
                        alert('Inmueble restaurado correctamente');

                        // Remover el card de la interfaz
                        const inmuebleCard = document.getElementById(`inmueble-${id}`);
                        inmuebleCard.style.display = 'none'; // Oculta el card restaurado

                    } else {
                        alert('Ocurrió un error al restaurar el inmueble');
                    }
                })
            }
        }

</script>
@endsection
