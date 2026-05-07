<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- WAJIB: Tambahkan ini untuk menggunakan DB::table

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                
                // Pastikan kolom is_available dibuat sebelum digunakan
                if (!Schema::hasColumn('menus', 'is_available')) {
                    // Tambahkan kolom is_available
                    $table->boolean('is_available')->default(true)->after('image');
                }
            });
            
            // PENTING: Operasi DB::table (UPDATE data) harus dilakukan DI LUAR Schema::table
            // Setelah semua kolom yang dibutuhkan dibuat.
            
            if (Schema::hasColumn('menus', 'is_active') && Schema::hasColumn('menus', 'is_available')) {
                // Update data: set is_available = is_active
                // Kita gunakan DB::raw() untuk menyalin nilai antar kolom
                DB::table('menus')->update(['is_available' => DB::raw('is_active')]);
            }
            
            // Catatan: Bagian di bawah ini (name, description, price, category, image)
            // terlihat seperti migrasi yang seharusnya sudah selesai di create_menus_table.
            // Memeriksanya di migrasi perbaikan ini tidak berbahaya, tapi tidak diperlukan 
            // jika Anda yakin migrasi creation sudah berjalan.
            Schema::table('menus', function (Blueprint $table) {
                if (!Schema::hasColumn('menus', 'name')) {
                    $table->string('name')->after('id');
                }
                
                if (!Schema::hasColumn('menus', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                
                if (!Schema::hasColumn('menus', 'price')) {
                    $table->decimal('price', 10, 2)->default(0)->after('description');
                }
                
                if (!Schema::hasColumn('menus', 'category')) {
                    $table->string('category')->after('price');
                }
                
                if (!Schema::hasColumn('menus', 'image')) {
                    $table->string('image')->nullable()->after('category');
                }
            });
        }
    }

    public function down(): void
    {
        // Migrasi perbaikan seharusnya memiliki metode down() yang membatalkan perubahannya.
        // Kita hanya perlu menjatuhkan (drop) kolom yang ditambahkan, yaitu 'is_available'.
        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                if (Schema::hasColumn('menus', 'is_available')) {
                    $table->dropColumn('is_available');
                }
                // Jika Anda ingin mengembalikan kolom is_active setelah drop is_available, tambahkan di sini.
            });
        }
    }
};