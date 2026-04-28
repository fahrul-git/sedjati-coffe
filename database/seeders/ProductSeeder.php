<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Espresso', 'category' => 'Coffee', 'price' => 18000, 'stock' => 50],
            ['name' => 'Cappuccino', 'category' => 'Coffee', 'price' => 28000, 'stock' => 40],
            ['name' => 'Cafe Latte', 'category' => 'Coffee', 'price' => 30000, 'stock' => 35],
            ['name' => 'Croissant', 'category' => 'Pastry', 'price' => 22000, 'stock' => 25],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'slug' => Str::slug($product['name']),
                    'category' => $product['category'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'description' => 'Menu unggulan Sedjati Coffee.',
                    'is_active' => true,
                ]
            );
        }
    }
}
