<?php

namespace App\Rules;

use App\Enums\DocumentType;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjCpf implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function __construct(protected readonly bool $safe = true) {
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->validateAux($value)) {
            $fail("The :attribute is invalid.");
        }
    }

    public function validateAux(string $cnpjCpf): bool
    {
        return match ($this->data['document_type']) {
            DocumentType::CPF->value => Cpf::validateCpf($cnpjCpf),
            DocumentType::CNPJ->value => Cnpj::validateCnpj($cnpjCpf)
                                            && ($this->safe ? SafeCnpj::validateBrasilApi($cnpjCpf) : true),
        };
    }
}
