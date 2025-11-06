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
        Schema::create('new_purchase_order_item', function (Blueprint $table) { 
            $table->id();
            $table->integer('quantity');
            $table->integer('remaining_quantity')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('product_unit')->nullable();
            $table->string('remark');
            $table->foreignId('new_purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('spare_part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->string('material_specification')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('rate_kgs', 8, 2)->nullable();
            $table->decimal('per_pc_weight', 8, 2)->nullable();
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_purchase_order_item');
    }
};
