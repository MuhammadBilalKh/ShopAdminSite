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
        Schema::create('major_areas', function (Blueprint $table) {
            $table->unsignedBigInteger("major_area_id")->autoIncrement();
            $table->string("major_area_name")->index();
            $table->unsignedBigInteger("city_id")->index();
            $table->unsignedBigInteger("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('major_areas');
    }
};
