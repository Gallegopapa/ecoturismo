<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Don Manolo - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Café Don Manolo</h1>
            <p class="subtitulo">Vereda El Estanquillo, Pereira</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/cafemanolo5.jpg') }}" alt="Café Don Manolo" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/cafemanolo.jpg') }}" alt="Vista café" class="miniatura">
                <img src="{{ asset('imagenes/cafemanolo2.png') }}" alt="Cultivos" class="miniatura">
                <img src="{{ asset('imagenes/cafemanolo4.jpg') }}" alt="Paisaje" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>Café Don Manolo es una auténtica finca cafetera ubicada en la vereda El Estanquillo de Pereira. Este lugar ofrece una experiencia única donde los visitantes pueden conocer el proceso del café, desde el cultivo hasta la taza, rodeados de impresionantes vistas a las montañas de Risaralda.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Café de origen</li>
                    <li> Cultivos de café</li>
                    <li> Vistas panorámicas</li>
                    <li> Tour del café</li>
                    <li> Atardeceres</li>
                    <li> Aire puro</li>
                    <li> Puntos fotográficos</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario:</span>
                        <span class="valor">8:00 AM - 5:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Martes a Domingo</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Tour del café:</span>
                        <span class="valor">Reserva previa</span>
                    </div>
                </div>
            </div>

            <div class="restaurantes">
                <h2>Restaurantes en Pereira</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Cardinal Comida Peruana de Autor</strong>
                            <ul>
                                <li>Cocina peruana de autor</li>
                                <li>Platos innovadores</li>
                                <li>Sabores auténticos</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Trattoria</strong>
                            <ul>
                                <li>Cocina italiana tradicional</li>
                                <li>Ambiente acogedor</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Latino Cocina Popular</strong>
                            <ul>
                                <li>Platos latinoamericanos</li>
                                <li>Toque moderno</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Kilaba</strong>
                            <ul>
                                <li>Fusión internacional</li>
                                <li>Ambiente contemporáneo</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Río y Carbón</strong>
                            <ul>
                                <li>Parrilla y mariscos</li>
                                <li>Vista al río</li>
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
                            <strong>Hotel GHL Abadía Plaza</strong>
                            <ul>
                                <li><strong>Distancia: </strong>2.7 km</li>
                                <li>Hotel moderno</li>
                                <li>Servicios de calidad</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel Soratama</strong>
                            <ul>
                                <li><strong>Distancia: </strong>2.7 km</li>
                                <li>Ubicación céntrica</li>
                                <li>Fácil acceso a atracciones</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel Luxor Plaza Pereira</strong>
                            <ul>
                                <li><strong>Distancia: </strong>2.7 km</li>
                                <li>Habitaciones confortables</li>
                                <li>Servicios completos</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Finca Hotel Villa Ilusión</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Dosquebradas</li>
                                <li>Entorno natural</li>
                                <li>Ambiente tranquilo</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Acceso en vehículo particular recomendado</li>
                    <li> Reservar con anticipación</li>
                    <li> Calzado cómodo para caminatas</li>
                    <li> Verificar clima antes de visitar</li>
                    <li> Llevar cámara para fotos</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.6084043808623!2d-75.70575742650577!3d4.837108240463014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3880a49642ec8f%3A0x31d2ba5f21b8d96c!2sCaf%C3%A9%20Don%20Manolo!5e0!3m2!1ses!2sco!4v1745092993533!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('territoriosdelcafe2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Café Don Manolo">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>