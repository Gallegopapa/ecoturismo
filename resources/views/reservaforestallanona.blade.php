<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Forestal La Nona - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Reserva Forestal La Nona</h1>
            <p class="subtitulo">Marsella, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/lanona9.jpg') }}" alt="Reserva La Nona" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/lanona.jpg') }}" alt="Senderos" class="miniatura">
                <img src="{{ asset('imagenes/lanona2.jpg') }}" alt="Flora" class="miniatura">
                <img src="{{ asset('imagenes/lanona6.jpg') }}" alt="Paisaje" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>La Reserva Forestal La Nona es un santuario natural ubicado en Marsella, Risaralda. Este espacio protegido alberga una rica biodiversidad, senderos ecológicos y ofrece una experiencia única para los amantes de la naturaleza. Es un lugar ideal para el avistamiento de aves, caminatas ecológicas y fotografía de naturaleza.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Bosque nativo</li>
                    <li> Avistamiento de aves</li>
                    <li> Senderos ecológicos</li>
                    <li> Puntos fotográficos</li>
                    <li> Flora diversa</li>
                    <li> Fuentes de agua</li>
                    <li> Vistas panorámicas</li>
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
                            <strong>Cafe Don Danilo</strong>
                            <ul>
                                <li>Café tradicional</li>
                                <li>Ambiente acogedor</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Molienda</strong>
                            <ul>
                                <li>Comidas ligeras</li>
                                <li>Ambiente relajado</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>RAYUELA</strong>
                            <ul>
                                <li>Comida local e internacional</li>
                                <li>Variedad gastronómica</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Milkshake</strong>
                            <ul>
                                <li>Batidos especiales</li>
                                <li>Postres artesanales</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Estancia</strong>
                            <ul>
                                <li>Platos típicos</li>
                                <li>Sabores regionales</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante El Mirador</strong>
                            <ul>
                                <li>Vista panorámica</li>
                                <li>Comida típica</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante Villa Fuerte</strong>
                            <ul>
                                <li>Comida tradicional y gourmet</li>
                                <li>Repostería especial</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante Fonda La Bodega</strong>
                            <ul>
                                <li>Especialidades ahumadas</li>
                                <li>Vía Pereira-Marsella</li>
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
                            <strong>Finca El Encuentro - Eje Cafetero</strong>
                            <ul>
                                <li>Alojamiento rural</li>
                                <li>Piscina y jardín</li>
                                <li>Vista a la montaña</li>
                                <li>Ambiente campestre</li>
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
                    <li> Hidratación suficiente</li>
                    <li> Ropa cómoda</li>
                    <li> Guía o mapa del área</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.316300924402!2d-75.72489722650555!3d4.886601740021024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e387fe7019421ed%3A0x35b25dc9223d8303!2sReserva%20forestal%20la%20nona-%20Casa%20de%20la%20carder!5e0!3m2!1ses!2sco!4v1745443746266!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('lugaresmontañosos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Reserva Forestal La Nona">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>