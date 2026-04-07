<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Risaralda EcoTurismo</title>
    <link rel="stylesheet" href="{{ asset('css/lugares.css') }}">
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1200px; margin:30px auto; padding:0 30px; }
        .category-header { background:#fff; border-radius:12px; padding:30px; margin-bottom:30px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .category-header h1 { margin:0; font-size:2.5em; }
        .category-icon { font-size:4em; margin-bottom:10px; }
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
    @auth
        @if(auth()->user()->is_admin)
            @include('components.header-admin')
        @else
            @include('components.header-user')
        @endif
    @else
        @include('components.header-guest')
    @endauth
    
    <div class="container">
        <div class="category-header">
            <div class="category-icon">{{ $category->icon ?? '' }}</div>
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p style="color:#6c6c68; margin-top:10px;">{{ $category->description }}</p>
            @endif
        </div>

        @if($places->count() > 0)
            <div class="cards">
                @foreach($places as $place)
                    <div class="card">
                        @if($place->image)
                            <img src="{{ $place->image }}" alt="{{ $place->name }}" onerror="this.src='{{ asset('imagenes/iconoecoturismo.jpg') }}'">
                        @else
                            <img src="{{ asset('imagenes/iconoecoturismo.jpg') }}" alt="{{ $place->name }}">
                        @endif
                        <div class="card-content">
                            <h4>{{ $place->name }}</h4>
                            <p>{{ \Illuminate\Support\Str::limit($place->description ?? 'Sin descripción', 100) }}</p>
                            @if($place->location)
                                <p style="font-size:0.85em; color:#6c6c68;"> {{ $place->location }}</p>
                            @endif
                            <a href="{{ route('place.show', $place) }}">Ver más →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:60px; background:#fff; border-radius:12px;">
                <p style="font-size:1.2em; color:#6c6c68;">No hay lugares en esta categoría aún.</p>
            </div>
        @endif
    </div>
</body>
</html>

