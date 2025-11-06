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
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('invoice');
            $table->string('status');
            $table->date('create_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('orderno')->nullable();
            $table->text('lrno')->nullable();
            $table->string('transport')->nullable();
            $table->string('address');
            $table->text('note')->nullable();
            $table->decimal('sub_total', 15, 2);
            $table->decimal('pfcouriercharge', 11, 2)->nullable();
            $table->decimal('discount', 15, 2);
            $table->string('discount_type')->nullable();
            $table->decimal('cgst', 8, 2)->nullable();
            $table->decimal('sgst', 8, 2)->nullable();
            $table->decimal('igst', 10, 2)->nullable();
            $table->decimal('courier_charge', 11, 2)->default(0.00);
            $table->decimal('round_off', 8, 2)->nullable();
            $table->decimal('balance', 15, 2);
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
