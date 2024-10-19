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
            $table->integer("parent_id")->default(0);
            $table->integer("category_id");
            $table->integer("customer_id");
            $table->text("zone");
//            $table->text("image")->nullable();
            $table->string("price");
            $table->enum("price_type", ["fixed", "negotiable"])->default("fixed");
            $table->enum("level", ["entry", "intermediate","expert"])->default("entry");
            $table->string("currency")->default("usd");
            $table->json('skill');
            $table->float("commotion")->default(0.0);
            $table->float("provider_amount")->default(0);
            $table->string("address")->nullable();
            $table->enum("status",["request","accept","complete"])->default('request');
            $table->text("description")->nullable();
            $table->string("location");
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
