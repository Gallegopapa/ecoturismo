@auth
<nav class="navbar-user" style="background:#24a148; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex; align-items:center; gap:20px;">
        <a href="{{ route('pagcentral') }}" style="color:#fff; font-weight:bold; font-size:1.2em; text-decoration:none;"> EcoTurismo</a>
        <a href="{{ url('/lugares') }}" style="color:#fff; text-decoration:none; padding:8px 15px; border-radius:5px; transition:background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">Lugares</a>
        <a href="{{ route('web.reservations.index') }}" style="color:#fff; text-decoration:none; padding:8px 15px; border-radius:5px; transition:background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">Mis Reservas</a>
        <a href="{{ route('favorites.index') }}" style="color:#fff; text-decoration:none; padding:8px 15px; border-radius:5px; transition:background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">Favoritos</a>
    </div>
    <div style="display:flex; align-items:center; gap:15px;">
        <span style="color:#fff; font-weight:600;"> {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
            @csrf
            <button type="submit" style="background:#fff; color:#24a148; border:none; padding:8px 15px; border-radius:5px; cursor:pointer; font-weight:600;">Cerrar Sesión</button>
        </form>
    </div>
</nav>
@else
<nav class="navbar-guest" style="background:#24a148; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex; align-items:center; gap:20px;">
        <a href="{{ route('pagcentral') }}" style="color:#fff; font-weight:bold; font-size:1.2em; text-decoration:none;"> EcoTurismo</a>
        <a href="{{ url('/lugares') }}" style="color:#fff; text-decoration:none; padding:8px 15px; border-radius:5px; transition:background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">Lugares</a>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <a href="{{ route('login') }}" style="background:#fff; color:#24a148; border:none; padding:8px 15px; border-radius:5px; text-decoration:none; font-weight:600;">Iniciar Sesión</a>
        <a href="{{ route('registro') }}" style="color:#fff; padding:8px 15px; border-radius:5px; border:1px solid rgba(255,255,255,0.15); text-decoration:none;">Registrarse</a>
    </div>
</nav>
@endauth

