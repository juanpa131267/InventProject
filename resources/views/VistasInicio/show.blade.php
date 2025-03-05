@extends('layouts.appWel')

@section('title', 'Inmueble: ' . $inmueble->descripcion)

@section('content')

    <!-- Banner -->
    <div class="page-banner overlay-dark bg-image" style="background-image: url('{{ asset('assetsWelcome/img/aptWindow.jpg') }}');">
        <div class="banner-section">
            <div class="container text-center wow fadeInUp">
                <h1 class="font-weight-normal text-white">{{ $inmueble->descripcion }}</h1>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal -->
    <div class="container my-5">
        <!-- Sección de Detalles del Inmueble -->
        <div class="row">
            <!-- Mapa -->
            <div class="col-md-6 mb-4">
                @if($inmueble->coordenadas)
                    <div id="map" style="height: 520px; width: 100%;"></div>
                @else
                    <p>No hay coordenadas disponibles para mostrar el mapa.</p>
                @endif
            </div>

            <!-- Detalles del Inmueble -->
            <div class="col-md-6 mb-4">
                <div class="collection-content bg-white p-4" style="height: 442px;">
                    <h3 class="element-title text-uppercase mb-4">Detalles de la Residencia</h3>
                    <p><strong>Descripción:</strong> {{ $inmueble->detalles_habitacion }}</p>
                    <p><strong>Dirección:</strong> {{ $inmueble->ubicacion->direccion }}</p>
                    <p><strong>Ciudad:</strong> {{ $inmueble->ubicacion->ciudad }}</p>
                    <p><strong>Departamento:</strong> {{ $inmueble->ubicacion->estado }}</p>
                    <p><strong>País:</strong> {{ $inmueble->ubicacion->pais }}</p>
                    <p><strong>Alquiler (Mensual):</strong> ${{ number_format($inmueble->precio, 0, ',', '.') }}</p>

                    @if($inmueble->coordenadas)
                        <p><strong>Ubicación:</strong>
                            <a href="https://www.google.com/maps?q={{ $inmueble->coordenadas }}" target="_blank" rel="noopener noreferrer">
                                Ver en Google Maps
                            </a>
                        </p>
                    @else
                        <p><strong>Ubicación:</strong> No disponible</p>
                    @endif
                    <p><strong>Fecha de Publicación:</strong> {{ $inmueble->created_at->format('d/m/Y') }}</p>
                    <p><strong>Última Actualización:</strong> {{ $inmueble->updated_at->format('d/m/Y') }}</p>
                    <p><strong>Estado de Disponibilidad:</strong>
                        @if($inmueble->estado_disponibilidad == 'disponible')
                            <span class="text-success">Disponible</span>
                        @elseif($inmueble->estado_disponibilidad == 'no_disponible')
                            <span class="text-danger">No Disponible</span>
                        @else
                            <span class="text-warning">Estado Desconocido</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Registro Fotográfico del Inmueble -->
        <section class="py-5">
            <h3 class="font-weight-bold mb-4 text-center">Registro Fotográfico</h3>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach($inmueble->imagenes as $imagen)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($inmueble->imagenes as $imagen)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ $imagen->url_imagen }}" class="d-block w-100" alt="Imagen del inmueble" style="height: 500px; object-fit: cover; cursor: pointer;" 
                                 data-image-url="{{ $imagen->url_imagen }}" 
                                 data-description="{{ $imagen->descripcion }}" 
                                 onclick="showImage(this)">
                            <div class="carousel-caption d-none d-md-block" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); background-color: rgba(0, 0, 0, 0.6); padding: 10px; color: white; font-size: 18px;">
                                <p>{{ $imagen->descripcion }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </div>
        </section>

        <!-- Modal para imagen expandida -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="expandedImage" src="" alt="Imagen Expandid" class="img-fluid mb-3">
                        <p id="imageDescription" class="text-muted"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Propietario -->
        <section class="collection bg-light py-5">
            <h3 class="font-weight-bold mb-4 text-center">INFORMACIÓN DE CONTACTO</h3>
            <p class="text-center">Si estás interesado en esta residencia, puedes contactar al propietario para obtener más información.</p>
            <div class="row">
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="contact-item">
                        <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
                            <span class="mai-person" style="font-size: 20px;"></span>
                        </div>
                        <div class="info mt-3 text-center">
                            <h4>Publicado por</h4>
                            <p>{{ $inmueble->rolxpersona->persona->usuario->login }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="contact-item">
                        <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
                            <span class="mai-call" style="font-size: 20px;"></span>
                        </div>
                        <div class="info mt-3 text-center">
                            <h4>Teléfono</h4>
                            <p>{{ $inmueble->rolxpersona->persona->telefono }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="contact-item">
                        <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
                            <span class="mai-mail" style="font-size: 20px;"></span>
                        </div>
                        <div class="info mt-3 text-center">
                            <h4>Email</h4>
                            <p>{{ $inmueble->rolxpersona->persona->correo }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

        <!-- Botones de Acción -->
        <div class="col-12 text-center mt-4 wow zoomIn">
            <a href="{{ url('/search') }}" class="btn btn-primary">Volver a la búsqueda</a>
        </div>
    </div>

    <!-- Cargar el script de Mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.12.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.12.0/mapbox-gl.css" rel="stylesheet" />

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoicmVudGFsLW1hcCIsImEiOiJjbTM2cmJkeGcwOG05MmtvY2J3N3VkYzloIn0.GhOqMCnv1z8lH7OSceekJQ';

        function initMap() {
            var coordenadas = "{{ $inmueble->coordenadas }}";
            if(coordenadas) {
                var coordsArray = coordenadas.split(',');
                var lat = parseFloat(coordsArray[0]);
                var lng = parseFloat(coordsArray[1]);

                var map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [lng, lat],
                    zoom: 15
                });

                var marker = new mapboxgl.Marker()
                    .setLngLat([lng, lat])
                    .addTo(map);
            }
        }

        window.onload = initMap;

        // Función para mostrar la imagen en el modal
        function showImage(element) {
            var imageUrl = element.getAttribute('data-image-url');
            var description = element.getAttribute('data-description');
            
            // Actualizar el modal con la imagen seleccionada
            var expandedImage = document.getElementById('expandedImage');
            var imageDescription = document.getElementById('imageDescription');
            expandedImage.src = imageUrl;
            imageDescription.textContent = description;

            // Mostrar el modal
            $('#imageModal').modal('show');
        }
    </script>
@endsection