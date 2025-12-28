<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
             DB::statement('ALTER TABLE `sale_items` MODIFY COLUMN `qty` DECIMAL(13, 4) NOT NULL DEFAULT 0.0000');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
             DB::statement('ALTER TABLE `sale_items` MODIFY COLUMN `qty` DECIMAL(8, 2) NOT NULL DEFAULT 0.00');
        });
    }
};
