<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
    <title>@yield('title', 'Risaralda EcoTurismo')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        nav.navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .btn-primary {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        
        .btn-primary:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        
        .btn-danger {
            background-color: #e74c3c;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
    
    @yield('extra_css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"> Risaralda EcoTurismo</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->tipo_usuario === 'empresa')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.dashboard') }}"> Mi Panel de Empresa</a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/panel"> Panel Admin</a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/configuracion"> Configuración</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/logout"> Logout</a>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted">{{ auth()->user()->email }}</span>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login"> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/registro"> Registro</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Risaralda EcoTurismo. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    @yield('extra_js')
</body>
</html>
