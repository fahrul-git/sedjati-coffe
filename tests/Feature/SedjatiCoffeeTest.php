<?php

namespace Tests\Feature;

use App\Models\Produk;
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
        $product = Produk::create([
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
            'table_number' => 'A01',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'item_option' => 'es', 'item_note' => 'Tanpa gula'],
            ],
        ]);

        $order = \App\Models\Pesanan::first();

        $response->assertRedirect(route('orders.payment.create', $order));
        $this->assertDatabaseHas('pesanan', [
            'customer_name' => 'Budi',
            'table_number' => 'A01',
            'user_id' => $kasir->id,
            'payment_status' => 'pending',
        ]);
        $this->assertDatabaseHas('detail_pesanan', [
            'produk_id' => $product->id,
            'quantity' => 2,
            'item_option' => 'es',
            'item_note' => 'Tanpa gula',
            'serving_type' => 'es',
        ]);
    }

    public function test_kasir_can_pay_order_with_qris(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $order = \App\Models\Pesanan::create([
            'order_number' => 'SDJ-TEST-001',
            'user_id' => $kasir->id,
            'customer_name' => 'Sinta',
            'order_date' => now(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => 50000,
        ]);

        $response = $this->actingAs($kasir)->post(route('orders.payment.store', $order), [
            'payment_method' => 'qris',
        ]);

        $response->assertRedirect(route('orders.receipt', $order));
        $this->assertDatabaseHas('pesanan', [
            'id' => $order->id,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);
    }

    public function test_kasir_can_pay_order_with_cash_and_get_change(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $order = \App\Models\Pesanan::create([
            'order_number' => 'SDJ-TEST-002',
            'user_id' => $kasir->id,
            'customer_name' => 'Raka',
            'order_date' => now(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => 42000,
        ]);

        $response = $this->actingAs($kasir)->post(route('orders.payment.store', $order), [
            'payment_method' => 'cash',
            'paid_amount' => 50000,
        ]);

        $response->assertRedirect(route('orders.receipt', $order));

        $order->refresh();

        $this->assertSame(8000.0, $order->change_amount);
        $this->assertDatabaseHas('pesanan', [
            'id' => $order->id,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);
    }
}
