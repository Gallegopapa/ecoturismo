<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos</title>
    <link rel="stylesheet" href="{{ asset('css/lugares.css') }}">
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1200px; margin:30px auto; padding:0 30px; }
        .cards { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; }
        .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .card img { width:100%; height:200px; object-fit:cover; }
        .card-content { padding:20px; }
        .card h4 { margin:0 0 10px 0; color:#1c1c1a; }
        .card p { color:#6c6c68; font-size:0.9em; margin:10px 0; }
        a { color:#24a148; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
    @include('components.header-user')
    
    <div class="container">
        <h1 style="margin-bottom:30px; color:#1c1c1a;">Mis Favoritos</h1>

        @if($favorites->count() > 0)
            <div class="cards">
                @foreach($favorites as $favorite)
                    <div class="card">
                        @if($favorite->place->image)
                            <img src="{{ $favorite->place->image }}" alt="{{ $favorite->place->name }}" onerror="this.src='{{ asset('imagenes/iconoecoturismo.jpg') }}'">
                        @else
                            <img src="{{ asset('imagenes/iconoecoturismo.jpg') }}" alt="{{ $favorite->place->name }}">
                        @endif
                        <div class="card-content">
                            <h4>{{ $favorite->place->name }}</h4>
                            <p>{{ \Illuminate\Support\Str::limit($favorite->place->description ?? 'Sin descripción', 100) }}</p>
                            <a href="{{ url('/lugares/' . $favorite->place->id) }}">Ver más →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:60px; background:#fff; border-radius:12px;">
                <p style="font-size:1.2em; color:#6c6c68; margin-bottom:20px;">No tienes favoritos aún.</p>
                <a href="{{ url('/lugares') }}">Explorar lugares →</a>
            </div>
        @endif
    </div>
</body>
</html>

