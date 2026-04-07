<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel ADMIN - Ecoturismo</title>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
</head>
<body>
    @include('components.header-admin')
    
    <main class="container">
        <h1>Panel ADMIN</h1>

        <div id="status-message" class="status-message"></div>

        <div class="tabs">
            <button id="tab-places" class="active">Lugares</button>
            <button id="tab-users">Usuarios</button>
        </div>

        <section id="places-panel" class="panel">
            <h2 id="form-title">Crear lugar</h2>
            <form id="place-form" enctype="multipart/form-data">
                <input type="hidden" id="place-id" />
                <label>
                    Nombre<br/>
                    <input id="name" required />
                </label>
                <label>
                    Ubicación<br/>
                    <input id="location" />
                </label>
                <label>
                    Descripción<br/>
                    <textarea id="description" rows="4"></textarea>
                </label>
                <label>
                    Imagen<br/>
                    <input id="image" type="file" accept="image/*" />
                </label>
                <div class="actions">
                    <button id="save" type="submit">Guardar</button>
                    <button id="cancel" type="button">Cancelar</button>
                </div>
            </form>

            <h2>Lista de lugares</h2>
            <div class="table-wrapper">
                <table id="places-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>

        <section id="users-panel" class="panel" style="display:none">
            <h2>Usuarios</h2>
            <form id="user-form">
                <input type="hidden" id="user-id" />
                <label>
                    Nombre<br/>
                    <input id="user-name" required />
                </label>
                <label>
                    Email<br/>
                    <input id="user-email" type="email" required />
                </label>
                <label id="user-password-label">
                    Contraseña (opcional - si no se proporciona, se generará una automáticamente)<br/>
                    <input id="user-password" type="password" minlength="6" maxlength="20" />
                    <small style="display:block;color:#666;margin-top:4px;">Dejar vacío para generar una contraseña aleatoria segura (6-20 caracteres)</small>
                </label>
                <label>
                    Rol<br/>
                    <select id="user-role">
                        <option value="user">Usuario</option>
                        <option value="admin">Admin</option>
                    </select>
                </label>
                <div class="actions">
                    <button id="user-save" type="submit">Guardar usuario</button>
                    <button id="user-cancel" type="button">Cancelar</button>
                </div>
            </form>

            <div class="table-wrapper">
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="{{ asset('js/admin-panel.js') }}"></script>
</body>
</html>

