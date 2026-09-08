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
        Schema::create('activies', function (Blueprint $table) {
            $table->unsignedBigInteger("activity_id");
            $table->string("activity_description");
            $table->unsignedBigInteger("model_id");
            $table->string("model_class");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activies');
    }
};
