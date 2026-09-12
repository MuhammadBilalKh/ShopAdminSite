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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger("product_review_id")->autoIncrement();
            $table->unsignedBigInteger("customer_id")->index();
            $table->unsignedBigInteger("product_id")->index();
            $table->string("description");
            $table->unsignedInteger("rating");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
