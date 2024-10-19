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
            $table->id();
            $table->string('name');
//            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->tinyInteger('email_verify')->default(0);
            $table->string('password');
//            $table->string('ref_code')->unique();
//            $table->integer('otp')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active');
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
