<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalizedEmail = strtolower(trim((string) $value));

        // Extraer el dominio del email
        $emailParts = explode('@', $normalizedEmail);

        // Validar que tenga exactamente 2 partes (usuario@dominio)
        if (count($emailParts) !== 2 || $emailParts[0] === '' || $emailParts[1] === '') {
            $fail('El correo electrónico debe ser válido.');
            return;
        }

        // Validar que el dominio sea exclusivamente gmail.com
        if ($emailParts[1] !== 'gmail.com') {
            $fail('Solo se permiten correos con dominio @gmail.com.');
        }
    }
}
