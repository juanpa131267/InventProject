<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title')</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assetsWelcome/css/maicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsWelcome/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsWelcome/vendor/owl-carousel/css/owl.carousel.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsWelcome/vendor/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsWelcome/css/theme.css') }}">
</head>
<body>
  <!-- Back to top button -->
  <div class="back-to-top"></div>

  <!-- Header -->
  <header>
    @include('partials.header') <!-- Archivo parcial para el encabezado -->
    
  </header>

  <!-- Main content for each page -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="page-footer">
    @include('partials.footer') <!-- Archivo parcial para el pie de página -->
  </footer>

  <!-- JavaScript -->
  <script src="{{ asset('assetsWelcome/js/jquery-3.5.1.min.js') }}"></script>
  <script src="{{ asset('assetsWelcome/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assetsWelcome/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assetsWelcome/vendor/wow/wow.min.js') }}"></script>
  <script src="{{ asset('assetsWelcome/js/theme.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
