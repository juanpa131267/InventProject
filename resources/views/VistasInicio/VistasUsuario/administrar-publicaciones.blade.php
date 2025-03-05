@extends('layouts.appWel')

@section('title', 'Administrar Mis Inmuebles')

@section('content')
    <!-- Banner de la página -->
    <div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptWindow.jpg') }});">
        <div class="banner-section">
            <div class="container text-center wow fadeInUp">
                <h1 class="font-weight-normal">Mis Inmuebles</h1>
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
                <a href="{{ url('/RestoreUserPost') }}" class="btn btn-danger ml-2">
                    <i class="mai-trash fs-2 text-white"></i> Recuperar
                </a>
            </div>
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

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
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

        const filteredInmuebles = inmuebles.filter(item => item.rolxpersona.persona_id === {{ Auth::user()->persona->id }});

        if (filteredInmuebles.length > 0) {
            filteredInmuebles.forEach(item => {
                const precioFormateado = formatPrice(item.precio);
                const personaRol = item.rolxpersona ? `${item.rolxpersona.persona.nombres} ${item.rolxpersona.persona.apellido}` : 'N/A';
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
                        </div>
                    `;
                } else {
                    imagesCarousel = `<img src="{{ asset('assetsWelcome/img/default-image.jpg') }}" class="d-block w-100" alt="Imagen predeterminada" style="height: 200px; object-fit: cover;">`;
                }

                const card = `
                    <div class="col-md-4 mb-4" id="inmueble-card-${item.id}">
                        <div class="card shadow-sm rounded-lg">
                            <div class="card-header p-0">${imagesCarousel}</div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${item.descripcion}</h5>
                                <p class="card-text">
                                    <strong>Dirección:</strong>
                                    <a href="${googleMapsLink}" target="_blank">${direccion}, ${ciudad}, ${estado}, ${pais}</a><br>
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
                                        estadoVerificacion === 'rechazado' ? 
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
                                <!-- Botones -->
                                <div class="mt-auto">
                                    <div class="mb-2">
                                        <a href="{{ url('inmuebles-administrar') }}/${item.id}" class="btn btn-primary w-100">
                                            <i class="mai-eye fs-2 text-white"></i> Ver
                                        </a>
                                    </div>
                                    <div class="mb-2">
                                        <a href="{{ url('inmuebles-administrar') }}/${item.id}/edit" class="btn btn-info w-100">
                                            <i class="mai-pencil fs-2 text-white"></i> Editar
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{ url('inmuebles-administrar') }}/${item.id}" method="POST" style="display:inline;" onsubmit="deleteInmueble(event, ${item.id})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="mai-trash fs-2 text-white"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                mosaicContainer.innerHTML += card;
            });
        } else {
            mosaicContainer.innerHTML = '<div class="col-12 text-center">No se encontraron inmuebles para administrar.</div>';
        }
    }

    function deleteInmueble(event, inmuebleId) {
        event.preventDefault();

        // Confirmación de eliminación
        if (confirm('¿Estás seguro de que quieres eliminar este inmueble?')) {
            fetch(`/inmuebles-administrar/${inmuebleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    document.getElementById(`inmueble-card-${inmuebleId}`).remove(); // Eliminar la card
                    alert('Se ha eliminado correctamente');
                } else {
                    alert('Error al eliminar el inmueble');
                }
            })
            .catch(error => {
                console.error('Error al eliminar el inmueble:', error);
                alert('Error al eliminar el inmueble');
            });
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
