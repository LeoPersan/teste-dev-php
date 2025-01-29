<?php

namespace App\Enums;

enum DocumentType: string
{
    use EnumToArray;

    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
