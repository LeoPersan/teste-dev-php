<?php

namespace Tests\Feature\Models;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

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
}
