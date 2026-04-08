<nav class="navbar-guest" style="background:#24a148; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="display:flex; align-items:center; gap:20px;">
        <a href="{{ route('pagcentral') }}" style="color:#fff; font-weight:bold; font-size:1.2em; text-decoration:none;"> EcoTurismo</a>
        <a href="{{ route('lugares') }}" style="color:#fff; text-decoration:none; padding:8px 15px; border-radius:5px; transition:background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">Lugares</a>
    </div>
    <div style="display:flex; align-items:center; gap:15px;">
        <a href="{{ route('login') }}" style="background:#22c55e; color:#ffffff; text-decoration:none; padding:8px 15px; border-radius:8px; font-weight:600; transition:background 0.18s, transform 0.12s;" onmouseover="this.style.background='#15803d'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#22c55e'; this.style.transform='none'">Iniciar Sesión</a>
        <a href="{{ route('registro') }}" style="background:#fff; color:#24a148; text-decoration:none; padding:8px 15px; border-radius:8px; font-weight:600; transition:background 0.18s, transform 0.12s;" onmouseover="this.style.background='#f0f0f0'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#fff'; this.style.transform='none'">Registrarse</a>
    </div>
</nav>

