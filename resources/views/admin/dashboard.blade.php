<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administración</title>
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1400px; margin:30px auto; padding:0 30px; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:40px; }
        .stat-card { background:#fff; border-radius:12px; padding:25px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .stat-card h3 { margin:0 0 10px 0; color:#6c6c68; font-size:0.9em; font-weight:600; }
        .stat-card .number { font-size:2.5em; font-weight:bold; color:#24a148; margin:10px 0; }
        .section { background:#fff; border-radius:12px; padding:25px; margin-bottom:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { margin-top:0; color:#1c1c1a; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ececec; }
        th { background:#f9f9f9; color:#6c6c68; font-weight:600; }
        .table-wrap { width:100%; overflow-x:auto; }
        .table-wrap table { min-width:760px; }
        .badge { padding:4px 12px; border-radius:12px; font-size:0.85em; font-weight:600; }
        .badge-pending { background:#fff3cd; color:#856404; }
        .badge-confirmed { background:#d4edda; color:#155724; }
        .badge-cancelled { background:#f8d7da; color:#721c24; }
        .popular-place { display:flex; justify-content:space-between; align-items:center; padding:15px; border-bottom:1px solid #ececec; }
        .popular-place:last-child { border-bottom:none; }
        @media (max-width:768px) {
            .container { margin:20px auto; padding:0 12px; }
            .section { padding:16px; }
        }
    </style>
</head>
<body>
    @include('components.header-admin')
    
    <div class="container">
        <h1 style="margin-bottom:30px; color:#1c1c1a;">Dashboard de Administración</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Lugares</h3>
                <div class="number">{{ $stats['total_places'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Total de Usuarios</h3>
                <div class="number">{{ $stats['total_users'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Total de Reservas</h3>
                <div class="number">{{ $stats['total_reservations'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Reservas Pendientes</h3>
                <div class="number" style="color:#ffc107;">{{ $stats['pending_reservations'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Reservas Confirmadas</h3>
                <div class="number" style="color:#28a745;">{{ $stats['confirmed_reservations'] }}</div>
            </div>
            <div class="stat-card">
                <h3>Total de Reseñas</h3>
                <div class="number">{{ $stats['total_reviews'] }}</div>
            </div>
        </div>

        <div class="section">
            <h2>Reservas Recientes</h2>
            @if($recent_reservations->count() > 0)
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Lugar</th>
                                <th>Fecha Visita</th>
                                <th>Personas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->usuario->name }}</td>
                                    <td>{{ $reservation->place->name }}</td>
                                    <td>{{ $reservation->fecha_visita ? $reservation->fecha_visita->format('d/m/Y') : ($reservation->fecha ? $reservation->fecha->format('d/m/Y') : 'N/A') }}</td>
                                    <td>{{ $reservation->personas }}</td>
                                    <td>
                                        <span class="badge badge-{{ $reservation->estado }}">
                                            {{ ucfirst($reservation->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No hay reservas recientes.</p>
            @endif
        </div>

        <div class="section">
            <h2>Lugares Más Populares</h2>
            @if($popular_places->count() > 0)
                @foreach($popular_places as $place)
                    <div class="popular-place">
                        <div>
                            <strong>{{ $place->name }}</strong>
                            <p style="margin:5px 0; color:#6c6c68; font-size:0.9em;">{{ $place->reservations_count }} reservas</p>
                        </div>
                        <a href="{{ route('admin.places.index') }}" style="color:#24a148; text-decoration:none;">Ver →</a>
                    </div>
                @endforeach
            @else
                <p>No hay lugares con reservas aún.</p>
            @endif
        </div>
    </div>
</body>
</html>

