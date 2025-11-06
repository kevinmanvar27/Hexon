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
        Schema::create('delivery_challan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_challan_id');
            $table->foreign('delivery_challan_id')->references('id')->on('delivery_challans')->onDelete('cascade');
            $table->unsignedBigInteger('spare_part_id');
            $table->foreign('spare_part_id')->references('id')->on('spare_parts')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->decimal('wt_pc', 10, 2);
            $table->text('material_specification')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_challan_items');
    }
};
