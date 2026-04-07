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
    <h1>Parques y Más...</h1>

<div class="contenedor">
    <div class="cards">
        <div class="card"><a href="https://maps.app.goo.gl/7seGQZ2LHdAMoNqJ6">
            <img src="{{ asset('imagenes/divisa.jpeg') }}" alt=""></a>
            <h4>La divisa de Don Juan</h4>
            <p>La Divisa de Don Juan es una finca cafetera familiar en Pereira, Colombia, que ofrece tours guiados. La finca es un importante productor de café y un área de conservación de la biodiversidad.</p>
        </div>
        <div class="card"><a href="https://maps.app.goo.gl/f3w9DC9zRFUMDEzv9">

            <img src="{{ asset('imagenes/nudo.jpg') }}" alt="">
        </a>
            <h4>Alto Del Nudo</h4>
            <p>El Alto del Nudo es una reserva natural regional en la Serranía El Nudo, en el departamento de Risaralda, Colombia. Es un lugar de gran biodiversidad, con senderos ecológicos y miradores.</p>
        </div>
        <div class="card"><a href="https://maps.app.goo.gl/DyrpMApsB3Mz1hmV6">

            <img src="{{ asset('imagenes/toro.jpg') }}" alt="">
        </a>
            <h4>Alto Del Toro</h4>
            <p>El Alto del Toro es una vereda en Dosquebradas, Risaralda, Colombia. También hay una ruta de ciclismo y senderismo que lleva a este lugar.</p>
        </div>
    </div>
</div>        


<div class="contenedor2">
    <div class="cards">
        
        <div class="card"><a href="https://maps.app.goo.gl/utPFba5beSRuuaNt6">

            <img src="{{ asset('imagenes/jardin.jpeg') }}" alt="">
        </a>
            <h4>Jardín Botánico</h4>
            <p>El Jardín Botánico de la Universidad Tecnológica de Pereira (UTP) es un lugar para observar aves, conocer la flora y la fauna, y aprender sobre el medio ambiente. Está ubicado en el campus universitario, a pocos minutos del centro de Pereira.</p>
        </div>

        <div class="card">

            <img src="{{ asset('imagenes/tatama.jpg') }}" alt="">
            <h4>Parque Nacional Natural Tatamá</h4>
            <p>El Páramo Tatamá es un páramo colombiano situado sobre los 4200 m s. n. m. y está ubicado en la Cordillera Occidental, entre los departamentos de Chocó, Valle del Cauca y Risaralda.</p>
            <a href="https://maps.app.goo.gl/hPSphPUBmXGBqeGJ6"><button class="ubicacion ubicacionn">Ubicación</button></a>
        </div>
        <div class="card">

            <img src="{{ asset('imagenes/jardinM.jpg') }}" alt="">

            <h4>Jardín Botánico De Marsella</h4>
            <p>El Jardín Botánico Alejandro Humboldt de Marsella, Risaralda, es un espacio que conserva y exhibe plantas, además de promover la concientización sobre el medio ambiente.</p>
            <a href="https://maps.app.goo.gl/L2ysAcHE6EvuNq3U7"><button class="ubicacion">Ubicación</button></a>
        </div>
        </div>
</div>     


        <a href="{{ route('pagcentral') }}"><button class="volver">Volver</button></a>

</div>
<footer>© 2025 Risaralda EcoTurismo</footer>
</body>
</html>