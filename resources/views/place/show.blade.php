<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $place->name }} - Risaralda EcoTurismo</title>
    <link rel="stylesheet" href="{{ asset('css/detallelugar.css') }}">
    <link rel="icon" href="{{ asset('imagenes/iconoecoturismo.jpg') }}">
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
    <div class="contenedor-detalle">
        <header class="header-lugar">
            <h1>{{ $place->name }}</h1>
            @if($place->location)
                <p class="subtitulo">{{ $place->location }}</p>
            @endif
        </header>

        <div class="galeria">
            @if($place->image)
                <img src="{{ $place->image }}" alt="{{ $place->name }}" class="imagen-principal" onerror="this.src='{{ asset('imagenes/iconoecoturismo.jpg') }}'">
            @else
                <img src="{{ asset('imagenes/iconoecoturismo.jpg') }}" alt="{{ $place->name }}" class="imagen-principal">
            @endif
        </div>

        <section class="informacion">
            @if($place->description)
                <div class="descripcion">
                    <h2>Descripción</h2>
                    <p>{{ $place->description }}</p>
                </div>
            @endif

            @if($place->location)
                <div class="ubicacion">
                    <h2>Ubicación</h2>
                    <p>{{ $place->location }}</p>
                </div>
            @endif

            <!-- Información de Contacto -->
            @if($place->telefono || $place->email || $place->sitio_web)
                <div class="contacto" style="margin-top:30px;">
                    <h2>Información de Contacto</h2>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        @if($place->telefono)
                            <p style="margin:5px 0;">
                                <strong> Teléfono:</strong> 
                                <a href="tel:{{ $place->telefono }}" style="color:#24a148; text-decoration:none;">{{ $place->telefono }}</a>
                            </p>
                        @endif
                        @if($place->email)
                            <p style="margin:5px 0;">
                                <strong> Email:</strong> 
                                <a href="mailto:{{ $place->email }}" style="color:#24a148; text-decoration:none;">{{ $place->email }}</a>
                            </p>
                        @endif
                        @if($place->sitio_web)
                            <p style="margin:5px 0;">
                                <strong> Sitio Web:</strong> 
                                <a href="{{ $place->sitio_web }}" target="_blank" rel="noopener noreferrer" style="color:#24a148; text-decoration:none;">{{ $place->sitio_web }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Horarios Disponibles y Ocupados -->
            <div class="horarios" style="margin-top:30px; padding:25px; background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-top:0; color:#1c1c1a; border-bottom:2px solid #24a148; padding-bottom:10px;"> Horarios y Disponibilidad</h2>
                
                @if($schedules && $schedules->count() > 0)
                    <div style="margin-bottom:30px;">
                        <h3 style="color:#24a148; margin-bottom:15px;">Horarios de Atención</h3>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                            @php
                                $horariosPorDia = [];
                                foreach($schedules as $schedule) {
                                    $horariosPorDia[$schedule->dia_semana][] = $schedule;
                                }
                            @endphp
                            @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                @if(isset($horariosPorDia[$dia]))
                                    <div style="padding:15px; background:#f0f9f4; border-radius:8px; border-left:4px solid #24a148;">
                                        <strong style="text-transform:capitalize; color:#1c1c1a; display:block; margin-bottom:8px;">{{ ucfirst($dia) }}</strong>
                                        @foreach($horariosPorDia[$dia] as $schedule)
                                            @php
                                                $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $schedule->hora_inicio);
                                                $horaFin = \Carbon\Carbon::createFromFormat('H:i', $schedule->hora_fin);
                                            @endphp
                                            <div style="color:#2d5016; font-size:0.95em; margin:5px 0; font-weight:500;">
                                                 {{ $horaInicio->format('g:i A') }} - {{ $horaFin->format('g:i A') }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="padding:15px; background:#f9f9f9; border-radius:8px; border-left:4px solid #ccc;">
                                        <strong style="text-transform:capitalize; color:#999; display:block; margin-bottom:8px;">{{ ucfirst($dia) }}</strong>
                                        <div style="color:#999; font-size:0.9em;">Cerrado</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if($reservations && $reservations->count() > 0)
                        <div style="margin-top:30px; padding-top:25px; border-top:2px solid #ececec;">
                            <h3 style="color:#d7263d; margin-bottom:15px;"> Horarios Ocupados (Próximas Reservas)</h3>
                            <div style="display:grid; gap:12px;">
                                @php
                                    $reservasPorFecha = [];
                                    foreach($reservations as $reservation) {
                                        $fecha = $reservation->fecha_visita->format('Y-m-d');
                                        if (!isset($reservasPorFecha[$fecha])) {
                                            $reservasPorFecha[$fecha] = [];
                                        }
                                        $reservasPorFecha[$fecha][] = $reservation;
                                    }
                                @endphp
                                @foreach($reservasPorFecha as $fecha => $reservasDelDia)
                                    @php
                                        // Usar directamente la fecha de la reserva (ya es un objeto Carbon)
                                        // Esto evita problemas de zona horaria al hacer parse
                                        $fechaObj = $reservasDelDia[0]->fecha_visita->copy()->startOfDay();
                                        $diaSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'][$fechaObj->dayOfWeek];
                                    @endphp
                                    <div style="padding:15px; background:#fff5f5; border-radius:8px; border-left:4px solid #d7263d;">
                                        <strong style="color:#1c1c1a; display:block; margin-bottom:10px;">
                                             {{ ucfirst($diaSemana) }}, {{ $fechaObj->format('d/m/Y') }}
                                        </strong>
                                        <div style="display:flex; flex-wrap:wrap; gap:10px;">
                                            @foreach($reservasDelDia as $reservation)
                                                <div style="padding:8px 12px; background:#fff; border:1px solid #d7263d; border-radius:6px; font-size:0.9em;">
                                                    <span style="color:#d7263d; font-weight:600;">
                                                         {{ $reservation->hora_visita ? \Carbon\Carbon::createFromFormat('H:i', $reservation->hora_visita)->format('g:i A') : 'Sin hora' }}
                                                    </span>
                                                    @if($reservation->personas)
                                                        <span style="color:#6c6c68; margin-left:8px;">
                                                            ({{ $reservation->personas }} {{ $reservation->personas == 1 ? 'persona' : 'personas' }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p style="margin-top:15px; color:#6c6c68; font-size:0.9em; font-style:italic;">
                                 Estos horarios ya están reservados. Selecciona otro horario disponible al hacer tu reserva.
                            </p>
                        </div>
                    @else
                        <div style="margin-top:30px; padding:15px; background:#e8f5e9; border-radius:8px; border-left:4px solid #24a148;">
                            <p style="margin:0; color:#2d5016; font-weight:500;">
                                 No hay reservas programadas. Todos los horarios están disponibles.
                            </p>
                        </div>
                    @endif
                @else
                    <div style="padding:20px; background:#fff3cd; border-radius:8px; border-left:4px solid #ffc107;">
                        <p style="margin:0; color:#856404; font-weight:500;">
                             No hay horarios configurados para este lugar.
                        </p>
                        <p style="margin:10px 0 0 0; color:#856404; font-size:0.9em;">
                            Los administradores pueden configurar los horarios desde el panel de administración. 
                            Por favor, contacta al administrador o intenta más tarde.
                        </p>
                        @auth
                            @if(auth()->user()->is_admin)
                                <p style="margin:15px 0 0 0; color:#856404; font-size:0.9em; font-weight:600;">
                                     Como administrador, puedes agregar horarios desde el panel de administración.
                                </p>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </section>

        @auth
            <div style="margin:30px 0; padding:20px; background:#f9f9f9; border-radius:10px;">
                <h3>Acciones</h3>
                <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top:15px;">
                    <a href="{{ route('web.reservations.create', $place) }}" style="background:#24a148; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Reservar Ahora</a>
                    <button id="btn-favorite" style="background:#ffc107; color:#1c1c1a; padding:12px 24px; border-radius:8px; border:none; cursor:pointer; font-weight:600;">⭐ Agregar a Favoritos</button>
                </div>
            </div>
        @else
            <div style="margin:30px 0; padding:20px; background:#fff3cd; border-radius:10px; text-align:center;">
                <p style="margin:0 0 15px 0;">¿Te gusta este lugar? <a href="{{ route('login') }}" style="color:#24a148; font-weight:600;">Inicia sesión</a> para reservar o agregarlo a favoritos.</p>
            </div>
        @endauth

        <!-- Sección de Reseñas y Comentarios -->
        <section style="margin:40px 0; padding:30px; background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="margin-top:0; color:#1c1c1a;">Reseñas y Comentarios</h2>
            
            @if($averageRating > 0)
                <div style="margin-bottom:20px; padding:15px; background:#f9f9f9; border-radius:8px;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div>
                            <div style="font-size:2em; font-weight:bold; color:#24a148;">{{ number_format($averageRating, 1) }}</div>
                            <div style="color:#6c6c68; font-size:0.9em;">{{ $reviews->count() }} {{ $reviews->count() == 1 ? 'reseña' : 'reseñas' }}</div>
                        </div>
                        <div style="font-size:1.5em; color:#ffc107;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating))
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            @auth
                @if(!$userReview)
                    <div style="margin-bottom:30px; padding:20px; background:#f9f9f9; border-radius:10px;">
                        <h3 style="margin-top:0;">Deja tu reseña</h3>
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="place_id" value="{{ $place->id }}">
                            
                            <label style="display:block; margin-top:15px; font-weight:600;">Calificación *</label>
                            <div style="display:flex; gap:5px; margin:10px 0;">
                                @for($i = 5; $i >= 1; $i--)
                                    <label style="cursor:pointer; font-size:2em; color:#ddd;">
                                        <input type="radio" name="rating" value="{{ $i }}" required style="display:none;" onchange="updateStars(this)">
                                        <span class="star" data-rating="{{ $i }}">☆</span>
                                    </label>
                                @endfor
                            </div>
                            
                            <label style="display:block; margin-top:15px; font-weight:600;">Comentario</label>
                            <textarea name="comment" rows="4" maxlength="500" style="width:100%; padding:10px; border-radius:8px; border:1px solid #dcdcdc; margin-top:5px;" placeholder="Escribe tu experiencia... (Máx. 500 caracteres)"></textarea>
                            @error('comment')
                                <div style="color:#d7263d; margin-top:8px;">{{ $message }}</div>
                            @enderror
                            
                            @if($errors->has('review'))
                                <div style="color:#d7263d; margin-top:10px;">{{ $errors->first('review') }}</div>
                            @endif
                            
                            <button type="submit" style="background:#24a148; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; font-weight:600; margin-top:15px;">Publicar Reseña</button>
                        </form>
                    </div>
                @else
                    <div style="margin-bottom:20px; padding:15px; background:#e8f5e9; border-radius:8px;">
                        <p style="margin:0; color:#155724;">✓ Ya has dejado una reseña para este lugar.</p>
                        <form method="POST" action="{{ route('reviews.destroy', $userReview) }}" style="margin-top:10px;" onsubmit="return confirm('¿Eliminar tu reseña?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#d7263d; color:#fff; border:none; padding:5px 15px; border-radius:5px; cursor:pointer; font-size:0.9em;">Eliminar mi reseña</button>
                        </form>
                    </div>
                @endif
            @else
                <div style="margin-bottom:20px; padding:15px; background:#fff3cd; border-radius:8px; text-align:center;">
                    <p style="margin:0;"><a href="{{ route('login') }}" style="color:#24a148; font-weight:600;">Inicia sesión</a> para dejar una reseña.</p>
                </div>
            @endauth

            @if(session('status'))
                <div style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:20px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="margin-top:30px;">
                <h3 style="margin-bottom:20px;">Todas las reseñas ({{ $reviews->count() }})</h3>
                @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div style="padding:20px; border-bottom:1px solid #ececec; margin-bottom:15px; background:#fff; border-radius:8px;">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:10px;">
                                <div>
                                    <strong style="color:#1c1c1a; font-size:1.1em;">{{ $review->usuario->name }}</strong>
                                    <div style="color:#ffc107; font-size:1.2em; margin:5px 0;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <span style="color:#6c6c68; font-size:0.9em;">{{ $review->fecha_comentario->format('d/m/Y') }}</span>
                            </div>
                            @if($review->comment)
                                <p style="color:#1c1c1a; margin:10px 0 15px 0; line-height:1.6;" id="comment-text-{{ $review->id }}">{{ $review->comment }}</p>
                            @endif
                            @auth
                                @if($review->user_id == auth()->id())
                                    <div style="display:flex; gap:10px; margin-top:12px; flex-wrap:wrap;">
                                        <button type="button" class="btn-edit" data-id="{{ $review->id }}" style="background:#3498db; color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-size:0.9em; font-weight:600; transition:all 0.3s ease;">
                                             Editar
                                        </button>
                                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('¿Estás seguro de eliminar tu reseña?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:#e74c3c; color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-size:0.9em; font-weight:600; transition:all 0.3s ease;">
                                                 Eliminar
                                            </button>
                                        </form>
                                    </div>

                                    <form method="POST" action="{{ route('reviews.update', $review) }}" class="edit-form" id="edit-form-{{ $review->id }}" style="display:none; margin-top:15px; padding:15px; background:#f9f9f9; border-radius:8px; border-left:4px solid #3498db;">
                                        @csrf
                                        @method('PUT')
                                        <h4 style="margin-top:0; color:#2c3e50;">Editar tu reseña</h4>
                                        <label style="display:block; font-weight:600; margin-bottom:8px; color:#2c3e50;">Calificación *</label>
                                        <div style="display:flex; gap:5px; margin:8px 0 12px 0;">
                                            @for($i = 5; $i >= 1; $i--)
                                                <label style="cursor:pointer; font-size:1.8em; color:#ddd; transition:color 0.2s ease;">
                                                    <input type="radio" name="rating" value="{{ $i }}" style="display:none;" onchange="updateEditStars(this)" {{ $review->rating == $i ? 'checked' : '' }}>
                                                    <span class="edit-star" data-rating="{{ $i }}" data-review="{{ $review->id }}" style="cursor:pointer;">{{ $review->rating >= $i ? '★' : '☆' }}</span>
                                                </label>
                                            @endfor
                                        </div>
                                        <label style="display:block; font-weight:600; margin-bottom:8px; color:#2c3e50;">Comentario *</label>
                                        <textarea name="comment" rows="3" maxlength="500" style="width:100%; padding:10px; border-radius:6px; border:2px solid #e0e0e0; margin-bottom:8px; font-family:inherit; font-size:0.95em;">{{ $review->comment }}</textarea>
                                        <div style="text-align:right; font-size:0.85rem; color:#999; margin-bottom:12px;">
                                            <span id="char-count-{{ $review->id }}">{{ strlen($review->comment) }}</span>/500 caracteres
                                        </div>
                                        @error('comment')
                                            <div style="color:#d7263d; margin-bottom:8px; font-size:0.9em;">{{ $message }}</div>
                                        @enderror
                                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                            <button type="submit" style="background:#27ae60; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">
                                                 Guardar cambios
                                            </button>
                                            <button type="button" onclick="cancelEdit({{ $review->id }})" style="background:#95a5a6; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">
                                                ✕ Cancelar
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                @else
                    <p style="text-align:center; padding:40px; color:#6c6c68;">Aún no hay reseñas para este lugar. ¡Sé el primero en comentar!</p>
                @endif
            </div>
        </section>

        <div class="botones">
            <a href="{{ route('lugares') }}" class="boton-volver">Volver a Lugares</a>
        </div>
    </div>
    @auth
    <form id="favorite-form" method="POST" action="{{ route('favorites.store') }}" style="display:none;">
        @csrf
        <input type="hidden" name="place_id" value="{{ $place->id }}">
    </form>
    <script>
        document.getElementById('btn-favorite').addEventListener('click', function() {
            document.getElementById('favorite-form').submit();
        });
        
        // Actualizar estrellas al seleccionar rating
        function updateStars(radio) {
            const rating = parseInt(radio.value);
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                if (starRating <= rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }

        // Cancelar edición de reseña
        function cancelEdit(reviewId) {
            const form = document.getElementById('edit-form-' + reviewId);
            if (form) {
                form.style.display = 'none';
            }
        }

        // Actualizar estrellas en formulario de edición
        function updateEditStars(input) {
            const rating = parseInt(input.value);
            const reviewId = input.closest('form').id.replace('edit-form-', '');
            const form = document.getElementById('edit-form-' + reviewId);
            const stars = form.querySelectorAll('.edit-star');
            stars.forEach(star => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                if (starRating <= rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }
                    star.textContent = '★';
                    star.style.color = '#ffc107';
                } else {
                    star.textContent = '☆';
                    star.style.color = '#ddd';
                }
            });
        }
        
        // Hover en estrellas
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                const stars = document.querySelectorAll('.star');
                stars.forEach((s, index) => {
                    const sRating = parseInt(s.getAttribute('data-rating'));
                    if (sRating <= rating) {
                        s.style.color = '#ffc107';
                    }
                });
            });
            
            star.addEventListener('mouseleave', function() {
                const selected = document.querySelector('input[name="rating"]:checked');
                if (selected) {
                    updateStars(selected);
                } else {
                    document.querySelectorAll('.star').forEach(s => {
                        s.textContent = '☆';
                        s.style.color = '#ddd';
                    });
                }
            });
            
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                document.querySelector(`input[value="${rating}"]`).checked = true;
                updateStars(document.querySelector(`input[value="${rating}"]`));
            });
        });

        // Edit buttons: toggle edit form
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const form = document.getElementById('edit-form-' + id);
                if (form.style.display === 'none' || !form.style.display) {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });
        });

        // Cancel edit
        document.querySelectorAll('.btn-cancel-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const form = document.getElementById('edit-form-' + id);
                if (form) form.style.display = 'none';
            });
        });

        // Actualizar contador de caracteres en formularios de edición
        document.querySelectorAll('form.edit-form textarea[name="comment"]').forEach(textarea => {
            const form = textarea.closest('form');
            const reviewId = form.id.replace('edit-form-', '');
            const charCount = document.getElementById('char-count-' + reviewId);
            
            if (charCount) {
                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }
        });
            });
        });

        // Edit stars interactions
        document.querySelectorAll('.edit-star').forEach(star => {
            const reviewId = star.getAttribute('data-review');
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                document.querySelectorAll(`#edit-form-${reviewId} .edit-star`).forEach(s => {
                    const r = parseInt(s.getAttribute('data-rating'));
                    s.style.color = r <= rating ? '#ffc107' : '#ddd';
                    s.textContent = r <= rating ? '★' : '☆';
                });
            });
            star.addEventListener('mouseleave', function() {
                const selected = document.querySelector(`#edit-form-${reviewId} input[name="rating"]:checked`);
                if (selected) {
                    const sel = parseInt(selected.value);
                    document.querySelectorAll(`#edit-form-${reviewId} .edit-star`).forEach(s => {
                        const r = parseInt(s.getAttribute('data-rating'));
                        s.style.color = r <= sel ? '#ffc107' : '#ddd';
                        s.textContent = r <= sel ? '★' : '☆';
                    });
                }
            });
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                const input = document.querySelector(`#edit-form-${reviewId} input[value="${rating}"]`);
                if (input) input.checked = true;
                document.querySelectorAll(`#edit-form-${reviewId} .edit-star`).forEach(s => {
                    const r = parseInt(s.getAttribute('data-rating'));
                    s.style.color = r <= rating ? '#ffc107' : '#ddd';
                    s.textContent = r <= rating ? '★' : '☆';
                });
            });
        });
    </script>
    @endauth
</body>
</html>

