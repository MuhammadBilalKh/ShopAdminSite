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
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger("order_id")->autoIncrement();
            $table->string("customer_order_id")->unique();
            $table->unsignedInteger("total_amount");
            $table->unsignedBigInteger("customer_id");
            $table->unsignedTinyInteger("order_status");
            $table->unsignedBigInteger("order_process_by");
            $table->unsignedBigInteger("shipping_method_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_tabe');
    }
};
