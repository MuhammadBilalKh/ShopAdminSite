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
        Schema::create('product_has_tags', function (Blueprint $table) {
            $table->unsignedBigInteger("product_has_tags_id")->autoIncrement();
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("tag_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_has_tags');
    }
};
