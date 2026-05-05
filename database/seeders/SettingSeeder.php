<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'business_name', 'label' => 'Nama Usaha', 'value' => 'Sedjati Coffee', 'type' => 'text', 'sort_order' => 1],
            ['group' => 'general', 'key' => 'business_address', 'label' => 'Alamat', 'value' => 'Jl. Sedjati Coffee No. 8, Yogyakarta', 'type' => 'textarea', 'sort_order' => 2],
            ['group' => 'general', 'key' => 'business_contact', 'label' => 'Kontak', 'value' => '0812-3456-7890', 'type' => 'text', 'sort_order' => 3],
            ['group' => 'general', 'key' => 'company_logo_path', 'label' => 'Logo Perusahaan', 'value' => null, 'type' => 'image', 'sort_order' => 4],
            ['group' => 'payment', 'key' => 'tax_percent', 'label' => 'Pajak (%)', 'value' => '11', 'type' => 'number', 'sort_order' => 1],
            ['group' => 'payment', 'key' => 'service_charge_percent', 'label' => 'Service Charge (%)', 'value' => '5', 'type' => 'number', 'sort_order' => 2],
            ['group' => 'payment', 'key' => 'payment_methods', 'label' => 'Metode Pembayaran', 'value' => json_encode(['cash', 'qris', 'debit card']), 'type' => 'json', 'sort_order' => 3],
            ['group' => 'order', 'key' => 'order_number_format', 'label' => 'Format Nomor Pesanan', 'value' => 'SDJ-{date}-{sequence}', 'type' => 'text', 'sort_order' => 1],
            ['group' => 'order', 'key' => 'default_payment_status', 'label' => 'Status Default', 'value' => 'pending', 'type' => 'select', 'sort_order' => 2],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
