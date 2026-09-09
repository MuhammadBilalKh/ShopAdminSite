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
        Schema::create('warehouse', function (Blueprint $table) {
            $table->unsignedBigInteger("warehouse_id")->autoIncrement();
            $table->string("warehouse_name")->index();
            $table->string("slug")->index();
            $table->unsignedBigInteger("minor_area_id")->index();
            $table->string("warehouse_address")->index();
            $table->unsignedBigInteger("created_by")->index();
            $table->unsignedTinyInteger("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse');
    }
};
