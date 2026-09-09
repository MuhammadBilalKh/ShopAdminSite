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
        Schema::create('minor_areas', function (Blueprint $table) {
            $table->unsignedBigInteger("minor_area_id")->autoIncrement();
            $table->string("minor_area_name")->index();
            $table->unsignedBigInteger("major_area_id")->index();
            $table->unsignedBigInteger("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minor_areas');
    }
};
