<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_access_customer_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('customers.index'));

        $response->assertOk();
        $response->assertSee('Customers');
    }

    public function test_admin_can_store_customer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('customers.store'), [
            'name' => 'Nadia Permata',
            'phone' => '081234567890',
            'first_purchase_date' => '2026-05-01',
            'total_transactions' => 4,
            'total_spending' => 145000,
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Nadia Permata',
            'phone' => '081234567890',
            'total_transactions' => 4,
        ]);
    }

    public function test_admin_can_update_general_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('settings.general.update'), [
            'business_name' => 'Sedjati Coffee Express',
            'business_address' => 'Jl. Malioboro No. 99',
            'business_contact' => '081200001111',
        ]);

        $response->assertRedirect(route('settings.index'));
        $this->assertSame('Sedjati Coffee Express', Setting::getValue('business_name'));
    }

    public function test_admin_can_create_user_from_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('settings.users.store'), [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@sedjaticoffee.test',
            'password' => 'password123',
            'role' => 'kasir',
        ]);

        $response->assertRedirect(route('settings.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'kasirbaru@sedjaticoffee.test',
            'role' => 'kasir',
        ]);
    }

    public function test_admin_can_create_product_with_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $imageContent = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z0ioAAAAASUVORK5CYII='
        );

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'name' => 'Matcha Latte',
            'category' => 'Non Coffee',
            'price' => 32000,
            'stock' => 15,
            'description' => 'Creamy matcha latte.',
            'is_active' => '1',
            'image' => UploadedFile::fake()->createWithContent('matcha-latte.png', $imageContent),
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Produk::where('name', 'Matcha Latte')->firstOrFail();

        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_kasir_cannot_access_product_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get(route('products.index'));

        $response->assertForbidden();
    }

    public function test_kasir_cannot_access_customer_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get(route('customers.index'));

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
