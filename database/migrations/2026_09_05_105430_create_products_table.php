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
            $table->unsignedBigInteger("product_id")->autoIncrement();
            $table->string("product_name");
            $table->unsignedBigInteger("category_id");
            $table->decimal("regular_price");
            $table->decimal("sales_price")->nullable();
            $table->unsignedInteger("quantity");
            $table->string("description");
            $table->unsignedBigInteger("created_by");
            $table->unsignedBigInteger("updated_by");
            $table->unsignedTinyInteger("is_new");
            $table->unsignedTinyInteger("is_featured");
            $table->string("product_profile_image");
            $table->string("unique_product_id");
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
