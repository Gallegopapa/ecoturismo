<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ValidPhone implements Rule
{
    /**
     * Patrón para validar teléfonos
     * Acepta:
     * - +XX XXXXXXXXX (formato internacional)
     * - XXXXXXXXXX (10+ dígitos)
     * - (XXX) XXX-XXXX (formato USA)
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remover espacios y caracteres especiales para contar dígitos
        $digitsOnly = preg_replace('/[^\d+]/', '', $value);
        $digitCount = strlen(preg_replace('/[^\d]/', '', $digitsOnly));

        // Validar que tenga entre 7 y 15 dígitos
        if ($digitCount < 7 || $digitCount > 15) {
            $fail('El teléfono debe contener entre 7 y 15 dígitos.');
        }

        // Validar que sea un formato reconocible
        $validFormats = [
            '/^\+\d{1,3}\s?\d{6,14}$/',                    // Internacional: +XX XXXXXXXXX
            '/^\d{7,15}$/',                                  // Números solo: 1234567
            '/^\+\d{1,3}-\d{1,4}-\d{1,4}-\d{1,4}$/',       // +XX-XXX-XXX-XXX
            '/^\(\d{3}\)\s?\d{3}-?\d{4}$/',                 // (XXX) XXX-XXXX
        ];

        $isValid = false;
        foreach ($validFormats as $pattern) {
            if (preg_match($pattern, $value)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail('El formato del teléfono no es válido. Use +CC 1234567890 o 1234567890.');
        }
    }
}
