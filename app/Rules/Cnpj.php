<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || ! self::validateCnpj($value)) {
            $fail('The :attribute is invalid.');
        }
    }

    public static function validateCnpj(string $cnpj): bool
    {
        $cnpj = (string) preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 12; $i < 14; $i++) {
            for ($j = 0, $sum = 0, $factor = $i - 7; $j < $i; $j++, $factor--) {
                $sum += ((int) $cnpj[$j]) * $factor;
                if ($factor == 2) {
                    $factor = 10;
                }
            }
            $sum = (10 * $sum) % 11;
            $sum = $sum >= 10 ? 0 : $sum;
            if ($cnpj[$j] != $sum) {
                return false;
            }
        }

        return true;
    }
}
