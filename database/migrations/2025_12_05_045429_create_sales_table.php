<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // $table->string('invoice_no')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('sale_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount',14,2);
            $table->decimal('discount',14,2)->default(0);
            $table->decimal('tax',14,2)->default(0);
            $table->decimal('shipping',14,2)->default(0);
            $table->decimal('paid', 10, 2)->default(0);
            $table->decimal('grand_total',14,2);
            $table->string('payment_status')->default('due'); // paid, partial, due, cancelled
            $table->string('status')->default(1); // 1 = active, 0 = deleted/cancelled
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
