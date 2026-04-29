<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Espresso',
                'category' => 'Coffee',
                'price' => 18000,
                'stock' => 80,
                'description' => 'Single shot espresso dengan karakter bold dan aroma cokelat pekat.',
            ],
            [
                'name' => 'Americano',
                'category' => 'Coffee',
                'price' => 22000,
                'stock' => 75,
                'description' => 'Espresso yang dipadukan air panas untuk rasa clean dan ringan.',
            ],
            [
                'name' => 'Cappuccino',
                'category' => 'Coffee',
                'price' => 28000,
                'stock' => 70,
                'description' => 'Perpaduan espresso, steamed milk, dan foam yang creamy seimbang.',
            ],
            [
                'name' => 'Cafe Latte',
                'category' => 'Coffee',
                'price' => 30000,
                'stock' => 65,
                'description' => 'Minuman kopi susu lembut dengan karakter milky yang dominan.',
            ],
            [
                'name' => 'Mocha',
                'category' => 'Coffee',
                'price' => 32000,
                'stock' => 60,
                'description' => 'Kopi susu dengan sentuhan cokelat manis yang comforting.',
            ],
            [
                'name' => 'Caramel Macchiato',
                'category' => 'Coffee',
                'price' => 34000,
                'stock' => 55,
                'description' => 'Kopi susu dengan lapisan karamel yang manis dan harum.',
            ],
            [
                'name' => 'Vanilla Latte',
                'category' => 'Coffee',
                'price' => 33000,
                'stock' => 58,
                'description' => 'Latte lembut dengan aroma vanilla yang hangat.',
            ],
            [
                'name' => 'Hazelnut Latte',
                'category' => 'Coffee',
                'price' => 33000,
                'stock' => 52,
                'description' => 'Latte manis gurih dengan sentuhan hazelnut yang khas.',
            ],
            [
                'name' => 'Cold Brew',
                'category' => 'Coffee',
                'price' => 29000,
                'stock' => 45,
                'description' => 'Ekstraksi dingin dengan rasa smooth dan tingkat keasaman rendah.',
            ],
            [
                'name' => 'Affogato',
                'category' => 'Coffee',
                'price' => 35000,
                'stock' => 40,
                'description' => 'Espresso panas disajikan bersama es krim vanilla yang creamy.',
            ],
            [
                'name' => 'French Fries',
                'category' => 'Side Dish',
                'price' => 24000,
                'stock' => 55,
                'description' => 'Kentang goreng renyah dengan pilihan rasa ori, bbq, atau spicy.',
            ],
            [
                'name' => 'Croissant',
                'category' => 'Side Dish',
                'price' => 26000,
                'stock' => 40,
                'description' => 'Croissant lembut dengan pilihan isian coklat, strawberry, atau vanilla.',
            ],
            [
                'name' => 'Sandwich',
                'category' => 'Side Dish',
                'price' => 28000,
                'stock' => 35,
                'description' => 'Sandwich gurih yang cocok untuk teman ngopi kapan saja.',
            ],
            [
                'name' => 'Banana Roll',
                'category' => 'Side Dish',
                'price' => 25000,
                'stock' => 38,
                'description' => 'Banana roll manis dengan pilihan topping coklat, strawberry, atau vanilla.',
            ],
            [
                'name' => 'Burger',
                'category' => 'Side Dish',
                'price' => 32000,
                'stock' => 30,
                'description' => 'Burger dengan pilihan chicken burger, beef burger, atau cheese burger.',
            ],
        ];

        foreach ($products as $product) {
            Produk::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category' => $product['category'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'description' => $product['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
