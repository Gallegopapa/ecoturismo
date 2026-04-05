<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerro Canceles - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Cerro Canceles</h1>
            <p class="subtitulo">Pereira, Risaralda</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/canceles7.jpg') }}" alt="Cerro Canceles" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/canceles.jpg') }}" alt="Vista panorámica" class="miniatura">
                <img src="{{ asset('imagenes/canceles2.jpg') }}" alt="Senderos" class="miniatura">
                <img src="{{ asset('imagenes/canceles4.webp') }}" alt="Paisaje" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>El Cerro Canceles es un importante punto natural y mirador de Pereira que ofrece impresionantes vistas de la ciudad y sus alrededores. Es un destino popular para caminantes y amantes de la naturaleza, ideal para actividades al aire libre y fotografía paisajística.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Mirador natural</li>
                    <li> Senderos de montaña</li>
                    <li> Vistas panorámicas</li>
                    <li> Avistamiento de aves</li>
                    <li> Flora nativa</li>
                    <li> Punto fotográfico</li>
                    <li> Atardeceres espectaculares</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Horario recomendado:</span>
                        <span class="valor">6:00 AM - 5:00 PM</span>
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
                            <strong>Restaurante La Ruana Pereira</strong>
                            <ul>
                                <li>Comida típica colombiana</li>
                                <li>Ambiente tradicional</li>
                                <li>Servicio acogedor</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Lucerna Restaurante</strong>
                            <ul>
                                <li>Repostería artesanal</li>
                                <li>Platos internacionales</li>
                                <li>Ambiente moderno</li>
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
                            <strong>Hotel Soratama</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Centro de Pereira</li>
                                <li>Fácil acceso al cerro</li>
                                <li>Servicios completos</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Movich Hotel de Pereira</strong>
                            <ul>
                                <li>Hotel de lujo</li>
                                <li>Comodidades modernas</li>
                                <li>Excelente servicio</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Calzado adecuado para senderismo</li>
                    <li> Llevar agua suficiente</li>
                    <li> Ropa apropiada según clima</li>
                    <li> Verificar pronóstico del tiempo</li>
                    <li> Ir acompañado por seguridad</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.8140254118885!2d-75.67335238206178!3d4.8019629803086845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e388791576b94ab%3A0x856c5ce4b62a2b09!2sCerro%20Canceles!5e0!3m2!1ses!2sco!4v1745094966856!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('territoriosdelcafe2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Cerro Canceles">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>