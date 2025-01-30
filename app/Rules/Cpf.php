<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !self::validateCpf($value)) {
            $fail('The :attribute is invalid.');
        }
    }

    public static function validateCpf(string $cpf): bool
    {
        $cpf = (string) preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            for ($j = 0, $sum = 0; $j < $i; $j++) {
                $sum += ((int) $cpf[$j]) * (($i + 1) - $j);
            }
            $sum = 11 - ($sum % 11);
            $sum = $sum >= 10 ? 0 : $sum;
            if ($cpf[$j] != $sum) {
                return false;
            }
        }

        return true;
    }
}
