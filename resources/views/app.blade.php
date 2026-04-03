<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
    <title>{{ config('app.name', 'Risaralda EcoTurismo') }}</title>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="root"></div>
    
    @auth
        <script>
            window.Laravel = {
                user: @json(auth()->user()),
                isAdmin: {{ auth()->user()->is_admin ? 'true' : 'false' }}
            };
        </script>
    @else
        <script>
            window.Laravel = {
                user: null,
                isAdmin: false
            };
        </script>
    @endauth
</body>
</html>
