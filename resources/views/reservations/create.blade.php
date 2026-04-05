<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reserva - {{ $place->name }}</title>
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:800px; margin:30px auto; padding:0 30px; }
        .card { background:#fff; border-radius:12px; padding:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        label { display:block; font-weight:600; margin-top:15px; color:#1c1c1a; }
        input[type="text"], input[type="date"], input[type="time"], input[type="number"], textarea { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #dcdcdc; font-size:0.95rem; box-sizing:border-box; }
        textarea { min-height:90px; resize:vertical; }
        button { background:#24a148; border:none; color:#fff; padding:12px 24px; border-radius:8px; cursor:pointer; font-weight:600; margin-top:20px; }
        button.secondary { background:#6c6c68; }
        .place-info { background:#f9f9f9; padding:20px; border-radius:10px; margin-bottom:20px; }
        .place-info h2 { margin-top:0; }
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
        <div class="card">
            <div class="place-info">
                <h2>{{ $place->name }}</h2>
                @if($place->location)
                    <p style="color:#6c6c68; margin:5px 0;"> {{ $place->location }}</p>
                @endif
                @if($place->description)
                    <p style="margin-top:10px;">{{ $place->description }}</p>
                @endif
            </div>

            <h1 style="margin-bottom:20px;">Crear Reserva - {{ $place->name }}</h1>

            <form method="POST" action="{{ route('web.reservations.store') }}">
                @csrf
                <input type="hidden" name="place_id" value="{{ $place->id }}">

                <label>Fecha de visita *</label>
                <input type="date" id="fecha_visita" name="fecha_visita" required min="{{ date('Y-m-d') }}" value="{{ old('fecha_visita') }}">

                <label>Hora de visita *</label>
                <select id="hora_visita" name="hora_visita" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid #dcdcdc; font-size:0.95rem; box-sizing:border-box;">
                    <option value="">Selecciona primero una fecha</option>
                </select>
                <div id="loading-horarios" style="display:none; color:#6c6c68; margin-top:5px; font-size:0.9em;">Cargando horarios disponibles...</div>
                <div id="error-horarios" style="display:none; color:#d7263d; margin-top:5px; font-size:0.9em;"></div>

                <label>Número de personas *</label>
                <input type="number" name="personas" required min="1" max="50" value="{{ old('personas', 1) }}">

                <label>Teléfono de contacto</label>
                <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto') }}" placeholder="Ej: 3001234567">

                <label>Precio total (COP)</label>
                <input type="number" name="precio_total" min="0" step="0.01" value="{{ old('precio_total') }}" placeholder="Opcional">

                <label>Comentarios adicionales</label>
                <textarea name="comentarios" placeholder="Notas especiales, requerimientos, etc.">{{ old('comentarios') }}</textarea>

                @if($errors->any())
                    <div style="background:#f8d7da; color:#721c24; padding:12px; border-radius:8px; margin-top:15px;">
                        <ul style="margin:0; padding-left:20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Confirmar Reserva</button>
                    <a href="{{ route('place.show', $place) }}">
                        <button type="button" class="secondary">Cancelar</button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const placeId = {{ $place->id }};
        const fechaInput = document.getElementById('fecha_visita');
        const horaSelect = document.getElementById('hora_visita');
        const loadingDiv = document.getElementById('loading-horarios');
        const errorDiv = document.getElementById('error-horarios');

        fechaInput.addEventListener('change', function() {
            const fecha = this.value;
            
            if (!fecha) {
                horaSelect.innerHTML = '<option value="">Selecciona primero una fecha</option>';
                errorDiv.style.display = 'none';
                return;
            }

            // Limpiar select y mostrar loading
            horaSelect.innerHTML = '<option value="">Cargando...</option>';
            horaSelect.disabled = true;
            loadingDiv.style.display = 'block';
            errorDiv.style.display = 'none';

            // Hacer petición a la API
            fetch(`/api/places/${placeId}/available-schedules?fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    loadingDiv.style.display = 'none';
                    horaSelect.disabled = false;
                    
                    if (data.horarios_disponibles && data.horarios_disponibles.length > 0) {
                        horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
                        data.horarios_disponibles.forEach(horario => {
                            const option = document.createElement('option');
                            option.value = horario.hora;
                            option.textContent = horario.hora_display;
                            horaSelect.appendChild(option);
                        });
                    } else {
                        horaSelect.innerHTML = '<option value="">No hay horarios disponibles para esta fecha</option>';
                        errorDiv.textContent = 'No hay horarios disponibles para la fecha seleccionada. Por favor, selecciona otra fecha.';
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    loadingDiv.style.display = 'none';
                    horaSelect.disabled = false;
                    horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                    errorDiv.textContent = 'Error al cargar los horarios disponibles. Por favor, intenta de nuevo.';
                    errorDiv.style.display = 'block';
                    console.error('Error:', error);
                });
        });

        // Si hay una fecha previamente seleccionada (por ejemplo, después de un error de validación), cargar horarios
        @if(old('fecha_visita'))
            fechaInput.dispatchEvent(new Event('change'));
            // Restaurar hora seleccionada si existe
            @if(old('hora_visita'))
                setTimeout(() => {
                    horaSelect.value = '{{ old("hora_visita") }}';
                }, 500);
            @endif
        @endif
    </script>
</body>
</html>

