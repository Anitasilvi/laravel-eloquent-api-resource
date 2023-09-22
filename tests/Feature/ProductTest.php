<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testProduct()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name,
                    ],
                    "price" => $product->price,
                    "is_expensive" => $product->price > 1000,
                    "created_at" => $product->created_at->toJSON(),
                    "updated_at" => $product->updated_at->toJSON()
                ]
            ]);
    }

    public function testProductsPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get("/api/products-paging")
            ->assertStatus(200);

        self::assertNotNull($response->json("links"));
        self::assertNotNull($response->json("meta"));
        self::assertNotNull($response->json("data"));
    }

    public function testAdditionalMetadata()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $response = $this->get('/api/products-debug/' . $product->id)
            ->assertStatus(200)
            ->assertJson([
                "author" => "Anita Silvi Ferdina",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price
                ]
            ]);
        self::assertNotNull($response->json("server_time"));
    }
}
