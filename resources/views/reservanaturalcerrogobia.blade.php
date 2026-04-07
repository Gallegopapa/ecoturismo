<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Natural Cerro Gobia - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Reserva Natural Cerro Gobia</h1>
            <p class="subtitulo">Quinchía, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/gobia7.jpg') }}" alt="Cerro Gobia" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/gobia.jpg') }}" alt="Vista panorámica" class="miniatura">
                <img src="{{ asset('imagenes/gobia3.jpg') }}" alt="Senderos" class="miniatura">
                <img src="{{ asset('imagenes/gobia5.jpg') }}" alt="Paisaje" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>La Reserva Natural Cerro Gobia es un importante punto natural ubicado cerca de Quinchía, Risaralda. Este lugar ofrece una experiencia única de senderismo con vistas panorámicas espectaculares, biodiversidad local y la oportunidad de conectar con la naturaleza en un entorno preservado.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Cerro natural</li>
                    <li> Flora nativa</li>
                    <li> Avistamiento de aves</li>
                    <li> Senderos ecológicos</li>
                    <li> Puntos fotográficos</li>
                    <li> Vistas panorámicas</li>
                    <li> Aire puro</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario:</span>
                        <span class="valor">6:00 AM - 5:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Todos los días</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Entrada:</span>
                        <span class="valor">Consultar guías locales</span>
                    </div>
                </div>
            </div>

            <div class="restaurantes">
                <h2>Restaurantes cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Emilia Café Club</strong>
                            <ul>
                                <li>Café y cervecería</li>
                                <li>Ambiente acogedor</li>
                                <li>Comida ligera</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Friducha</strong>
                            <ul>
                                <li>Comida rápida gourmet</li>
                                <li>Especialidad en gorditas</li>
                                <li>Presentación única</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Merced Latino Restaurante</strong>
                            <ul>
                                <li>Comida fusión</li>
                                <li>Sabores tradicionales</li>
                                <li>Toques modernos</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>El Balcón de las Delicias</strong>
                            <ul>
                                <li>Comida típica</li>
                                <li>Ambiente familiar</li>
                                <li>Platos regionales</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="alojamientos">
                <h2>Alojamientos cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Hotel Quinchía</strong>
                            <ul>
                                <li>Ubicación céntrica</li>
                                <li>Wi-Fi gratuito</li>
                                <li>Tours organizados</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Finca Hotel Los Guayacanes</strong>
                            <ul>
                                <li>Alojamiento rural</li>
                                <li>Entorno natural</li>
                                <li>Ambiente tranquilo</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Alojamientos en Airbnb</strong>
                            <ul>
                                <li>Opciones variadas</li>
                                <li>Casas de campo</li>
                                <li>Estancias personalizadas</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Calzado para senderismo</li>
                    <li> Protector solar</li>
                    <li> Agua suficiente</li>
                    <li> Mochila ligera</li>
                    <li> Contactar guías locales</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.4912400342955!2d-75.74004917326283!3d5.341719286929363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e47a70d75584475%3A0xe657b0867912d6a3!2sReserva%20Natural%20Cerro%20Gobia!5e0!3m2!1ses!2sco!4v1745451888410!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('lugaresmontañosos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Reserva Natural Cerro Gobia">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>