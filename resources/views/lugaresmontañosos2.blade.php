<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques Y Más...</title>
    <link rel="stylesheet" href="{{ asset('public/css/lugares.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
    <div class="contenedorTodo">
        <h1>Parques y Más...</h1>
        <button id="mostrar-favoritos" class="mostrar-favoritos">
            Favoritos (<span id="contador-favoritos">0</span>)
        </button>

        <div class="contenedor">
            <div class="cards">
                <div class="card">
                    <img src="{{ asset('imagenes/divisa.jpeg') }}" alt="La Divisa De Don Juan">
                    <h4>La Divisa De Don Juan</h4>
                    <p>Vía Altagracia, Altagracia, Pereira, Risaralda</p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/7seGQZ2LHdAMoNqJ6">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('divisadonjuan') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="La Divisa De Don Juan">🤍</button>
                </div>

                <div class="card">
                    <img src="{{ asset('imagenes/nudo.jpg') }}" alt="Alto Del Nudo">
                    <h4>Alto Del Nudo</h4>
                    <p>Pereira, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/f3w9DC9zRFUMDEzv9">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('altodelnudo') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Alto Del Nudo">🤍</button>
                </div>

                <div class="card">
                    <img src="{{ asset('imagenes/toro.jpg') }}" alt="Alto Del Toro">
                    <h4>Alto Del Toro</h4>
                    <p>Pereira, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/DyrpMApsB3Mz1hmV6">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('altodeltoro') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Alto Del Toro">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/jardin.jpeg') }}" alt="Jardín Botánico">
                    <h4>Jardín Botánico</h4>
                    <p>Universidad tecnologica de pereira, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/hhkmfB9owU9PcB6Z7">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('jardinbotanicoutp') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Jardín Botánico">🤍</button>
                </div>
                
                <div class="card">
                    <img src="{{ asset('imagenes/tatama.jpg') }}" alt="Parque Nacional Natural Tatamá">
                    <h4>Parque Nacional Natural Tatamá</h4>
                    <p>Cra. 5 #565, Pueblo Rico, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/hPSphPUBmXGBqeGJ6">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('parquetatama') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Parque Nacional Natural Tatamá">🤍</button>
                </div>

                <div class="card">
                    <img src="{{ asset('imagenes/jardinM.jpg') }}" alt="Jardín Botánico De Marsella">
                    <h4>Jardín Botánico De Marsella</h4>
                    <p>Marsella, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/L2ysAcHE6EvuNq3U7">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('jardinbotanicomarsella') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Jardín Botánico De Marsella">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/araucarias.jpg') }}" alt="Parque Las Araucarias">
                    <h4>Parque Las Araucarias</h4>
                    <p>
                        Cl. 13, Santa Rosa de Cabal, Risaralda
                        </p>
                        <button class="ubication-button">
                            <a href="https://maps.app.goo.gl/SDZUo3UZpzU3YWq28">
                                <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                            </a>
                        </button>
                        <a href="{{ route('parquearaucarias') }}">
                            <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Parque Las Araucarias">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/cuchilla.jpg') }}" alt="Parque Regional Natural Cuchilla de San Juan">
                    <h4>Parque Regional Natural Cuchilla de San Juan</h4>
                    <p>Belén de Umbría, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/2uWtBq8BNCCHuCft9">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('parquecuchillasanjuan') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Parque Regional Natural Cuchilla de San Juan">🤍</button>
                </div>
    
                
                


                <div class="card">
                    <img src="{{ asset('imagenes/emilia.jpg') }}" alt="Parque Natural Regional Santa Emilia">
                    <h4>Parque Natural Regional Santa Emilia</h4>
                    <p>Carrera 4 #1"N, Belén de Umbría, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/5G6AXY18b8hAwdfW6">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('parquesantaemilia') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Parque Natural Regional Santa Emilia">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/batero.jpg') }}" alt="Cerro Batero">
                    <h4>Cerro Batero</h4>
                    <p>Quinchía, Risaralda</p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/q6mCEfzAjGfJkuh56">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('cerrobatero') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Cerro Batero">🤍</button>
                </div>
        
                <div class="card">
                    <img src="{{ asset('imagenes/bernardo.jpg') }}" alt="Puente Bernardo Arango">
                    <h4>Puente Bernardo Arango</h4>
                    <p>Cra 8 #3-2, La Virginia, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/NfTwdEXPBZiyWXmn9">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('puentebernardoarango') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Puente Bernardo Arango">🤍</button>
                </div>
                
                <div class="card">
                    <img src="{{ asset('imagenes/estacion.jpg') }}" alt="Estación Pereira">
                    <h4>Estación Pereira</h4>
                    <p>Marsella, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/od3D1wQWK2SWD5Kt7">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('estacionpereira') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Estación Pereira">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/lanona.jpg') }}" alt="Reserva Forestal La Nona">
                    <h4>Reserva Forestal La Nona</h4>
                    <p>a 7 km de Marsella,Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/XacC2ScWUKbcgutv8">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('reservaforestallanona') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Reserva Forestal La Nona">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/kaukita.jpeg') }}" alt="Kaukitá Bosque Reserva">
                    <h4>Kaukitá Bosque Reserva</h4>
                    <p>Porteria Malabar Kaukita, km 7 Via Cerritos, Pereira, Risaralda</p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/K3C93FAURYARtAvv6">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('kaukitabosquereserva') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Estación Pereira">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/gobia.jpg') }}" alt="Reserva Natural Cerro Gobia">
                    <h4>Reserva Natural Cerro Gobia</h4>
                    <p>Quinchía, Risaralda
                    </p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/8BF3SXF4RTpRbVxeA">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('reservanaturalcerrogobia') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Reserva Natural Cerro Gobia">🤍</button>
                </div>
                <div class="card">
                    <img src="{{ asset('imagenes/distritomanejo.jpg') }}" alt="Reserva Natural DMI Agualinda">
                    <h4>Reserva Natural DMI Agualinda</h4>
                    <p>Apía, Risaralda</p>
                    <button class="ubication-button">
                        <a href="https://maps.app.goo.gl/UNc9cTccV6LuGySU7">
                            <img src="{{ asset('imagenes/mapa2.svg') }}" style="width: 24px; height: 24px;" alt="">
                        </a>
                    </button>
                    <a href="{{ route('distritomanejo') }}">
                        <button class="ubicacion ubicacionn">Más Info</button>
                    </a>
                    <button class="favorito" data-lugar="Reserva Natural DMI Agualinda">🤍</button>
                </div>
            </div>
            </div>
            
            
            <!-- <div class="contenedor4">
                <div class="cards">
                </div> -->
                
                <!-- Contenedor de favoritos -->
                <div id="popup-favoritos" class="popup hidden">
                <div class="popup-content">
                    <button id="cerrar-popup" class="cerrar-popup">X</button>
                    <h2>Mis Favoritos</h2>
                    <ul id="favoritos-list"></ul>
                    <p id="mensaje-vacio" class="hidden">No has agregado ningún lugar aún.</p>
                </div>
            </div>
            <a href="pagcentral2.html">
                <button class="volver">Volver</button>
            </a>
            
        </div>
        <!-- Botón de volver -->

    <footer>© 2025 Risaralda EcoTurismo</footer>
    <script src="{{ asset('js/lugares.js') }}"></script>
    <script src="{{ asset('js/favoritos.js') }}"></script>
</body>
</html> 