<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Rentalo</title>
    <link rel="stylesheet" href="{{ asset('assetsWelcome/CSS_S/estilos.css') }}"/>
</head>
<body>
    <div class="login-box">
        <h2>INICIAR SESIÓN</h2>
        
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first('login') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="user-box">
                <input type="text" name="login" required>
                <label>Usuario</label>
            </div>

            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Contraseña</label>
                <div class="button-container">
                    <a href="#" class="link-button" onclick="event.preventDefault(); this.closest('form').submit();">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Ingresar
                    </a>
                    <a href="{{ url('/') }}" class="link-button cancel-button">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Cancelar
                    </a>
                </div>
        </form>
    </div>
</body>
</html>
