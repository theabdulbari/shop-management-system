<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            // Add a 'note' column, allowing NULL values (optional)
            $table->text('note')->nullable(); // Use 'string' for shorter text
        });
    }

    public function down(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            // Reverse the change: drop the column if migration is rolled back
            $table->dropColumn('note');
        });
    }
};
