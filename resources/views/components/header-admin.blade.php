<style>
    .navbar-admin {
        background: #1c1c1a;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar-admin-left,
    .navbar-admin-right {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .navbar-admin-link {
        color: #fff;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .navbar-admin-link:hover {
        background: #2a2a28;
    }

    .navbar-admin-brand {
        color: #24a148;
        font-weight: bold;
        font-size: 1.2em;
        text-decoration: none;
    }

    .navbar-admin-user {
        color: #fff;
        font-weight: 600;
    }

    .navbar-admin-role {
        color: #24a148;
        background: #0d3d1f;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.85em;
    }

    .navbar-admin-logout {
        background: #d7263d;
        color: #fff;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .navbar-admin {
            padding: 12px;
        }

        .navbar-admin-left,
        .navbar-admin-right {
            width: 100%;
        }

        .navbar-admin-link {
            padding: 7px 10px;
        }

        .navbar-admin-user {
            font-size: 0.9rem;
        }
    }
</style>

<nav class="navbar-admin">
    <div class="navbar-admin-left">
        <a href="{{ route('pagcentral') }}" class="navbar-admin-brand"> EcoTurismo</a>
        <a href="{{ route('admin.dashboard') }}" class="navbar-admin-link">Dashboard</a>
        <a href="{{ route('admin.places.index') }}" class="navbar-admin-link">Lugares</a>
        <a href="{{ route('admin.reservations.index') }}" class="navbar-admin-link">Reservas</a>
        <a href="{{ route('admin.categories.index') }}" class="navbar-admin-link">Categorías</a>
    </div>
    <div class="navbar-admin-right">
        <span class="navbar-admin-user"> {{ auth()->user()->name }}</span>
        <span class="navbar-admin-role">ADMIN</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
            @csrf
            <button type="submit" class="navbar-admin-logout">Cerrar Sesión</button>
        </form>
    </div>
</nav>

