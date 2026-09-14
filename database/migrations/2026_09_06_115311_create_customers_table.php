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
        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedBigInteger("customer_id")->autoIncrement();
            $table->string("full_name");
            $table->string("mobile_number")->unique()->index();
            $table->string("cnic")->unique()->index();
            $table->string("account_status")->default(1);
            $table->string("address");
            $table->string("email_address")->unique()->index();
            $table->string("password");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
