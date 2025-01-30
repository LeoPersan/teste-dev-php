<?php

namespace App\Http\Requests;

use App\Exceptions\InputValidationException;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    protected function failedValidation($validator)
    {
        throw new InputValidationException($validator);
    }
}
