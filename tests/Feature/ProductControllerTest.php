<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = env('API_KEY');
    }

    // Teste para o endpoint GET /products
    public function test_get_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/api/products', [
            'X-API-KEY' => $this->apiKey,
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    // Teste para o endpoint GET /products/{code}
    public function test_get_product_by_code()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products/' . $product->code, [
            'X-API-KEY' => $this->apiKey,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['code' => $product->code]);
    }

    // Teste para o endpoint PUT /products/{code}
    public function test_update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'product_name' => 'Novo Nome do Produto',
            'status' => 'published',
        ];

        $response = $this->put('/api/products/' . $product->code, $updatedData, [
            'X-API-KEY' => $this->apiKey,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $updatedData);
    }

    // Teste para o endpoint PUT /products/{code} com produto não encontrado
    public function test_update_non_existent_product()
    {
        $response = $this->put('/api/products/non-existent-code', [
            'product_name' => 'Novo Nome do Produto'
        ], [
            'X-API-KEY' => $this->apiKey,
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Product not found']);
    }
}
