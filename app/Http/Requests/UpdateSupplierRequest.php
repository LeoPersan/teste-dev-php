<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'max:4', Rule::enum(DocumentType::class)],
            'document_number' => ['required', 'string', 'max:14'],
            'name' => ['string', 'min:3', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'phone' => ['string', 'max:15'],
            'address' => ['string'],
        ];
    }
}
