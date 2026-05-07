<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
                'price' => 25000,
                'category' => 'makanan',
                'is_available' => true,
            ],
            [
                'name' => 'Mie Goreng Jawa',
                'description' => 'Mie goreng dengan bumbu khas Jawa',
                'price' => 22000,
                'category' => 'makanan',
                'is_available' => true,
            ],
            [
                'name' => 'Ayam Bakar',
                'description' => 'Ayam bakar dengan bumbu spesial',
                'price' => 30000,
                'category' => 'makanan',
                'is_available' => true,
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Es teh dengan gula aren',
                'price' => 8000,
                'category' => 'minuman',
                'is_available' => true,
            ],
            [
                'name' => 'Jus Jeruk',
                'description' => 'Jus jeruk segar',
                'price' => 12000,
                'category' => 'minuman',
                'is_available' => true,
            ],
            [
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam aromatik',
                'price' => 10000,
                'category' => 'minuman',
                'is_available' => true,
            ],
            [
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng renyah',
                'price' => 15000,
                'category' => 'snack',
                'is_available' => true,
            ],
            [
                'name' => 'Singkong Keju',
                'description' => 'Singkong goreng dengan taburan keju',
                'price' => 13000,
                'category' => 'snack',
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Menu sample created successfully!');
    }
}