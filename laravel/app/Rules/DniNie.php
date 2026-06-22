<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class DniNie implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('El :attribute no és un DNI/NIE vàlid.');

            return;
        }

        $value = strtoupper($value);

        // NIE: X/Y/Z + 7 dígits + lletra
        if (preg_match('/^[XYZ]\d{7}[A-Z]$/', $value)) {
            $value = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], $value);
        }

        // DNI: 8 dígits + lletra
        if (! preg_match('/^\d{8}[A-Z]$/', $value)) {
            $fail('El :attribute ha de tenir format DNI o NIE vàlid.');

            return;
        }

        $numero = (int) substr($value, 0, -1);
        $lletra = substr($value, -1);
        $lletres = 'TRWAGMYFPDXBNJZSQVHLCKE';

        if ($lletres[$numero % 23] !== $lletra) {
            $fail('La lletra del :attribute no és correcta.');
        }
    }
}
