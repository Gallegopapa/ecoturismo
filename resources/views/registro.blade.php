<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
    <title>Registro - Risaralda EcoTurismo</title>
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
            <form id="formulario" action="{{ route('registro.store') }}" method="POST" style="overflow-y: auto; max-height: 95vh;">
                @csrf
                
                <div class="form-title">Crear Cuenta</div>
                <div class="form-subtitle">Únete a nuestra comunidad de viajeros</div>

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
                    <label for="name">Nombre de Usuario</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Tu nombre de usuario" required>
                    @error('name')
                        <div class="error-message" style="margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required>
                    @error('email')
                        <div class="error-message" style="margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6" maxlength="20">
                        <button type="button" id="mostrarContraseña"></button>
                    </div>
                    @error('password')
                        <div class="error-message" style="margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" required minlength="6" maxlength="20">
                        <button type="button" id="mostrarContraseña2"></button>
                    </div>
                </div>

                <div class="form-group" style="flex-direction: row; align-items: center; margin-bottom: 20px;">
                    <input type="checkbox" id="terminos" name="terminos" required style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="terminos" style="margin-left: 8px; margin-bottom: 0;">
                        Acepto los <a href="#" style="color: #2ecc71;">términos y condiciones</a>
                    </label>
                </div>

                <button id="btn" type="submit">Crear Cuenta</button>

                <div class="form-links" style="justify-content: center;">
                    <span>¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión aquí</a></span>
                </div>
            </form>
        </div>

        <!-- LADO DERECHO - IMAGEN -->
        <div class="image-section">
            <div class="image-content">
                <!-- El usuario puede reemplazar esta imagen -->
                <img src="{{ asset('imagenes/heroImage.jpg') }}" alt="Risaralda EcoTurismo" style="display: none;">
                
                <h2>Únete a Nosotros</h2>
                <p>Acceso a reservas exclusivas, recomendaciones personalizadas y una comunidad de viajeros apasionados por la naturaleza.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Risaralda EcoTurismo. Todos los derechos reservados.</p>
    </footer>

    <script type="text/javascript" src="{{ asset('js/login.js') }}"></script>
</body>
</html>