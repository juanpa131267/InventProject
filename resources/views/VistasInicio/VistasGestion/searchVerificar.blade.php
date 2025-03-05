@extends('layouts.appWel')

@section('title', 'Buscar Inmuebles por verificar')

@section('content')
    <!-- Banner de la página -->
    <div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptWindow.jpg') }});">
        <div class="banner-section">
            <div class="container text-center wow fadeInUp">
                <h1 class="font-weight-normal">Listado de Inmuebles por verificar</h1>
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
            <input type="text" id="search-inmueble" class="form-control d-inline-block w-50" placeholder="Buscar por descripción" aria-label="Buscar por">
        </div>

        <div class="row" id="inmueble-mosaic"></div>
    </div>

    <div class="col-12 text-center py-3 wow fadeInUp" data-wow-delay="400ms">
        <a href="{{ url('/') }}" class="btn btn-primary">Inicio</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchInmuebles();
        });

        document.getElementById('search-inmueble').addEventListener('input', function() {
            fetchInmuebles(this.value);
        });

        function fetchInmuebles(search = '') {
            const url = search ? `/api/inmuebles?q=${search}` : '/api/inmuebles';
            console.log('Fetching inmuebles from URL:', url);

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    renderInmuebles(data);
                })
                .catch(error => {
                    console.error('Error al buscar inmuebles:', error);
                    document.getElementById('inmueble-mosaic').innerHTML = '<div class="col-12 text-center">Error al cargar los inmuebles. Intente nuevamente.</div>';
                });
        }

        function renderInmuebles(inmuebles) {
            const mosaicContainer = document.getElementById('inmueble-mosaic');
            mosaicContainer.innerHTML = '';

            // Filtrar inmuebles según los valores "pendiente" y "borrador"
            const filteredInmuebles = inmuebles.filter(item => 
                item.estado_verificacion === 'pendiente' && item.estado_publicacion === 'borrador'
            );

            if (filteredInmuebles.length > 0) {
                filteredInmuebles.forEach(item => {
                    const precioFormateado = formatPrice(item.precio);
                    const personaRol = item.rolxpersona 
                        ? `${item.rolxpersona.persona.nombres} ${item.rolxpersona.persona.apellido}`
                        : 'N/A';

                    const ubicacion = item.ubicacion || {};
                    const direccion = ubicacion.direccion || 'No disponible';
                    const ciudad = ubicacion.ciudad || 'No disponible';
                    const estado = ubicacion.estado || 'No disponible';
                    const pais = ubicacion.pais || 'Colombia';
                    const coordenadas = item.coordenadas || 'No disponible';
                    const googleMapsLink = `https://www.google.com/maps?q=${coordenadas}`;
                    const estadoVerificacion = item.estado_verificacion || 'desconocido';
                    const estadoDisponibilidad = item.estado_disponibilidad || 'desconocido';
                    const estadoPublicacion = item.estado_publicacion || 'desconocido';

                    let imagesCarousel = '';
                    if (item.imagenes && item.imagenes.length > 0) {
                        imagesCarousel = `
                            <div id="carousel-${item.id}" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    ${item.imagenes.map((img, index) => `
                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                            <img src="${img.url_imagen}" class="d-block w-100" alt="Imagen del inmueble" style="height: 200px; object-fit: cover;">
                                        </div>
                                    `).join('')}
                                </div>
                                <a class="carousel-control-prev" href="#carousel-${item.id}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-${item.id}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        `;
                    } else {
                        imagesCarousel = `<img src="{{ asset('assetsWelcome/img/default-image.jpg') }}" class="d-block w-100" alt="Imagen predeterminada" style="height: 200px; object-fit: cover;">`;
                    }

                    const card = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm rounded-lg">
                                <div class="card-header p-0">
                                    ${imagesCarousel}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">${item.descripcion}</h5>
                                    <p class="card-text">
                                        <strong>Dirección:</strong> 
                                        <a href="${googleMapsLink}" target="_blank">
                                            ${direccion}, ${ciudad}, ${estado}, ${pais}
                                        </a><br>
                                        <strong>Alquiler (Mensual):</strong> ${precioFormateado}<br>
                                        <strong>Propietario:</strong> ${personaRol}
                                    </p>
                                    <p><strong>Estado de Disponibilidad:</strong>
                                        ${estadoDisponibilidad === 'disponible' ? 
                                            '<span class="text-success">Disponible</span>' : 
                                            estadoDisponibilidad === 'no_disponible' ? 
                                            '<span class="text-danger">No Disponible</span>' : 
                                            '<span class="text-info">Estado Desconocido</span>'}
                                    </p>
                                    <p><strong>Estado de Verificación:</strong>
                                        ${estadoVerificacion === 'aprobado' ? 
                                            '<span class="text-success">Aprobado</span>' : 
                                            estadoVerificacion === 'pendiente' ? 
                                            '<span class="text-info">Pendiente</span>' :
                                            estadoVerificacion == 'rechazado'?
                                            '<span class="text-danger">Rechazado</span>' :
                                            '<span class="text-info">Estado Desconocido</span>'}
                                    </p>
                                    <p><strong>Estado de Publicación:</strong>
                                        ${estadoPublicacion === 'publicado' ? 
                                            '<span class="text-success">Publicado</span>' : 
                                            estadoPublicacion === 'borrador' ? 
                                            '<span class="text-info">Borrador</span>' :
                                            estadoPublicacion === 'oculto' ? 
                                            '<span class="text-danger">Oculto</span>' :
                                            '<span class="text-info">Estado Desconocido</span>'}
                                    </p>
                                    <div class="text-center">
                                        <a href="{{ url('inmuebles-verificar') }}/${item.id}" class="btn btn-danger">Verificar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    mosaicContainer.innerHTML += card;
                });
            } else {
                mosaicContainer.innerHTML = '<div class="col-12 text-center">No se encontraron inmuebles para verificar.</div>';
            }
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            }).format(price);
        }
    </script>
@endsection
