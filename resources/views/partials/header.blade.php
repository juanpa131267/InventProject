<header>
  <meta name="csrf-token" content="{{ csrf_token() }}"></meta>
  <!-- Topbar con información de contacto y redes sociales -->
  <div class="topbar bg-light py-2">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-sm-8">
          <div class="site-info d-flex align-items-center">
            <a href="tel:+573107777771" class="text-dark mr-3">
              <span class="mai-call text-primary"></span> +57 310 777 7771
            </a>
            <span class="divider">|</span>
            <a href="mailto:rentalo.atencionalcliente@gmail.com" class="text-dark mx-3">
              <span class="mai-mail text-primary"></span> rentalo.atencionalcliente@gmail.com
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar principal -->
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('assetsWelcome/img/logA.png') }}" alt="Logo" class="img-fluid" style="width: 40px; margin-right: 10px;">
        <span class="text-primary">REN</span>TALO
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupport" aria-controls="navbarSupport" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupport">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">Acerca de</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contacto</a></li>

          @guest
            <li class="nav-item">
              <a class="btn btn-primary ml-lg-3" href="{{ url('/login') }}">Ingresar</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary ml-lg-3" href="{{ url('/register') }}">Registrarse</a>
            </li>
          @endguest

          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                {{ auth()->user()->login }} 
                ({{ auth()->user()->persona->roles->first()->rol->descripcion ?? 'Sin rol' }})
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ url('/perfil') }}">Mi Perfil</a>
                @if(auth()->user()->tienePermiso('acceso_CRUD'))
                  <a class="dropdown-item" href="{{ url('/administrar-tablas') }}">Administrar Tablas CRUD</a>
                @endif
                @if(auth()->user()->tienePermiso('verificar_publicacion'))
                  <a class="dropdown-item" href="{{ url('/verificar-publicaciones') }}">Verificar Publicaciones</a>
                @endif
                <a class="dropdown-item" href="{{ url('/crear-publicacion') }}">Crear Publicación</a>
                <a class="dropdown-item" href="{{ url('/administrar-publicaciones') }}">Administrar Tus Publicaciones</a>
                <a class="dropdown-item" href="{{ url('/logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>
</header>
