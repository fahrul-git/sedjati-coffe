<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Elena Jenkins', 'phone' => '081220001001', 'first_purchase_date' => now()->subMonths(4)->toDateString(), 'total_transactions' => 8, 'total_spending' => 324500],
            ['name' => 'Marcus Brown', 'phone' => '081220001002', 'first_purchase_date' => now()->subMonths(3)->toDateString(), 'total_transactions' => 5, 'total_spending' => 214000],
            ['name' => 'Sarah Williams', 'phone' => '081220001003', 'first_purchase_date' => now()->subMonths(2)->toDateString(), 'total_transactions' => 11, 'total_spending' => 486000],
            ['name' => 'David Thorne', 'phone' => '081220001004', 'first_purchase_date' => now()->subMonths(1)->toDateString(), 'total_transactions' => 3, 'total_spending' => 129500],
            ['name' => 'Lisa Geller', 'phone' => '081220001005', 'first_purchase_date' => now()->subWeeks(3)->toDateString(), 'total_transactions' => 6, 'total_spending' => 275000],
        ];

        foreach ($customers as $customer) {
            Customer::query()->updateOrCreate(
                ['phone' => $customer['phone']],
                $customer
            );
        }
    }
}
