<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lugares</title>
    <link rel="stylesheet" href="{{ asset('public/css/lugares.css') }}">
    <link rel="icon" href="imagenes/iconoecoturismo.jpg">
</head>
<body>
<div class="contenedorTodo">
    <h1>Territorios Del Café</h1>
        

<div class="contenedor2">
    <div class="cards">
        <!-- <div class="card"><a href="https://maps.app.goo.gl/V3xmC7CL2zwNVZpAA?g_st=iw">

            <img src="{{ asset('imagenes/nudo.jpg') }}" alt="">
        </a>
            <h4>Alto Del Nudo</h4>
            <p>El Alto del Nudo es una reserva natural regional en la Serranía El Nudo, en el departamento de Risaralda, Colombia. Es un lugar de gran biodiversidad, con senderos ecológicos y miradores.</p>
        </div> -->
        <!-- <div class="card"><a href="https://maps.app.goo.gl/sd2o451ZSrF897rc9?g_st=iw">

            <img src="{{ asset('imagenes/toro.jpg') }}" alt="">
        </a>
            <h4>Alto Del Toro</h4>
            <p>El Alto del Toro es una vereda en Dosquebradas, Risaralda, Colombia. También hay una ruta de ciclismo y senderismo que lleva a este lugar.</p>
        </div>
        <div class="card"><a href="https://maps.app.goo.gl/4jW5PRttezwAmehY6?g_st=iw">

            <img src="{{ asset('imagenes/lolo.jpg') }}" alt="">
        </a>
            <h4>Chorros De Don Lolo</h4>
            <p>Los Chorros de Don Lolo son una cascada de 40 metros de altura en Santa Rosa de Cabal, Risaralda, Colombia. Se encuentran en un bosque húmedo secundario y son un destino popular para los amantes de la naturaleza.</p>
        </div> -->
        </div>
</div>

<div class="contenedor3">
    <div class="cards">
        <div class="card"><a href="https://maps.app.goo.gl/Szaz4EXNWszusTK1A">

            <img src="{{ asset('imagenes/tambo.jpg') }}" alt="">
        </a>
            <h4>Tambo El Privilegio</h4>
            <p>El Tambo El Privilegio es un mirador y punto de parada en la autopista que va de Pereira a Manizales, en el departamento de Risaralda. Es un lugar para disfrutar de la vista, tomar café, y descansar.</p>
        </div>
        <div class="card"><a href="https://maps.app.goo.gl/DYrSFfubNimqcEq66">

            <img src="{{ asset('imagenes/leo.jpg') }}" alt="">
        </a>
            <h4>Café De Leo</h4>
            <p>El Café de Leo es un lugar en Santa Rosa de Cabal, Colombia, que ofrece café de la región, gastronomía local y bebidas a base de café especial. Algunos lo describen como un lugar único, bonito y con una excelente atención.</p>
        </div>
        <div class="card"><a href="https://maps.app.goo.gl/7seGQZ2LHdAMoNqJ6">
            <img src="{{ asset('imagenes/divisa2.jpg') }}" alt=""></a>
            <h4>La divisa de Don Juan</h4>
            <p>La Divisa de Don Juan es una finca cafetera familiar en Pereira, Colombia, que ofrece tours guiados. La finca es un importante productor de café y un área de conservación de la biodiversidad.</p>
        </div>
    </div>
        <a href="{{ route('pagcentral') }}"><button class="volver">Volver</button></a>
</div>
</div>
<footer>© 2025 Risaralda EcoTurismo</footer>
</body>
</html>