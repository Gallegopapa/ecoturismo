<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estación Pereira - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Estación Pereira</h1>
            <p class="subtitulo">Centro Histórico, Pereira</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/estacion.jpg') }}" alt="Estación Pereira" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/estacion2.webp') }}" alt="Interior Estación" class="miniatura">
                <img src="{{ asset('imagenes/estacion3.jpg') }}" alt="Centro Comercial" class="miniatura">
                <img src="{{ asset('imagenes/estacion4.jpg') }}" alt="Arquitectura" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>La Estación Pereira es una vereda ubicada en el municipio de Marsella, en el departamento de Risaralda, Colombia. Se encuentra aproximadamente a 40 kilómetros de la cabecera municipal, siendo una de las zonas más apartadas y pintorescas del municipio .</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Vistas panorámicas</li>
                    <li> Naturaleza exuberante</li>
                    <li> Cultivos de café</li>
                    <li> Senderos rurales</li>
                    <li> Paisaje cafetero</li>
                    <li> Flora diversa</li>
                    <li> Avistamiento de aves</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Acceso:</span>
                        <span class="valor">Acceso libre</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Días:</span>
                        <span class="valor">Todos los días</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Entrada:</span>
                        <span class="valor">Gratuita</span>
                    </div>
                    <div class="fila">
                        <span class="etiqueta">Nota:</span>
                        <span class="valor">Se recomienda visitar durante el día</span>
                    </div>
                </div>
            </div>

            <div class="restaurantes">
                <h2>Restaurantes cercanos</h2>
                <div class="info">
                    <ol>
                        <li>
                            <strong>La Estancia</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Marsella</li>
                                <li>Comida típica colombiana</li>
                                <li>Ambiente acogedor</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>La Molienda Café</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Marsella</li>
                                <li>Café bar</li>
                                <li>Snacks y bebidas locales</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante Fonda La Bodega</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Km 10 vía Pereira-Marsella</li>
                                <li>Comida típica ahumada</li>
                                <li>Vista al paisaje cafetero</li>
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
                            <strong>Hotel Carmen</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Marsella, Risaralda</li>
                                <li>Cerca de la plaza principal</li>
                                <li>Alojamiento sencillo y cómodo</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Finca El Encuentro - Eje Cafetero</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Vereda La Oriental, Marsella</li>
                                <li>Estilo rural con jardín y piscina</li>
                                <li>Vista a la montaña</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel Spa La Colina</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Km 6.5 vía Pereira-Marsella</li>
                                <li>Servicios de spa y sauna</li>
                                <li>Tina de hidromasaje</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.19348120193!2d-75.81687842650541!3d4.907263389835327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e4787563dda6003%3A0x33e34295fd7835fe!2sEstacion%20Pereira%2C%20Marsella%2C%20Risaralda!5e0!3m2!1ses!2sco!4v1745081259878!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('lugaresmontañosos2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Estación Pereira">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>