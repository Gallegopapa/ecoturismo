<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - Administración</title>
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1400px; margin:30px auto; padding:0 30px; }
        .section { background:#fff; border-radius:12px; padding:25px; margin-bottom:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ececec; }
        th { background:#f9f9f9; color:#6c6c68; font-weight:600; }
        .table-wrap { width:100%; overflow-x:auto; }
        .table-wrap table { min-width:980px; }
        .badge { padding:4px 12px; border-radius:12px; font-size:0.85em; font-weight:600; }
        .badge-pendiente { background:#fff3cd; color:#856404; }
        .badge-confirmada { background:#d4edda; color:#155724; }
        .badge-cancelada { background:#f8d7da; color:#721c24; }
        .status { background:#d4edda; color:#155724; border-radius:8px; padding:12px 16px; margin-bottom:20px; }
        select, button { padding:8px 15px; border-radius:6px; border:1px solid #dcdcdc; }
        button { background:#24a148; color:#fff; border:none; cursor:pointer; font-weight:600; }
        button.danger { background:#d7263d; }
        .actions { display:flex; gap:10px; }
        @media (max-width:768px) {
            .container { margin:20px auto; padding:0 12px; }
            .section { padding:16px; }
        }
    </style>
</head>
<body>
    @include('components.header-admin')
    
    <div class="container">
        <h1 style="margin-bottom:30px; color:#1c1c1a;">Gestión de Reservas</h1>

        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="section">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Lugar</th>
                            <th>Fecha Visita</th>
                            <th>Hora</th>
                            <th>Personas</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>{{ $reservation->usuario->name }}</td>
                                <td>{{ $reservation->place->name }}</td>
                                <td>{{ $reservation->fecha_visita ? $reservation->fecha_visita->format('d/m/Y') : ($reservation->fecha ? $reservation->fecha->format('d/m/Y') : 'N/A') }}</td>
                                <td>{{ $reservation->hora_visita ? \Carbon\Carbon::parse($reservation->hora_visita)->format('H:i') : 'N/A' }}</td>
                                <td>{{ $reservation->personas }}</td>
                                <td>{{ $reservation->telefono_contacto ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($reservation->estado) }}">
                                        {{ ucfirst($reservation->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <select name="estado" onchange="this.form.submit()" style="margin-right:5px;">
                                            <option value="pendiente" {{ $reservation->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $reservation->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="cancelada" {{ $reservation->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar esta reserva?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:40px; color:#6c6c68;">No hay reservas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

