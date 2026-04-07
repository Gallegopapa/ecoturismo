<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parque Nacional Natural Tatamá - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Parque Nacional Natural Tatamá</h1>
            <p class="subtitulo">Risaralda, Colombia</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/tatama.jpg') }}" alt="Parque Nacional Natural Tatamá" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/tatama2.jpg') }}" alt="Páramo" class="miniatura">
                <img src="{{ asset('imagenes/tatama3.jpg') }}" alt="Paisaje" class="miniatura">
                <img src="{{ asset('imagenes/tatama4.jpg') }}" alt="Flora" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>El Páramo Tatamá es un páramo colombiano situado sobre los 4200 m s. n. m. y está ubicado en la Cordillera Occidental, entre los departamentos de Chocó, Valle del Cauca y Risaralda. Es un área protegida que alberga una rica biodiversidad y ecosistemas únicos, siendo un importante centro de conservación natural.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Páramo de alta montaña</li>
                    <li> Ecosistemas únicos</li>
                    <li> Rica biodiversidad</li>
                    <li> Senderos ecológicos</li>
                    <li> Fuentes hídricas</li>
                    <li> Bosques nativos</li>
                    <li> Observación de aves</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario:</span>
                        <span class="valor">6:00 AM - 4:00 PM</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Todos los días (con permiso previo)</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Nota importante:</span>
                        <span class="valor">Se requiere autorización de Parques Nacionales</span>
                    </div>
                </div>
            </div>

            <div class="aviso-importante">
                <h2>Aviso Importante</h2>
                <p>Por ser una reserva natural protegida, no hay servicios de restaurante dentro del parque. Se recomienda llevar provisiones y agua suficiente para la visita.</p>
            </div>
            <br>
            <div class="alojamientos">
                <h2>Alojamientos cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>Hotel Santuario Plaza</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Centro de Santuario, Risaralda</li>
                                <li>Habitaciones amplias y confortables</li>
                                <li>Cerca de atractivos culturales</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Finca Montezuma</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Entrada del parque</li>
                                <li>Servicios de guías disponibles</li>
                                <li>Ideal para senderismo</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>LA CASONA Finca Hostal</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Santuario</li>
                                <li>Calificación excepcional</li>
                                <li>Experiencia auténtica y acogedora</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Glamping La Cabañita Santuario</strong>
                            <ul>
                                <li>Alojamiento único en entorno natural</li>
                                <li>Ideal para escapadas románticas</li>
                                <li>Perfecto para familias</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel Junior Plaza</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Santuario</li>
                                <li>Precios accesibles</li>
                                <li>Habitaciones cómodas</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Llevar suficiente agua y alimentos</li>
                    <li> Ropa adecuada para clima frío y lluvia</li>
                    <li> Tramitar permisos con anticipación</li>
                    <li> Contratar guías certificados</li>
                    <li> Llevar equipo de comunicación</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.9425879569203!2d-76.09896212650446!3d5.112956137948907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e47edd28525bbab%3A0xd2116b8bc9b618c2!2sParque%20Nacional%20Natural%20Tatam%C3%A1!5e0!3m2!1ses!2sco!4v1744956181106!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('lugaresmontañosos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Parque Nacional Natural Tatamá">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>