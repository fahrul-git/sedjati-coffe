<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SedjatiCoffeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Login Sedjati Coffee');
    }

    public function test_admin_can_access_product_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('products.index'));

        $response->assertOk();
    }

    public function test_kasir_cannot_access_product_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get(route('products.index'));

        $response->assertForbidden();
    }

    public function test_kasir_can_create_order(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::create([
            'name' => 'Americano',
            'slug' => 'americano',
            'category' => 'Coffee',
            'price' => 25000,
            'stock' => 10,
            'description' => 'Hot americano.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($kasir)->post(route('orders.store'), [
            'customer_name' => 'Budi',
            'notes' => 'Tanpa gula',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi',
            'user_id' => $kasir->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
