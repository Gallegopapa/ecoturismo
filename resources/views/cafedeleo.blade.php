<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café de Leo - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Café de Leo</h1>
            <p class="subtitulo">Santa Rosa de Cabal, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/cafedeleo.webp') }}" alt="Café de Leo" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/cafedeleo2.jpg') }}" alt="Interior del café" class="miniatura">
                <img src="{{ asset('imagenes/cafedeleo3.jpg') }}" alt="Productos" class="miniatura">
                <img src="{{ asset('imagenes/cafedeleo4.jpeg') }}" alt="Ambiente" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>Café de Leo es un acogedor establecimiento en Santa Rosa de Cabal que ofrece una experiencia única con café de la región, gastronomía local y bebidas especiales. El lugar se destaca por su ambiente cálido, atención personalizada y la calidad de sus productos derivados del café.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Café especial de origen</li>
                    <li> Repostería artesanal</li>
                    <li> Ambiente acogedor</li>
                    <li> Bebidas especiales</li>
                    <li> Gastronomía local</li>
                    <li> Atención personalizada</li>
                    <li> Música ambiental</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario:</span>
                        <span class="valor">8:00 AM - 8:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Martes a Domingo</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Rango de precios:</span>
                        <span class="valor">$5.000 - $25.000 COP</span>
                    </div>
                </div>
            </div>

            <div class="restaurantes">
                <h2>Restaurantes cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Mordiscos Tapas</strong>
                            <ul>
                                <li>Tapas y platos para compartir</li>
                                <li>Comida ligera</li>
                                <li>Ambiente casual</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurantes del Centro</strong>
                            <ul>
                                <li>Comida típica colombiana</li>
                                <li>Platos internacionales</li>
                                <li>Diversas opciones gastronómicas</li>
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
                            <strong>Hotel Los Cristales</strong>
                            <ul>
                                <li><strong>Distancia: </strong>360 metros</li>
                                <li>Habitaciones cómodas</li>
                                <li>Servicios modernos</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Cosecha Hostal</strong>
                            <ul>
                                <li><strong>Distancia: </strong>1.2 km</li>
                                <li>Ambiente tranquilo</li>
                                <li>Entorno montañoso</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel Casa Confort</strong>
                            <ul>
                                <li><strong>Distancia: </strong>1.4 km</li>
                                <li>Entorno rural</li>
                                <li>Comodidades modernas</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Reservar para grupos grandes</li>
                    <li> Excelente para fotos</li>
                    <li> Aceptan tarjetas</li>
                    <li> Parqueadero cercano</li>
                    <li> Ideal para reuniones</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.447772281621!2d-75.6197229084994!3d4.864387633706872!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e38822a6389f8bf%3A0x1543dd4b8c5e857c!2sCaf%C3%A9%20de%20Leo.Cafe%20de%20especialidad.Santa%20Rosa%20de%20Cabal!5e0!3m2!1ses!2sco!4v1745096386645!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('territoriosdelcafe2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Café de Leo">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>