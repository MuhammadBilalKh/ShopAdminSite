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
        Schema::create('carts', function (Blueprint $table) {
            $table->unsignedBigInteger("cart_id")->autoIncrement();
            $table->unsignedBigInteger("product_id")->index();
            $table->unsignedBigInteger("customer_id")->index();
            $table->unsignedInteger("quantity");
            $table->decimal('price', 12, 2);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts_tabe');
    }
};
