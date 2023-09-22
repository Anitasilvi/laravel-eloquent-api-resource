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
                    "created_at" => $product->created_at->toJSON(),
                    "updated_at" => $product->updated_at->toJSON()
                ]
            ]);
    }

    public function testCollectionWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get("/api/products")
            ->assertStatus(200);

        $names = $response->json("data.*.name");
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Food", $names);
        }
        for ($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Gadget", $names);
        }
    }
}