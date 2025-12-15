<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique()->nullable();
        $table->string('status')->default(1);
        $table->text('description')->nullable();
        $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
        $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
