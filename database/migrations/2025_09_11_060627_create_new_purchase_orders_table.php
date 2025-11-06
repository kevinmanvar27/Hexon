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
        Schema::create('new_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->date('create_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('invoice');
            $table->text('prno')->nullable();
            $table->text('note')->nullable();
            $table->string('status');

            // foreignId automatically creates unsignedBigInteger and allows foreign key constraint
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('po_revision_and_date')->nullable();
            $table->string('reason_of_revision')->nullable();
            $table->string('quotation_ref_no')->nullable();
            $table->text('remarks')->nullable();
            $table->date('pr_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_purchase_orders');
    }
};
