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
    <main class="contenido">
        <div class="contenedorTodo">
            <h1>Paraísos Acuáticos</h1>

            <div class="cards">
        <!-- <div class="card"><a href="https://maps.app.goo.gl/L6jLqvJGhZqbC7Am6?g_st=iw">
            <img src="{{ asset('imagenes/divisa.jpg') }}" alt=""></a>
            <h4>La divisa de Don Juan</h4>
            <p>La Divisa de Don Juan es una finca cafetera familiar en Pereira, Colombia, que ofrece tours guiados. La finca es un importante productor de café y un área de conservación de la biodiversidad.</p>
        </div> -->
            <div class="card"><a href="https://maps.app.goo.gl/M6RgB1GUYqJwGdGfA">

                <img src="{{ asset('imagenes/Lago.jpeg') }}" alt="">
            </a>
                <h4>Lago De La Pradera</h4>
                <p>El Lago de La Pradera es un lago artificial en Dosquebradas, Colombia, que se ha convertido en un popular espacio de recreación y esparcimiento.</p>
            </div>
            <div class="card"><a href="https://maps.app.goo.gl/ndHDFrHHQYfNt8n19">

                <img src="{{ asset('imagenes/laguna.jpg') }}" alt="">
            </a>
                <h4>La Laguna Del Otún</h4>
                <p>La Laguna del Otún es un embalse natural en el Parque Nacional Natural Los Nevados, en Colombia. Es un atractivo turístico y un humedal Ramsar que se alimenta del deshielo del nevado Santa Isabel.</p>
            </div>
            <div class="card"><a href="https://maps.app.goo.gl/iraGYyGvchLDCFaj8">

                <img src="{{ asset('imagenes/lolo.jpg') }}" alt="">
            </a>
                <h4>Chorros De Don Lolo</h4>
                <p>Los Chorros de Don Lolo son una cascada de 40 metros de altura en Santa Rosa de Cabal, Risaralda, Colombia. Se encuentran en un bosque húmedo secundario y son un destino popular para los amantes de la naturaleza.</p>
            </div>
            <div class="card"><a href="https://maps.app.goo.gl/XbZoEF6SsNpzKCL88">

                <img src="{{ asset('imagenes/farallones.jpeg') }}" alt="">
            </a>
                <h4>Balneario Los Farallones</h4>
                <p>El balneario Los Farallones es un centro recreativo de la Virginia, Risaralda. Este balneario ofrece una variedad de instalaciones y servicios diseñados para el disfrute de toda la familia.</p>
            </div>
    </div>        

    <a href="{{ route('pagcentral') }}"><button class="volver">Volver</button></a>
            </div>

            <a href="{{ route('pagcentral') }}"><button class="volver">Volver</button></a>
        </div>
    </main>

    <footer>© 2025 Risaralda EcoTurismo</footer>
</body>
</html>