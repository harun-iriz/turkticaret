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
        Schema::create('used_offers', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('offer_id');
            $table->string('offer_title');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_title')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('author')->nullable();
            $table->double('min_order')->nullable();
            $table->integer('offer_rate')->nullable();
            $table->double('discounted_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_offers');
    }
};
