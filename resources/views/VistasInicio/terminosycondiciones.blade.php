@extends('layouts.appWel')

@section('title', 'Términos y Condiciones | RENTALO')

@section('content')

<div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptAbout.jpg') }});">
    <div class="banner-section">
        <div class="container text-center wow fadeInUp">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb breadcrumb-dark bg-transparent justify-content-center py-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Términos y Condiciones</li>
                </ol>
            </nav>
        </div> <!-- .container -->
    </div> <!-- .banner-section -->
</div> <!-- .page-banner -->

<div class="container py-4">
    {{-- Encabezado con ícono estilizado --}}
    <div class="text-center mb-4">
        <div class="container text-center my-4 wow fadeInRight">
                  <img src="{{ asset('assetsWelcome/img/log.png') }}" alt="Logo" class="img-fluid" style="width: 17%;">
        </div>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Tarjeta de Términos y Condiciones --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0"><strong>Términos y Condiciones de Uso</strong></h5>
        </div>
        <div class="card-body">
            {{-- Definiciones --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>1. Definiciones</strong></h5>
                <ul>
                    <li><strong>Plataforma</strong>: El sitio web RENTALO que facilita la publicación y búsqueda de residencias estudiantiles.</li>
                    <li><strong>Usuario</strong>: Cualquier persona que utilice la Plataforma, ya sea para publicar propiedades o para buscar opciones de alquiler.</li>
                    <li><strong>Propietario</strong>: Usuario registrado que publica un anuncio de propiedad en la Plataforma.</li>
                    <li><strong>Buscador de Residencias</strong>: Usuario que busca residencias disponibles a través de la Plataforma.</li>
                </ul>
            </div>

            {{-- Uso de la Plataforma --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>2. Uso de la Plataforma</strong></h5>
                <p>RENTALO es un espacio para la publicación de residencias y no se involucra en transacciones ni contratos. <strong> La responsabilidad sobre la veracidad de la información y la negociación entre propietarios y buscadores recae exclusivamente en ellos. </strong></p>
            </div>

            {{-- Obligaciones del Propietario --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>3. Obligaciones y Responsabilidades del Propietario</strong></h5>
                <ul>
                    <li><strong>Publicación de Información:</strong> El propietario es responsable de la veracidad y precisión de la información publicada en la Plataforma. RENTALO no valida la exactitud de los datos.</li>
                    <li><strong>Condiciones de Alquiler:</strong> RENTALO no interviene en los acuerdos de alquiler entre propietarios y arrendatarios. Todas las condiciones y términos del alquiler deben ser establecidos directamente entre las partes.</li>
                </ul>
            </div>

            {{-- Obligaciones del Buscador de Residencias --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>4. Obligaciones y Responsabilidades del Buscador de Residencias</strong></h5>
                <ul>
                    <li><strong>Uso de la Información:</strong> El buscador debe verificar la exactitud de la información antes de tomar decisiones. RENTALO no garantiza la calidad de las residencias ni la veracidad de los anuncios.</li>
                    <li><strong>Interacciones con Propietarios:</strong> Las interacciones entre el buscador y el propietario son de su exclusiva responsabilidad. RENTALO no es responsable de la ejecución de acuerdos o de cualquier conflicto que surja.</li>
                </ul>
            </div>

            {{-- Limitación de Responsabilidad --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>5. Limitación de Responsabilidad</strong></h5>
                <p>RENTALO no asume responsabilidad alguna por los daños, pérdidas o inconvenientes derivados de la información publicada en la plataforma. El uso del sitio es bajo su propio riesgo, y los usuarios son responsables de sus interacciones.</p>
            </div>

            {{-- Responsabilidad del Usuario --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>6. Responsabilidad del Usuario por Contenido Publicado</strong></h5>
                <ul>
                    <li><strong>Contenido Propio:</strong> El usuario es responsable del contenido que publique. RENTALO no se hace responsable de contenido inapropiado o ilegal. Cualquier contenido que infrinja derechos será removido.</li>
                </ul>
            </div>

            {{-- Modificaciones de los Términos --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>7. Modificaciones de los Términos y Condiciones</strong></h5>
                <p>RENTALO se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones serán publicadas en este sitio web y entrarán en vigor de inmediato. Es responsabilidad del usuario revisar periódicamente estos términos.</p>
            </div>

            {{-- Privacidad y Protección de Datos --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>8. Privacidad y Protección de Datos</strong></h5>
                <p>RENTALO recopila y procesa los datos personales de acuerdo con su Política de Privacidad, en cumplimiento con la legislación colombiana sobre protección de datos <a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=49981" target="_blank" class="text-primary"> (Ley 1581 de 2012)</a>.</p>
            </div>

            {{-- Aceptación de los Términos --}}
            <div class="mb-4">
                <h5 class="mb-3"><strong>9. Aceptación de los Términos y Condiciones</strong></h5>
                <p>Al utilizar la Plataforma, el usuario confirma su aceptación de estos Términos y Condiciones.</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp text-center mt-5">
                    <a href="{{ url('/') }}" class="btn btn-primary">INICIO</a>
                </div>
    </div>
</div>

@endsection
