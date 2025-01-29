<?php

namespace Tests\Feature\Models;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'brasilapi.com.br/api/cnpj/v1/*' => Http::response('', 200),
        ]);
    }

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
                    'document_type',
                    'document_number',
                    'email',
                    'phone',
                    'address',
                ],
            ],
        ]);

        $response->assertJsonCount(5, 'data');

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully retrieved.',
        ]);
    }

    #[DataProvider('paginationDataProvider')]
    public function test_get_supplier_pagination($perPage)
    {
        Supplier::factory()->count(15)->create();
        $maxPage = ceil(15 / $perPage);

        for ($page = 1; $page <= $maxPage; $page++) {
            $response = $this->get("/api/suppliers?per_page=$perPage&page=$page");

            $count = $page < $maxPage ? $perPage : 15 % $perPage;
            $count = $count === 0 ? $perPage : $count;

            $response->assertStatus(200);

            $response->assertJsonCount($count, 'data');

            $response->assertJsonFragment([
                'status' => 'success',
                'message' => 'Data successfully retrieved.',
            ]);
        }
    }

    public static function paginationDataProvider(): array
    {
        return [
            [1],
            [5],
            [10],
            [15],
        ];
    }

    public function test_get_supplier_with_search()
    {
        $suppliers = Supplier::factory()->count(5)->create();

        foreach ($suppliers as $supplier) {
            $this->get("/api/suppliers?document_number={$supplier->document_number}")
                ->assertJsonCount(1, 'data')
                ->assertJsonFragment(['document_number' => $supplier->document_number]);
        }

        $this->get('/api/suppliers?document_number=1234567890')
            ->assertJsonCount(0, 'data');
    }


    public function test_store_supplier()
    {
        $data = Supplier::factory()->make()->toArray();

        $response = $this->post('/api/suppliers', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'document_type',
                'document_number',
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

    public function test_store_supplier_and_clear_phone()
    {
        $data = Supplier::factory()->make()->toArray();
        $data['phone'] = $this->faker->numerify('(##) #####-####');

        $response = $this->post('/api/suppliers', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'document_type',
                'document_number',
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
            'phone' => preg_replace('/[^0-9]/', '', $data['phone']),
        ]);
    }

    #[DataProvider('invalidDataProvider')]
    public function test_store_supplier_with_invalid_data($data, $field)
    {
        $response = $this->withHeader('Accept', 'application/json')->post('/api/suppliers', $data);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'status',
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
            [['name' => str_repeat('a', 256), 'email' => '', 'phone' => '', 'address' => ''], 'name'],
            [['name' => 'Supplier Company', 'email' => '', 'phone' => '', 'address' => ''], 'email'],
            [['name' => 'Supplier Company', 'email' => str_repeat('a', 256) . '@test.com', 'phone' => '', 'address' => ''], 'email'],
            [['name' => 'Supplier Company', 'email' => 'test.test', 'phone' => '', 'address' => ''], 'email'],
            [['name' => 'Supplier Company', 'email' => 'test@test.com', 'phone' => '', 'address' => ''], 'phone'],
            [['name' => 'Supplier Company', 'email' => 'test@test.com', 'phone' => '9999999999999999', 'address' => ''], 'phone'],
            [['name' => 'Supplier Company', 'email' => 'test@test.com', 'phone' => '081234567890', 'address' => ''], 'address'],
        ];
    }

    public function test_update_supplier()
    {
        $supplier = Supplier::factory()->create();

        $data = Supplier::factory()->make()->toArray();

        $response = $this->put("/api/suppliers/{$supplier->id}", $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'document_type',
                'document_number',
                'email',
                'phone',
                'address',
            ],
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully updated.',
        ]);

        $response->assertJsonFragment($data);

        $this->assertDatabaseHas('suppliers', $data);
    }


    public function test_update_supplier_not_found()
    {
        $response = $this->withHeader('Accept', 'application/json')->put('/api/suppliers/1', []);

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message',
        ]);

        $response->assertJsonFragment([
            'status' => 'error',
            'message' => 'Supplier not found.',
        ]);
    }

    #[DataProvider('invalidDataProvider')]
    public function test_update_supplier_with_invalid_data($data, $field)
    {
        $supplier = Supplier::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->put("/api/suppliers/{$supplier->id}", $data);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                $field,
            ],
        ]);
    }

    public function test_delete_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->delete("/api/suppliers/{$supplier->id}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Data successfully deleted.',
        ]);

        $this->assertDatabaseMissing('suppliers', $supplier->toArray());
    }

    public function test_delete_supplier_not_found()
    {
        $response = $this->withHeader('Accept', 'application/json')->delete('/api/suppliers/1');

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message',
        ]);

        $response->assertJsonFragment([
            'status' => 'error',
            'message' => 'Supplier not found.',
        ]);
    }
}
