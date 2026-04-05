<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>

<body>
    <div class="container">
        <div class="form">
            <div class="contact-info">
                <h3 class="tittle">Pongámonos en contacto</h3>
                <p class="text">Escríbenos y te buscamos la mejor opción para tu página
                </p>
                <div class="info">
                    <div class="information">
                        <img src="{{ asset('imagenes/maps-and-location.png') }}" class="icon" alt="">
                        <p>Dosquebradas-Pereira</p>
                    </div>
                    <div class="information">
                        <img src="{{ asset('imagenes/correo-electronico.png') }}" class="icon" alt="">
                        <a href="mailto:proyectoecoturismo2@gmail.com">

                            <p>proyectoecoturismo2@gmail.com</p>
                        </a>
                    </div>
                    <div class="information">
                        <img src="{{ asset('imagenes/telefono.png') }}" class="icon" alt="">
                        <a href="tel:3134152020">

                            <p>3134152020</p>
                        </a>
                    </div>
                    <div class="information copyright">
                        <p>&copy; 2025 RisaraldaEcoTurismo</p>
                    </div>
                </div>
                <div class="social-media">
                    <p>Conéctate con nosotros:</p>
                    <div class="social-icon">
                        <a href="https://www.facebook.com/share/1Bf6vo7qJA/?mibextid=wwXIfr">
                            <img src="{{ asset('imagenes/iconofb.png') }}" width="30px" alt="">
                        </a>

                        <a href="https://wa.link/tuxrjn">
                            <img src="{{ asset('imagenes/iconowp.png') }}" width="30px" alt="">
                        </a>

                        <a href="#">
                            <img src="{{ asset('imagenes/iconoig.png') }}" width="30px" alt="">
                        </a>
                        <button id="volver"><a href="pagcentral2.html">Volver</a></button>
                    </div>
                </div>
            </div>
            <div class="contact-form">
                <!-- <span class="circle one"></span>
                <span class="circle two"></span> -->

                <form action="contactosbienhecho.html">
                    <h3 class="tittle">Contáctanos</h3>
                    <div class="input-container focus">
                        <input type="text" name="name" class="input">
                        <label for="">Nombre de usuario</label>
                        <span>Nombre de usuario</span>
                    </div>

                    <div class="input-container focus">
                        <input type="email" name="email" class="input">
                        <label for="">Correo</label>
                        <span>Correo</span>

                    </div>

                    <div class="input-container focus">
                        <input type="tel" name="phone" class="input">
                        <label for="">Teléfono</label>
                        <span>Teléfono</span>

                    </div>

                    <div class="input-container textarea focus">
                        <textarea name="message" class="input"></textarea>
                        <label for="">Mensaje</label>
                        <span>Mensaje</span>

                    </div>
                    <input type="submit" value="Enviar" class="btn">
                </form>
            </div>
        </div>
    </div>
    <!-- <footer>© 2025 Risaralda EcoTurismo</footer> -->

</body>

</html>