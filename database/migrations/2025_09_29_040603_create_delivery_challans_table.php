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
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->string('job_work_name');
            $table->longText('pdf_files')->nullable();
            $table->string('po_revision_and_date')->nullable();
            $table->string('reason_of_revision')->nullable();
            $table->string('quotation_ref_no')->nullable();
            $table->text('remarks')->nullable();
            $table->date('pr_date')->nullable();
            $table->string('prno')->nullable();
            $table->string('po_no')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_challans');
    }
};
