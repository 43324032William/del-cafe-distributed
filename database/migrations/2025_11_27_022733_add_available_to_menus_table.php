<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Pastikan hanya menambahkan field available saja
            if (!Schema::hasColumn('menus', 'available')) {
                $table->boolean('available')->default(true)->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('available');
        });
    }
};