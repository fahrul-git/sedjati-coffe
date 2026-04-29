<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $kasir = User::query()
            ->where('role', 'kasir')
            ->get();

        $produk = Produk::query()
            ->where('is_active', true)
            ->get();

        if ($kasir->isEmpty() || $produk->isEmpty()) {
            return;
        }

        Pesanan::query()->delete();

        $namaPelanggan = [
            'Andi', 'Budi', 'Citra', 'Dewi', 'Eka', 'Farhan', 'Gita', 'Hana', 'Indra', 'Joko',
            'Karin', 'Lukman', 'Maya', 'Nanda', 'Oki', 'Putri', 'Raka', 'Sinta', 'Tio', 'Vina',
        ];

        $productOptions = [
            'Espresso' => ['panas', 'es'],
            'Americano' => ['panas', 'es'],
            'Cappuccino' => ['panas', 'es'],
            'Cafe Latte' => ['panas', 'es'],
            'Mocha' => ['panas', 'es'],
            'Caramel Macchiato' => ['panas', 'es'],
            'Vanilla Latte' => ['panas', 'es'],
            'Hazelnut Latte' => ['panas', 'es'],
            'Cold Brew' => ['panas', 'es'],
            'Affogato' => ['panas', 'es'],
            'French Fries' => ['ori', 'bbq', 'spicy'],
            'Croissant' => ['coklat', 'strawberry', 'vanilla'],
            'Sandwich' => ['original'],
            'Banana Roll' => ['coklat', 'strawberry', 'vanilla'],
            'Burger' => ['chicken burger', 'beef burger', 'cheese burger'],
        ];

        for ($i = 1; $i <= 20; $i++) {
            $tanggalPesanan = now()
                ->subDays(fake()->numberBetween(0, 12))
                ->setTime(fake()->numberBetween(8, 21), fake()->randomElement([0, 10, 15, 20, 30, 40, 45, 50]));

            $pesanan = Pesanan::create([
                'order_number' => 'SDJ-'.now()->format('ymd').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'user_id' => $kasir->random()->id,
                'customer_name' => Arr::random($namaPelanggan),
                'order_date' => $tanggalPesanan,
                'status' => 'completed',
                'payment_method' => fake()->randomElement(['cash', 'qris', 'debit card']),
                'payment_status' => 'paid',
                'paid_amount' => 0,
                'paid_at' => $tanggalPesanan,
                'notes' => fake()->boolean(30) ? fake()->randomElement([
                    'Kurangi gula',
                    'Tanpa es',
                    'Extra shot espresso',
                    'Take away',
                    'Pakai susu oat',
                ]) : null,
                'total_amount' => 0,
            ]);

            $selectedProduk = $produk->random(fake()->numberBetween(1, 4));
            if (! is_iterable($selectedProduk)) {
                $selectedProduk = collect([$selectedProduk]);
            } else {
                $selectedProduk = collect($selectedProduk);
            }

            $total = 0;

            foreach ($selectedProduk as $itemProduk) {
                $quantity = fake()->numberBetween(1, 3);
                $subtotal = (float) $itemProduk->price * $quantity;
                $itemOption = fake()->randomElement($productOptions[$itemProduk->name] ?? ['original']);

                $pesanan->detailPesanan()->create([
                    'produk_id' => $itemProduk->id,
                    'product_name' => $itemProduk->name,
                    'serving_type' => $itemProduk->category === 'Coffee' ? $itemOption : 'panas',
                    'item_option' => $itemOption,
                    'price' => $itemProduk->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $itemProduk->decrement('stock', $quantity);
                $total += $subtotal;
            }

            $pesanan->update([
                'total_amount' => $total,
                'paid_amount' => $total,
            ]);
        }
    }
}
