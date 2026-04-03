<?php

namespace App\Models;


use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    protected $table = 'usuarios';

    // CLAVE PRIMARIA (muy importante)
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'telefono',
        'password',
        'fecha_registro',
        'is_admin',
        'tipo_usuario',
        'foto_perfil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'fecha_registro' => 'datetime',
    ];

    public function sendPasswordResetNotification($token): void
    {
        $frontendUrl = env('FRONTEND_URL', config('app.url'));
        $resetUrl = rtrim($frontendUrl, '/') . '/reset-password?token=' . $token . '&email=' . urlencode($this->email);

        $fromName    = env('MAIL_FROM_NAME', 'Risaralda EcoTurismo');
        $fromAddress = env('MAIL_FROM_ADDRESS', 'noreply@sgallego.dev');
        $apiKey      = env('RESEND_API_KEY');

        $html = '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>@import url(\'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap\');</style>
</head>
<body style="margin:0;padding:0;font-family:Inter,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#071a0e 0%,#0d2619 50%,#071a0e 100%);min-height:100vh;">
    <tr><td align="center" style="padding:60px 20px;">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;">

        <!-- Badge superior -->
        <tr><td align="center" style="padding-bottom:32px;">
          <span style="display:inline-block;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.3);color:#4ade80;font-size:12px;font-weight:700;padding:8px 22px;border-radius:999px;letter-spacing:1.5px;text-transform:uppercase;">🔐 Seguridad de cuenta</span>
        </td></tr>

        <!-- Título -->
        <tr><td align="center" style="padding-bottom:24px;">
          <h1 style="margin:0;color:#ffffff;font-size:44px;font-weight:800;line-height:1.15;text-align:center;">Recupera tu<br>contraseña</h1>
        </td></tr>

        <!-- Subtítulo -->
        <tr><td align="center" style="padding-bottom:44px;">
          <p style="margin:0 auto;color:rgba(255,255,255,.65);font-size:16px;line-height:1.75;text-align:center;max-width:400px;">Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong style="color:#4ade80;">Risaralda EcoTurismo</strong>.</p>
        </td></tr>

        <!-- Pills -->
        <tr><td align="center" style="padding-bottom:50px;">
          <table cellpadding="0" cellspacing="8" align="center">
            <tr>
              <td><span style="display:inline-block;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);font-size:13px;font-weight:500;padding:10px 20px;border-radius:999px;">🔒 Enlace seguro</span></td>
              <td><span style="display:inline-block;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);font-size:13px;font-weight:500;padding:10px 20px;border-radius:999px;">⏱ Válido 60 min</span></td>
              <td><span style="display:inline-block;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);font-size:13px;font-weight:500;padding:10px 20px;border-radius:999px;">🌿 Un solo uso</span></td>
            </tr>
          </table>
        </td></tr>

        <!-- Botón CTA -->
        <tr><td align="center" style="padding-bottom:50px;">
          <a href="' . $resetUrl . '" style="display:inline-block;background:#4ade80;color:#071a0e;text-decoration:none;padding:18px 52px;border-radius:10px;font-size:17px;font-weight:700;letter-spacing:.2px;">Restablecer contraseña &nbsp;→</a>
        </td></tr>

        <!-- Aviso -->
        <tr><td align="center">
          <p style="margin:0;color:rgba(255,255,255,.35);font-size:13px;line-height:1.8;text-align:center;">Si no solicitaste este cambio, puedes ignorar este correo.<br>Tu contraseña permanecerá sin cambios.</p>
        </td></tr>

        <!-- Divider -->
        <tr><td style="padding:36px 0 20px;">
          <div style="border-top:1px solid rgba(255,255,255,.08);"></div>
        </td></tr>

        <!-- Footer -->
        <tr><td align="center">
          <p style="margin:0;color:rgba(255,255,255,.25);font-size:12px;">© ' . date('Y') . ' Risaralda EcoTurismo &nbsp;·&nbsp; sgallego.dev</p>
        </td></tr>

      </table>
    </td></tr>
  </table>
</body>
</html>';

        $resend = \Resend::client($apiKey);
        $resend->emails->send([
            'from'    => "{$fromName} <{$fromAddress}>",
            'to'      => [$this->email],
            'subject' => 'Recuperación de contraseña - Risaralda EcoTurismo',
            'html'    => $html,
        ]);
    }


    /**
     * Accessor para foto_perfil
     */
    public function getFotoPerfilAttribute($value)
    {
        if (!$value || $value === "null") {
            return null;
        }

        // Si ya es una URL absoluta válida o data source, devolverla
        if (preg_match('/^https?:\/\//', $value) || strpos($value, 'data:') === 0) {
            return $value;
        }

        // Si ya viene como ruta absoluta relativa al dominio, respetarla tal cual.
        if (strpos($value, '/api/profile/photo/') === 0) {
            return $value;
        }

        $path = parse_url((string) $value, PHP_URL_PATH) ?: (string) $value;
        $filename = basename((string) $path);

        if (!$filename) {
            return null;
        }

        // Devolvemos SIEMPRE el endpoint sin extensión estática para que los Reverse Proxies (Nginx/Apache) no la intercepten.
        return '/api/profile/photo/stream?f=' . rawurlencode($filename);
    }

    /**
     * RELACIONES
     */

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function placesManaged()
    {
        return $this->belongsToMany(
            Place::class,
            'place_company_users',
            'company_user_id',
            'place_id'
        )->withPivot('es_principal')
         ->withTimestamps();
    }

    public function companyReservations()
    {
        return $this->hasMany(CompanyReservation::class, 'company_user_id', 'id');
    }

    public function placeAssignments()
    {
        return $this->hasMany(PlaceCompanyUser::class, 'company_user_id', 'id');
    }

    /**
     * Helpers
     */

    public function isCompanyUser(): bool
    {
        return $this->tipo_usuario === 'empresa';
    }

    public function isAdmin(): bool
    {
        return $this->is_admin || $this->tipo_usuario === 'admin';
    }
}