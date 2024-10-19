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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->integer("zone_id")->default(0);
            $table->string("name")->nullable();
            $table->string("email")->unique();
            $table->string("password");
            $table->string("phone", 20)->unique();
            $table->string("country");
            $table->integer("one_star")->default(0);
            $table->integer("two_star")->default(0);
            $table->integer("three_star")->default(0);
            $table->integer("four_star")->default(0);
            $table->integer("five_star")->default(0);
            $table->integer('Total_service_count')->default(0);
            $table->float('avg_rating')->default(0);
            $table->string("image")->nullable();
            $table->text("bio")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
