<?php

namespace Tests\Feature\Models;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_supplier()
    {
        Supplier::factory()->count(5)->create();

        $response = $this->get('/api/suppliers');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $response->assertJsonCount(5, 'data');

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully retrieved.',
        ]);
    }

    public function test_create_supplier()
    {
        $data = [
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'phone' => preg_replace('/[^0-9]/', '', $this->faker->phoneNumber),
            'address' => $this->faker->address,
        ];

        $response = $this->post('/api/suppliers', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'address',
            ],
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully created.',
        ]);

        $response->assertJsonFragment($data);

        $this->assertDatabaseHas('suppliers', $data);
    }

    public function test_create_supplier_and_clear_phone()
    {
        $phone = preg_replace('/([\d]{2})([\d]{4})([\d]{4,5})/', '($1) $2-$3', $this->faker->phoneNumber);
        $data = [
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'phone' => $phone,
            'address' => $this->faker->address,
        ];

        $response = $this->post('/api/suppliers', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'address',
            ],
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully created.',
        ]);

        $this->assertDatabaseHas('suppliers', [
            ...$data,
            'phone' => preg_replace('/[^0-9]/', '', $phone),
        ]);

    }

    #[DataProvider('invalidDataProvider')]
    public function test_create_supplier_with_invalid_data($data, $field)
    {
        $response = $this->withHeader('Accept', 'application/json')->post('/api/suppliers', $data);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                $field,
            ],
        ]);
    }

    public static function invalidDataProvider(): array
    {
        return [
            [['name' => '', 'email' => '', 'phone' => '', 'address' => ''], 'name'],
            [['name' => 'Supplier Company', 'email' => '', 'phone' => '', 'address' => ''], 'email'],
            [['name' => 'Supplier Company', 'email' => 'test.test', 'phone' => '', 'address' => ''], 'email'],
            [['name' => 'Supplier Company', 'email' => 'test@test.com', 'phone' => '', 'address' => ''], 'phone'],
            [['name' => 'Supplier Company', 'email' => 'test@test.com', 'phone' => '081234567890', 'address' => ''], 'address'],
        ];
    }
}
