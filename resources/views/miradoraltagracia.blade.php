<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mirador Altagracia - Información Detallada</title>
    <link rel="stylesheet" href="{{ asset('public/css/detallelugar.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>Mirador Altagracia</h1>
            <p class="subtitulo">Corregimiento de Altagracia, Pereira</p>
        </header>

        <div class="galeria">
            <img src="{{ asset('imagenes/mirador4.jpg') }}" alt="Mirador Altagracia" class="imagen-principal">
            <div class="miniaturas">
                <img src="{{ asset('imagenes/mirador.jpg') }}" alt="Vista panorámica" class="miniatura">
                <img src="{{ asset('imagenes/mirador.webp') }}" alt="Atardecer" class="miniatura">
                <img src="{{ asset('imagenes/mirador5.jpg') }}" alt="Paisaje" class="miniatura">
            </div>
        </div>

        <section class="informacion">
            <div class="descripcion">
                <h2>Descripción</h2>
                <p>El Mirador Altagracia es un punto panorámico privilegiado que ofrece vistas espectaculares del Valle del Cauca y las montañas circundantes. Este lugar es perfecto para contemplar atardeceres, tomar fotografías y disfrutar de la gastronomía local mientras se aprecia el paisaje cafetero.</p>
            </div>

            <div class="caracteristicas">
                <h2>Características</h2>
                <ul>
                    <li> Vistas panorámicas</li>
                    <li> Atardeceres espectaculares</li>
                    <li> Punto fotográfico</li>
                    <li> Café de la región</li>
                    <li> Gastronomía local</li>
                    <li> Aire puro</li>
                    <li> Paisaje cafetero</li>
                </ul>
            </div>

            <div class="horarios-precios">
                <h2>Horarios y Precios</h2>
                <div class="tabla-info">
                    <div class="fila">
                        <span class="etiqueta">Acceso:</span>
                        <span class="valor">24 horas</span>
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
                            <strong>Café Mirador Altagracia</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>A pocos pasos del mirador</li>
                                <li>Vistas impresionantes</li>
                                <li>Comida y café regional</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante Rancho Familiar</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Vereda Filo Bonito</li>
                                <li>Platos típicos</li>
                                <li>Ambiente campestre</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Almagracia Restaurante</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Vereda Cañaveral</li>
                                <li>Especialidad en parrilla</li>
                                <li>Carne Angus importada</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Restaurante Kilómetro 4</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Vía Condina, Km 5</li>
                                <li>Porciones generosas</li>
                                <li>Precios accesibles</li>
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
                            <strong>Finca Cielo Alto</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Vía Pereira-Altagracia</li>
                                <li>Piscina y sauna</li>
                                <li>Ideal para grupos y familias</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Ecohotel Guaduales Nature</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Variante Condina</li>
                                <li>Hotel ecológico</li>
                                <li>Rodeado de naturaleza</li>
                            </ul>
                        </li>
                        <br>
                        <li>
                            <strong>Hotel San Fermin Pereira</strong>
                            <ul>
                                <li><strong>Ubicación: </strong>Centro de Pereira</li>
                                <li>Jardín y restaurante</li>
                                <li>Wifi gratuito</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="recomendaciones">
                <h2>Recomendaciones</h2>
                <ul>
                    <li> Mejor hora: atardecer</li>
                    <li> Llevar cámara</li>
                    <li> Ropa abrigada</li>
                    <li> Verificar clima</li>
                    <li> Acceso en vehículo</li>
                </ul>
            </div>

            <div class="ubicacion">
                <h2>Cómo Llegar</h2>
                <div class="mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.202791225164!2d-75.71844801114503!3d4.734803000000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e386301a7afaec7%3A0x5f5c4cd6c65833f8!2sMirador%20de%20Altagracia!5e0!3m2!1ses!2sco!4v1745094514352!5m2!1ses!2sco" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                </div>
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('territoriosdelcafe2') }}" class="boton-volver">Volver a Lugares</a>
            <button class="boton-favorito" data-lugar="Mirador Altagracia">🤍</button>
        </div>
    </div>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html>