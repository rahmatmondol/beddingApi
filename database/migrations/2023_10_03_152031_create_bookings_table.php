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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer("provider_id")->default(0);
            $table->integer("customer_id")->default(0);
            $table->integer("campaign_id")->default(0);
            $table->integer("category_id")->default(0);
            $table->integer("service_id")->default(0);
            $table->integer("payment_id")->default(0);
            $table->enum("payment_method",["online","cash"])->default("online");
            $table->tinyInteger("is_paid")->default('0');
            $table->text("metadata")->nullable();
            $table->float("total_amount")->default(0.00);
            $table->float("service_charge")->default(0.00);
            $table->float("provider_service_charge")->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
