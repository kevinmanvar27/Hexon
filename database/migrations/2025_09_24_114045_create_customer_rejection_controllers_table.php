<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type')->default('customer');
            $table->integer('quantity');
            $table->text('note')->nullable(); // optional note field
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_rejections');
    }
};
