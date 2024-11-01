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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("image")->nullable();
            $table->float("price");
            $table->enum("price_type", ["fixed", "negotiable"])->default("fixed");
            $table->enum("level", ["entry", "intermediate","expert"])->default("entry");
            $table->string("currency")->default("usd");
            $table->text('skills');
            $table->float("commission")->default(0.0);
            $table->float("provider_amount")->default(0);
            $table->enum("status",["request","accept","complete"])->default('request');
            $table->text("description")->nullable();
            $table->string("location");
            $table->string("latitude");
            $table->string("longitude");
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->nullable();
            $table->boolean("is_featured")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
