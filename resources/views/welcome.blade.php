@extends('layouts.appWel')

@section('title', 'Rentalo')

@section('content')
<div class="page-banner overlay-dark bg-image" style="background-image: url('{{ asset('assetsWelcome/img/aptWelcome.jpg') }}');">
  <div class="hero-section">
    <div class="container text-center wow zoomIn">
      <span class="subhead">Encuentra tu espacio perfecto con</span>
      <h1 class="display-4">RENTALO</h1>
      <a class="btn btn-primary ml-lg-3" href="{{ url('/search') }}">TU HOGAR TE ESPERA</a>
    </div>
  </div>
</div>

<div class="bg-light">
  <div class="page-section py-3 mt-md-n5 custom-index">
    <div class="container">
      <div class="row justify-content-center">
        <!-- Tarjetas de servicios -->
        <div class="col-md-4 py-3 py-md-0">
          <div class="card-service wow fadeInUp">
            <div class="circle-shape bg-primary text-white">
              <span class="mai-chatbubbles-outline"></span>
            </div>
            <p><span>Publicación</span></p>
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card-service wow fadeInUp">
            <div class="circle-shape bg-primary text-white">
              <span class="mai-eye"></span>
            </div>
            <p><span>Visibilidad</span></p>
          </div>
        </div>
        <div class="col-md-4 py-3 py-md-0">
          <div class="card-service wow fadeInUp">
            <div class="circle-shape bg-primary text-white">
              <span class="mai-search"></span>
            </div>
            <p><span>Búsqueda</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-section pb-0">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 py-3 wow fadeInUp">
          <h1>¡Bienvenido!</h1>
          <p class="text-grey mb-4">En Rentalo proponemos un método alternativo al tradicional para la búsqueda y publicación de residencias para estudiantes.</p>
          <a href="{{ url('/about') }}" class="btn btn-primary">Saber más</a>
        </div>
        <div class="col-lg-6 wow fadeInRight mb-4" data-wow-delay="400ms">
          <div class="img-place custom-img-1">
            <img src="{{ asset('assetsWelcome/img/logB.png') }}" alt="" class="img-fluid" style="width: 65%;">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-section">
  <div class="container">
    <h1 class="text-center mb-5 wow fadeInUp">Algunas Residencias Disponibles</h1>
    <div class="owl-carousel wow fadeInUp" id="inmuebleCarousel">
      <!-- Las tarjetas de inmueble se generarán aquí mediante JavaScript -->
    </div>
    <div class="col-12 text-center mt-4 wow zoomIn">
      <a href="{{ url('/search') }}" class="btn btn-primary">Más Residencias</a>
    </div>
  </div>
</div>

<div class="page-section">
  <div class="container">
    <h1 class="text-center wow fadeInUp">¿Dudas acerca nuestra plataforma?</h1>
    <p class="lead text-center wow fadeInUp" data-wow-delay="400ms">Ponte en contacto con nosotros y te responderemos lo más pronto posible</p>
    
    <div class="contact-info text-center mt-4">
      <div class="row">
        <!-- Contacto -->
        <div class="col-md-4 wow fadeInUp">
          <div class="contact-item">
            <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
              <span class="mai-call" style="font-size: 20px;"></span>
            </div>
            <div class="info mt-3">
              <h2>Teléfono</h2>
              <p>+57 310 777 7771</p>
            </div>
          </div>
        </div>

        <div class="col-md-4 wow fadeInUp" data-wow-delay="400ms">
          <div class="contact-item">
            <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
              <span class="mai-mail" style="font-size: 20px;"></span>
            </div>
            <div class="info mt-3">
              <h2>Email</h2>
              <p>rentalo.atencionalcliente@gmail.com</p>
            </div>
          </div>
        </div>

        <div class="col-md-4 wow fadeInUp" data-wow-delay="800ms">
          <div class="contact-item">
            <div class="icon d-flex align-items-center justify-content-center bg-primary text-white" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
              <span class="mai-location" style="font-size: 20px;"></span>
            </div>
            <div class="info mt-3">
              <h2>Dirección</h2>
              <p><a href="https://maps.app.goo.gl/38wMmf52Bpi4e6bm9" target="_blank" style="text-decoration: none; color: inherit;">Carrera 19 No. 17 - 33, Girardot, Cundinamarca, Colombia</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-12 text-center mt-4 wow zoomIn">
      <a href="{{ url('/contact') }}" class="btn btn-primary">Contáctanos</a>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchInmuebles();
});

function fetchInmuebles(search = '') {
    const url = search ? `/api/inmuebles?q=${search}` : '/api/inmuebles';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const carouselContainer = document.getElementById('inmuebleCarousel');
            carouselContainer.innerHTML = '';

            // Filtrar los inmuebles en base a los criterios después de recibir los datos
            const filteredInmuebles = data.filter(item => 
                item.estado_verificacion === 'aprobado' && 
                item.estado_disponibilidad === 'disponible' && 
                item.estado_publicacion === 'publicado'
            );

            if (filteredInmuebles.length > 0) {
                // Seleccionar solo los primeros 4 inmuebles
                filteredInmuebles.slice(0, 4).forEach(item => {
                    const precioFormateado = formatPrice(item.precio);
                    const personaRol = item.rolxpersona 
                        ? `${item.rolxpersona.persona.nombres} ${item.rolxpersona.persona.apellido}`
                        : 'N/A';

                    const ubicacion = item.ubicacion || {};
                    const direccion = ubicacion.direccion || 'No disponible';
                    const coordenadas = item.coordenadas || 'No disponible';
                    const googleMapsLink = `https://www.google.com/maps?q=${coordenadas}`;
                    const estadoDisponibilidad = item.estado_disponibilidad || 'desconocido';

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
                        <div class="item">
                            <div class="card shadow-sm rounded-lg">
                                <div class="card-header bg-primary text-white text-center font-weight-bold">${item.descripcion}</div>
                                <div class="card-body">
                                    ${imagesCarousel}
                                    <p class="mt-2 mb-1"><strong>Propietario:</strong> ${personaRol}</p>
                                    <p><strong>Dirección:</strong> <a href="${googleMapsLink}" target="_blank">${direccion}</a></p>
                                    <p class="mb-2 mb-1"><strong>Ubicación:</strong> ${ubicacion.ciudad}, ${ubicacion.estado}</p>
                                    <p><strong>Alquiler (Mensual):</strong> ${precioFormateado}</p>
                                    <p><strong>Estado de Disponibilidad:</strong>
                                        ${estadoDisponibilidad === 'disponible' ? 
                                            '<span class="text-success">Disponible</span>' : 
                                            estadoDisponibilidad === 'no_disponible' ? 
                                            '<span class="text-danger">No Disponible</span>' : 
                                            '<span class="text-warning">Estado Desconocido</span>'}
                                    </p>
                                    <div class="text-center">
                                      <a href="{{ url('inmuebles') }}/${item.id}" class="btn btn-primary">Ver detalles</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    `;
                    carouselContainer.insertAdjacentHTML('beforeend', card);
                });

                $('#inmuebleCarousel').owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 2 },
                        1000: { items: 3 }
                    }
                });
            } else {
                carouselContainer.innerHTML = '<p>No hay inmuebles disponibles en este momento.</p>';
            }
        })
        .catch(error => console.error('Error al cargar los inmuebles:', error));
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
