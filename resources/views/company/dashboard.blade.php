@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4"> Panel de Empresa</h1>
            <p class="text-muted">Gestiona las reservas de tu lugar: <strong>{{ $placesManaged[0]->name ?? 'Sin lugar' }}</strong></p>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px;">
                    <h5 class="card-title">Pendientes</h5>
                    <h2 class="mb-0">{{ $stats['pendientes'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 8px;">
                    <h5 class="card-title">Aceptadas</h5>
                    <h2 class="mb-0">{{ $stats['aceptadas'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px;">
                    <h5 class="card-title">Rechazadas</h5>
                    <h2 class="mb-0">{{ $stats['rechazadas'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Reservas ({{ $stats['total'] }} total)</h5>
        </div>
        <div class="card-body">
            @if (count($reservations) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Fecha de Visita</th>
                                <th>Hora</th>
                                <th>Personas</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $cr)
                                <tr>
                                    <td><strong>{{ $cr->reservation->usuario->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $cr->reservation->usuario->email ?? 'N/A' }}</td>
                                    <td>{{ $cr->reservation->usuario->telefono ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cr->reservation->fecha_reserva)->format('d/m/Y') }}</td>
                                    <td>{{ substr($cr->reservation->hora_reserva ?? '', 0, 5) }}</td>
                                    <td>{{ $cr->reservation->numero_personas ?? 0 }}</td>
                                    <td>
                                        @if ($cr->estado === 'pendiente')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @elseif ($cr->estado === 'aceptada')
                                            <span class="badge bg-success">Aceptada</span>
                                        @elseif ($cr->estado === 'rechazada')
                                            <span class="badge bg-danger">Rechazada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($cr->estado === 'pendiente')
                                            <form method="POST" action="{{ route('company.accept', $cr->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aceptar esta reserva?')">
                                                    ✓ Aceptar
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $cr->id }}">
                                                ✕ Rechazar
                                            </button>

                                            <!-- Modal de rechazo -->
                                            <div class="modal fade" id="rejectModal{{ $cr->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rechazar Reserva</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="{{ route('company.reject', $cr->id) }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="reason{{ $cr->id }}" class="form-label">Razón del Rechazo</label>
                                                                    <select class="form-select" id="reason{{ $cr->id }}" name="rejection_reason_id" required>
                                                                        <option value="">-- Selecciona una razón --</option>
                                                                        @foreach ($rejectionReasons as $reason)
                                                                            <option value="{{ $reason->id }}">{{ $reason->razon }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="comment{{ $cr->id }}" class="form-label">Comentario (opcional)</label>
                                                                    <textarea class="form-control" id="comment{{ $cr->id }}" name="comentario" rows="3" placeholder="Agrega más detalles..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">Respondida</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <strong>Sin reservas</strong> - No hay reservas pendientes en este momento
                </div>
            @endif
        </div>
    </div>

    <!-- Botón para volver -->
    <div class="mt-4">
        <a href="/" class="btn btn-secondary">← Volver al inicio</a>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }

    .btn-sm {
        margin: 2px;
    }
</style>

@endsection
