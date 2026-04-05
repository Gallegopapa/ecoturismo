<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lago de La Pradera - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="../imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Lago de La Pradera</h1>
            <p class="subtitulo">Dosquebradas, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('../imagenes/Lago.jpeg') }}" alt="Lago de La Pradera" class="imagen-principal">
            <div class="miniaturas">
                <!-- Añade más imágenes del lugar -->
                <img src="{{ asset('../imagenes/lago-2.jpg') }}" alt="Vista del lago" class="miniatura">
                <img src="{{ asset('../imagenes/lago-3.png') }}" alt="Área de descanso" class="miniatura">
                <img src="{{ asset('../imagenes/lago-4.png') }}" alt="Actividades recreativas" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>El Lago de La Pradera es un hermoso lago artificial ubicado en el corazón de Dosquebradas, Risaralda. Este espacio natural se ha convertido en uno de los principales atractivos turísticos de la región, ofreciendo un perfecto equilibrio entre naturaleza y recreación.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <!-- <li> Lago artificial con área aproximada de X hectáreas</li> -->
                    <li> Actividades acuáticas disponibles</li>
                    <li> Senderos peatonales alrededor del lago</li>
                    <li> Zonas verdes para picnic y descanso</li>
                    <li> Parqueadero disponible</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario:</span>
                        <span class="valor">8:00 AM - 6:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Lunes a Domingo</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Entrada:</span>
                        <span class="valor">Gratuita</span>
                    </div>
                </div>
            </div>
            <div class="restaurantes">
                <div class="info">

                    <h2 class="title1">Restaurantes Y Servicios</h2>
                    <ol>
                        <li>
                        <strong>El Cubo Restaurante al aire libre</strong>
                        <ul>
                            <li><strong>Ubicacion: </strong> Dentro del Parque Lago la Pradera</li>
                            <li>Ofrece desayunos y almuerzos balanceados en un entorno natural.</li>
                            <li>Ideal para disfrutar de una comida al aire libre.</li>
                        </ul>
                    </li>
                    <br>
                    <li>
                        <strong>Exotic Sabor</strong>
                        <ul>
                            <li><strong>Ubicación: </strong>Cerca del Lago La Pradera.</li>
                            <li>Especializado en comida típica y tradicional colombiana.</li>
                            <li><strong>Horario: </strong>Martes a domingo de 8:00 a.m. a 4:00 p.m.</li>
                            <li><strong>Contacto: </strong><a href="tel:3125419898">3125419898</a></li>
                        </ul>
                    </li>
                    <br>
                    <li>
                        <strong>Mi Pequeño Refugio Pradera</strong>
                        <ul>
                            <li><strong>Ubicación: </strong>Próximo al Lago La Pradera</li>
                            <li>Ofrece desayunos, comidas, cenas y bebidas en un ambiente acogedor.</li>
                            <li>Cocina colombiana con precios moderados.</li>
                        </ul>
                        
                    </li>
                    <br>
                    <li>
                        <strong>La Chicharronería de María</strong>
                        <ul>
                            <li><strong>Ubicación: </strong>Frente a la entrada del Parque Lago La Pradera, Diagonal 24</li>
                            <li>Especialidad en chicharrones y platos típicos.</li>
                            <li><strong>Contacto: </strong><a href="tel:3154124846">3154124846</a></li>
                        </ul>
                    </li>
                    <br>
                    <li>
                        <strong>Delicias Al Wok</strong>
                        <ul>
                            <li><strong>Ubicación: </strong>Calle 21 #25-82, La Pradera, Dosquebradas.</li>
                            <li>Restaurante de comida china con especialidad en arroz al wok.</li>
                            <li>Ofrece servicio a domicilio.</li>
                        </ul>
                    </li>
                </ol>
            </div>
            </div>
            <div class="alojamientos">
                <div class="info">
                    <h2>Alojamientos Cercanos</h2>
                    <ol>
                        <li>
                            <strong>Hotel Pereira Plaza</strong>
                            <ul>
                                <li><strong>Tipo: </strong>Hotel 3 estrellas</li>
                                <li><strong>Servicios: </strong>Wifi gratuito, recepción 24h</li>
                                <li><strong>Dirección: </strong>Carrera 4 # 22-43, Pereira</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Lagos De La Pradera By Parceros Group</strong>
                            <ul>
                                <li><strong>Tipo: </strong>Alojamiento Familiar</li>
                                <li><strong>Servicios: </strong>Cocina, piscina, jardín</li>
                                <li><strong>Dirección: </strong>Diagonal 25 #24A-1, Dosquebradas</li>
                                <li><strong>Reseña: </strong>4.7 estrellas</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Lago La Pradera (Alojamiento en el lago)
                            </strong>
                            <ul>
                                <li><strong>Tipo: </strong>Apartamento</li>
                                <li><strong>Servicios: </strong>Balcón, wifi gratuito</li>
                                <li><strong>Dirección: </strong>Transversal 22, Dosquebradas</li>
                            </ul>
                        </li>
                    </ol>
                </div>
                
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.6774665726575!2d-75.67593312650587!3d4.825332440567579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3880d47de0e0a1%3A0xdc139c1e3e8a3ab!2sEl%20Lago%20de%20La%20Pradera!5e0!3m2!1ses!2sco!4v1744939561176!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('paraisosacuaticos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Lago De La Pradera">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>