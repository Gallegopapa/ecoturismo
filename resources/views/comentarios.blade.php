<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/comentarios.css') }}">
    <title>Reseñas y Comentarios</title>
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
    <script src="{{ asset('js/comentarios.js') }}"></script>

</head>
<body>
<div class="contenedorTodo">
    <section class="review" id="review">
    <div class="middle-text">    
        <h4>Nuestros Clientes</h4>
        <h2>Reseñas Y Comentarios</h2>
    </section>
    
    <section class="review-content">
        <div class="box">
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsum ab, asperiores dolores cupiditate eius dolorem.</p>
        
        <div class="in-box">
            <div class="bx-img">
                <img src="{{ asset('imagenes/usuario.jpg') }}" >
            </div>
            <div class="bxx-text">
                <h4>Karla Ospina</h4>
                <h5>Excelente, cumple con su trabajo, lo recomiendo mucho.</h5>
                <div class="ratings">
                    <a href="#"><i id="star1">★</i></a>
                    <a href="#"><i id="star2">★</i></a>
                    <a href="#"><i id="star3">★</i></a>
                    <a href="#"><i id="star4">★</i></a>
                    <a href="#"><i id="star5">★</i></a>
                </div>
            </div>
        </div>
    </div>


        <div class="box">
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsum ab, asperiores dolores cupiditate eius dolorem.</p>
        
        <div class="in-box">
            <div class="bx-img">
                <img src="{{ asset('imagenes/usuario.jpg') }}" >
            </div>
            <div class="bxx-text">
                <h4>Frank Navarro</h4>
                <h5>Gran servicio, todo es rapido y profesional.</h5>
                <div class="ratings">
                    <a href="#"><i id="starY1">★</i></a>
                    <a href="#"><i id="starY2">★</i></a>
                    <a href="#"><i id="starY3">★</i></a>
                    <a href="#"><i id="starY4">★</i></a>
                    <a href="#"><i id="starY5">★</i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsum ab, asperiores dolores cupiditate eius dolorem.</p>
    
    <div class="in-box">
        <div class="bx-img">
            <img src="{{ asset('imagenes/usuario.jpg') }}" >
        </div>
        <div class="bxx-text">
            <h4>Joan Montoya</h4>
            <h5>Me lo recomendo un compañero y todo correcto.</h5>
            <div class="ratings">
                <a href="#"><i id="starX1">★</i></a>
                <a href="#"><i id="starX2">★</i></a>
                <a href="#"><i id="starX3">★</i></a>
                <a href="#"><i id="starX4">★</i></a>
                <a href="#"><i id="starX5">★</i></a>
            </div>
        </div>
    </div>
</div>
</section>
<form action="{{ route('mensajes') }}" method="POST">
    @csrf
<section class="comentaarios">
    <div class="input-container textarea focus">
        <textarea name="message" class="input" placeholder="Déjanos tu comentario: "></textarea>
    </div>
    <input type="submit" value="Enviar" class="btn">
</section>
</form>
<a href="{{ route('pagcentral') }}" class="boton-volver">Volver</a>
</div>
</body>
</html>