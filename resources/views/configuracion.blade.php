<?php
session_start();
require_once base_path('resources/views/conexion.php');

$mensaje = '';


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - RisaraldaEcoTurismo</title>
    <link rel="stylesheet" href="{{ asset('css/configuracion.css') }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="titulos">
                <img src="{{ asset('imagenes/iconoecoturismo.jpg') }}" width="70px" alt="Logo">
                <h2 class="risaralda">RisaraldaEcoTurismo</h2>
            </div>
            <a href="pagcentral2.php" class="volver">Volver</a>
        </div>
    </header>

    <main class="config-container">
        <div class="config-menu">
            <div class="menu-item active" data-section="perfil">
                <i class="icon"></i>
                <span>Perfil</span>
            </div>
            <div class="menu-item" data-section="favoritos">
                <i class="icon">❤️</i>
                <span>Favoritos</span>
            </div>
            <div class="menu-item" data-section="seguridad">
                <i class="icon"></i>
                <span>Cambia Tu Contraseña</span>
            </div>
            <div class="menu-item" data-section="Cerrar Sesión">
                <a href="{{ route('pagcentral') }}" id="cerrar-sesion"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="15" viewBox="0 0 48 48"><g fill="none" stroke="#2c3e50" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M23.9917 6H6V42H24"/><path d="M33 33L42 24L33 15"/><path d="M16 23.9917H42"/></g></svg> Cerrar Sesión</a>


            </div>
        </div>

        <div class="config-content">
            <!-- Sección Perfil -->
            <section id="perfil" class="config-section active">
                <h2>Información del Perfil</h2>
                <div class="profile-info">
                    <div class="info-group">
                        <label>Nombre de Usuario</label>
                        <?php if (isset($usuario)): ?>
                                <p id="perfil-username"><?php echo htmlspecialchars($usuario['username']); ?></p>
                            <?php else: ?>
                                <p id="perfil-username">Usuario no encontrado</p>
                            <?php endif; ?>
                    </div>
                    <div class="info-group">
                        <label>Correo Electrónico</label>
                        <?php if (isset($usuario)): ?>
                                <p id="perfil-email"><?php echo htmlspecialchars($usuario['email']); ?></p>
                            <?php else: ?>
                                <p id="perfil-username">Usuario no encontrado</p>
                            <?php endif; ?>
                    </div>
                    <div class="info-group">
                        <label>Fecha de Registro</label>
                        <p id="profile-date">18/04/2025</p>
                    </div>
                </div>
            </section>

            <!-- Sección Favoritos -->
            <section id="favoritos" class="config-section">
                <h2>Lugares Favoritos</h2>
                <div class="favorites-container" id="favorites-list">
                    <!-- Los favoritos se cargarán dinámicamente -->
                </div>
            </section>

            <!-- Sección Seguridad -->
            <section id="seguridad" class="config-section">
                <h2>Cambiar Contraseña</h2>
                <form id="password-form" class="password-change-form">
                    <div class="form-group">
                        <label for="current-password">Contraseña Actual</label>
                        <input type="password" id="current-password" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">Nueva Contraseña (6-20 caracteres)</label>
                        <input type="password" id="new-password" required minlength="6" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirm-password" required minlength="6" maxlength="20">
                    </div>
                    <button type="submit" class="btn-cambiar">Cambiar Contraseña</button>
                </form>
            </section>
        </div>
    </main>

    <script src="{{ asset('js/configuracion.js') }}"></script>
</body>
</html>