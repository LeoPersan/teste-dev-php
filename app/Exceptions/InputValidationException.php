<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InputValidationException extends Exception
{
    protected $code = 422;

    public function __construct(protected Validator $validator) {
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $this->getMessage(),
            'errors' => $this->validator->errors(),
        ], 422);
    }
}
