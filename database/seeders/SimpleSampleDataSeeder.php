<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SimpleSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('menus')->delete();

        // Cek struktur tabel menus
        $menuColumns = [];
        if (Schema::hasTable('menus')) {
            $columns = DB::select('DESCRIBE menus');
            foreach ($columns as $column) {
                $menuColumns[] = $column->Field;
            }
        }

        // Buat sample menus hanya dengan kolom yang ada
        $menuData = [
            'name' => 'Nasi Goreng Spesial',
            'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
            'price' => 25000,
            'category' => 'Makanan Utama',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Hanya tambahkan is_available jika kolomnya ada
        if (in_array('is_available', $menuColumns)) {
            $menuData['is_available'] = true;
        }

        $menuId1 = DB::table('menus')->insertGetId($menuData);

        // Menu kedua
        $menuData2 = [
            'name' => 'Es Teh Manis',
            'description' => 'Es teh dengan gula aren',
            'price' => 8000,
            'category' => 'Minuman',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (in_array('is_available', $menuColumns)) {
            $menuData2['is_available'] = true;
        }

        $menuId2 = DB::table('menus')->insertGetId($menuData2);

        // Buat sample orders
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => null,
            'order_number' => 'ORD-TEST-001',
            'queue_number' => 'Q-TEST-001',
            'customer_name' => 'Test Customer',
            'customer_phone' => '082376167727',
            'customer_email' => 'test@example.com',
            'notes' => 'Ini pesanan test',
            'total_amount' => 58000,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat order items
        DB::table('order_items')->insert([
            [
                'order_id' => $orderId,
                'menu_id' => $menuId1,
                'quantity' => 2,
                'price' => 25000,
                'unit_price' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $orderId,
                'menu_id' => $menuId2,
                'quantity' => 1,
                'price' => 8000,
                'unit_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('Test phone number: 082376167727');
        $this->command->info('Menu IDs: ' . $menuId1 . ', ' . $menuId2);
        $this->command->info('Order ID: ' . $orderId);
    }
}