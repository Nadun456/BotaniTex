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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehicle_type_id');
            $table->bigInteger('manufacture_id');
            $table->bigInteger('vehicle_model_id');
            $table->string('seo_url');  
            $table->string('ex_color'); 
            $table->string('in_color'); 
            $table->string('features'); 
            $table->enum('transmission',['Auto','Manual','Triptonic'])->default('Auto');
            $table->string('year'); 
            $table->string('chassis_no'); 
            $table->string('condition'); 
            $table->integer('seats'); 
            $table->integer('doors'); 
            $table->integer('passengers'); 
            $table->string('engine_capacity');
            $table->string('mileage');
            $table->enum('fuel_type',['Diesel', 'Petrol' ,'Hybrid', 'Electric'])->default('Petrol');
            $table->string('drive_type');  
            $table->string('auction_grade');  
            $table->string('interior_condition');
            $table->enum('availability',['Available', 'Arriving', 'Sold'])->default('Sold'); 
            $table->string('editorContent'); 
            $table->boolean('featured')->default(true); 
            $table->boolean('latest')->default(true); 
            $table->boolean('status')->default(true);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
