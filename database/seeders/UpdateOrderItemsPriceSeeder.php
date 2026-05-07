<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateOrderItemsPriceSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->update(['order_items.price' => DB::raw('menus.price')]);
            
        $this->command->info('Order items prices updated successfully!');
    }
}