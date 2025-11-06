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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('valve_type')->nullable();
            $table->bigInteger('product_code')->nullable();
            $table->string('actuation')->nullable();
            $table->string('pressure_rating', 1000)->nullable();
            $table->string('valve_size')->nullable();
            $table->enum('valve_size_rate', ['inch', 'centimeter'])->nullable();
            $table->string('media')->nullable();
            $table->string('flow')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('mrp')->nullable();
            $table->string('media_temperature')->nullable();
            $table->enum('media_temperature_rate', ['FAHRENHEIT', 'CELSIUS'])->nullable();
            $table->string('body_material')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('primary_material_of_construction')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
