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
       Schema::create('loans', function (Blueprint $table) {
        $table->id();

        // Payer info
        $table->string('payer_name');
        $table->string('phone', 20)->nullable();
        $table->text('address')->nullable();

        // Loan info
        $table->date('loan_date');
        $table->decimal('amount', 14, 4);

        // Payment info
        $table->decimal('paid', 14, 4)->default(0);
        $table->decimal('due', 14, 4);
        $table->date('possible_paid_date')->nullable();

        // Status
        $table->enum('status', ['due', 'partial', 'paid'])->default('due');

        $table->text('note')->nullable();

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
