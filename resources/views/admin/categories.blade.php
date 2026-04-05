<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Administración</title>
    <style>
        body { background:#f5f7fb; font-family: 'Montserrat', sans-serif; margin:0; padding:0; }
        .container { max-width:1200px; margin:30px auto; padding:0 30px; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:30px; margin-bottom:30px; }
        .card { background:#fff; border-radius:12px; padding:25px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .status { background:#d4edda; color:#155724; border-radius:8px; padding:12px 16px; margin-bottom:20px; }
        label { display:block; font-weight:600; margin-top:12px; color:#1c1c1a; }
        input[type="text"], textarea { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #dcdcdc; font-size:0.95rem; box-sizing:border-box; }
        textarea { min-height:90px; resize:vertical; }
        button { background:#24a148; border:none; color:#fff; padding:10px 18px; border-radius:8px; cursor:pointer; font-weight:600; margin-top:15px; }
        button.danger { background:#d7263d; }
        button.secondary { background:#004d40; }
        table { width:100%; border-collapse:collapse; margin-top:30px; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ececec; }
        th { background:#f9f9f9; color:#6c6c68; font-weight:600; }
        .actions { display:flex; gap:10px; }
        .table-wrap { width:100%; overflow-x:auto; }
        .table-wrap table { min-width:760px; }
        @media (max-width:900px) { .grid { grid-template-columns:1fr; } }
        @media (max-width:768px) {
            .container { margin:20px auto; padding:0 12px; }
            .card { padding:16px; }
            .actions { flex-wrap:wrap; }
        }
    </style>
</head>
<body>
    @include('components.header-admin')
    
    <div class="container">
        <h1 style="margin-bottom:30px; color:#1c1c1a;">Gestión de Categorías</h1>

        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="grid">
            <div class="card">
                <h2>Crear nueva categoría</h2>
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <label>Nombre</label>
                    <input type="text" name="name" required value="{{ old('name') }}">

                    <label>Descripción</label>
                    <textarea name="description">{{ old('description') }}</textarea>

                    <label>Icono (emoji o texto)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="Ej: , , ">

                    <button type="submit">Guardar categoría</button>
                </form>
            </div>

            <div class="card" style="background:#1b1b18; color:#fff;">
                <h2>Consejos</h2>
                <ul style="line-height:1.6; padding-left:18px;">
                    <li>Las categorías ayudan a organizar los lugares.</li>
                    <li>Puedes usar emojis como iconos (, , , etc.).</li>
                    <li>Los lugares pueden tener múltiples categorías.</li>
                    <li>Asigna categorías desde la edición de lugares.</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <h2>Listado de categorías</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Lugares</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td style="font-size:1.5em;">{{ $category->icon ?? '' }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td style="color:#6c6c68; font-size:0.9em;">{{ $category->slug }}</td>
                                <td>{{ $category->places_count }} lugares</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" style="width:150px; margin-right:5px;">
                                        <button type="submit" class="secondary" style="margin-top:0;">Actualizar</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger" style="margin-top:0;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color:#6c6c68;">No hay categorías creadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

