<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {email? : Correo destino (por defecto el MAIL_FROM_ADDRESS)}';
    protected $description = 'Envía un correo de prueba para verificar la configuración SMTP';

    public function handle(): int
    {
        $to = $this->argument('email') ?? config('mail.from.address');

        $this->info("Configuración SMTP actual:");
        $this->line("  Mailer:     " . config('mail.default'));
        $this->line("  Host:       " . config('mail.mailers.smtp.host'));
        $this->line("  Port:       " . config('mail.mailers.smtp.port'));
        $this->line("  Username:   " . config('mail.mailers.smtp.username'));
        $this->line("  Encryption: " . config('mail.mailers.smtp.scheme'));
        $this->line("  From:       " . config('mail.from.address'));
        $this->line("  Enviando a: {$to}");
        $this->newLine();

        try {
            Mail::raw(
                "Este es un correo de prueba enviado desde RisaraldaEcoTurismo.\n\n"
                . "Si ves este mensaje, el SMTP está configurado correctamente.\n\n"
                . "Hora: " . now()->toDateTimeString(),
                function ($message) use ($to) {
                    $message->to($to)
                        ->subject(' Prueba SMTP - Risaralda EcoTurismo');
                }
            );

            $this->info(" Correo enviado correctamente a: {$to}");
            return 0;
        } catch (\Exception $e) {
            $this->error(" Error al enviar correo:");
            $this->error($e->getMessage());
            return 1;
        }
    }
}
