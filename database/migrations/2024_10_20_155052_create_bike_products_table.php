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
        Schema::create('bike_products', function (Blueprint $table) {
            $table->id();
          
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku');
            $table->integer('price')->nullable(); 
            $table->integer('quantity'); 
            $table->integer('minimum_order_quantity'); 
            $table->longText('description'); 
            $table->longText('specification'); 
            $table->longText('comfortable');
            $table->longText('country');
            $table->string('color');
            $table->string('model');
            $table->string('manufacture');
            $table->string('featured');
            $table->longText('categories');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bike_products');
    }
};
