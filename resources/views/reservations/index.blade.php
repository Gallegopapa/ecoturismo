<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1200px; margin:30px auto; padding:0 30px; }
        .section { background:#fff; border-radius:12px; padding:25px; margin-bottom:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .status { background:#d4edda; color:#155724; border-radius:8px; padding:12px 16px; margin-bottom:20px; }
        .reservation-card { border:1px solid #ececec; border-radius:10px; padding:20px; margin-bottom:15px; }
        .reservation-card h3 { margin-top:0; color:#1c1c1a; }
        .badge { padding:4px 12px; border-radius:12px; font-size:0.85em; font-weight:600; display:inline-block; }
        .badge-pendiente { background:#fff3cd; color:#856404; }
        .badge-confirmada { background:#d4edda; color:#155724; }
        .badge-cancelada { background:#f8d7da; color:#721c24; }
        button { background:#d7263d; color:#fff; border:none; padding:8px 15px; border-radius:6px; cursor:pointer; font-weight:600; }
        .info-row { display:flex; gap:30px; margin:10px 0; flex-wrap:wrap; }
        .info-item { color:#6c6c68; }
        .info-item strong { color:#1c1c1a; }
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
        <h1 style="margin-bottom:30px; color:#1c1c1a;">Mis Reservas</h1>

        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        
        @if(session('error'))
            <div style="background:#f8d7da; color:#721c24; border-radius:8px; padding:12px 16px; margin-bottom:20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="section">
            @if($reservations->count() > 0)
                @foreach($reservations as $reservation)
                    <div class="reservation-card">
                        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:15px;">
                            <div>
                                <h3>{{ $reservation->place->name }}</h3>
                                <span class="badge badge-{{ strtolower($reservation->estado) }}">
                                    {{ ucfirst($reservation->estado) }}
                                </span>
                            </div>
                            <a href="{{ url('/lugares/' . $reservation->place->id) }}">Ver lugar →</a>
                        </div>
                        <div class="info-row">
                            <div class="info-item">
                                <strong>Fecha de visita:</strong> 
                                {{ $reservation->fecha_visita ? $reservation->fecha_visita->format('d/m/Y') : ($reservation->fecha ? $reservation->fecha->format('d/m/Y') : 'N/A') }}
                            </div>
                            @if($reservation->hora_visita)
                                <div class="info-item">
                                    <strong>Hora:</strong> {{ \Carbon\Carbon::parse($reservation->hora_visita)->format('H:i') }}
                                </div>
                            @endif
                            <div class="info-item">
                                <strong>Personas:</strong> {{ $reservation->personas }}
                            </div>
                            @if($reservation->precio_total)
                                <div class="info-item">
                                    <strong>Precio:</strong> ${{ number_format($reservation->precio_total, 0, ',', '.') }} COP
                                </div>
                            @endif
                        </div>
                        @if($reservation->comentarios)
                            <div style="margin-top:10px; padding:10px; background:#f9f9f9; border-radius:6px;">
                                <strong>Comentarios:</strong> {{ $reservation->comentarios }}
                            </div>
                        @endif
                        @if($reservation->estado == 'pendiente' || $reservation->estado == 'confirmada')
                            <form method="POST" action="{{ route('web.reservations.destroy', $reservation) }}" style="margin-top:15px;" onsubmit="return confirm('¿Cancelar esta reserva?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Cancelar Reserva</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @else
                <p style="text-align:center; padding:40px; color:#6c6c68;">No tienes reservas aún. <a href="{{ url('/lugares') }}">Explora lugares</a></p>
            @endif
        </div>
    </div>
</body>
</html>

