<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel orders memiliki semua kolom yang diperlukan
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                // Tambahkan kolom yang mungkin belum ada
                if (!Schema::hasColumn('orders', 'order_number')) {
                    $table->string('order_number')->unique()->after('id');
                }
                
                if (!Schema::hasColumn('orders', 'queue_number')) {
                    $table->string('queue_number')->nullable()->after('order_number');
                }
                
                if (!Schema::hasColumn('orders', 'customer_name')) {
                    $table->string('customer_name')->after('queue_number');
                }
                
                if (!Schema::hasColumn('orders', 'customer_phone')) {
                    $table->string('customer_phone')->after('customer_name');
                }
                
                if (!Schema::hasColumn('orders', 'customer_email')) {
                    $table->string('customer_email')->nullable()->after('customer_phone');
                }
                
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('customer_email');
                }
                
                if (!Schema::hasColumn('orders', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->default(0)->after('notes');
                }
                
                if (!Schema::hasColumn('orders', 'status')) {
                    $table->string('status')->default('pending')->after('total_amount');
                }
                
                if (!Schema::hasColumn('orders', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('status');
                }
                
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('user_id');
                }
                
                // Pastikan timestamps ada
                if (!Schema::hasColumn('orders', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Pastikan tabel order_items memiliki semua kolom yang diperlukan
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'order_id')) {
                    $table->foreignId('order_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('order_items', 'menu_id')) {
                    $table->foreignId('menu_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('order_items', 'quantity')) {
                    $table->integer('quantity')->default(1);
                }
                
                if (!Schema::hasColumn('order_items', 'price')) {
                    $table->decimal('price', 10, 2)->default(0);
                }
                
                if (!Schema::hasColumn('order_items', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2)->default(0);
                }
                
                // Pastikan timestamps ada
                if (!Schema::hasColumn('order_items', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback untuk safety
    }
};