<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('expiry_notifications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('purchase_item_id');
        $table->unsignedBigInteger('product_id');
        $table->date('expiry_date');
        $table->boolean('notified')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiry_notifications');
    }
};
