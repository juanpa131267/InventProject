@extends('layouts.appWel')

@section('title', 'Rentalo')

@section('content')

<div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptAbout.jpg') }});">
    <div class="banner-section">
        <div class="container text-center wow fadeInUp">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb breadcrumb-dark bg-transparent justify-content-center py-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About</li>
                </ol>
            </nav>
            <h1 class="font-weight-normal">About Us</h1>
        </div> <!-- .container -->
    </div> <!-- .banner-section -->
</div> <!-- .page-banner -->

<div class="page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 wow fadeInUp">
                <h1 class="text-center mb-3">BIENVENIDO</h1>
                <div class="text-lg" style="text-align: justify;">
                    <p>RENTALO tiene como objetivo proponer un método alternativo al tradicional para la búsqueda y publicación de residencias para estudiantes universitarios.</p>
                    <p>Nos enfocamos en simplificar y mejorar el proceso de búsqueda y alquiler de residencias adecuadas para estudiantes, brindando una plataforma que ofrece información detallada y accesible sobre las opciones de alojamiento disponibles.</p>
                    <p>¡Encuentra tu espacio perfecto con RENTALO!</p>
                </div>
            </div>

            <div class="container text-center my-4 wow fadeInRight">
                  <img src="{{ asset('assetsWelcome/img/log.png') }}" alt="Logo" class="img-fluid" style="width: 25%;">
          </div>

            <div class="page-section bg-light">
                <div class="container">
                    <h1 class="text-center mb-5 wow fadeInUp">¡Nuestra calidad!</h1>
                    <div class="owl-carousel wow fadeInUp" id="doctorSlideshow">
                        <div class="item">
                            <div class="col-md-4 py-3 wow zoomIn">
                                <div class="card-service">
                                    <div class="circle-shape bg-primary text-white">
                                        <span class="mai-chatbubbles-outline"></span>
                                    </div>
                                    <p><span>Comunicación</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4 py-3 wow zoomIn">
                                <div class="card-service">
                                    <div class="circle-shape bg-primary text-white">
                                        <span class="mai-document"></span>
                                    </div>
                                    <p><span>Información</span> detallada</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4 py-3 wow zoomIn">
                                <div class="card-service">
                                    <div class="circle-shape bg-primary text-white">
                                        <span class="mai-thumbs-up"></span>
                                    </div>
                                    <p>Espacio de <span>publicación</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4 py-3 wow zoomIn">
                                <div class="card-service">
                                    <div class="circle-shape bg-primary text-white">
                                        <span class="mai-search"></span>
                                    </div>
                                    <p><span>Búsqueda</span> detallada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
            <div class="col-lg-8 wow fadeInUp text-center mt-5">
                <a href="{{ url('/') }}" class="btn btn-primary">INICIO</a>
            </div>
        </div> 
    </div> 
</div>

@endsection
