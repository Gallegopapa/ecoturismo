<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
    <title>Login - Risaralda EcoTurismo</title>
</head>
<body>
    <video autoplay loop muted id="video_background" preload="auto" volume="50">
        <source src="{{ asset('imagenes/Videofondo2.mp4') }}" type="video/mp4" />
    </video>
    
    <header>
        <h1> Risaralda EcoTurismo</h1>
    </header>
    
    <div class="contenedor">
        <!-- LADO IZQUIERDO - FORMULARIO -->
        <div class="form-section">
            <form id="formulario" action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="form-title">Bienvenido</div>
                <div class="form-subtitle">Inicia sesión en tu cuenta</div>

                @if(session('status'))
                    <div class="success-message">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="error-message">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required autofocus>
                    @error('email')
                        <div class="error-message" style="margin-top: 5px;">{{ $message }}</div>
                    @enderror
                    @error('credentials')
                        <div class="error-message" style="margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required minlength="6" maxlength="20">
                        <button type="button" id="mostrarContraseña"></button>
                    </div>
                </div>

                <button id="btn" type="submit">Iniciar sesión</button>

                <div class="form-links">
                    <span>¿No tienes cuenta? <a href="{{ url('/registro') }}">Regístrate aquí</a></span>
                    <a href="{{ url('/forgot-password') }}" style="color: #2ecc71;">Olvidé contraseña</a>
                </div>
            </form>
        </div>

        <!-- LADO DERECHO - IMAGEN -->
        <div class="image-section">
            <div class="image-content">
                <!-- El usuario puede reemplazar esta imagen -->
                <img src="{{ asset('imagenes/heroImage.jpg') }}" alt="Risaralda EcoTurismo" style="display: none;">
                
                <h2>Explora la naturaleza</h2>
                <p>Descubre los destinos ecológicos más hermosos de Risaralda. Reserva tu experiencia de ecoturismo hoy mismo.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Risaralda EcoTurismo. Todos los derechos reservados.</p>
    </footer>

    <script type="text/javascript" src="{{ asset('js/login.js') }}"></script>
</body>
</html>