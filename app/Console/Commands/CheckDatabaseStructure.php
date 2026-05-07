<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDatabaseStructure extends Command
{
    protected $signature = 'db:check-structure';
    protected $description = 'Check database structure for required tables and columns';

    public function handle()
    {
        $this->info('Checking database structure...');

        // Check orders table
        if (Schema::hasTable('orders')) {
            $this->info('✓ Orders table exists');
            $columns = ['id', 'customer_name', 'customer_phone', 'customer_email', 'notes', 'total_amount', 'status', 'created_at', 'updated_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $this->info("  ✓ Column '{$column}' exists");
                } else {
                    $this->error("  ✗ Column '{$column}' missing");
                }
            }
        } else {
            $this->error('✗ Orders table missing');
        }

        // Check order_items table
        if (Schema::hasTable('order_items')) {
            $this->info('✓ Order_items table exists');
            $columns = ['id', 'order_id', 'menu_id', 'quantity', 'price', 'created_at', 'updated_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $this->info("  ✓ Column '{$column}' exists");
                } else {
                    $this->error("  ✗ Column '{$column}' missing");
                }
            }
        } else {
            $this->error('✗ Order_items table missing');
        }

        // Check menus table
        if (Schema::hasTable('menus')) {
            $this->info('✓ Menus table exists');
        } else {
            $this->error('✗ Menus table missing');
        }

        return Command::SUCCESS;
    }
}