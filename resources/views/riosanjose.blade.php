<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Río San José - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Río San José</h1>
            <p class="subtitulo">Pereira, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/sanjose.webp') }}" alt="Río San José" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/sanjose2.png') }}" alt="Vista río" class="miniatura">
                <img src="{{ asset('imagenes/sanjose3.jpg') }}" alt="Paisaje" class="miniatura">
                <img src="{{ asset('imagenes/sanjose4.webp') }}" alt="Entorno" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>El Río San José es uno de los tesoros naturales de Pereira, ofreciendo un refugio refrescante con sus aguas cristalinas y entorno natural. Este río es perfecto para actividades al aire libre, como caminatas, picnics y fotografía, rodeado de exuberante vegetación y un clima agradable.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Aguas cristalinas</li>
                    <li> Vegetación nativa</li>
                    <li> Zonas de baño</li>
                    <li> Aire puro</li>
                    <li> Puntos fotográficos</li>
                    <li> Paisajes naturales</li>
                    <li> Senderos naturales</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario recomendado:</span>
                        <span class="valor">7:00 AM - 5:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Todos los días</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Entrada:</span>
                        <span class="valor">Gratuita</span>
                    </div>
                </div>
            </div>

            <div class="restaurantes">
                <h2>Restaurantes cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Mirador Campestre</strong>
                            <ul>
                                <li>Platos típicos</li>
                                <li>Sancocho tradicional</li>
                                <li>Vistas panorámicas</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Rioja Restaurante Campestre</strong>
                            <ul>
                                <li>Ambiente campestre</li>
                                <li>Comida tradicional</li>
                                <li>Entorno natural</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Mrs. Coffee</strong>
                            <ul>
                                <li>Bebidas especiales</li>
                                <li>Repostería artesanal</li>
                                <li>Ambiente acogedor</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Patrono Cerritos</strong>
                            <ul>
                                <li>Ambiente moderno</li>
                                <li>Platos tradicionales</li>
                                <li>Ubicación estratégica</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Octavo Restaurante Giratorio</strong>
                            <ul>
                                <li>Vista 360°</li>
                                <li>Hotel Sonesta</li>
                                <li>Experiencia única</li>
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
                            <strong>Hacienda Hotel San José</strong>
                            <ul>
                                <li>Hotel boutique 5 estrellas</li>
                                <li>Piscina y spa</li>
                                <li>Restaurante propio</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Rincón San José</strong>
                            <ul>
                                <li>Camping natural</li>
                                <li>Contacto con naturaleza</li>
                                <li>Experiencia única</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Raíces del Guadual</strong>
                            <ul>
                                <li>Alojamiento rural</li>
                                <li>15 minutos del centro</li>
                                <li>Comodidades modernas</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Entre Los Árboles Hotel</strong>
                            <ul>
                                <li>Hotel boutique</li>
                                <li>Vereda Mundo Nuevo</li>
                                <li>Servicios premium</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel El Opita</strong>
                            <ul>
                                <li>Ubicación céntrica</li>
                                <li>Económico</li>
                                <li>Fácil acceso</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Llevar calzado adecuado para agua</li>
                    <li> Protector solar biodegradable</li>
                    <li> Snacks y agua</li>
                    <li> Ropa de cambio</li>
                    <li> No dejar residuos</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.873070386995!2d-75.6482596265059!3d4.79182334086394!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3886f29fd7d645%3A0x8c367d29ad375366!2sRio%20San%20Jos%C3%A9%20Pereira%20(Risaralda)!5e0!3m2!1ses!2sco!4v1745441598180!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('paraisosacuaticos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Río San José">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>