@extends('layouts.appWel')

@section('title', 'Rentalo')

@section('content')

<div class="page-banner overlay-dark bg-image" style="background-image: url({{ asset('assetsWelcome/img/aptContact.jpg') }});">
    <div class="hero-section">
        <div class="container text-center wow zoomIn">
            <span class="subhead">Estamos aquí para ayudarte</span>
            <h1 class="display-4">Contacto</h1>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="container">
        <h1 class="text-center wow fadeInUp">¿Tienes preguntas?</h1>
        <p class="lead text-center wow fadeInUp" data-wow-delay="400ms">Ponte en contacto con nosotros y te responderemos lo más pronto posible</p>

        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="contact-info text-center mt-4">
            <form action="{{ route('contact.send') }}" method="POST" class="main-form">
                @csrf
                <div class="row mt-5">
                    <div class="col-12 col-sm-6 py-2 wow fadeInLeft">
                        <input type="text" name="name" class="form-control" placeholder="Nombre completo" required>
                    </div>
                    <div class="col-12 col-sm-6 py-2 wow fadeInRight">
                        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                    </div>
                    <div class="col-12 col-sm-6 py-2 wow fadeInLeft" data-wow-delay="300ms">
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-12 col-sm-6 py-2 wow fadeInRight" data-wow-delay="300ms">
                        <select name="type" id="departement" class="custom-select" required>
                            <option value="" disabled selected>Tipo de Duda</option>
                            <option value="general">General</option>
                            <option value="arrendar">Arrendar</option>
                            <option value="poner-arriendo">Poner en arriendo</option>
                            <option value="otro">Otro...</option>
                        </select>
                    </div>
                    <div class="col-12 py-2 wow fadeInUp" data-wow-delay="300ms">
                        <input type="tel" name="phone" class="form-control" placeholder="Número de contacto" required>
                    </div>
                    <div class="col-12 py-2 wow fadeInUp" data-wow-delay="300ms">
                        <textarea name="message" id="message" class="form-control" rows="6" placeholder="Descripción" required></textarea>
                    </div>
                    <div class="col-12 text-center py-3 wow fadeInUp" data-wow-delay="400ms">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="{{ url('/') }}" class="btn btn-danger">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
