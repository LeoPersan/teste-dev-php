<?php

namespace App\Rules;

use App\Enums\DocumentType;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjCpf implements DataAwareRule, ValidationRule
{
    /** @var array<string, mixed> */
    protected array $data = [];

    public function __construct(protected readonly bool $safe = true)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
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
        if (!is_string($value) || !$this->validateAux($value)) {
            $fail('The :attribute is invalid.');
        }
    }

    public function validateAux(string $cnpjCpf): bool
    {
        if (!is_string($this->data['document_type'])) {
            return false;
        }

        return match (DocumentType::from($this->data['document_type'])) {
            DocumentType::CPF => Cpf::validateCpf($cnpjCpf),
            DocumentType::CNPJ => Cnpj::validateCnpj($cnpjCpf)
                && ($this->safe ? SafeCnpj::validateBrasilApi($cnpjCpf) : true),
        };
    }
}
