<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = $this->faker->randomElement(DocumentType::values());

        return [
            'name' => $this->faker->name,
            'document_type' => $documentType,
            'document_number' => match ($documentType) {
                DocumentType::CPF->value => MakeCpf::make(),
                DocumentType::CNPJ->value => MakeCnpj::make(),
            },
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->numerify('###########'),
            'address' => $this->faker->address,
        ];
    }
}
