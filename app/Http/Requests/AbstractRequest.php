<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\InputValidationException;

abstract class AbstractRequest extends FormRequest
{
    protected function failedValidation($validator)
    {
        throw new InputValidationException($validator);
    }
}
