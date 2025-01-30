<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class SafeCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !self::validateBrasilApi($value)) {
            $fail('The :attribute is invalid.');
        }
    }

    public static function validateBrasilApi(string $cnpj): bool
    {
        return Http::get("https://brasilapi.com.br/api/cnpj/v1/$cnpj")->successful();
    }
}
